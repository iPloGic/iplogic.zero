<?php
namespace Iplogic\Zero\Exchange;

class ParseXML extends Base
{
	function __construct($config = [])
	{
		$configKeys = [
			"SOURCE",      //  loading from   local/ftp
			"LIST_NODE",
			"COMPARISON",
		];
		foreach($configKeys as $key) {
			if (isset( $config[$key]))
				$this->config[$key] = $config[$key];
			else
				$this->config[$key] = false;
		}
		parent::__construct($config);
	}

	public function getElementsList()
	{
		if ($this->config["SOURCE"] == "ftp") {
			if(!$this->getFTP()) {
				return false;
			}
		}
		if (!$xml = self::getXML( $this->config["LOCAL_FILE"] )) {
			return false;
		}
		$arList = [];
		if ($node = $xml->SelectNodes($this->config["LIST_NODE"])) {
			return $node->children();
		}
		if (ZERO_EXCHANGE_DEBUG) {
			echo "Cant get node ".$this->config["LIST_NODE"]."<br><br>";
		}
		return false;
	}

	public function getElementsListArray()
	{
		$arList = [];
		if ($children = $this->getElementsList()) {
			foreach($children as $item) {
				$arList[] = \Iplogic\Zero\Helper::objToArray($item);
			}
			return $arList;
		}
		return false;
	}

	public function getClearElementsListArray() {
		if(!is_array($this->config["COMPARISON"])) {
			if (ZERO_EXCHANGE_DEBUG) {
				echo "\$config['COMPARISON'] is not an array<br><br>";
			}
			return false;
		}
		if(!$list = $this->getElementsList()) {
			return false;
		}
		$clearArray = [];
		foreach($list as $item) {
			$val = [];
			foreach ($this->config["COMPARISON"] as $field => $source) {
				if ($source["TYPE"] == "ATTR")
					$val[$field] = $item->getAttribute($source["NAME"]);
				if ($source["TYPE"] == "SUBNODE_TEXT") {
					$subnodes = $item->elementsByName($source["NAME"]);
					if(count($subnodes))
						$val[$field] = $subnodes[0]->textContent();
				}
			}
			$clearArray[] = $val;
		}
		return $clearArray;
	}

}