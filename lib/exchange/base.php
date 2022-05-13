<?php

namespace Iplogic\Zero\Exchange;

use \Bitrix\Main\Application;
use \Bitrix\Main\Web\HttpClient;
use \Bitrix\Main\Web\Json;

class Base
{
	protected $config;
	protected $ftp;

	function __construct($config = [])
	{
		$configKeys = [
			"FTP_HOST",
			"FTP_USER",
			"FTP_PASS",
			"REMOTE_FILE",
			"LOCAL_FILE",
			"TARGET_FILE",
		];
		foreach( $configKeys as $key ) {
			if( isset($config[$key]) ) {
				$this->config[$key] = $config[$key];
			}
			else {
				$this->config[$key] = false;
			}
		}
	}

	public static function getXML($file, $clean_ns = true)
	{
		$name = self::processingFilePath($file);
		if( !$objXML = simplexml_load_file($name) ) {
			if( ZERO_EXCHANGE_DEBUG ) {
				echo "Cant create XML object tree from file:<br>" . $name . "<br><br>";
			}
			return false;
		}
		return $objXML;
	}

	public static function getXMLasArray($file)
	{
		return self::getXML($file)->GetArray();
	}

	public static function sendHttpQuery($url, $type = 'POST', $headers = [], $params = null)
	{
		$type = strtoupper($type);
		$cl = new HttpClient(['socketTimeout' => 100]);
		$cl->disableSslVerification();
		foreach( $headers as $key => $val ) {
			$cl->setHeader($key, $val);
		}
		$cl->query($type, $url, $params);
		$body = "";
		if( self::isJson($cl->getResult()) ) {
			$body = Json::decode($cl->getResult());
		}
		else {
			$body = $cl->getResult();
		}
		return [
			"status"  => $cl->getStatus(),
			"body"    => $body,
			"headers" => $cl->getHeaders()->toArray(),
			"errors"  => $cl->getError(),
		];
	}

	public static function sendHttpQueryCurl($url, $type = 'GET', $header = [], $params = [])
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		#curl_setopt($ch, CURLOPT_HEADER, 1);
		if( count($header) ) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		}

		if( $type == 'GET' ) {
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 600);
			curl_setopt($ch, CURLOPT_HEADEROPT, CURLHEADER_UNIFIED);
			curl_setopt($ch, CURLOPT_USERAGENT,
				'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:53.0) Gecko/20100101 Firefox/53.0');
		}
		if( $type == 'POST' ) {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		}
		if( $type == 'PUT' ) {
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
			curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
		}
		$result = curl_exec($ch);
		$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		return [
			"status" => $code,
			"result" => $result,
			//"headers" =>
		];
	}

	public static function prepareXmlText($str)
	{
		$bad = ["<", ">", "'", '"', "&"];
		$good = ["&lt;", "&gt;", "&apos;", "&quot;", "&amp;"];
		$str = str_replace($bad, $good, $str);
		return $str;
	}

	public static function win1251Encode($str)
	{
		$str = iconv("UTF-8", "windows-1251", $str);
		return $str;
	}

	public static function win1251EncodeRecursive($array)
	{
		foreach( $array as $key => $value ) {
			if( is_array($value) ) {
				$array[$key] = self::win1251EncodeRecursive($array[$key]);
			}
			else {
				$array[$key] = win1251Encode($value);
			}
		}
		return $array;
	}

	public static function utfEncode($str)
	{
		$str = iconv("windows-1251", "UTF-8", $str);
		return $str;
	}

	public static function utfEncodeRecursive($array)
	{
		foreach( $array as $key => $value ) {
			if( is_array($value) ) {
				$array[$key] = self::utfEncodeRecursive($array[$key]);
			}
			else {
				$array[$key] = utfEncode($value);
			}
		}
		return $array;
	}

	public static function isJson($string)
	{
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}

	public function connectFTP()
	{
		$this->ftp = ftp_connect($this->config['FTP_HOST']);
		$login = ftp_login($this->ftp, $this->config['FTP_USER'], $this->config['FTP_PASS']);
		if( !$login ) {
			return false;
		}
		return $this->ftp;
	}

	public function sendFTP($remote = false, $local = false)
	{
		if( !$remote ) {
			if( $this->config["REMOTE_FILE"] ) {
				$remote = $this->config["REMOTE_FILE"];
			}
			else {
				return false;
			}
		}
		if( !$local ) {
			if( $this->config["LOCAL_FILE"] ) {
				$local = $this->config["LOCAL_FILE"];
			}
			else {
				return false;
			}
		}
		if( !$this->connectFTP() ) {
			if( ZERO_EXCHANGE_DEBUG ) {
				echo "Cant connect to FTP<br><br>";
			}
			return false;
		}
		else {
			$res = ftp_put($this->ftp, $remote, self::processingFilePath($local), FTP_ASCII);
			ftp_close($this->ftp);
			return $res;
		}
	}

	public function getFTP($local = false, $remote = false)
	{
		if( !$remote ) {
			if( $this->config["REMOTE_FILE"] ) {
				$remote = $this->config["REMOTE_FILE"];
			}
			else {
				return false;
			}
		}
		if( !$local ) {
			if( $this->config["LOCAL_FILE"] ) {
				$local = $this->config["LOCAL_FILE"];
			}
			else {
				return false;
			}
		}
		if( !$this->connectFTP() ) {
			if( ZERO_EXCHANGE_DEBUG ) {
				echo "Cant connect to FTP<br><br>";
			}
			return false;
		}
		else {
			$res = ftp_get($this->ftp, self::processingFilePath($local), $remote, FTP_ASCII);
			ftp_close($this->ftp);
			return $res;
		}
	}

	protected static function getNumberFromKey($key)
	{
		$ar = explode("_", $key);
		return $ar[count($ar) - 1];
	}

	protected static function processingFilePath($file)
	{
		if(
			substr($file, 0, 2) == './' ||
			substr($file, 0, 3) == '../' ||
			substr($file, 0, 4) == 'http'
		) {
			$name = $file;
		}
		else {
			$name = Application::getDocumentRoot() . $file;
		}
		return $name;
	}

}
