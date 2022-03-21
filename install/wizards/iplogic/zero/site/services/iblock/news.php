<? if( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true ) die();

use Bitrix\Main\Config\Option,
	Bitrix\Main\Loader;

if( !Loader::includeModule("iblock") ) {
	return;
}

if( $wizard->GetVar("iblockNews") != "Y" ) {
	return;
}

$iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH . "/xml/" . LANGUAGE_ID . "/news.xml";
$iblockCode = "news_" . WIZARD_SITE_ID;
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

CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH . "/news/index.php", ["NEWS_IBLOCK_ID" => $iblockID]);

$arFields = [
	"ACTIVE_FROM" => [
		"IS_REQUIRED" => "Y",
		"DEFAULT_VALUE" => "=now"
	],
	"CODE" => [
		"IS_REQUIRED" => "Y",
		"DEFAULT_VALUE" => [
			"UNIQUE" => "Y",
			"TRANSLITERATION" => "Y",
			"TRANS_LEN" => 100,
			"TRANS_CASE" => "L",
			"TRANS_SPACE" => "-",
			"TRANS_OTHER" => "-",
			"TRANS_EAT" => "Y",
			"USE_GOOGLE" => "N"
		]
	],
	"PREVIEW_PICTURE" => [
		"IS_REQUIRED" => "N",
		"DEFAULT_VALUE" => [
			"FROM_DETAIL" => "Y",
			"SCALE" => "N",
			"WIDTH" => "",
			"HEIGHT" => "",
			"IGNORE_ERRORS" => "N",
			"METHOD" => "resample",
			"COMPRESSION" => 95,
			"DELETE_WITH_DETAIL" => "N",
			"UPDATE_WITH_DETAIL" => "N",
		]
	],
	"DETAIL_TEXT_TYPE" => [
		"IS_REQUIRED" => "Y",
		"DEFAULT_VALUE" => "html"
	],
];
\CIBlock::setFields($iblockID, $arFields);

