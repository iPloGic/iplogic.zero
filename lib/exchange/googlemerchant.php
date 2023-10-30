<?php // https://support.google.com/merchants/answer/7052112?hl=ru
namespace Iplogic\Zero\Exchange;

use \Iplogic\Zero\Catalog;


/**
 * Class for creating a catalog upload in Google Merchant format / Класс создания выгрузки каталога в формате Google Merchant
 * @package Iplogic\Zero\Exchange
 */
class GoogleMerchant extends \Iplogic\Zero\Exchange\ExportXML
{
	/**
	 * \Iplogic\Zero\Catalog object / Объект \Iplogic\Zero\Catalog
	 * @var object
	 */
	protected $catalog;
	/**
	 * Products array for upload / Массив товаров для выгрузки
	 * @var array
	 */
	protected $arProducts;


	/**
	 * Class constructor / Конструктор класса
	 * @param array $config - configuration array / массив конфигурации
	 */
	function __construct($config)
	{
		$configKeys = [
			"PRODUCT_IBLOCK_ID",
			"OFFER_IBLOCK_ID",
			"KEY_FIELD",
			"COMPARISON",
			"FILTER",
			"SKU_LINK",
			"CURRENCY",
			"TITLE",
			"LINK",
			"DESCRIPTION",
			"EXCLUDE_SECTION",
		];
		foreach( $configKeys as $key ) {
			if( isset($config[$key]) ) {
				$this->config[$key] = $config[$key];
			}
			else {
				$this->config[$key] = false;
			}
		}
		$config["NODES"] = [
			"NAME"     => 'rss',
			"ATTR"     => [
				"xmlns:xmlns:g" => "http://base.google.com/ns/1.0",
				"version"       => "2.0",
			],
			"CHILDREN" => [
				[
					"NAME"     => "channel",
					"CHILDREN" => [
						[
							"NAME" => "title",
							"TEXT" => $this->config["TITLE"],
						],
						[
							"NAME" => "link",
							"TEXT" => $this->config["LINK"],
						],
						[
							"NAME" => "description",
							"TEXT" => $this->config["DESCRIPTION"],
						],
					],
				],
			],
		];
		parent::__construct($config);
		$this->catalog = new \Iplogic\Zero\Catalog();
		$this->catalog->product_iblock_id = $this->config["PRODUCT_IBLOCK_ID"];
		$this->catalog->offer_iblock_id = $this->config["OFFER_IBLOCK_ID"];
	}


	/**
	 * Export run / Запуск экспорта
	 * @return bool
	 */
	public function go()
	{
		$arFilter = $this->config["FILTER"];
		$arSelect = ["ID"];
		foreach( $this->config["COMPARISON"] as $c ) {
			if( $c["TYPE"] == "FIELD" ) {
				$arSelect[] = $c["VALUE"];
			}
			if( $c["TYPE"] == "PROP" ) {
				$arSelect[] = "PROPERTY_" . $c["VALUE"];
			}
			if( substr($c["TYPE"], 0, 6) == "PRICE_" ) {
				$arSelect[] = $c["TYPE"];
			}
			if( substr($c["TYPE"], 0, 6) == "STORE_" ) {
				$arSelect[] = str_replace("STORE_", "STORE_AMOUNT_", $c["TYPE"]);
			}
			if( $c["TYPE"] == "FUNC" ) {
				foreach( $c["PARAMS"] as $p ) {
					if( $p["TYPE"] == "FIELD" ) {
						$arSelect[] = $p["VALUE"];
					}
					if( $p["TYPE"] == "PROP" ) {
						$arSelect[] = "PROPERTY_" . $p["VALUE"];
					}
					if( substr($p["TYPE"], 0, 6) == "PRICE_" ) {
						$arSelect[] = $p["TYPE"];
					}
					if( substr($p["TYPE"], 0, 6) == "STORE_" ) {
						$arSelect[] = str_replace("STORE_", "STORE_AMOUNT_", $p["TYPE"]);
					}
				}
			}
		}
		$arSelect[] = "PROPERTY_" . $this->config["SKU_LINK"];
		$arSelect = array_unique($arSelect);
		$this->arProducts = [];
		$arFilter["IBLOCK_ID"] = $this->config["PRODUCT_IBLOCK_ID"];
		$_prd = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
		while( $prd = $_prd->GetNext() ) {
			$exclude = false;
			if( count($this->config["EXCLUDE_SECTION"]) ) {
				$sections = \CIBlockElement::GetElementGroups($prd["ID"], true);
				while( $ar_group = $sections->Fetch() ) {
					if( in_array($ar_group["ID"], $this->config["EXCLUDE_SECTION"]) ) {
						$exclude = true;
					}
				}
			}
			if( !$exclude ) {
				$this->arProducts[$prd["ID"]] = $this->getValArray($prd);
			}
		}
		unset($_prd);
		$arFilter["IBLOCK_ID"] = $this->config["OFFER_IBLOCK_ID"];
		$_prd = \CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
		while( $prd = $_prd->GetNext() ) {
			if( !isset($this->arProducts[$prd["PROPERTY_" . $this->config["SKU_LINK"] . "_VALUE"]]) ) {
				continue;
			}
			$this->arProducts[$prd["ID"]] = $this->getValArray($prd);
			$this->arProducts[$prd["PROPERTY_" . $this->config["SKU_LINK"] . "_VALUE"]]["EXCLUDE"] = "Y";
		}
		unset($_prd);

		$this->modifyResult();

		foreach( $this->arProducts as $key => $arProduct ) {
			if( $arProduct["EXCLUDE"] == "Y" ) {
				unset($this->arProducts[$key]);
				continue;
			}
			$item = [
				"NAME"     => "item",
				"CHILDREN" => [],
			];
			foreach( $this->config["COMPARISON"] as $key => $c ) {
				if( $c["TYPE"] == "CONST" ) {
					$item["CHILDREN"][] = [
						"NAME" => "g:g:" . $key,
						"TEXT" => self::prepareXmlText($c["VALUE"]),
					];
				}
				else {
					$item["CHILDREN"][] = [
						"NAME" => "g:g:" . $key,
						"TEXT" => self::prepareXmlText($arProduct[$key]),
					];
				}
			}
			$this->config["NODES"]["CHILDREN"][0]["CHILDREN"][] = $item;
			unset($this->arProducts[$key]);
		}
		$this->startXML();
		$this->saveXML();
	}


