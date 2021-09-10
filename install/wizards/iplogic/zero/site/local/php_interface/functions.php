<?

/**
 * Returns file size in human-understandable units
 *
 * @param $bites - size of file in bites
 * @param $rounding - rounding accuracy
 * @param $arDesignations - unit designations array
 * @return string
 */
function humanFileSize($bites, $rounding = 2, $arDesignations = [" B"," Kb"," Mb"," Gb"]) {
	if ($bites < 1024) {
		return $bites.$arDesignations[0];
	}
	$size = $bites/1024;
	if ($size < 1024) {
		return round($size, $rounding).$arDesignations[1];
	}
	$size = $size/1024;
	if ($size < 1024) {
		return round($size, $rounding).$arDesignations[2];
	}
	$size = $size/1024;
	return round($size, $rounding).$arDesignations[3];
}


/**
 * Формирует окончание для числительных
 *
 * Пример: $count . ' бутыл' . numberEnd($count, 'ка|ки|ок');
 *
 * @param $number - количественный показатель
 * @param $titles - склонение (один|два|пять)
 * @return mixed
 */
if (!function_exists('numberEnd')) {
	function numberEnd($number, $titles)
	{
		if (!is_array($titles))
			$titles = explode("|", $titles);
		$cases = array(2, 0, 1, 1, 1, 2);
		return $titles[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];
	}
}


/**
 * Returns file size in human-understandable units
 *
 * @param $sText - login information
 * @param $toFile - file name
 * @param $sModule - module ID
 * @param $traceDepth - trace depth
 * @param $bShowArgs - include arguments
 * @return mixed
 */
function __log($sText, $toFile = "", $sModule = "", $traceDepth = 10, $bShowArgs = false)
{
	$ob = $sText;

	$toPath = '';
	if (substr($toFile, -1) == '/') {
		$toPath = $toFile;
		$toFile = '';
	}

	if ($toFile == '') {
		$toFile = '.default.log';

		$sPlace = '';
		if (function_exists("debug_backtrace")) {
			$arBacktrace = debug_backtrace();
			$sPlace = $arBacktrace[0]['file'] . ', ' . $arBacktrace[0]['line'];

			if (isset($arBacktrace[1]['function'])) {
				$toFile = $arBacktrace[1]['function'] . '.log';
				if (count(func_get_args()) == 0)
					$sText = $arBacktrace[1]['args'];
			}

			if (isset($arBacktrace[1]['class']))
				$toFile = str_replace('\\', '-', $arBacktrace[1]['class']) . '@' . $toFile;
		}
	}

	if (substr($toFile, 0, 2) == './' || substr($toFile, 0, 1) == '/')
		$LOG_FILENAME = $toFile;
	else
		$LOG_FILENAME = $_SERVER["DOCUMENT_ROOT"] . "/.logs/" . $toPath . $toFile;

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