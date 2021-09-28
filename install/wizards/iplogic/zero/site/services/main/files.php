<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if (!defined("WIZARD_SITE_PATH"))
	return;

function getReplacements() {
	include($_SERVER["DOCUMENT_ROOT"]."/bitrix/php_interface/dbconn.php");
	return ["DB_TYPE" => $DBType, "DB_HOST" => $DBHost, "DB_LOGIN" => $DBLogin, "DB_PASS" => $DBPassword, "DB_BASE" => $DBName, "DB_TABLE_TIPE" => MYSQL_TABLE_TYPE];
}

$arReplacements = getReplacements();

CopyDirFiles(
	WIZARD_ABSOLUTE_PATH."/site/public/".LANGUAGE_ID,
	WIZARD_SITE_PATH,
	$rewrite = true, 
	$recursive = true,
	$delete_after_copy = false
);

CopyDirFiles(
	WIZARD_ABSOLUTE_PATH."/site/bitrix/",
	$_SERVER["DOCUMENT_ROOT"],
	$rewrite = true, 
	$recursive = true,
	$delete_after_copy = false
);


CWizardUtil::ReplaceMacros($_SERVER["DOCUMENT_ROOT"]."/bitrix/php_interface/dbconn.php", $arReplacements);
CWizardUtil::ReplaceMacros($_SERVER["DOCUMENT_ROOT"]."/bitrix/.settings.php", $arReplacements);


$arUrlRewrite = array();
if (file_exists(WIZARD_SITE_ROOT_PATH."/urlrewrite.php"))
{
	include(WIZARD_SITE_ROOT_PATH."/urlrewrite.php");
}

$arNewUrlRewrite = array(
	array(
		"CONDITION"	=>	"#^".WIZARD_SITE_DIR."catalog/#",
		"RULE"	=>	"",
		"ID"	=>	"bitrix:catalog",
		"PATH"	=>	 WIZARD_SITE_DIR."catalog/index.php",
	),
	array(
		"CONDITION"	=>	"#^".WIZARD_SITE_DIR."personal/#",
		"RULE"	=>	"",
		"ID"	=>	"bitrix:sale.personal.section",
		"PATH"	=>	 WIZARD_SITE_DIR."personal/index.php",
	),
);

foreach ($arNewUrlRewrite as $arUrl)
{
	if (!in_array($arUrl, $arUrlRewrite))
	{
		CUrlRewriter::Add($arUrl);
	}
}

?>