<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

$arServices = Array(
	"main" => Array(
		"NAME" => Loc::getMessage("SERVICE_MAIN_SETTINGS"),
		"STAGES" => Array(
			"files.php", // Copy bitrix files
			"template.php", // Install template
			//"theme.php", // Install theme
			"menu.php",
			"settings.php",
			"site_settings.php",
			"mail.php",
		),
	),

	"catalog" => Array(
		"NAME" => Loc::getMessage("SERVICE_CATALOG"),
		"STAGES" => Array(
			"index.php",
		),
	),

	"iblock" => Array(
		"NAME" => Loc::getMessage("SERVICE_IBLOCK"),
		"STAGES" => Array(
			"types.php", //IBlock types
			"brands.php",
			"catalog.php",
			"offers.php",
		),
	),

	"sale" => Array(
		"NAME" => Loc::getMessage("SERVICE_SALE"),
		"STAGES" => Array(
			"locations.php",
			"step1.php",
			"step2.php",
			"step3.php"
		),
	),

	"mail" => Array(
		"NAME" => Loc::getMessage("SERVICE_MAIL"),
		"STAGES" => Array(
			"mail.php",
		),
	),

);
?>