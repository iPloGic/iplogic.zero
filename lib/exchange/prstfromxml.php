<?php
namespace Iplogic\Zero\Exchange;

use \Iplogic\Zero\Catalog;

class PriceAndStockFromXML extends ParseXML
{
	protected $catalog;
	protected $arFromXML;
	protected $arProdIds;

	function __construct($config = [])
	{
		$configKeys = [
			"RESET_STOCKS_TO_ZERO",
			"PRODUCT_IBLOCK_ID",
			"OFFER_IBLOCK_ID",
			"KEY_FIELD",
			"CURRENCY",
		];
		foreach($configKeys as $key) {
			if (isset( $config[$key]))
				$this->config[$key] = $config[$key];
			else
				$this->config[$key] = false;
		}
		parent::__construct($config);
		$this->catalog = new Catalog();
		$this->catalog->product_iblock_id = $this->config["PRODUCT_IBLOCK_ID"];
		$this->catalog->offer_iblock_id = $this->config["OFFER_IBLOCK_ID"];
		$this->catalog->currency = $this->config["CURRENCY"];
	}

	public function go()
	{
		$this->arProdIds = $this->catalog->getAllIdsByProp($this->config["KEY_FIELD"]);
		if($this->config["RESET_STOCKS_TO_ZERO"]) {
			foreach($this->config["COMPARISON"] as $field => $source) {
				if ($field == "STOCK") {
					$this->catalog->setStock($this->arProdIds);
				}
				if (substr($field,0,6) == "STORE_") {
					$store = self::getNumberFromKey($field);
					$this->catalog->setStock($this->arProdIds, 0, $store);
				}
			}
		}
		if($this->arFromXML = $this->getClearElementsListArray()) {
			foreach($this->arFromXML as $set) {
				$productID = false;
				foreach($set as $key => $value) {
					if ($key == $this->config["KEY_FIELD"]) {
						$productID = $this->arProdIds[$value];
						break;
					}
				}
				if($productID) {
					foreach($set as $key => $value) {
						if ($value == "") {
							$value = 0;
						}
						if ($key == "STOCK") {
							$this->catalog->setStock($productID, $value);
						}
						if (substr($key,0,6) == "STORE_") {
							$store = self::getNumberFromKey($key);
							$this->catalog->setStock($productID, $value, $store);
						}
						if (substr($key,0,6) == "PRICE_") {
							$group = self::getNumberFromKey($key);
							$this->catalog->setPrice($productID, $value, $group);
						}
					}
				}
			}
		}
	}

}