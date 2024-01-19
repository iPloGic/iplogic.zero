<?php

namespace Iplogic\Zero\Exchange;

use \Bitrix\Main\Application;
use \Bitrix\Main\Web\HttpClient;
use \Bitrix\Main\Web\Json;


/**
 * Base exchange class / Базовый класс обмена
 * @package Iplogic\Zero\Exchange
 */
class Base
{
	/**
	 * Configuration array / Массив конфигурации
	 * @var array
	 */
	protected $config;
	/**
	 * FTP connection object / Объект FTP соединения
	 * @var object
	 */
	protected $ftp;


	/**
	 * Class constructor / Конструктор класса
	 * @param array $config - configuration array / массив конфигурации
	 */
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


	/**
	 * Getting SimpleXMLElement object from XML file / Получает SimpleXMLElement объект из XML файла
	 *
	 * @param string $file - name of file / имя файла
	 * @return false|object
	 */
	public static function getXML($file)
	{
		$name = self::processingFilePath($file);
		if( !$objXML = simplexml_load_file($name) ) {
			if( ZERO_EXCHANGE_DEBUG ) {
				echo "Cant create XML object tree from file:<br>" . $name . "<br><br>";
				$errors = libxml_get_errors();
				foreach( $errors as $error ) {
					echo display_xml_error($error, $objXML);
				}
			}
			return false;
		}
		return $objXML;
	}


	/**
	 * Getting array from XML file / Получает массив из XML файла
	 *
	 * @param string $file - name of file / имя файла
	 * @return mixed
	 */
	public static function getXMLasArray($file)
	{
		return self::getXML($file)->GetArray();
	}


	/**
	 * Sending an HTTP request using Bitrix / Отправка HTTP запроса средствами Битрикс
	 *
	 * @param string $url - send URL / URL отправки
	 * @param string $type - request type / тип запроса [GET/POST/PUT]
	 * @param array $headers - request headers / заголовки запроса
	 * @param mixed $params - request parameters / параметры запроса
	 * @return array
	 */
	public static function sendHttpQuery($url, $type = 'POST', $headers = [], $params = null)
	{
		$type = strtoupper($type);
		$cl = new HttpClient(['socketTimeout' => 100]);
		$cl->disableSslVerification();
		foreach( $headers as $key => $val ) {
			$cl->setHeader($key, $val);
		}
		$cl->query($type, $url, $params);
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


	/**
	 * Sending an HTTP request using CURL / Отправка HTTP запроса средствами CURL
	 *
	 * @param string $url - send URL / URL отправки
	 * @param string $type - request type / тип запроса [GET/POST/PUT]
	 * @param array $header - request headers / заголовки запроса
	 * @param array $params - request parameters / параметры запроса
	 * @return array
	 */
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
			//curl_setopt($ch, CURLOPT_HEADEROPT, CURLHEADER_UNIFIED);
			curl_setopt(
				$ch,
				CURLOPT_USERAGENT,
				'Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:53.0) Gecko/20100101 Firefox/53.0'
			);
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
		];
	}


	/**
	 * Replacing special characters in text for use in XML / Замена специальных символов в тексте для использования в XML
	 *
	 * @param string $str - text line / текстовая строка
	 * @return string
	 */
	public static function prepareXmlText($str)
	{
		$bad = ["<", ">", "'", '"', "&"];
		$good = ["&lt;", "&gt;", "&apos;", "&quot;", "&amp;"];
		$str = str_replace($bad, $good, $str);
		return $str;
	}


	/**
	 * Encoding text line from UTF-8 to windows-1251 / Перекодирование текстовой строки из UTF-8 в windows-1251
	 *
	 * @param string $str - text line / текстовая строка
	 * @return string
	 */
	public static function win1251Encode($str)
	{
		$str = iconv("UTF-8", "windows-1251", $str);
		return $str;
	}


	/**
	 * Recursive encoding of array from UTF-8 to windows-1251 / Рекурсивное перекодирование массива из UTF-8 в windows-1251
	 *
	 * @param array $array - array for encoding / массив для перекодирования
	 * @return array
	 */
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


	/**
	 * Encoding text line from windows-1251 to UTF-8 / Перекодирование текстовой строки из windows-1251 в UTF-8
	 *
	 * @param string $str - text line / текстовая строка
	 * @return string
	 */
	public static function utfEncode($str)
	{
		$str = iconv("windows-1251", "UTF-8", $str);
		return $str;
	}


	/**
	 * Recursive encoding of array from windows-1251 to UTF-8 / Рекурсивное перекодирование массива из windows-1251 в UTF-8
	 *
	 * @param array $array - array for encoding / массив для перекодирования
	 * @return array
	 */
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


	/**
	 * JSON validation / Валидация JSON
	 *
	 * @param string $string - text line / текстовая строка
	 * @return bool
	 */
	public static function isJson($string)
	{
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}


	/**
	 * Creating an FTP connection object / Создание объекта FTP соединения
	 *
	 * @return false|object
	 */
	public function connectFTP()
	{
		$this->ftp = ftp_connect($this->config['FTP_HOST']);
		$login = ftp_login($this->ftp, $this->config['FTP_USER'], $this->config['FTP_PASS']);
		if( !$login ) {
			return false;
		}
		ftp_pasv($this->ftp, true);
		return $this->ftp;
	}


	/**
	 * Sending a file via FTP / Отправка файла через FTP
	 *
	 * @param mixed $remote - remote file name / имя удаленного файла
	 * @param mixed $local - local file name / имя локального файла
	 * @return bool
	 */
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


	/**
	 * Uploading a file via FTP / Загрузка файла через FTP
	 *
	 * @param mixed $remote - remote file name / имя удаленного файла
	 * @param mixed $local - local file name / имя локального файла
	 * @return bool
	 */
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


	/**
	 * Getting a numeric value from a string key / Получение числового значения из строкового ключа
	 *
	 * For example, for the PRICE_5 key, 5 will be returned / Например, для ключа PRICE_5 будет возвращено 5
	 *
	 * @param string $key - string key / строковый ключ [xxxx_num]
	 * @return int
	 */
	protected static function getNumberFromKey($key)
	{
		$ar = explode("_", $key);
		if( is_int($ar[count($ar) - 1]) ) {
			return $ar[count($ar) - 1];
		}
		return 0;
	}


	/**
	 * File Name Processing / Обработка имен файлов
	 *
	 * More about names / Подробно об именах https://iplogic.ru/doc/course/3/page/29/?LESSON_PATH=27.29
	 *
	 * @param string $file - file name / имя файла
	 * @return string
	 */
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