	/**
	 * Modification of an array of goods before passing to an XML object / Модификация массива товаров перед передачей в объект XML
	 */
	protected function modifyResult()
	{
		return;
	}


	/**
	 * Getting an array of product for XML from an array of product from the database / Получение массива товара для XML из массива товара из базы
	 *
	 * @param array $prd - product array from base / массив товара из базы
	 * @return array
	 */
	protected function getValArray($prd)
	{
		$arProduct = [];
		$parent = $this->arProducts[$prd["PROPERTY_" . $this->config["SKU_LINK"] . "_VALUE"]];
		foreach( $this->config["COMPARISON"] as $key => $c ) {
			if( $parent && $c["USE_PARENT"] == "H" ) {
				$arProduct[$key] = $parent[$key];
				continue;
			}
			if( $c["TYPE"] == "FIELD" ) {
				$arProduct[$key] = $prd[$c["VALUE"]];
			}
			if( $c["TYPE"] == "PROP" ) {
				$arProduct[$key] = $prd["PROPERTY_" . $c["VALUE"] . "_VALUE"];
			}
			if( substr($c["TYPE"], 0, 6) == "PRICE_" ) {
				$arProduct[$key] = $prd[$c["TYPE"]] . " " . $this->config["CURRENCY"];
			}
			if( substr($c["TYPE"], 0, 6) == "STORE_" ) {
				$arSelect[] = $prd[str_replace("STORE_", "STORE_AMOUNT_", $c["TYPE"])];
			}
			if( $c["TYPE"] == "FUNC" ) {
				$params = [];
				foreach( $c["PARAMS"] as $p ) {
					if( $p["TYPE"] == "FIELD" ) {
						$params[] = $prd[$p["VALUE"]];
					}
					if( $p["TYPE"] == "PROP" ) {
						$params[] = $prd["PROPERTY_" . $p["VALUE"] . "_VALUE"];
					}
					if( substr($p["TYPE"], 0, 6) == "PRICE_" ) {
						$params[] = $prd[$p["TYPE"]];
					}
					if( substr($p["TYPE"], 0, 6) == "STORE_" ) {
						$params[] = $prd[str_replace("STORE_", "STORE_AMOUNT_", $p["TYPE"])];
					}
				}
				$method = $c["VALUE"];
				$arProduct[$key] = $this->$method($params);
			}
			if( !$arProduct[$key] && $c["USE_PARENT"] == "Y" ) {
				$arProduct[$key] = $parent[$key];
			}
		}
		return $arProduct;
	}


	/**
	 * Getting availability in Google Merchant format (from $config) / Получение доступности в формате Google Merchant (из $config)
	 *
	 * @param array $params - parameters from $config array / параметры из массива $config
	 * @return string
	 */
	protected function getAvailability($params)
	{
		$val = $params[0];
		if( !$val ) {
			return "out_of_stock";
		}
		if( $val == "N" ) {
			return "out_of_stock";
		}
		return "in_stock";
	}


	/**
	 * Getting file address by ID (from $config) / Получение адреса файла по ID (из $config)
	 *
	 * @param array $params - parameters from $config array / параметры из массива $config
	 * @return string
	 */
	protected function getFile($params)
	{
		$p1 = intval($params[0]);
		$p2 = $params[1];
		if( $p1 > 0 ) {
			$ID = $p1;
		}
		elseif( $p2 > 0 ) {
			$ID = $p2;
		}
		else {
			return "";
		}
		$path = \CFile::getPath($ID);
		return $this->config["LINK"] . $path;
	}

}