<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;

$arServices = [

	"main" => [
		"NAME" => Loc::getMessage("SERVICE_MAIN_SETTINGS"),
		"STAGES" => [
			"template.php",
			"site_settings.php",
			"files.php",
			"menu.php",
			"settings.php",
			"agents.php",
			"groups.php",
			"mail.php",
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
			"types.php",
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

];
?>