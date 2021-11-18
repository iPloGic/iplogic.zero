<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Config\Option,
	Bitrix\Main\Localization\Loc;

$siteID = getSite($wizard)["ID"];

CModule::IncludeModule('fileman');
$arMenuTypes = GetMenuTypes($siteID);

if ($wizard->GetVar("menuTop") == "Y") {
	$arMenuTypes['top'] = Loc::getMessage("MENU_TOP");
}

if ($wizard->GetVar("menuBottom") == "Y") {
	$arMenuTypes['bottom'] = Loc::getMessage("MENU_BOTTOM");
}

if ($wizard->GetVar("menuSide") == "Y") {
	$arMenuTypes['side'] = Loc::getMessage("MENU_SIDE");
}

if ($wizard->GetVar("menuCatalog") == "Y") {
	$arMenuTypes['catalog'] = Loc::getMessage("MENU_CATALOG");
}

if ($wizard->GetVar("pubPrivate") == "Y") {
	$arMenuTypes['personal'] = Loc::getMessage("MENU_PRIVATE");
}


SetMenuTypes($arMenuTypes, $siteID);
Option::set("fileman", "num_menu_param", count($arMenuTypes), $siteID);

?>