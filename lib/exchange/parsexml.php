<?php

namespace Iplogic\Zero\Exchange;


/**
 * Class for XML file parsing / Класс для парсинга XML файлов
 * @package Iplogic\Zero\Exchange
 */
class ParseXML extends Base
{
	/**
	 * Class constructor / Конструктор класса
	 * @param array $config - configuration array / массив конфигурации
	 */
	function __construct($config = [])
	{
		$configKeys = [
			"SOURCE",      //  loading from   local/ftp
			"LIST_NODE",
			"COMPARISON",
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
	}


	/**
	 * Copying a local file / Копирование локального файла
	 *
	 * @param array $substitutes - array of replacement values / массив замены значений
	 * @return bool
	 */
	public function copyLocalFile($substitutes = [])
	{
		if( $this->config["SOURCE"] == "local" ) {
			if( !is_array($substitutes) || !count($substitutes) ) {
				return copy($this->config["REMOTE_FILE"], self::processingFilePath($this->config["LOCAL_FILE"]));
			}
			else {
				$fileText = file_get_contents($this->config["REMOTE_FILE"]);
				foreach( $substitutes as $old => $new ) {
					$fileText = str_replace(["<" . $old, "</" . $old], ["<" . $new, "</" . $new], $fileText);
				}
				if( file_put_contents($this->config["LOCAL_FILE"], $fileText) > 0 ) {
					return true;
				}
			}
		}
		return false;
	}


	/**
	 * Getting array of elements objects / Получение массива объектов элементов
	 * @return array|false
	 */
	public function getElementsList()
	{
		if( $this->config["SOURCE"] == "ftp" ) {
			if( !$this->getFTP() ) {
				if( ZERO_EXCHANGE_DEBUG ) {
					echo "Cant load file from FTP<br><br>";
				}
				return false;
			}
		}
		if( !$xml = self::getXML($this->config["LOCAL_FILE"]) ) {
			return false;
		}
		if( !$this->config["LIST_NODE"] || $this->config["LIST_NODE"] == "/" ) {
			return $xml->children();
		}
		if( $node = $this->getNodeByPath($xml, $this->config["LIST_NODE"]) ) {
			return $node->children();
		}
		if( ZERO_EXCHANGE_DEBUG ) {
			echo "Cant get node " . $this->config["LIST_NODE"] . "<br><br>";
		}
		return false;
	}


	/**
	 * Getting node object by string path / Получение объекта узла по строчному пути
	 *
	 * @param object $xml - XML object / объект XML
	 * @param string $path - string containing the path to the node / строка, содержащая путь до узла
	 * @return object|false
	 */
	public function getNodeByPath(&$xml, $path)
	{
		$arPath = explode("/", $path);
		$node = $xml;
		if( count($arPath) == 2 && $node->getName() == $arPath[1] ) {
			return $xml;
		}
		foreach( $arPath as $key => $child ) {
			if( $key > 1 ) {
				$node = $node->$child;
				if( $key == (count($arPath) - 1) ) {
					return $node;
				}
			}
		}
		return false;
	}

	/**
	 * Getting array of elements / Получение массива элементов
	 * @return array|false
	 */
	public function getElementsListArray()
	{
		$arList = [];
		if( $children = $this->getElementsList() ) {
			foreach( $children as $item ) {
				$arList[] = \Iplogic\Zero\Helper::objToArray($item);
			}
			return $arList;
		}
		return false;
	}


	/**
	 * Getting an array of elements by the mask specified in the "COMPARISON" element of the $config array /
	 * Получение массива элементов по маске заданной в элементе "COMPARISON" массива $config
	 * @return array|false
	 */
	public function getClearElementsListArray()
	{
		if( !is_array($this->config["COMPARISON"]) ) {
			if( ZERO_EXCHANGE_DEBUG ) {
				echo "\$config['COMPARISON'] is not an array<br><br>";
			}
			return false;
		}
		if( !$list = $this->getElementsList() ) {
			return false;
		}
		$clearArray = [];
		foreach( $list as $item ) {
			$val = [];
			foreach( $this->config["COMPARISON"] as $field => $source ) {
				$name = $source["NAME"];
				if( $source["TYPE"] == "NODE" ) {
					$val[$field] = $item->$name;
				}
				if( $source["TYPE"] == "ATTR" ) {
					$val[$field] = $item->attributes()->$name->__toString();
				}
				if( $source["TYPE"] == "SUBNODE_TEXT" ) {
					$val[$field] = $item->$name->__toString();
				}
			}
			$clearArray[] = $val;
		}
		unset($list);
		return $clearArray;
	}

}