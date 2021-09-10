<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
if(!defined("WIZARD_DEFAULT_SITE_ID") && !empty($_REQUEST["wizardSiteID"])) 
	define("WIZARD_DEFAULT_SITE_ID", $_REQUEST["wizardSiteID"]); 

use Bitrix\Main\Localization\Loc;

$arWizardDescription = Array(
	"NAME" => Loc::getMessage("PORTAL_WIZARD_NAME"), 
	"DESCRIPTION" => Loc::getMessage("PORTAL_WIZARD_DESC"), 
	"VERSION" => "1.0.0",
	"START_TYPE" => "WINDOW",
	"WIZARD_TYPE" => "INSTALL",
	"IMAGE" => "/images/".LANGUAGE_ID."/solution.png",
	"PARENT" => "wizard_sol",
	"TEMPLATES" => Array(
		Array("SCRIPT" => "wizard_sol")
	),
	"STEPS" => (defined("WIZARD_DEFAULT_SITE_ID") ? 
		//Array("SelectTemplateStep", "SelectThemeStep", "SiteSettingsStep", "DataInstallStep" ,"FinishStep") : 
		//Array("SelectSiteStep", "SelectTemplateStep", "SelectThemeStep", "SiteSettingsStep", "DataInstallStep" ,"FinishStep"))
		Array("SiteSettingsStep", "ShopSettingsStep", "PayerAndLocStep", "AgentsSettingsStep", "DataInstallStep", "FinishStep") : 
		Array("SelectSiteStep", "SiteSettingsStep", "ShopSettingsStep", "PayerAndLocStep", "AgentsSettingsStep", "DataInstallStep", "FinishStep"))
);
?>