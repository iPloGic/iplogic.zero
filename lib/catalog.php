<?php

namespace Iplogic\Zero;

use \Bitrix\Main\Loader;
use \Bitrix\Main\Config\Option;

class Catalog
{
	public $product_iblock_id;
	public $offer_iblock_id;
	public $brand_iblock_id;
	public $currency;
	protected $obIblockElement;
	public $arStoreStocks = [];

	function __construct($config = [])
	{
		self::modulesCheck();
		$this->obIblockElement = new \CIBlockElement;
	}

	public static function modulesCheck()
	{
		if( !Loader::includeModule("catalog") ) {
			return false;
		}
		if( !Loader::includeModule("iblock") ) {
			return false;
		}
		return true;
	}

	public function getAllIdsByProp($field, $activeOnly = false, $aditionFields = false)
	{
		return $this->getIdsByProp($this->product_iblock_id, $field, $activeOnly, $aditionFields) +
			$this->getIdsByProp($this->offer_iblock_id, $field, $aditionFields);
	}

	public function getIdsByProp($iblock, $field, $activeOnly = false, $aditionFields = false)
	{
		$arIds = [];
		$notField = "!" . $field;
		$arFilter = ['IBLOCK_ID' => $iblock, $notField => false];
		if( $activeOnly ) {
			$arFilter["=ACTIVE"] = "Y";
		}
		$arSelect = ['ID', 'IBLOCK_ID', $field];
		if($aditionFields) {
			$arSelect = array_merge($arSelect, $aditionFields);
		}
		$_prd = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
		$key = $field;
		if( substr($field, 0, 9) == "PROPERTY_" ) {
			$key .= "_VALUE";
		}
		while( $prd = $_prd->GetNext() ) {
			if(count($arSelect) < 4) {
				$arIds[$prd[$key]] = $prd['ID'];
			}
			else {
				$arIds[$prd[$key]] = $prd;
			}
		}
		return $arIds;
	}

	public function getPropByAllIds($field, $activeOnly = false, $aditionFields = false)
	{
		return $this->getPropByIds($this->product_iblock_id, $field, $activeOnly, $aditionFields) +
			$this->getPropByIds($this->offer_iblock_id, $field, $activeOnly, $aditionFields);
	}

	public function getPropByIds($iblock, $field, $activeOnly = false, $additionFields = false)
	{
		$arIds = [];
		$notField = "!" . $field;
		$arFilter = ['IBLOCK_ID' => $iblock, $notField => false];
		if( $activeOnly ) {
			$arFilter["=ACTIVE"] = "Y";
		}
		$arSelect = ['ID', 'IBLOCK_ID', $field];
		if($additionFields) {
			$arSelect = array_merge($arSelect, $additionFields);
		}
		$_prd = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
		$key = $field;
		if( substr($field, 0, 9) == "PROPERTY_" ) {
			$key .= "_VALUE";
		}
		while( $prd = $_prd->GetNext() ) {
			if(count($arSelect) < 4) {
				$arIds[(string)$prd['ID']] = $prd[$key];
			}
			else {
				$arIds[(string)$prd['ID']] = $prd;
			}
		}
		return $arIds;
	}

	public function setStock($ID, $stock = 0, $store = false)
	{
		if( !is_array($ID) ) {
			$ID = [$ID];
		}
		foreach( $ID as $priductID ) {
			if( $store ) {
				$arFields = [
					"PRODUCT_ID" => $priductID,
					"STORE_ID"   => $store,
					"AMOUNT"     => $stock,
				];
				$this->getStoreStocks($store);
				if( isset($this->arStoreStocks[$store][$priductID]) ) {
					if( $this->arStoreStocks[$store][$priductID]["AMOUNT"] != $stock ) {
						\Bitrix\Catalog\StoreProductTable::update(
							$this->arStoreStocks[$store][$priductID]["ID"],
							$arFields
						);
						$this->arStoreStocks[$store][$priductID]["AMOUNT"] = $stock;
					}
				}
				else {
					$result = \Bitrix\Catalog\StoreProductTable::add($arFields);
					if($result->isSuccess()) {
						$this->arStoreStocks[$store][$priductID] = [
							"ID" => $result->getId(),
							"AMOUNT" => $stock
						];
					}
				}
			}
			else {
				\Bitrix\Catalog\ProductTable::update($priductID, ['QUANTITY' => $stock]);
			}
		}
	}

