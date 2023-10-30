<?
if( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true ) {
	die();
}

if( !defined("WIZARD_SITE_ID") ) {
	return;
}

use Bitrix\Main\Localization\CultureTable;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;


$culture = CultureTable::getRow(
	[
		'filter' => [
			"=FORMAT_DATE"     => "DD.MM.YYYY",
			"=FORMAT_DATETIME" => "DD.MM.YYYY HH:MI:SS",
			"=FORMAT_NAME"     => CSite::GetDefaultNameFormat(),
			"=CHARSET"         => (defined("BX_UTF") ? "UTF-8" : "windows-1251"),
		],
	]
);
if( $culture ) {
	$cultureId = $culture["ID"];
}
else {
	$addResult = CultureTable::add(
		[
			"NAME"            => WIZARD_SITE_ID,
			"CODE"            => WIZARD_SITE_ID,
			"FORMAT_DATE"     => "DD.MM.YYYY",
			"FORMAT_DATETIME" => "DD.MM.YYYY HH:MI:SS",
			"FORMAT_NAME"     => CSite::GetDefaultNameFormat(),
			"CHARSET"         => (defined("BX_UTF") ? "UTF-8" : "windows-1251"),
		]
	);
	$cultureId = $addResult->getId();
}

$arFields = [
	"ACTIVE"      => "Y",
	"SORT"        => 100,
	"NAME"        => $wizard->GetVar("siteName"),
	"DIR"         => $wizard->GetVar("siteFolder"),
	"SITE_NAME"   => $wizard->GetVar("siteName"),
	"SERVER_NAME" => $wizard->GetVar("serverName"),
	"DOMAINS"     => $wizard->GetVar("domains"),
	"EMAIL"       => $wizard->GetVar("siteEmail"),
	"LANGUAGE_ID" => LANGUAGE_ID,
	"DOC_ROOT"    => "",
	"CULTURE_ID"  => $cultureId,
	'TEMPLATE'    => [
		[
			'CONDITION' => "",
			'SORT'      => 1,
			'TEMPLATE'  => $wizard->GetVar("templateDir"),
		],
	],
];

$obSite = new CSite;
$db_res = CSite::GetList($by = "sort", $order = "desc", ["LID" => WIZARD_SITE_ID]);
if( ($db_res && $res = $db_res->Fetch()) ) {
	$result = $obSite->Update(WIZARD_SITE_ID, $arFields);
}
else {
	$arFields["LID"] = WIZARD_SITE_ID;
	$result = $obSite->Add($arFields);
}