<? if( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true ) die();

use Bitrix\Main\Config\Option,
	Bitrix\Main\Loader;

if( !Loader::includeModule("iblock") || !Loader::includeModule("catalog") ) {
	return;
}

if( $wizard->GetVar("iblockOffer") != "Y" ) {
	return;
}

$iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH . "/xml/" . LANGUAGE_ID . "/offers.xml";
$iblockCode = "catalog_offers_" . WIZARD_SITE_ID;
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

$catalogIblockId = Option::get("iplogic.zero", "catalog_iblock_id", "", WIZARD_SITE_ID);
if( $catalogIblockId != "" ) {
	$properties =
		CIBlockProperty::GetList(["sort" => "asc", "name" => "asc"], ["CODE" => "CML2_LINK", "IBLOCK_ID" => $iblockID]);
	$prop_fields = $properties->GetNext();
	$arFields = [
		'LINK_IBLOCK_ID' => $catalogIblockId,
	];
	$ibp = new CIBlockProperty;
	if( !$ibp->Update($prop_fields['ID'], $arFields) ) {
		echo $ibp->LAST_ERROR;
	}
	$ID_SKU = CCatalog::LinkSKUIBlock($catalogIblockId, $iblockID);
	CCatalog::Update($iblockID, ['PRODUCT_IBLOCK_ID' => $catalogIblockId, 'SKU_PROPERTY_ID' => $ID_SKU]);
}

CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH . "/local/templates/" . $wizard->GetVar("templateDir") . "/header.php",
	["OFFERS_IBLOCK_ID" => $iblockID]);

$arFields = [
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