	public function getStoreStocks($ID)
	{
		if( isset($this->arStoreStocks[$ID]) ) {
			return $this->arStoreStocks[$ID];
		}
		$this->arStoreStocks[$ID] = [];
		$rsStore = \Bitrix\Catalog\StoreProductTable::getList(
			["filter" => ['STORE_ID' => $ID], "select" => ['ID', 'PRODUCT_ID', 'AMOUNT']]
		);
		while( $arStore = $rsStore->Fetch() ) {
			$this->arStoreStocks[$ID][$arStore['PRODUCT_ID']] = [
				"ID"     => (int)$arStore['ID'],
				"AMOUNT" => $arStore['AMOUNT'],
			];
		}
		return $this->arStoreStocks[$ID];
	}

	public function setPrice($priductID, $price, $groupID)
	{
		$arFields = [
			"PRODUCT_ID"       => $priductID,
			"CATALOG_GROUP_ID" => $groupID,
			"PRICE"            => (int)$price,
			"CURRENCY"         => $this->currency,
		];
		$res = \CPrice::GetList([], ["PRODUCT_ID" => $priductID, "CATALOG_GROUP_ID" => $groupID]);
		if( $arr = $res->Fetch() ) {
			\CPrice::Update($arr["ID"], $arFields);
		}
		else {
			\CPrice::Add($arFields);
		}
	}

	public function getBrands($fields = [])
	{
		$arBrands = [];
		if( !in_array("ID", $fields) ) {
			$fields[] = "ID";
		}
		if( !in_array("IBLOCK_ID", $fields) ) {
			$fields[] = "IBLOCK_ID";
		}
		$_mfs = \CIBlockElement::GetList([], ['IBLOCK_ID' => $this->brand_iblock_id], false, false, $fields);
		while( $mfs = $_mfs->GetNext() ) {
			$brand = [];
			foreach( $fields as $field ) {
				$brand[$field] = $mfs[$field];
			}
			$arBrands[$mfs['ID']] = $brand;
		}
		return $arBrands;
	}

	public function productAvailabilityReindex()
	{
		if ( Option::get("catalog", "show_catalog_tab_with_offers", "N") == "Y" ) {
			return;
		}
		$arProducts = [];
		$arOffers = [];
		$arFilter = ['IBLOCK_ID' => $this->product_iblock_id, "=ACTIVE" => "Y"];
		$arSelect = ['ID', 'IBLOCK_ID', 'AVAILABLE', 'QUANTITY', 'TYPE'];
		$_prd = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
		while( $prd = $_prd->GetNext() ) {
			$arProducts[(string)$prd['ID']] = [
				"AVAILABLE" => $prd["AVAILABLE"],
				"QUANTITY" => $prd["QUANTITY"],
				"TYPE" => $prd["TYPE"],
			];
		}
		$arFilter = ['IBLOCK_ID' => $this->offer_iblock_id, "=ACTIVE" => "Y"];
		$arSelect = ['ID', 'IBLOCK_ID', 'AVAILABLE', 'QUANTITY', 'PROPERTY_CML2_LINK'];
		$_prd = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
		while( $prd = $_prd->GetNext() ) {
			$arOffers[(string)$prd['ID']] = [
				"AVAILABLE" => $prd["AVAILABLE"],
				"QUANTITY" => $prd["QUANTITY"],
				"CML2_LINK" => $prd["PROPERTY_CML2_LINK_VALUE"],
			];
		}
		foreach($arOffers as $ID => $offer) {
			if ($offer["QUANTITY"] > 0) {
				if ($offer["AVAILABLE"] != "Y") {
					\Bitrix\Catalog\ProductTable::update($ID, ['AVAILABLE' => "Y"]);
				}
				if (isset($arProducts[$offer["CML2_LINK"]]) && $arProducts[$offer["CML2_LINK"]]["AVAILABLE"] != "Y") {
					\Bitrix\Catalog\ProductTable::update($offer["CML2_LINK"], ['AVAILABLE' => "Y"]);
					unset($arProducts[$offer["CML2_LINK"]]);
				}
			}
			else {
				if ($offer["AVAILABLE"] != "N") {
					\Bitrix\Catalog\ProductTable::update($ID, ['AVAILABLE' => "N"]);
				}
			}
		}
		foreach($arProducts as $ID => $product) {
			if ($product["QUANTITY"] > 0) {
				if ($product["AVAILABLE"] != "Y" && $product["TYPE"] == 1) {
					\Bitrix\Catalog\ProductTable::update($ID, ['AVAILABLE' => "Y"]);
				}
			}
			else {
				if ($product["AVAILABLE"] != "N" && $product["TYPE"] == 1) {
					\Bitrix\Catalog\ProductTable::update($ID, ['AVAILABLE' => "N"]);
				}
			}
			if ($product["TYPE"] == 3 && $product["AVAILABLE"] != "N") {
				\Bitrix\Catalog\ProductTable::update($ID, ['AVAILABLE' => "N", "QUANTITY" => 0]);
			}
		}
	}

}