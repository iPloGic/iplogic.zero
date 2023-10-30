<?php

namespace Iplogic\Zero\Exchange;

use \Iplogic\Zero\Catalog;


/**
 * The class of updating prices and stocks of the catalog from an XML file /
 * Класс обновления цены и остатков каталога из XML файла
 * @package Iplogic\Zero\Exchange
 */
class PriceAndStockFromXML extends ParseXML
{
	/**
	 * \Iplogic\Zero\Catalog object / Объект \Iplogic\Zero\Catalog
	 * @var object
	 */
	protected $catalog;
	/**
	 * An array of data from an XML file / Массив данных из XML файла
	 * @var array
	 */
	protected $arFromXML;
	/**
	 * Array of the form "key field value" => "Product ID" / Массив вида "значение ключевого поля" => "ID товара"
	 * @var array
	 */
	protected $arProdIds;


	/**
	 * Class constructor / Конструктор класса
	 * @param array $config - configuration array / массив конфигурации
	 */
	function __construct($config = [])
	{
		$configKeys = [
			"RESET_STOCKS_TO_ZERO",
			"PRODUCT_IBLOCK_ID",
			"OFFER_IBLOCK_ID",
			"KEY_FIELD",
			"CURRENCY",
		];
		foreach( $configKeys as $key ) {
			if( isset($config[$key]) ) {
				$this->config[$key] = $config[$key];
			}
			else {
				$this->config[$key] = false;
			}
		}
		parent::__construct($config);
		$this->catalog = new Catalog();
		$this->catalog->product_iblock_id = $this->config["PRODUCT_IBLOCK_ID"];
		$this->catalog->offer_iblock_id = $this->config["OFFER_IBLOCK_ID"];
		$this->catalog->currency = $this->config["CURRENCY"];
	}


	/**
	 * Import run / Запуск импорта
	 * @return bool
	 */
	public function go()
	{
		$this->arProdIds = $this->catalog->getAllIdsByProp($this->config["KEY_FIELD"]);
		if( $this->config["RESET_STOCKS_TO_ZERO"] ) {
			foreach( $this->config["COMPARISON"] as $field => $source ) {
				if( $field == "STOCK" ) {
					$this->catalog->setStock($this->arProdIds);
				}
				if( substr($field, 0, 6) == "STORE_" ) {
					$store = self::getNumberFromKey($field);
					$this->catalog->setStock($this->arProdIds, 0, $store);
				}
			}
		}
		if( $this->arFromXML = $this->getClearElementsListArray() ) {
			foreach( $this->arFromXML as $set ) {
				$productID = false;
				foreach( $set as $key => $value ) {
					if( $key == $this->config["KEY_FIELD"] ) {
						$productID = $this->arProdIds[$value];
						break;
					}
				}
				if( $productID ) {
					foreach( $set as $key => $value ) {
						if( $value == "" ) {
							$value = 0;
						}
						if( $key == "STOCK" ) {
							$this->catalog->setStock($productID, $value);
						}
						if( substr($key, 0, 6) == "STORE_" ) {
							$store = self::getNumberFromKey($key);
							$this->catalog->setStock($productID, $value, $store);
						}
						if( substr($key, 0, 6) == "PRICE_" ) {
							$group = self::getNumberFromKey($key);
							$this->catalog->setPrice($productID, $value, $group);
						}
					}
				}
			}
		}
	}

}