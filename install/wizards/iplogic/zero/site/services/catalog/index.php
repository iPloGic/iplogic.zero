<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Config\Option,
	Bitrix\Main\Localization\Loc,
	Bitrix\Main\Loader;

if(!Loader::includeModule("catalog"))
	return;

/*$catalogSubscribe = $wizard->GetVar("catalogSubscribe");
$curSiteSubscribe = ($catalogSubscribe == "Y") ? array("use" => "Y", "del_after" => "100") : array("del_after" => "100");
$subscribe = COption::GetOptionString("sale", "subscribe_prod", "");
$arSubscribe = unserialize($subscribe);
$arSubscribe[WIZARD_SITE_ID] = $curSiteSubscribe;
Option::set("sale", "subscribe_prod", serialize($arSubscribe));

$useStoreControl = $wizard->GetVar("useStoreControl");
$useStoreControl = ($useStoreControl == "Y") ? "Y" : "N";
$curUseStoreControl = COption::GetOptionString("catalog", "default_use_store_control", "N");
Option::set("catalog", "default_use_store_control", $useStoreControl);

$productReserveCondition = $wizard->GetVar("productReserveCondition");
$productReserveCondition = (in_array($productReserveCondition, array("O", "P", "D", "S"))) ? $productReserveCondition : "P";
Option::set("sale", "product_reserve_condition", $productReserveCondition);*/


$dbStores = CCatalogStore::GetList(array(), array("ACTIVE" => 'Y'));
if(!$dbStores->Fetch())
{
	$arStoreFields = array(
		"TITLE" => Loc::getMessage("CAT_STORE_NAME"),
		"ACTIVE" => "Y",
		"ADDRESS" => Loc::getMessage("STORE_ADR_1"),
		"DESCRIPTION" => Loc::getMessage("STORE_DESCR_1"),
	);
	$newStoreId = CCatalogStore::Add($arStoreFields);
	if($newStoreId)
	{
		$_SESSION['NEW_STORE_ID'] = $newStoreId;
	}
}

if(Option::get("iplogic.zero", "wizard_installed", "N", WIZARD_SITE_ID) == "Y"/* && !WIZARD_INSTALL_DEMO_DATA*/)
	return;


/* BASE PRICE */

$arLanguages = Array();
$rsLanguage = CLanguage::GetList($by, $order, array());
while($arLanguage = $rsLanguage->Fetch())
	$arLanguages[] = $arLanguage["LID"];

$arUserLang = Array();
foreach($arLanguages as $languageID) {
	WizardServices::IncludeServiceLang("index.php", $languageID);
	$arUserLang[$languageID] = Loc::getMessage("BASE_PRICE_NAME",null,$languageID);
}

$wizGroupId = Array();
/*if ($wizard->GetVar("installPriceBASE") == "Y"){*/
	$db_res = CCatalogGroup::GetGroupsList(array("CATALOG_GROUP_ID"=>'1', "BUY"=>"Y"));
	if ($ar_res = $db_res->Fetch())
	{
		$wizGroupId[] = $ar_res['GROUP_ID'];
	}
/*}*/
$wizGroupId[] = 2;

$dbResultList = CCatalogGroup::GetList(array(), array("CODE" => "retail"));
if($arRes = $dbResultList->Fetch())
{
	$arFields = Array();
	$arFields["USER_LANG"] = $arUserLang;
	$arFields["BASE"] = "Y";
	/*if ($wizard->GetVar("installPriceBASE") == "Y")
	{*/
		$arFields["USER_GROUP"] = $wizGroupId;
		$arFields["USER_GROUP_BUY"] = $wizGroupId;
	/*}*/
	CCatalogGroup::Update($arRes["ID"], $arFields);
}
else {
	$arFields = array(
		"BASE" => "Y",
		"NAME" => "retail",
		"SORT" => 100,
		"USER_GROUP" => $wizGroupId,
		"USER_GROUP_BUY" => $wizGroupId,
		"USER_LANG" => $arUserLang
	);
	CCatalogGroup::Add($arFields);
}

/* VAT */
$dbVat = CCatalogVat::GetListEx(
	array(),
	array('RATE' => 0),
	false,
	false,
	array('ID', 'RATE')
);
if(!($dbVat->Fetch()))
{
	$arF = array("ACTIVE" => "Y", "SORT" => "100", "NAME" => Loc::getMessage("WIZ_VAT_1"), "RATE" => 0);
	CCatalogVat::Add($arF);
}
$dbVat = CCatalogVat::GetListEx(
	array(),
	array('RATE' => Loc::getMessage("WIZ_VAT_2_VALUE")),
	false,
	false,
	array('ID', 'RATE')
);
if(!($dbVat->Fetch()))
{
	$arF = array("ACTIVE" => "Y", "SORT" => "200", "NAME" => Loc::getMessage("WIZ_VAT_2"), "RATE" => Loc::getMessage("WIZ_VAT_2_VALUE"));
	CCatalogVat::Add($arF);
}

/* OPTIONS */
Option::set("catalog", "allow_negative_amount", "N");
Option::set("catalog", "default_can_buy_zero", "N");
Option::set("catalog", "default_quantity_trace", "Y");