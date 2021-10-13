<?
if( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true ) die();

use Bitrix\Main\Config\Option,
	Bitrix\Main\Localization\Loc,
	Bitrix\Main\Loader;

if( !Loader::includeModule("catalog") ) {
	return;
}

/* default store if not exists */
$dbStores = CCatalogStore::GetList([], ["ACTIVE" => 'Y']);
if( !$dbStores->Fetch() ) {
	$arStoreFields = [
		"TITLE" => Loc::getMessage("CAT_STORE_NAME"),
		"ACTIVE" => "Y",
		"ADDRESS" => Loc::getMessage("STORE_ADR_1"),
		"DESCRIPTION" => Loc::getMessage("STORE_DESCR_1"),
	];
	$newStoreId = CCatalogStore::Add($arStoreFields);
	if( $newStoreId ) {
		$_SESSION['NEW_STORE_ID'] = $newStoreId;
	}
}

/* BASE PRICE */
$dbResultList = CCatalogGroup::GetList([], ["BASE" => "Y"]);
if( !$arRes = $dbResultList->Fetch() ) {

	$arLanguages = [];
	$rsLanguage = CLanguage::GetList($by, $order, []);
	while( $arLanguage = $rsLanguage->Fetch() )
		$arLanguages[] = $arLanguage["LID"];

	$arUserLang = [];
	foreach( $arLanguages as $languageID ) {
		WizardServices::IncludeServiceLang("index.php", $languageID);
		$arUserLang[$languageID] = Loc::getMessage("BASE_PRICE_NAME", null, $languageID);
	}

	$wizGroupId = [];
	$db_res = CCatalogGroup::GetGroupsList(["CATALOG_GROUP_ID" => '1', "BUY" => "Y"]);
	if( $ar_res = $db_res->Fetch() ) {
		$wizGroupId[] = $ar_res['GROUP_ID'];
	}
	$wizGroupId[] = 2;

	$arFields = [
		"BASE" => "Y",
		"NAME" => "retail",
		"SORT" => 100,
		"USER_GROUP" => $wizGroupId,
		"USER_GROUP_BUY" => $wizGroupId,
		"USER_LANG" => $arUserLang,
	];
	CCatalogGroup::Add($arFields);
}

/* VAT */
$dbVat = CCatalogVat::GetListEx(
	[],
	['RATE' => 0],
	false,
	false,
	['ID', 'RATE']
);
if( !($dbVat->Fetch()) ) {
	$arF = ["ACTIVE" => "Y", "SORT" => "100", "NAME" => Loc::getMessage("WIZ_VAT_0"), "RATE" => 0];
	CCatalogVat::Add($arF);
}
$dbVat = CCatalogVat::GetListEx(
	[],
	['RATE' => 10],
	false,
	false,
	['ID', 'RATE']
);
if( !($dbVat->Fetch()) ) {
	$arF = ["ACTIVE" => "Y", "SORT" => "200", "NAME" => Loc::getMessage("WIZ_VAT_10"), "RATE" => 10];
	CCatalogVat::Add($arF);
}
$dbVat = CCatalogVat::GetListEx(
	[],
	['RATE' => 20],
	false,
	false,
	['ID', 'RATE']
);
if( !($dbVat->Fetch()) ) {
	$arF = ["ACTIVE" => "Y", "SORT" => "300", "NAME" => Loc::getMessage("WIZ_VAT_20"), "RATE" => 20];
	CCatalogVat::Add($arF);
}


$s_count = 0;
$sites = \CSite::GetList($by = "sort", $order = "desc");
while( $sites_f = $sites->Fetch() )
	$s_count++;

if( $s_count < 2 ) {
	/* OPTIONS */
	Option::set("catalog", "allow_negative_amount", "N");
	Option::set("catalog", "default_can_buy_zero", "N");
	Option::set("catalog", "default_quantity_trace", "Y");
}