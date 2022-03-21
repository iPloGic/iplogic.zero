<? if( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true ) {
	die();
}

use \Bitrix\Main\Config\Option,
	\Bitrix\Main\Loader,
	\Bitrix\Main\Localization\Loc;

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

	$arProps = [];
	$properties = \CIBlockProperty::GetList([], ["IBLOCK_ID" => $iblockID]);
	while( $arPropField = $properties->GetNext() ) {
		$arProps[$arPropField["CODE"]] = $arPropField["ID"];
	}

	$arSettings = [
		"tabs" => implode(
			";", [
				implode(
					",",
					[
						"edit1--#--".Loc::getMessage("EDIT1")."--",
						"--ID--#--ID--",
						"--DATE_CREATE--#--".Loc::getMessage("DATE_CREATE")."--",
						"--TIMESTAMP_X--#--".Loc::getMessage("TIMESTAMP_X")."--",
						"--ACTIVE--#--".Loc::getMessage("ACTIVE")."--",
						"--ACTIVE_FROM--#--".Loc::getMessage("ACTIVE_FROM")."--",
						"--ACTIVE_TO--#--".Loc::getMessage("ACTIVE_TO")."--",
						"--DETAIL_PICTURE--#--".Loc::getMessage("DETAIL_PICTURE")."--",
						"--NAME--#--".Loc::getMessage("NAME")."--",
						"--SORT--#--".Loc::getMessage("SORT")."--",
						"--PROPERTY_".$arProps["REF"]."--#--".Loc::getMessage("PROPERTY_COLOR")."--",
						"--PROPERTY_".$arProps["COLOR"]."--#--".Loc::getMessage("PROPERTY_REF")."--",
						"--PROPERTY_".$arProps["BUTTON"]."--#--".Loc::getMessage("PROPERTY_BUTTON")."--",
						"--PROPERTY_".$arProps["WIDTH"]."--#--".Loc::getMessage("PROPERTY_WIDTH")."--",
						"--DETAIL_TEXT--#--".Loc::getMessage("DETAIL_TEXT")."--",
						"--XML_ID--#--".Loc::getMessage("XML_ID")."--",
					]
				),
				"--"
			]
		)
	];

	\CUserOptions::SetOption("form", "form_element_".$iblockID, $arSettings, true);
	\CUserOptions::SetOption("form", "form_element_".$iblockID, $arSettings, false, 1);

}
