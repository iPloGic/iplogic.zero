<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Config\Option,
	Bitrix\Main\Localization\Loc;

CModule::IncludeModule('fileman');
$arMenuTypes = GetMenuTypes(WIZARD_SITE_ID);

$arMenuTypes['catalog'] = Loc::getMessage("MENU_CATALOG");
$arMenuTypes['personal'] = Loc::getMessage("MENU_PRIVATE");

SetMenuTypes($arMenuTypes, WIZARD_SITE_ID);
Option::set("fileman", "num_menu_param", 2, false ,WIZARD_SITE_ID);

?>