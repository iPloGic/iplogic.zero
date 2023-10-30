<?
if( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true ) {
	die();
}

use Bitrix\Main\Config\Option,
	Bitrix\Main\Localization\Loc;

if( !CModule::IncludeModule("iblock") ) {
	return;
}

$arTypes = [];
if(
	$wizard->GetVar("iblockCatalog") == "Y" || $wizard->GetVar("iblockOffer") == "Y" ||
	$wizard->GetVar("iblockBrand") == "Y"
) {
	$arTypes[] = [
		"ID"       => "catalog",
		"SECTIONS" => "Y",
		"IN_RSS"   => "N",
		"SORT"     => 100,
		"LANG"     => [],
	];
}
if(
	$wizard->GetVar("iblockNews") == "Y" || $wizard->GetVar("iblockBanner") == "Y"
) {
	$arTypes[] = [
		"ID"       => "content",
		"SECTIONS" => "Y",
		"IN_RSS"   => "Y",
		"SORT"     => 200,
		"LANG"     => [],
	];
}

if( !count($arTypes) ) {
	return;
}


$arLanguages = [];
$rsLanguage = CLanguage::GetList();
while( $arLanguage = $rsLanguage->Fetch() )
	$arLanguages[] = $arLanguage["LID"];

$arAvLanguages = ["en", "ru"];

$iblockType = new CIBlockType;
foreach( $arTypes as $arType ) {
	$dbType = CIBlockType::GetList([], ["=ID" => $arType["ID"]]);
	if( $dbType->Fetch() ) {
		continue;
	}

	foreach( $arLanguages as $languageID ) {
		$fileLanguage = $languageID;
		if( !in_array($languageID, $arAvLanguages) ) {
			$fileLanguage = "en";
		}
		WizardServices::IncludeServiceLang("type_names.php", $fileLanguage);

		$code = mb_strtoupper($arType["ID"]);
		$arType["LANG"][$languageID]["NAME"] = GetMessage($code . "_TYPE_NAME");
		$arType["LANG"][$languageID]["ELEMENT_NAME"] = GetMessage($code . "_ELEMENT_NAME");

		if( $arType["SECTIONS"] == "Y" ) {
			$arType["LANG"][$languageID]["SECTION_NAME"] = GetMessage($code . "_SECTION_NAME");
		}
	}

	$iblockType->Add($arType);
}


$s_count = 0;
$sites = \CSite::GetList($by = "sort", $order = "desc");
while( $sites_f = $sites->Fetch() )
	$s_count++;

if( $s_count < 2 ) {
	Option::set('iblock', 'combined_list_mode', 'Y');
	Option::set("iblock", "use_htmledit", "Y");
}
Option::set("iblock", "show_xml_id", "Y");
?>