<?
if( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true ) {
	die();
}

use Bitrix\Main\Config\Option,
	Bitrix\Main\Localization\Loc;

if( !CModule::IncludeModule("iblock") ) {
	return;
}

$arExTypes = [];
$rsTypes = CIBlockType::GetList();
while( $arType = $rsTypes->Fetch() ) {
	$arExTypes[] = $arType["ID"];
}

$arTypes = [];
if(
	($wizard->GetVar("iblockCatalog") == "Y" || $wizard->GetVar("iblockOffer") == "Y" ||
		$wizard->GetVar("iblockBrand") == "Y") && !in_array("catalog", $arExTypes)
) {
	$arTypes[] = [
		"ID" => "catalog",
		"SECTIONS" => "Y",
		"IN_RSS" => "N",
		"SORT" => 100,
		"LANG" => [],
	];
}
if(
	($wizard->GetVar("iblockNews") == "Y" || $wizard->GetVar("iblockBanner") == "Y") && !in_array("content", $arExTypes)
) {
	$arTypes[] = [
		"ID" => "content",
		"SECTIONS" => "Y",
		"IN_RSS" => "Y",
		"SORT" => 200,
		"LANG" => [],
	];
}

if( !count($arTypes) ) {
	return;
}

$arLanguages = [];
$rsLanguage = \CLanguage::GetList($by, $order, []);
while( $arLanguage = $rsLanguage->Fetch() )
	$arLanguages[] = $arLanguage["LID"];

$iblockType = new CIBlockType;
foreach( $arTypes as $arType ) {
	//echo $arType["ID"].",";
	$dbType = CIBlockType::GetList([], ["=ID" => $arType["ID"]]);
	if( $dbType->Fetch() ) {
		continue;
	}

	foreach( $arLanguages as $languageID ) {
		WizardServices::IncludeServiceLang("type_names.php", $languageID);

		$code = strtoupper($arType["ID"]);
		$arType["LANG"][$languageID]["NAME"] = GetMessage($code . "_TYPE_NAME");
		$arType["LANG"][$languageID]["ELEMENT_NAME"] = GetMessage($code . "_ELEMENT_NAME");

		if( $arType["SECTIONS"] == "Y" ) {
			$arType["LANG"][$languageID]["SECTION_NAME"] = GetMessage($code . "_SECTION_NAME");
		}
	}

	$iblockType->Add($arType);
}
?>