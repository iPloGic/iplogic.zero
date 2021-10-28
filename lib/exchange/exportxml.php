<?php
namespace Iplogic\Zero\Exchange;

class ExportXML extends Base
{
	protected $xml;
	protected $main_node;

	function __construct($config = [])
	{
		$configKeys = [
			"DESTINATION",      //  saving to   local/ftp
			"NODES",
			"DISPLAY",
		];
		foreach ($configKeys as $key) {
			if (isset($config[$key]))
				$this->config[$key] = $config[$key];
			else
				$this->config[$key] = false;
		}
		parent::__construct($config);
		$this->xml = new \DomDocument('1.0',LANG_CHARSET);
		$this->main_node = simplexml_import_dom($this->xml->createElement($this->config["NODES"]["NAME"]));
		if(isset($this->config["NODES"]["ATTR"])) {
			foreach($this->config["NODES"]["ATTR"] as $name => $value) {
				$this->main_node->addAttribute($name, $value);
			}
		}
	}

	protected function startXML() {
		$this->main_node = $this->putChildren($this->main_node, $this->config["NODES"]["CHILDREN"]);
	}

	protected function putChildren($node, $children) {
		foreach($children as $child) {
			if (isset($child["TEXT"]))
				$obChild = $node->addChild($child["NAME"], $this->prepareXmlText($child["TEXT"]));
			else
				$obChild = $node->addChild($child["NAME"]);
			if(isset($child["ATTR"])) {
				foreach($child["ATTR"] as $name => $value) {
					$obChild->addAttribute($name, $value);
				}
			}
			if (isset($child["CHILDREN"]))
				$obChild = $this->putChildren($obChild, $child["CHILDREN"]);
		}
		return $node;
	}

	protected function saveXML() {
		$this->xml->appendChild(dom_import_simplexml($this->main_node));
		$this->xml->formatOutput = true;
		if($this->config["DISPLAY"]) {
			header('Content-type: text/xml; charset=utf-8');
			echo $this->xml->saveXML(null, LIBXML_NOEMPTYTAG );
		}
		else {
			if (!$this->xml->save(self::processingFilePath($this->config["LOCAL_FILE"]), LIBXML_NOEMPTYTAG )) {
				if (ZERO_EXCHANGE_DEBUG) {
					echo "Cant save local file ".self::processingFilePath($this->config["LOCAL_FILE"])."<br><br>";
				}
				return false;
			}
			if ($this->config["DESTINATION"] == "ftp") {
				if(!$this->sendFTP()) {
					if (ZERO_EXCHANGE_DEBUG) {
						echo "Cant copy file to FTP<br><br>";
					}
					return false;
				}
			}
		}
		return true;
	}
}
