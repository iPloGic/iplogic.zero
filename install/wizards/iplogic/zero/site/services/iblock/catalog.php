<? if( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true ) {
	die();
}

use Bitrix\Main\Config\Option,
	Bitrix\Main\Loader;

if( !Loader::includeModule("iblock") ) {
	return;
}

if( $wizard->GetVar("iblockCatalog") != "Y" ) {
	return;
}

$iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH . "/xml/" . LANGUAGE_ID . "/catalog.xml";
$iblockCode = "catalog_" . WIZARD_SITE_ID;
$iblockType = "catalog";
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

Option::set("iplogic.zero", "catalog_iblock_id", $iblockID, WIZARD_SITE_ID);

$ibp = new CIBlockProperty;

$brandsIblockId = Option::get("iplogic.zero", "brands_iblock_id", "", WIZARD_SITE_ID);
if( $brandsIblockId != "" ) {
	$properties =
		CIBlockProperty::GetList(["sort" => "asc", "name" => "asc"], ["CODE" => "BRAND", "IBLOCK_ID" => $iblockID]);
	$prop_fields = $properties->GetNext();
	$arFields = [
		'LINK_IBLOCK_ID' => $brandsIblockId,
	];
	if( !$ibp->Update($prop_fields['ID'], $arFields) ) {
		echo $ibp->LAST_ERROR;
	}
}

$properties =
	CIBlockProperty::GetList(["sort" => "asc", "name" => "asc"], ["CODE" => "LINKED", "IBLOCK_ID" => $iblockID]);
$prop_fields = $properties->GetNext();
$arFields = [
	'LINK_IBLOCK_ID' => $iblockID,
];
if( !$ibp->Update($prop_fields['ID'], $arFields) ) {
	echo $ibp->LAST_ERROR;
}

CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH . "/catalog/index.php", ["CATALOG_IBLOCK_ID" => $iblockID]);
CWizardUtil::ReplaceMacros(
	WIZARD_SITE_PATH . "/local/templates/" . $wizard->GetVar("templateDir") . "/header.php",
	["CATALOG_IBLOCK_ID" => $iblockID]
);
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH . "/.catalog.menu_ext.php", ["CATALOG_IBLOCK_ID" => $iblockID]);

$arFields = [
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
	"IBLOCK_SECTION" => [
		"IS_REQUIRED" => "N",
		"DEFAULT_VALUE" => [
			"KEEP_IBLOCK_SECTION_ID" => "Y"
		]
	],
	"SECTION_CODE" => [
		"IS_REQUIRED" => "Y",
		"DEFAULT_VALUE" => [
			"UNIQUE" => "N",
			"TRANSLITERATION" => "Y",
			"TRANS_LEN" => 100,
			"TRANS_CASE" => "L",
			"TRANS_SPACE" => "-",
			"TRANS_OTHER" => "-",
			"TRANS_EAT" => "Y",
			"USE_GOOGLE" => "N"
		]
	],
	"SECTION_DETAIL_TEXT_TYPE" => [
		"IS_REQUIRED" => "Y",
		"DEFAULT_VALUE" => "html"
	],
];
\CIBlock::setFields($iblockID, $arFields);