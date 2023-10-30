<?php

namespace Iplogic\Zero;

use \Bitrix\Main\Loader;
use \Bitrix\Main\Config\Option;


/**
 * Product Catalog function extension class / Класс расширения функций каталога товаров
 * @package Iplogic\Zero
 */
class Catalog
{
	/**
	 * Product information block ID / ID инфоблока товаров
	 * @var int
	 */
	public $product_iblock_id;
	/**
	 * Offer information block ID / ID инфоблока предложений
	 * @var int
	 */
	public $offer_iblock_id;
	/**
	 * Brand information block ID / ID инфоблока брендов
	 * @var int
	 */
	public $brand_iblock_id;
	/**
	 * Currency code / Код валюты
	 * @var string
	 */
	public $currency;
	/**
	 * Class object / Объект класса
	 * @var \CIBlockElement
	 */
	protected $obIblockElement;
	/**
	 * Array of stock by stores / Массив остатков по складам
	 * @var array
	 */
	public $arStoreStocks = [];


	/**
	 * Class constructor / Конструктор класса
	 */
	function __construct()
	{
		self::modulesCheck();
		$this->obIblockElement = new \CIBlockElement;
		return;
	}


	/**
	 * Checks for the necessary modules / Проверяет наличие необходимых модулей
	 * @return bool
	 */
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


	/**
	 * Getting the array "field value" => "Element ID" for products AND offers /
	 * Получение массива "значение поля" => "ID элемента" для товаров И предложений
	 *
	 * @param string $field - field (element keys) / поле (ключи элементов)
	 * @param bool $activeOnly - only active entities / только активные записи
	 * @param bool $additionFields - additional fields to include in the array / дополнительные поля для включения в массив
	 * @return array
	 */
	public function getAllIdsByProp($field, $activeOnly = false, $additionFields = false)
	{
		return $this->getIdsByProp($this->product_iblock_id, $field, $activeOnly, $additionFields) +
			$this->getIdsByProp($this->offer_iblock_id, $field, $additionFields);
	}


	/**
	 * Getting the array "field value" => "Element ID" for products OR offers /
	 * Получение массива "значение поля" => "ID элемента" для товаров ИЛИ предложений
	 *
	 * @param int $iblock - product or offer iblock ID / ID инфоблока товаров или предложений
	 * @param string $field - field (element keys) / поле (ключи элементов)
	 * @param bool $activeOnly - only active entities / только активные записи
	 * @param bool $additionFields - additional fields to include in the array / дополнительные поля для включения в массив
	 * @return array
	 */
	public function getIdsByProp($iblock, $field, $activeOnly = false, $additionFields = false)
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
				$arIds[$prd[$key]] = $prd['ID'];
			}
			else {
				$arIds[$prd[$key]] = $prd;
			}
		}
		return $arIds;
	}


	/**
	 * Getting the array "Element ID" => "field value" for products AND offers /
	 * Получение массива "ID элемента" => "значение поля" для товаров И предложений
	 *
	 * @param string $field - field (element value) / поле (значения элементов)
	 * @param bool $activeOnly - only active entities / только активные записи
	 * @param bool $additionFields - additional fields to include in the array / дополнительные поля для включения в массив
	 * @return array
	 */
	public function getPropByAllIds($field, $activeOnly = false, $additionFields = false)
	{
		return $this->getPropByIds($this->product_iblock_id, $field, $activeOnly, $additionFields) +
			$this->getPropByIds($this->offer_iblock_id, $field, $activeOnly, $additionFields);
	}


	/**
	 * Getting the array "Element ID" => "field value" for products OR offers /
	 * Получение массива "ID элемента" => "значение поля" для товаров ИЛИ предложений
	 *
	 * @param int $iblock - product or offer iblock ID / ID инфоблока товаров или предложений
	 * @param string $field - field (element value) / поле (значения элементов)
	 * @param bool $activeOnly - only active entities / только активные записи
	 * @param bool $additionFields - additional fields to include in the array / дополнительные поля для включения в массив
	 * @return array
	 */
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


	/**
	 * Sets product stock / Устанавливает остаток товара
	 *
	 * If the $store parameter is not specified or false, the total quantity for the product is set /
	 * Если параметр $store не указан или false устанавливается общее количество для товара
	 *
	 * @param int $ID - product ID / ID товара
	 * @param int $stock - product stock / остаток товара
	 * @param mixed $store - store ID / ID склада
	 */
	public function setStock($ID, $stock = 0, $store = false)
	{
		if( !is_array($ID) ) {
			$ID = [$ID];
		}
		foreach( $ID as $productID ) {
			if( $store ) {
				$arFields = [
					"PRODUCT_ID" => $productID,
					"STORE_ID"   => $store,
					"AMOUNT"     => $stock,
				];
				$this->getStoreStocks($store);
				if( isset($this->arStoreStocks[$store][$productID]) ) {
					if( $this->arStoreStocks[$store][$productID]["AMOUNT"] != $stock ) {
						\Bitrix\Catalog\StoreProductTable::update(
							$this->arStoreStocks[$store][$productID]["ID"],
							$arFields
						);
						$this->arStoreStocks[$store][$productID]["AMOUNT"] = $stock;
					}
				}
				else {
					$result = \Bitrix\Catalog\StoreProductTable::add($arFields);
					if($result->isSuccess()) {
						$this->arStoreStocks[$store][$productID] = [
							"ID" => $result->getId(),
							"AMOUNT" => $stock
						];
					}
				}
			}
			else {
				\Bitrix\Catalog\ProductTable::update($productID, ['QUANTITY' => $stock]);
			}
		}
	}


	/**
	 * Get all stocks by store / Получить все остатки по складу
	 *
	 * @param int $ID - store ID / ID склада
	 * @return array
	 */
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


	/**
	 * Sets product price / Устанавливает цену товара
	 *
	 * @param int $productID - product ID / ID товара
	 * @param int $price - price / цена
	 * @param int $groupID - price type ID / ID типа цены
	 * @return mixed
	 */
	public function setPrice($productID, $price, $groupID)
	{
		$arFields = [
			"PRODUCT_ID"       => $productID,
			"CATALOG_GROUP_ID" => $groupID,
			"PRICE"            => (int)$price,
			"CURRENCY"         => $this->currency,
		];
		$res = \CPrice::GetList([], ["PRODUCT_ID" => $productID, "CATALOG_GROUP_ID" => $groupID]);
		if( $arr = $res->Fetch() ) {
			return \CPrice::Update($arr["ID"], $arFields);
		}
		else {
			return \CPrice::Add($arFields);
		}
	}


	/**
	 * Getting brands array by ID / Получение массива брендов по ID
	 * @param array $fields - array of fields to include in the array / массив полей для включения в массив
	 * @return array
	 */
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


	/**
	 * Product availability reindex / Переиндексация доступности товаров
	 */
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
		return;
	}

}