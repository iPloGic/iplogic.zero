<?
//echo "<pre>"; print_r($arResult); echo "</pre>";

\Bitrix\Main\Loader::includeModule("catalog");

global $USER;

function getChildrenTopMenu($input, &$start = 0, $level = 0)
{
	$children = [];

	if( !$level ) {
		$lastDepthLevel = 1;
		if( is_array($input) ) {
			foreach( $input as $i => $arItem ) {
				if( $arItem["DEPTH_LEVEL"] > $lastDepthLevel ) {
					if( $i > 0 ) {
						$input[$i - 1]["IS_PARENT"] = 1;
					}
				}
				$lastDepthLevel = $arItem["DEPTH_LEVEL"];
			}
		}
	}
	for( $i = $start, $count = count($input); $i < $count; ++$i ) {
		$item = $input[$i];
		if( $level > $item['DEPTH_LEVEL'] - 1 ) {
			break;
		}
		elseif( !empty($item['IS_PARENT']) ) {
			++$i;
			$item['CHILDREN'] = getChildrenTopMenu($input, $i, $level + 1);
			--$i;
		}
		$children[] = $item;
	}

	$start = $i;
	return $children;
}

/* uncomment to get user fields of categories
$arIcons = [];
$arFilter = ['IBLOCK_ID'=>1, 'GLOBAL_ACTIVE'=>'Y', 'SECTION_ID'=>false];
$dbList = \CIBlockSection::GetList([], $arFilter, false, ["ID", "NAME", "UF_*"]);
while($arSect = $dbList->GetNext())
{
	$arIcons[$arSect["NAME"]] = CFile::GetPath($arSect["UF_ICON"]);
}
*/

$arResult = getChildrenTopMenu($arResult);

foreach( $arResult as $key => $arItem ) {
	$arResult[$key]["ICON"] = $arIcons[$arItem["TEXT"]];
}


if( $USER->isAdmin() ) {
	//echo "<pre>"; print_r($arResult); echo "</pre>";
}

?>