<?

namespace Iplogic\Zero;

/**
 * Module helpers / Хелперы модуля
 * @package Iplogic\Zero
 */
class Helper
{
	/**
	 * Returns file size in human-understandable units / Возвращает человекопонятный размер файла
	 *
	 * @param int $bites - size of file in bites / размер файла в битах
	 * @param int $rounding - rounding accuracy / точность округления
	 * @param array $arDesignations - unit designations array / массив обозначений единиц измерения
	 * @return string
	 */
	static function humanFileSize($bites, $rounding = 2, $arDesignations = [" B", " Kb", " Mb", " Gb"])
	{
		if( $bites < 1024 ) {
			return $bites . $arDesignations[0];
		}
		$size = $bites / 1024;
		if( $size < 1024 ) {
			return round($size, $rounding) . $arDesignations[1];
		}
		$size = $size / 1024;
		if( $size < 1024 ) {
			return round($size, $rounding) . $arDesignations[2];
		}
		$size = $size / 1024;
		return round($size, $rounding) . $arDesignations[3];
	}


	/**
	 * Forms the ending for russian numerals / Формирует окончание для русских числительных
	 *
	 * Example/Пример: $count . ' бутыл' . numberEnd($count, 'ка|ки|ок');
	 *
	 * @param int $number - quantity / количество
	 * @param string $titles - declension (one|two|five) / окончание (один|два|пять)
	 * @return mixed
	 */
	static function numberEnd($number, $titles)
	{
		if( !is_array($titles) ) {
			$titles = explode("|", $titles);
		}
		$cases = [2, 0, 1, 1, 1, 2];
		return $titles[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];
	}


	/**
	 * Object to array convertation / Конвертация объекта в массив
	 *
	 * @param $ob - object / объект
	 * @return mixed
	 */
	static function objToArray($ob)
	{
		$json_string = json_encode($ob);
		return json_decode($json_string, TRUE);
	}


	/**
	 * Clean directory without removal / Очистка директории без удаления
	 *
	 * @param string $path - directory path / путь к директории
	 * @return bool
	 */
	static function ClearDir($path)
	{
		if( file_exists($path) && is_dir($path) ) {
			$dirHandle = opendir($path);
			while( false !== ($file = readdir($dirHandle)) ) {
				if( $file != '.' && $file != '..' ) {
					$tmpPath = $path . DIRECTORY_SEPARATOR . $file;
					if( is_dir($tmpPath) ) {
						\Bitrix\Main\IO\Directory::deleteDirectory($tmpPath);
					}
					else {
						if( file_exists($tmpPath) ) {
							unlink($tmpPath);
						}
					}
				}
			}
			closedir($dirHandle);
			return true;
		}
		return false;
	}

}