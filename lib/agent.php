<?

namespace Iplogic\Zero;

use \Bitrix\Main\Loader;
use \Bitrix\Main\Config\Option;


/**
 * Module Agents / Агенты модуля
 * @package Iplogic\Zero
 */
class Agent
{

	/**
	 * Module ID / ID модуля
	 * @const string
	 */
	const MODULE_ID = 'iplogic.zero';


	/**
	 * Agent for receiving exchange rates of the Central Bank of Russia / Агент получения курсов валют ЦБРФ
	 * @return string
	 */
	public static function GetCurrencyRateAgent()
	{
		global $DB;

		if( !Loader::includeModule('currency') ) {
			return "\Iplogic\Zero\Agent::GetCurrencyRateAgent();";
		}

		$attempt = 0;
		$mAnswer = False;
		$rateDay = GetTime(time(), "SHORT", LANGUAGE_ID);

		// Currencies list
		$arCurList = explode(",", Option::get(self::MODULE_ID, "agent_currencies", 'USD,EUR'));

		while( !$mAnswer ) {
			$mAnswer = self::GetCurrencyXML();
			$attempt++;
			if( !$mAnswer && $attempt > 100 ) {
				break;
			}
		}

		if( $mAnswer ) {
			require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/classes/general/xml.php");
			$strQueryText = preg_replace("(<!DOCTYPE[^>]{1,}>)i", "", $mAnswer);
			$strQueryText = preg_replace("(<" . "\?XML[^>]{1,}\?" . ">)i", "", $strQueryText);
			$objXML = new \CDataXML();
			$objXML->LoadString($strQueryText);
			$arData = $objXML->GetArray();
			$arCurRate["CURRENCY_CBRF"] = [];

			if( is_array($arData) && count($arData["ValCurs"]["#"]["Valute"]) > 0 ) {
				for( $j1 = 0; $j1 < count($arData["ValCurs"]["#"]["Valute"]); $j1++ ) {
					if( in_array($arData["ValCurs"]["#"]["Valute"][$j1]["#"]["CharCode"][0]["#"], $arCurList) ) {
						\CCurrencyRates::Add(
							[
								'CURRENCY' => $arData["ValCurs"]["#"]["Valute"][$j1]["#"]["CharCode"][0]["#"],
								'DATE_RATE' => $rateDay,
								'RATE' => DoubleVal(
									str_replace(",", ".", $arData["ValCurs"]["#"]["Valute"][$j1]["#"]["Value"][0]["#"])
								),
								'RATE_CNT' => IntVal($arData["ValCurs"]["#"]["Valute"][$j1]["#"]["Nominal"][0]["#"]),
							]
						);
					}
				}
			}
		}

		return "\Iplogic\Zero\Agent::GetCurrencyRateAgent();";
	}


	/**
	 * Getting a file from the CBRF website / Получение файла с сайта ЦБРФ
	 * @return bool|mixed|string
	 */
	private static function GetCurrencyXML()
	{
		global $DB;
		$rateDay = GetTime(time(), "SHORT", LANGUAGE_ID);
		$QUERY_STR = "date_req=" . $DB->FormatDate($rateDay, \CLang::GetDateFormat("SHORT", SITE_ID), "D.M.Y");
		$strQueryText = QueryGetData("www.cbr.ru", 80, "/scripts/XML_daily.asp", $QUERY_STR, $errno, $errstr);
		$strQueryText = trim($strQueryText);
		if( strlen($strQueryText) <= 0 ) {
			return false;
		}
		if( LANG_CHARSET == "UTF-8" ) {
			$strQueryText = \Iplogic\Zero\Exchange\Base::utfEncodeRecursive($strQueryText);
		}
		return $strQueryText;
	}


