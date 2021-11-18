<? if( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true ) die();

use Bitrix\Main\Config\Option,
	Bitrix\Main\Loader;

if( !Loader::includeModule("iblock") ) {
	return;
}

if( $wizard->GetVar("iblockBanner") != "Y" ) {
	return;
}

$iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH . "/xml/" . LANGUAGE_ID . "/banners.xml";
$iblockCode = "banners_" . WIZARD_SITE_ID;
$iblockType = "content";
$iblockID = false;

$rsIBlock = CIBlock::GetList([], ["CODE" => $iblockCode, "TYPE" => $iblockType]);
if( $rsIBlock && $arIBlock = $rsIBlock->Fetch() ) {
	return;
}

if( $iblockID == false ) {
	$permissions = [
		"1" => "X",
		"2" => "R",
	];
	$dbGroup = CGroup::GetList($by = "", $order = "", ["STRING_ID" => "sale_administrator"]);
	if( $arGroup = $dbGroup->Fetch() ) {
		$permissions[$arGroup["ID"]] = 'W';
	}
	$dbGroup = CGroup::GetList($by = "", $order = "", ["STRING_ID" => "content_editor"]);
	if( $arGroup = $dbGroup->Fetch() ) {
		$permissions[$arGroup["ID"]] = 'W';
	}

	$iblockID = ImportIBlockFromXMLEx(
		$iblockXMLFile,
		$iblockCode,
		$iblockType,
		$permissions
	);

	if( $iblockID < 1 ) {
		return;
	}

}
