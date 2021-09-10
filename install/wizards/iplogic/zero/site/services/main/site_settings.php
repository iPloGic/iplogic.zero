<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if (!defined("WIZARD_SITE_ID"))
	return;

use Bitrix\Main\Localization\CultureTable,
	Bitrix\Main\Config\Option,
	Bitrix\Main\Localization\Loc;


$db_res = CSite::GetList($by="sort", $order="desc", array("LID" => WIZARD_SITE_ID));
if (($db_res && $res = $db_res->Fetch()))
{
	$culture = CultureTable::getRow(array('filter'=>array(
		"=FORMAT_DATE" => "DD.MM.YYYY",
		"=FORMAT_DATETIME" => "DD.MM.YYYY HH:MI:SS",
		"=FORMAT_NAME" => CSite::GetDefaultNameFormat(),
		"=CHARSET" => (defined("BX_UTF") ? "UTF-8" : "windows-1251"),
	)));
	if($culture)
	{
		$cultureId = $culture["ID"];
	}
	else
	{
		$addResult = CultureTable::add(array(
			"NAME" => WIZARD_SITE_ID,
			"CODE" => WIZARD_SITE_ID,
			"FORMAT_DATE" => "DD.MM.YYYY",
			"FORMAT_DATETIME" => "DD.MM.YYYY HH:MI:SS",
			"FORMAT_NAME" => CSite::GetDefaultNameFormat(),
			"CHARSET" => (defined("BX_UTF") ? "UTF-8" : "windows-1251"),
		));
		$cultureId = $addResult->getId();
	}

	$arFields = array(
		"ACTIVE" => "Y",
		"SORT" => 100,
		"NAME" => Option::get("zero", "site_zero_name"),
		"DIR" => Option::get("zero", "site_zero_folder"),
		"SITE_NAME" => Option::get("zero", "site_zero_name"),
		"SERVER_NAME" => Option::get("zero", "site_zero_server_name"),
		"DOMAINS" => Option::get("zero", "site_zero_domains"),
		"EMAIL" => Option::get("zero", "site_zero_email_from"),
		"LANGUAGE_ID" => LANGUAGE_ID,
		"DOC_ROOT" => "",
		"CULTURE_ID" => $cultureId,
		'TEMPLATE'=>array(
			array(
				'CONDITION' => "",
				'SORT' => 1,
				'TEMPLATE' => "zero"
			),
		)
	);
	$obSite = new CSite;
	$result = $obSite->Update(WIZARD_SITE_ID, $arFields);

}