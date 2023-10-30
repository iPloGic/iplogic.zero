<?php

namespace Iplogic\Zero;

/**
 * Class for creating CLI scripts / Класс для создания CLI скриптов
 * @package Iplogic\Zero
 */
class Cli
{
	/**
	 * Module ID / ID модуля
	 * @var string
	 */
	public static $moduleID = "iplogic.zero";

	/**
	 * Sets parameters from module settings / Устанавливает параметры из настроек модуля
	 */
	public static function initFromOptions()
	{
		if( !defined("ZERO_CLI_EXEC_METHOD") ) {
			$METHOD = \Bitrix\Main\Config\Option::get(self::$moduleID, "cli_execute_method", "WGET");
			define("ZERO_CLI_EXEC_METHOD", $METHOD);
		}
		if( !defined("ZERO_CLI_PHP") ) {
			$PHP = \Bitrix\Main\Config\Option::get(self::$moduleID, "cli_php", "/usr/bin/php");
			define("ZERO_CLI_PHP", $PHP);
		}
		if( !defined("ZERO_CLI_MISS_CERT") ) {
			$MISS_CERT = \Bitrix\Main\Config\Option::get(self::$moduleID, "cli_wget_miss_cert", "Y");
			define("ZERO_CLI_EXEC_MISS_CERT", $MISS_CERT);
		}
		return;
	}

	/**
	 * Checks whether the script is running via the console / Проверяет запущен ли скрипт через консоль
	 * @return bool
	 */
	public static function isCLI()
	{
		if( php_sapi_name() == "cli" ) {
			return true;
		}
		return false;
	}

	/**
	 * Stops working if the script is not run through the console / Прекращает работу если скрипт запущен не через консоль
	 */
	public static function checkForCLI()
	{
		if( !self::isCLI() ) {
			die("Deny: Only for CLI");
		}
		return;
	}

	/**
	 * Adds the "DOCUMENT_ROOT" and "SCRIPT_DIR_NAME" elements to the $_SERVER array / Добавляет в массив $_SERVER элементы "DOCUMENT_ROOT" и "SCRIPT_DIR_NAME"
	 */
	public static function completeServerArray()
	{
		global $_SERVER;
		if( !isset($_SERVER["DOCUMENT_ROOT"]) || $_SERVER["DOCUMENT_ROOT"] == "" ) {
			$_SERVER["DOCUMENT_ROOT"] = realpath(__DIR__ . "/../../../..");
		}
		$a = explode("/", $_SERVER['SCRIPT_NAME']);
		$a[count($a) - 1] = "";
		$_SERVER['SCRIPT_DIR_NAME'] = implode("/", $a);
		return;
	}

	/**
	 * Runs the script in the background / Запускает скрипт в фоновом режиме
	 *
	 * @param string $script - path to the script (absolute or http, depending on the settings) / путь к скрипту (абсолютный или http в зависимости от настроек)
	 * @param bool $die - stop executing the current script after startup / прекратить выполнение текущего скрипта после запуска
	 * @param array $argv - arguments for console launch / аргументы для консольного запуска
	 * @return array
	 */
	public static function runInBackground($script, $die = false, $argv = [])
	{
		if( !defined("ZERO_CLI_EXEC_METHOD") ) {
			/** Method for running background scripts / Метод запуска фоновых скриптов [PHP/WGET] */
			define("ZERO_CLI_EXEC_METHOD", "WGET");
		}
		if( !defined("ZERO_CLI_PHP") ) {
			/** Path to PHP / Путь к PHP */
			define("ZERO_CLI_PHP", "/usr/bin/php");
		}
		if( !defined("ZERO_CLI_MISS_CERT") ) {
			/** Do not check the certificate when starting via wget / Не проверять сертификат при запуске через wget [Y/N] */
			define("ZERO_CLI_EXEC_MISS_CERT", "Y");
		}
		$comm = $script;
		if( ZERO_CLI_EXEC_METHOD == "WGET" ) {
			$comm = "wget ";
			if( ZERO_CLI_EXEC_MISS_CERT == "Y" ) {
				$comm .= "--no-check-certificate ";
			}
			$comm .= "-b -q -O - " . $script;
		}
		if( ZERO_CLI_EXEC_METHOD == "PHP" ) {
			$comm = ZERO_CLI_PHP . " -f " . $script;
			foreach( $argv as $a ) {
				$comm .= " " . $a;
			}
		}
		$result = [];
		$state = -1;
		exec($comm, $result, $state);
		if( $die ) {
			die();
		}
		return $result;
	}
}
