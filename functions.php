<?
use \Bitrix\Main\Application;

/**
 * Puts information in log file
 *
 * @param $sText - login information
 * @param $fileName - file name (doesnt use if constant ZERO_LOG_FILE defined)
 * @param $sModule - module ID (doesnt use if constant ZERO_LOG_MODULE defined)
 * @param $traceDepth - trace depth
 * @param $bShowArgs - include arguments
 * @return mixed
 */
function _log_($sText, $fileName = "", $sModule = "", $traceDepth = 10, $bShowArgs = false)
{
	$ob = $sText;

	if (defined('ZERO_LOG_FILE'))
		$fileName = ZERO_LOG_FILE;

	if (defined('ZERO_LOG_MODULE'))
		$sModule = ZERO_LOG_MODULE;

	$toPath = '';
	if (substr($fileName, -1) == '/') {
		$toPath = $fileName;
		$fileName = '';
	}
	else {
		$toPath = "/.logs/";
	}

	if ($fileName == '') {
		$fileName = '.default.log';

		$sPlace = '';
		if (function_exists("debug_backtrace")) {
			$arBacktrace = debug_backtrace();
			$sPlace = $arBacktrace[0]['file'] . ', ' . $arBacktrace[0]['line'];

			if (isset($arBacktrace[1]['function'])) {
				$fileName = $arBacktrace[1]['function'] . '.log';
				if (count(func_get_args()) == 0)
					$sText = $arBacktrace[1]['args'];
			}

			if (isset($arBacktrace[1]['class']))
				$fileName = str_replace('\\', '-', $arBacktrace[1]['class']) . '@' . $fileName;
		}
	}

	if (substr($fileName, 0, 2) == './' || substr($fileName, 0, 3) == '../')
		$LOG_FILENAME = $fileName;
	elseif (substr($fileName, 0, 1) == '/')
		$LOG_FILENAME = Application::getDocumentRoot() . $fileName;
	else
		$LOG_FILENAME = Application::getDocumentRoot() . $toPath . $fileName;

	$dir = dirname($LOG_FILENAME);
	if (!file_exists($dir)) mkdir($dir, 0777, true);

	if (empty($sText)) $sText = ' -=empty=- ';

	if (is_array($sText) || is_object($sText))
		$sText = print_r($sText, true);

	if (strlen($sText) > 0) {
		ignore_user_abort(true);
		if ($fp = @fopen($LOG_FILENAME, "ab+")) {
			if (flock($fp, LOCK_EX)) {
				@fwrite($fp, date("Y-m-d H:i:s") . " - " . $sPlace . " - " . $sModule . " - " . $sText . "\n");
				if (function_exists("debug_backtrace")) {
					$arBacktrace = debug_backtrace();
					$strFunctionStack = "";
					$strFilesStack = "";
					$iterationsCount = min(count($arBacktrace), $traceDepth);
					for ($i = 1; $i < $iterationsCount; $i++) {
						if (strlen($strFunctionStack) > 0)
							$strFunctionStack .= " < ";

						if (isset($arBacktrace[$i]["class"]))
							$strFunctionStack .= $arBacktrace[$i]["class"] . "::";

						$strFunctionStack .= $arBacktrace[$i]["function"];

						if (isset($arBacktrace[$i]["file"]))
							$strFilesStack .= "\t" . $arBacktrace[$i]["file"] . ":" . $arBacktrace[$i]["line"] . "\n";
						if ($bShowArgs && isset($arBacktrace[$i]["args"])) {
							$strFilesStack .= "\t\t";
							if (isset($arBacktrace[$i]["class"]))
								$strFilesStack .= $arBacktrace[$i]["class"] . "::";
							$strFilesStack .= $arBacktrace[$i]["function"];
							$strFilesStack .= "(\n";
							foreach ($arBacktrace[$i]["args"] as $j => $value)
								$strFilesStack .= "\t\t\t" . $value . "\n";
							$strFilesStack .= "\t\t)\n";

						}
					}

					if (strlen($strFunctionStack) > 0) {
						@fwrite($fp, "    " . $strFunctionStack . "\n" . $strFilesStack);
					}
				}
				@fwrite($fp, "----------\n");
				@fflush($fp);
				@flock($fp, LOCK_UN);
				@fclose($fp);
			}
		}
		ignore_user_abort(false);
	}

	return $ob;
}