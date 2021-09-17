<?
namespace Iplogic\Zero;

class Helper
{
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
	 * Forms the ending for numerals
	 *
	 * Example: $count . ' бутыл' . numberEnd($count, 'ка|ки|ок');
	 *
	 * @param $number - quantitative indicator
	 * @param $titles - declension (one|two|five)
	 * @return mixed
	 */
	function numberEnd($number, $titles)
	{
		if (!is_array($titles))
			$titles = explode("|", $titles);
		$cases = array(2, 0, 1, 1, 1, 2);
		return $titles[($number % 100 > 4 && $number % 100 < 20) ? 2 : $cases[min($number % 10, 5)]];
	}


	/**
	 * Object to array convertation
	 *
	 * @param $ob - object
	 * @return mixed
	 */
	function objToArray($ob)
	{
		$json_string = json_encode( $ob );
		return json_decode( $json_string, TRUE );
	}

}