	/**
	 * Upload folder cleanup agent / Агент очистки папки upload
	 * @return string
	 */
	public static function CleanUpUploadAgent()
	{
		global $DB;

		$deleteFiles = Option::get(self::MODULE_ID, "agent_delete_files", 'Y');
		$saveBackup = Option::get(self::MODULE_ID, "agent_save_backup", 'Y');
		$patchBackup = $_SERVER['DOCUMENT_ROOT'] . "/upload" .
			Option::get(self::MODULE_ID, "agent_backup_folder", '/iblock_Backup/');
		$rootDirPath =
			$_SERVER['DOCUMENT_ROOT'] . "/upload" . Option::get(self::MODULE_ID, "agent_search_path", '/iblock');
		$relDirPath = Option::get(self::MODULE_ID, "agent_search_path", '/iblock');

		if( !file_exists($patchBackup) && $saveBackup == "Y" ) {
			CheckDirPath($patchBackup);
		}

		$arFilesCache = [];
		$result = $DB->Query('SELECT FILE_NAME, SUBDIR FROM b_file WHERE MODULE_ID = "iblock"');
		while( $row = $result->Fetch() ) {
			$arFilesCache[] = "/" . $row['SUBDIR'] . "/" . $row['FILE_NAME'];
		}
		$hRootDir = opendir($rootDirPath);
		$count = 0;
		$contDir = 0;
		$countFile = 0;
		$i = 1;
		$removeFile = 0;
		while( false !== ($subDirName = readdir($hRootDir)) ) {
			if( $subDirName == '.' || $subDirName == '..' ) {
				continue;
			}
			// Checked files counter
			$filesCount = 0;
			$subDirPath = "$rootDirPath/$subDirName";
			$hSubDir = opendir($subDirPath);
			while( false !== ($fileName = readdir($hSubDir)) ) {
				if( $fileName == '.' || $fileName == '..' ) {
					continue;
				}
				$fullPath = "$subDirPath/$fileName";
				if( is_dir($fullPath) ) {
					$subSubDirPath = "$rootDirPath/$subDirName/$fileName";
					$hSubSubDir = opendir($subSubDirPath);
					while( false !== ($fName = readdir($hSubSubDir)) ) {
						if( $fName == '.' || $fName == '..' ) {
							continue;
						}
						$countFile++;
						$_fullPath = "$subSubDirPath/$fName";
						if( in_array("$relDirPath/$subDirName/$fileName/$fName", $arFilesCache) ) {
							$filesCount++;
							continue;
						}
						$backTrue = false;
						if( $deleteFiles === 'Y' ) {
							if( $saveBackup == "Y" ) {
								if( !file_exists($patchBackup . $subSubDirPath) ) {
									if( CheckDirPath($patchBackup . $subSubDirPath . '/') ) {
										$backTrue = true;
									}
								}
								else {
									$backTrue = true;
								}
							}
							if( $backTrue ) {
								if( $saveBackup === 'Y' ) {
									CopyDirFiles($_fullPath, $patchBackup . $subSubDirPath . '/' . $fName);
								}
							}
							if( unlink($_fullPath) ) {
								rmdir($subSubDirPath);
								$removeFile++;
							}
						}
						else {
							$filesCount++;
						}
					}
				}
				else {
					$countFile++;
					if( in_array("$relDirPath/$subDirName/$fileName", $arFilesCache) ) {
						$filesCount++;
						continue;
					}
					$backTrue = false;
					if( $deleteFiles === 'Y' ) {
						if( $saveBackup == "Y" ) {
							if( !file_exists($patchBackup . $subDirName) ) {
								if( CheckDirPath($patchBackup . $subDirName . '/') ) {
									$backTrue = true;
								}
							}
							else {
								$backTrue = true;
							}
						}
						if( $backTrue ) {
							if( $saveBackup === 'Y' ) {
								CopyDirFiles($fullPath, $patchBackup . $subDirName . '/' . $fileName);
							}
						}
						if( unlink($fullPath) ) {
							$removeFile++;
						}
					}
					else {
						$filesCount++;
					}
				}
				$i++;
				$count++;
				unset($fileName, $backTrue);
			}
			closedir($hSubDir);
			if( $deleteFiles == "Y" && !$filesCount ) {
				rmdir($subDirPath);
			}
			$contDir++;
		}
		closedir($hRootDir);
		return "\Iplogic\Zero\Agent::CleanUpUploadAgent();";
	}

}