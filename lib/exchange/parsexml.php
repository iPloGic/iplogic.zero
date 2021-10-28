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
				if (ZERO_EXCHANGE_DEBUG) {
					echo "Cant load file from FTP<br><br>";
				}
				return false;
			}
		}
		if (!$xml = self::getXML( $this->config["LOCAL_FILE"] )) {
			return false;
		}
		if (!$this->config["LIST_NODE"] || $this->config["LIST_NODE"] == "/") {
			return $xml->children();
		}
		if ($node = $this->getNodeByPath($xml, $this->config["LIST_NODE"])) {
			return $node->children();
		}
		if (ZERO_EXCHANGE_DEBUG) {
			echo "Cant get node ".$this->config["LIST_NODE"]."<br><br>";
		}
		return false;
	}

	public function getNodeByPath(&$xml, $path) {
		$arPath = explode("/", $path);
		$node = $xml;
		if (count($arPath) == 2 && $node->getName() == $arPath[1]) {
			return $xml;
		}
		foreach($arPath as $key => $child) {
			if($key > 1) {
				$node = $node->$child;
				if ($key == (count($arPath)-1)) {
					return $node;
				}
			}
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
				$name = $source["NAME"];
				if ($source["TYPE"] == "ATTR")
					$val[$field] = $item->attributes()->$name->__toString();
				if ($source["TYPE"] == "SUBNODE_TEXT") {
					$val[$field] = $item->$name->__toString();
				}
			}
			$clearArray[] = $val;
		}
		return $clearArray;
	}

}