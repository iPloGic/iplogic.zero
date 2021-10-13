<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

$arServices = [
	"main" => [
		"NAME" => Loc::getMessage("SERVICE_MAIN_SETTINGS"),
		"STAGES" => [
			"files.php",
			"template.php",
			//"theme.php",
			"menu.php",
			"settings.php",
			"site_settings.php",
			"agents.php"
		],
	],

	"catalog" => [
		"NAME" => Loc::getMessage("SERVICE_CATALOG"),
		"STAGES" => [
			"index.php",
		],
	],

	"iblock" => [
		"NAME" => Loc::getMessage("SERVICE_IBLOCK"),
		"STAGES" => [
			"types.php", //IBlock types
			"brands.php",
			"catalog.php",
			"offers.php",
			"news.php",
			"banners.php",
		],
	],

	"sale" => [
		"NAME" => Loc::getMessage("SERVICE_SALE"),
		"STAGES" => [
			"locations.php",
			"step1.php",
			"step2.php",
			"step3.php"
		],
	],

	"mail" => [
		"NAME" => Loc::getMessage("SERVICE_MAIL"),
		"STAGES" => [
			"mail.php",
		],
	],

];
?>