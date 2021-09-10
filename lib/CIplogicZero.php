<?
namespace Iplogic\Zero;

use Bitrix\Main\Loader;


class CIplogicZero {

	public static function exclLocationsDeliveryRestrictions()
	{
		return new \Bitrix\Main\EventResult(
			\Bitrix\Main\EventResult::SUCCESS,
			array(
				'\ExclLocationsDeliveryRestriction' => '/bitrix/modules/iplogic.zero/lib/ExclLocationsDeliveryRestriction.php',
			)
		);
	}

	public static function GetCurrencyRateAgent()
	{
		global $DB;

		if(!Loader::includeModule('currency'))
			return "AgentGetCurrencyRate();";

		$attempt = 0;
		$mAnswer = False;
		$rateDay = GetTime(time(), "SHORT", LANGUAGE_ID);

		// Список нужных валют
		$arCurList = array('USD', 'EUR');

		while (!$mAnswer) {
			$mAnswer = self::GetCurrencyXML();
			$attempt++;
			if (!$mAnswer && $attempt>100) {
				// если результат не получен с 100-й попытки, то прекращаем обращения к серверу и отправляем сообщение администратору (раскоментировать)
				// mail('admin@mysite.ru', 'Неудача обновления курсов валют', 'Неудача обновления курсов валют',"Content-type: text/html; charset=\"utf-8\" \r\n");
				break;
			}
		}

		if ($mAnswer) {
			require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/classes/general/xml.php");
			$strQueryText =  preg_replace("(<!DOCTYPE[^>]{1,}>)i", "", $mAnswer);
			$strQueryText =  preg_replace("(<"."\?XML[^>]{1,}\?".">)i", "", $strQueryText);
			$objXML = new \CDataXML();
			$objXML->LoadString($strQueryText);
			$arData = $objXML->GetArray();
			$arFields = array();
			$arCurRate["CURRENCY_CBRF"] = array();

			if (is_array($arData) && count($arData["ValCurs"]["#"]["Valute"])>0) {
				for ($j1 = 0; $j1<count($arData["ValCurs"]["#"]["Valute"]); $j1++) { 
					if (in_array($arData["ValCurs"]["#"]["Valute"][$j1]["#"]["CharCode"][0]["#"], $arCurList)) { 
						\CCurrencyRates::Add(array(
							'CURRENCY' => $arData["ValCurs"]["#"]["Valute"][$j1]["#"]["CharCode"][0]["#"],
							'DATE_RATE' => $rateDay,
							'RATE' => DoubleVal(str_replace(",", ".", $arData["ValCurs"]["#"]["Valute"][$j1]["#"]["Value"][0]["#"])),
							'RATE_CNT' => IntVal($arData["ValCurs"]["#"]["Valute"][$j1]["#"]["Nominal"][0]["#"]),
						));
					}
				}
			}
		}

		return "Iplogic\Zero\CIplogicZero::GetCurrencyRateAgent();";
	}

	private static function GetCurrencyXML() {
		global $DB;
		$rateDay = GetTime(time(), "SHORT", LANGUAGE_ID);
		$QUERY_STR = "date_req=".$DB->FormatDate($rateDay, \CLang::GetDateFormat("SHORT", SITE_ID), "D.M.Y");
		$strQueryText = QueryGetData("www.cbr.ru", 80, "/scripts/XML_daily.asp", $QUERY_STR, $errno, $errstr);
		$strQueryText = trim($strQueryText);
		if (strlen($strQueryText) <= 0) {
			//AddMessage2Log("Empty answer from CBRF");
			return false;
		}
		// данная строка нужна только если у вас сайт в кодировке utf8
		$strQueryText = iconv('windows-1251', 'utf-8', $strQueryText); 
		return $strQueryText;
	}

	public static function CleanUpUploadAgent() {
		global $DB;
		define("NO_KEEP_STATISTIC", true);
		define("NOT_CHECK_PERMISSIONS", true);
		$deleteFiles = 'yes'; // Should we delete files yes/no
		$saveBackup = 'yes'; // Create backup yes/no
		// Backup dir
		$patchBackup = $_SERVER['DOCUMENT_ROOT'] . "/upload/iblock_Backup/";
		// Dir for file searching
		$rootDirPath = $_SERVER['DOCUMENT_ROOT'] . "/upload/iblock";

		$time_start = microtime(true);

		// Backup dir creating
		if (!file_exists($patchBackup)) {
			CheckDirPath($patchBackup);
		}
		// Получаем записи из таблицы b_file
		$arFilesCache = array();
		$result = $DB->Query('SELECT FILE_NAME, SUBDIR FROM b_file WHERE MODULE_ID = "iblock"');
		while ($row = $result->Fetch()) {
			$arFilesCache[$row['FILE_NAME']] = $row['SUBDIR'];
		}
		$hRootDir = opendir($rootDirPath);
		$count = 0;
		$contDir = 0;
		$countFile = 0;
		$i = 1;
		$removeFile=0;
		while (false !== ($subDirName = readdir($hRootDir))) {
			if ($subDirName == '.' || $subDirName == '..') {
				continue;
			}
			//Счётчик пройденых файлов
			$filesCount = 0;
			$subDirPath = "$rootDirPath/$subDirName"; //Путь до подкатегорий с файлами
			$hSubDir = opendir($subDirPath);
			while (false !== ($fileName = readdir($hSubDir))) {
				if ($fileName == '.' || $fileName == '..') {
					continue;
				}
				$countFile++;
				if (array_key_exists($fileName, $arFilesCache)) { //Файл с диска есть в списке файлов базы - пропуск
					$filesCount++;
					continue;
				}
				$fullPath = "$subDirPath/$fileName"; // полный путь до файла
				$backTrue = false; //для создание бэкапа
				if ($deleteFiles === 'yes') {
					if (!file_exists($patchBackup . $subDirName)) {
						if (CheckDirPath($patchBackup . $subDirName . '/')) { //создал поддиректорию
							$backTrue = true;
						}
					} else {
						$backTrue = true;
					}
					if ($backTrue) {
						if ($saveBackup === 'yes') {
							CopyDirFiles($fullPath, $patchBackup . $subDirName . '/' . $fileName); //копия в бэкап
						}
					}
					//Удаление файла
					if (unlink($fullPath)) {
						$removeFile++;
					}
				} else {
					$filesCount++;
				}
				$i++;
				$count++;
				unset($fileName, $backTrue);
			}
			closedir($hSubDir);
			//Удалить поддиректорию, если удаление активно и счётчик файлов пустой - т.е каталог пуст
			if ($deleteFiles && !$filesCount) {
				rmdir($subDirPath);
			}
			$contDir++;
		}
		closedir($hRootDir);
		return "Iplogic\Zero\CIplogicZero::CleanUpUploadAgent();";
	}

}