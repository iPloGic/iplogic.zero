<?php

namespace Iplogic\Zero\Exchange;

/**
 * XML file creation class / Класс создания XML файлов
 * @package Iplogic\Zero\Exchange
 */
class ExportXML extends Base
{
	/**
	 * DomDocument object / Объект DomDocument
	 * @var object
	 */
	protected $xml;
	/**
	 * Main document node SimpleXMLElement object / SimpleXMLElement объект основного узла документа
	 * @var object
	 */
	protected $main_node;


	/**
	 * Class constructor / Конструктор класса
	 * @param array $config - configuration array / массив конфигурации
	 */
	function __construct($config = [])
	{
		$configKeys = [
			"DESTINATION",      //  saving to   local/ftp
			"NODES",
			"DISPLAY",
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
		$this->xml = new \DomDocument('1.0', LANG_CHARSET);
		$this->main_node = simplexml_import_dom($this->xml->createElement($this->config["NODES"]["NAME"]));
		if( isset($this->config["NODES"]["ATTR"]) ) {
			foreach( $this->config["NODES"]["ATTR"] as $name => $value ) {
				$this->main_node->addAttribute($name, $value);
			}
		}
	}


	/**
	 * Export run / Запуск экспорта
	 * @return bool
	 */
	public function go()
	{
		$this->startXML();
		return $this->saveXML();
	}


	/**
	 * Starts filling the main node / Начинает заполнение главного узла
	 */
	protected function startXML()
	{
		if(
			is_array($this->config["NODES"]["CHILDREN"][0]["CHILDREN"]) && 
			count($this->config["NODES"]["CHILDREN"][0]["CHILDREN"]) > 0
		) {
			$this->main_node = $this->putChildren($this->main_node, $this->config["NODES"]["CHILDREN"]);
		}
	}


	/**
	 * Adding children to a node / Добавление потомков к узлу
	 *
	 * @param object $node - node SimpleXMLElement object / SimpleXMLElement объект узла
	 * @param array $children - array of children
	 * @return mixed
	 */
	protected function putChildren($node, $children)
	{
		foreach( $children as $child ) {
			if( isset($child["TEXT"]) ) {
				$obChild = $node->addChild($child["NAME"], $this->prepareXmlText($child["TEXT"]));
			}
			else {
				$obChild = $node->addChild($child["NAME"]);
			}
			if( isset($child["ATTR"]) ) {
				foreach( $child["ATTR"] as $name => $value ) {
					$obChild->addAttribute($name, $value);
				}
			}
			if( isset($child["CHILDREN"]) ) {
				$obChild = $this->putChildren($obChild, $child["CHILDREN"]);
			}
		}
		return $node;
	}


	/**
	 * Generating an XML file from the main node object / Формирование XML файла из объекта главного узла
	 * @return bool
	 */
	protected function saveXML()
	{
		$this->xml->appendChild(dom_import_simplexml($this->main_node));
		$this->xml->formatOutput = true;
		if( $this->config["DISPLAY"] ) {
			header('Content-type: text/xml; charset=utf-8');
			echo $this->xml->saveXML(null, LIBXML_NOEMPTYTAG);
		}
		else {
			if( !$this->xml->save(self::processingFilePath($this->config["LOCAL_FILE"]), LIBXML_NOEMPTYTAG) ) {
				if( ZERO_EXCHANGE_DEBUG ) {
					echo "Cant save local file " . self::processingFilePath($this->config["LOCAL_FILE"]) . "<br><br>";
				}
				return false;
			}
			if( $this->config["DESTINATION"] == "ftp" ) {
				if( !$this->sendFTP() ) {
					if( ZERO_EXCHANGE_DEBUG ) {
						echo "Cant copy file to FTP<br><br>";
					}
					return false;
				}
			}
		}
		return true;
	}
}
