<?php
namespace Iplogic\Zero;

class Cli
{
	public static $moduleID = "iplogic.zero";

	function initFromOptions()
	{
		if(!defined("ZERO_CLI_EXEC_METHOD")) {
			$METHOD = \Bitrix\Main\Config\Option::get(self::$moduleID, "cli_execute_method", "WGET");
			define("ZERO_CLI_EXEC_METHOD", $METHOD);
		}
		if(!defined("ZERO_CLI_PHP")) {
			$PHP = \Bitrix\Main\Config\Option::get(self::$moduleID, "cli_php", "/usr/bin/php");
			define("ZERO_CLI_EXEC_METHOD", $PHP);
		}
		if(!defined("ZERO_CLI_MISS_CERT")) {
			$MISS_CERT = \Bitrix\Main\Config\Option::get(self::$moduleID, "cli_wget_miss_cert", "Y");
			define("ZERO_CLI_EXEC_MISS_CERT", $MISS_CERT);
		}
	}

	public static function isCLI()
	{
		if (php_sapi_name() == "cli") {
			return true;
		}
		return false;
	}

	public static function checkForCLI()
	{
		if (!self::isCLI()) {
			die("Deny: Only for CLI");
		}
		return;
	}

	public static function completeServerArray()
	{
		global $_SERVER;
		if(!isset($_SERVER["DOCUMENT_ROOT"]) || $_SERVER["DOCUMENT_ROOT"] == "") {
			$_SERVER["DOCUMENT_ROOT"] = realpath(__DIR__."/../../../..");
		}
		$a = explode("/", $_SERVER['SCRIPT_NAME']);
		$a[count($a)-1] = "";
		$_SERVER['SCRIPT_DIR_NAME'] = implode("/",$a);
		return;
	}

	public static function runInBackground($script, $die = false, $argv=[])
	{
		if(!defined("ZERO_CLI_EXEC_METHOD"))
			define("ZERO_CLI_EXEC_METHOD", "WGET");
		if(!defined("ZERO_CLI_PHP"))
			define("ZERO_CLI_PHP", "/usr/bin/php");
		if(!defined("ZERO_CLI_MISS_CERT"))
			define("ZERO_CLI_EXEC_MISS_CERT", "Y");
		$comm = $script;
		if(ZERO_CLI_EXEC_METHOD == "WGET") {
			$comm = "wget ";
			if(ZERO_CLI_EXEC_MISS_CERT == "Y") {
				$comm .= "--no-check-certificate ";
			}
			$comm .= "-b -q -O - ".$script;
		}
		if(ZERO_CLI_EXEC_METHOD == "PHP") {
			$comm = ZERO_CLI_PHP." -f ".$script;
			foreach($argv as $a) {
				$comm .= " ".$a;
			}
		}
		$result = [];
		$state = -1;
		exec($comm, $result, $state);
		if($die) {
			die();
		}
		return $result;
	}
}
