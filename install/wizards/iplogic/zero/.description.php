<?
if( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true ) {
	die();
}
if( !defined("WIZARD_DEFAULT_SITE_ID") && !empty($_REQUEST["wizardSiteID"]) ) {
	define("WIZARD_DEFAULT_SITE_ID", $_REQUEST["wizardSiteID"]);
}

use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;

$IS_SHOP = false;
if(
	is_dir($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/sale") &&
	is_dir($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/catalog")
) {
	$IS_SHOP = true;
}

$arWizardDescription = [
	"NAME" => Loc::getMessage("PORTAL_WIZARD_NAME"),
	"DESCRIPTION" => Loc::getMessage("PORTAL_WIZARD_DESC"),
	"VERSION" => "1.0.0",
	"START_TYPE" => "WINDOW",
	"WIZARD_TYPE" => "INSTALL",
	"IMAGE" => "/images/" . LANGUAGE_ID . "/solution.png",
	"PARENT" => "wizard_sol",
	"TEMPLATES" => [
		["SCRIPT" => "wizard_sol"],
	],
];
if( !defined("WIZARD_DEFAULT_SITE_ID") ) {
	$arWizardDescription["STEPS"][] = "SelectSiteStep";
}
$arWizardDescription["STEPS"][] = "SelectTemplateStep";
$arWizardDescription["STEPS"][] = "SiteSettingsStep";
if( $IS_SHOP ) {
	$arWizardDescription["STEPS"][] = "ShopSettingsStep";
	$arWizardDescription["STEPS"][] = "PayerAndLocStep";
}
$arWizardDescription["STEPS"][] = "SpecialSettingsStep";
$arWizardDescription["STEPS"][] = "DataInstallStep";
$arWizardDescription["STEPS"][] = "FinishStep";
?>