<?php
namespace Iplogic\Zero;

use \Bitrix\Main\Loader;

class Catalog
{
	public $product_iblock_id;
	public $offer_iblock_id;
	public $brand_iblock_id;
	public $currency;
	protected $obIblockElement;
	protected $obProduct;
	protected $arStoreStocks;

	function __construct($config = [])
	{
		self::modulesCheck();
		$this->obIblockElement = new \CIBlockElement;
		$this->obProduct = new \CCatalogProduct;
	}

	public static function modulesCheck()
	{
		if (!Loader::includeModule("catalog")){
			return false;
		}
		if (!Loader::includeModule("iblock")){
			return false;
		}
		return true;
	}

	public function getAllIdsByProp($field)
	{
		return $this->getIdsByProp($this->product_iblock_id, $field) +
			$this->getIdsByProp($this->offer_iblock_id, $field);
	}

	public function getIdsByProp($iblock, $field)
	{
		$arIds = [];
		$notField = "!".$field;
		$arFilter = ['IBLOCK_ID' => $iblock, $notField => false];
		$arSelect = ['ID', 'IBLOCK_ID', $field];
		$_prd = \CIBlockElement::GetList([],$arFilter,false,false,$arSelect);
		$key = $field;
		if(substr($field,0, 9) == "PROPERTY_")
			$key .= "_VALUE";
		while ($prd = $_prd->GetNext()) {
			$arIds[$prd[$key]] = $prd['ID'];
		}
		return $arIds;
	}

	public function getPropByAllIds($field)
	{
		return $this->getPropByIds($this->product_iblock_id, $field) +
			$this->getPropByIds($this->offer_iblock_id, $field);
	}

	public function getPropByIds($iblock, $field)
	{
		$arIds = [];
		$notField = "!".$field;
		$arFilter = ['IBLOCK_ID' => $iblock, $notField => false];
		$arSelect = ['ID', 'IBLOCK_ID', $field];
		$_prd = \CIBlockElement::GetList([],$arFilter,false,false,$arSelect);
		$key = $field;
		if(substr($field,0, 9) == "PROPERTY_")
			$key .= "_VALUE";
		while ($prd = $_prd->GetNext()) {
			$arIds[(string)$prd['ID']] = $prd[$key];
		}
		return $arIds;
	}

	public function setStock($ID, $stock = 0, $store = false)
	{
		if(!is_array($ID))
			$ID = [$ID];
		foreach($ID as $priductID) {
			if ($store) {
				$arFields = Array(
					"PRODUCT_ID" => $priductID,
					"STORE_ID" => $store,
					"AMOUNT" => $stock,
				);
				if (array_key_exists($priductID, $this->getStoreStocks($store))) {
					\CCatalogStoreProduct::Update($this->arStoreStocks[$store][$priductID]["ID"], $arFields);
				}
				else {
					\CCatalogStoreProduct::Add($arFields);
				}
			}
			else {
				$this->obProduct->Update($priductID, ['QUANTITY'=>$stock]);
			}
		}
	}

	public function getStoreStocks($ID)
	{
		if(isset($this->arStoreStocks[$ID]))
			return $this->arStoreStocks[$ID];
		$rsStore = \CCatalogStoreProduct::GetList([], ['STORE_ID' => $ID], false, false, ['ID','PRODUCT_ID','AMOUNT']);
		while ($arStore = $rsStore->Fetch()) {
			$this->arStoreStocks[$ID][$arStore['PRODUCT_ID']] = [
				"ID" => $arStore['AMOUNT'],
				"AMOUNT" => $arStore['AMOUNT']
			];
		}
		return $this->arStoreStocks[$ID];
	}

	public function setPrice($priductID, $price, $groupID)
	{
		$arFields = Array(
			"PRODUCT_ID" => $priductID,
			"CATALOG_GROUP_ID" => $groupID,
			"PRICE" => (int)$price,
			"CURRENCY" => $this->currency,
		);
		$res = \CPrice::GetList([],["PRODUCT_ID" => $priductID, "CATALOG_GROUP_ID" => $groupID]);
		if ($arr = $res->Fetch()){
			\CPrice::Update($arr["ID"], $arFields);
		}else{
			\CPrice::Add($arFields);
		}
	}

	public function getBrands($fields = []) {
		$arBrands = [];
		if (!in_array("ID", $fields))
			$fields[] = "ID";
		if (!in_array("IBLOCK_ID", $fields))
			$fields[] = "IBLOCK_ID";
		$_mfs = \CIBlockElement::GetList([],['IBLOCK_ID'=>$this->brand_iblock_id,'ACTIVE'=>'Y'],false,false,$fields);
		while ($mfs = $_mfs->GetNext()) {
			$brand = [];
			foreach($fields as $field) {
				$brand[$field] = $mfs[$field];
			}
			$arBrands[$mfs['ID']] = $brand;
		}
		return $arBrands;
	}

}