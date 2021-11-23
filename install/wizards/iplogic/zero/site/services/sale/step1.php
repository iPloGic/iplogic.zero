<?
if( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true ) {
	die();
}

use Bitrix\Sale\BusinessValue,
	Bitrix\Sale\OrderStatus,
	Bitrix\Sale\DeliveryStatus,
	Bitrix\Main\Localization\Loc,
	Bitrix\Main,
	Bitrix\Catalog,
	Bitrix\Sale,
	Bitrix\Main\Config\Option,
	Bitrix\Main\Loader;

if( !Loader::includeModule('sale') ) {
	return;
}

$s_count = 0;
$sites = \CSite::GetList($by = "sort", $order = "desc");
while( $sites_f = $sites->Fetch() )
	$s_count++;

$saleConverted15 = Option::get("main", "~sale_converted_15", "") == "Y";
if( $saleConverted15 ) {
	$BIZVAL_INDIVIDUAL_DOMAIN = BusinessValue::INDIVIDUAL_DOMAIN;
	$BIZVAL_ENTITY_DOMAIN = BusinessValue::ENTITY_DOMAIN;
}
else {
	$BIZVAL_INDIVIDUAL_DOMAIN = null;
	$BIZVAL_ENTITY_DOMAIN = null;
}

if( Option::get("catalog", "1C_GROUP_PERMISSIONS") == "" ) {
	Option::set("catalog", "1C_GROUP_PERMISSIONS", "1", Loc::getMessage('SALE_1C_GROUP_PERMISSIONS'));
}

$arGeneralInfo = [];

$dbSite = CSite::GetByID(WIZARD_SITE_ID);
if( $arSite = $dbSite->Fetch() ) {
	$lang = $arSite["LANGUAGE_ID"];
}
if( strlen($lang) <= 0 ) {
	$lang = "ru";
}
$bRus = false;
if( $lang == "ru" ) {
	$bRus = true;
}

$arLanguages = [];
$rsLanguage = CLanguage::GetList($by, $order, []);
while( $arLanguage = $rsLanguage->Fetch() )
	$arLanguages[] = $arLanguage["LID"];

WizardServices::IncludeServiceLang("step1.php", $lang);

$shopLocalization = $wizard->GetVar("shopLocalization");
$personType = $wizard->GetVar("personType");
$paysystem = $wizard->GetVar("paysystem");

Option::set("iplogic.zero", "shopLocalization", $shopLocalization, WIZARD_SITE_ID);


// default currency
$defCurrency = "EUR";
if( $lang == "ru" ) {
	if( $shopLocalization == "ua" ) {
		$defCurrency = "UAH";
	}
	elseif( $shopLocalization == "bl" ) {
		$defCurrency = "BYR";
	}
	elseif( $shopLocalization == "kz" ) {
		$defCurrency = "KZT";
	}
	else {
		$defCurrency = "RUB";
	}
}
elseif( $lang == "en" ) {
	$defCurrency = "USD";
}
if( !($ar_usd_cur = CCurrency::GetByID($defCurrency)) ) {
	$arFields = [
		"CURRENCY" => $defCurrency,
		"LANG"     => [],
	];
	foreach( $arLanguages as $languageID ) {
		WizardServices::IncludeServiceLang("step1.php", $languageID);
		$arFields["LANG"][$languageID]["FORMAT_STRING"] = Loc::getMessage("CURRENCY_FORMAT_" . $defCurrency);
		$arFields["LANG"][$languageID]["FULL_NAME"] = Loc::getMessage("CURRENCY_NAME_" . $defCurrency);
		$arFields["LANG"][$languageID]["DEC_POINT"] = ".";
		$arFields["LANG"][$languageID]["THOUSANDS_SEP"] = "\xA0";
		$arFields["LANG"][$languageID]["DECIMALS"] = 2;
		$arFields["LANG"][$languageID]["CURRENCY"] = $defCurrency;
		$arFields["LANG"][$languageID]["LID"] = $languageID;
	}
	WizardServices::IncludeServiceLang("step1.php", $lang);
	CCurrency::Add($arFields);
}
$dbCurrency = Bitrix\Sale\Internals\SiteCurrencyTable::getList(
	[
		"filter" => [
			"LID" => WIZARD_SITE_ID,
		],
	]
);
if( $curCurrency = $dbCurrency->fetch() ) {
	if( $curCurrency["CURRENCY"] != $defCurrency ) {
		Bitrix\Sale\Internals\SiteCurrencyTable::update(
			WIZARD_SITE_ID,
			[
				"CURRENCY" => $defCurrency,
			]
		);
	}
}
else {
	Bitrix\Sale\Internals\SiteCurrencyTable::add(
		[
			"LID"      => WIZARD_SITE_ID,
			"CURRENCY" => $defCurrency,
		]
	);
}


// Company
if( $bRus ) {
	if( $shopLocalization == "ru" || $shopLocalization == "kz" || $shopLocalization == "by" ) {

		$shopLocation = $wizard->GetVar("shopLocation");
		Option::set("iplogic.zero", "shopLocation", $shopLocation, WIZARD_SITE_ID);
		$shopOfName = $wizard->GetVar("shopOfName");
		Option::set("iplogic.zero", "shopOfName", $shopOfName, WIZARD_SITE_ID);
		$shopAdr = $wizard->GetVar("shopAdr");
		Option::set("iplogic.zero", "shopAdr", $shopAdr, WIZARD_SITE_ID);
		$shopZip = $wizard->GetVar("shopZip");
		Option::set("iplogic.zero", "shopZip", $shopZip, WIZARD_SITE_ID);

		$shopINN = $wizard->GetVar("shopINN");
		Option::set("iplogic.zero", "shopINN", $shopINN, WIZARD_SITE_ID);
		$shopKPP = $wizard->GetVar("shopKPP");
		Option::set("iplogic.zero", "shopKPP", $shopKPP, WIZARD_SITE_ID);
		$shopNS = $wizard->GetVar("shopNS");
		Option::set("iplogic.zero", "shopNS", $shopNS, WIZARD_SITE_ID);
		$shopBANK = $wizard->GetVar("shopBANK");
		Option::set("iplogic.zero", "shopBANK", $shopBANK, WIZARD_SITE_ID);
		$shopBANKREKV = $wizard->GetVar("shopBANKREKV");
		Option::set("iplogic.zero", "shopBANKREKV", $shopBANKREKV, WIZARD_SITE_ID);
		$shopKS = $wizard->GetVar("shopKS");
		Option::set("iplogic.zero", "shopKS", $shopKS, WIZARD_SITE_ID);
		/*$siteStamp = $wizard->GetVar("siteStamp");
		if( $siteStamp == "" ) {
			$siteStamp = Option::get("iplogic.zero", "siteStamp", "", WIZARD_SITE_ID);
		}*/
	}
	elseif( $shopLocalization == "ua" ) {

		$shopLocation = $wizard->GetVar("shopLocation_ua");
		Option::set("iplogic.zero", "shopLocation_ua", $shopLocation, WIZARD_SITE_ID);
		$shopOfName = $wizard->GetVar("shopOfName_ua");
		Option::set("iplogic.zero", "shopOfName_ua", $shopOfName, WIZARD_SITE_ID);
		$shopAdr = $wizard->GetVar("shopAdr_ua");
		Option::set("iplogic.zero", "shopAdr_ua", $shopAdr, WIZARD_SITE_ID);
		$shopZip = $wizard->GetVar("shopZip_ua");
		Option::set("iplogic.zero", "shopZip_ua", $shopZip, WIZARD_SITE_ID);

		$shopEGRPU_ua = $wizard->GetVar("shopEGRPU_ua");
		Option::set("iplogic.zero", "shopEGRPU_ua", $shopEGRPU_ua, WIZARD_SITE_ID);
		$shopINN_ua = $wizard->GetVar("shopINN_ua");
		Option::set("iplogic.zero", "shopINN_ua", $shopINN_ua, WIZARD_SITE_ID);
		$shopNDS_ua = $wizard->GetVar("shopNDS_ua");
		Option::set("iplogic.zero", "shopNDS_ua", $shopNDS_ua, WIZARD_SITE_ID);
		$shopNS_ua = $wizard->GetVar("shopNS_ua");
		Option::set("iplogic.zero", "shopNS_ua", $shopNS_ua, WIZARD_SITE_ID);
		$shopBank_ua = $wizard->GetVar("shopBank_ua");
		Option::set("iplogic.zero", "shopBank_ua", $shopBank_ua, WIZARD_SITE_ID);
		$shopMFO_ua = $wizard->GetVar("shopMFO_ua");
		Option::set("iplogic.zero", "shopMFO_ua", $shopMFO_ua, WIZARD_SITE_ID);
		$shopPlace_ua = $wizard->GetVar("shopPlace_ua");
		Option::set("iplogic.zero", "shopPlace_ua", $shopPlace_ua, WIZARD_SITE_ID);
		$shopFIO_ua = $wizard->GetVar("shopFIO_ua");
		Option::set("iplogic.zero", "shopFIO_ua", $shopFIO_ua, WIZARD_SITE_ID);
		$shopTax_ua = $wizard->GetVar("shopTax_ua");
		Option::set("iplogic.zero", "shopTax_ua", $shopTax_ua, WIZARD_SITE_ID);
	}
	$shopEmail = $wizard->GetVar("shopEmail");
	Option::set("iplogic.zero", "shopEmail", $shopEmail, WIZARD_SITE_ID);

	/*if( strlen($siteStamp) > 0 ) {
		if( IntVal($siteStamp) > 0 ) {
			$ff = CFile::GetByID($siteStamp);
			if( $zr = $ff->Fetch() ) {
				$strOldFile = str_replace("//", "/",
					WIZARD_SITE_ROOT_PATH . "/" . (Option::get("main", "upload_dir", "upload")) . "/" . $zr["SUBDIR"] .
					"/" . $zr["FILE_NAME"]);
				@copy($strOldFile, WIZARD_SITE_PATH . "include/stamp.gif");
				CFile::Delete($zr["ID"]);
				$siteStamp = WIZARD_SITE_DIR . "include/stamp.gif";
				Option::set("iplogic.zero", "siteStamp", $siteStamp, WIZARD_SITE_ID);
			}
		}
	}
	else {
		$siteStamp = "";
	}*/
}


//Person Types
$arPersonTypeNames = [];
$dbPerson = CSalePersonType::GetList([], ["LID" => WIZARD_SITE_ID]);
while( $arPerson = $dbPerson->Fetch() ) {
	$arPersonTypeNames[$arPerson["ID"]] = $arPerson["NAME"];
}
if( !$bRus ) {
	$personType["fiz"] = "Y";
	$personType["ur"] = "N";
}

$fizExist = in_array(Loc::getMessage("SALE_WIZARD_PERSON_1"), $arPersonTypeNames);
$urExist = in_array(Loc::getMessage("SALE_WIZARD_PERSON_2"), $arPersonTypeNames);
$fizUaExist = in_array(Loc::getMessage("SALE_WIZARD_PERSON_3"), $arPersonTypeNames);

$personTypeFiz = (isset($personType["fiz"]) && $personType["fiz"] == "Y" ? "Y" : "N");
Option::set("iplogic.zero", "personTypeFiz", $personTypeFiz, WIZARD_SITE_ID);
$personTypeUr = (isset($personType["ur"]) && $personType["ur"] == "Y" ? "Y" : "N");
Option::set("iplogic.zero", "personTypeUr", $personTypeUr, WIZARD_SITE_ID);

if( in_array(Loc::getMessage("SALE_WIZARD_PERSON_1"), $arPersonTypeNames) ) {
	$arGeneralInfo["personType"]["fiz"] = array_search(Loc::getMessage("SALE_WIZARD_PERSON_1"), $arPersonTypeNames);
	CSalePersonType::Update(
		array_search(Loc::getMessage("SALE_WIZARD_PERSON_1"), $arPersonTypeNames),
		[
			"ACTIVE" => $personTypeFiz,
		]
	);
}
elseif( $personTypeFiz == "Y" ) {
	$arGeneralInfo["personType"]["fiz"] = CSalePersonType::Add(
		[
			"LID"  => WIZARD_SITE_ID,
			"NAME" => Loc::getMessage("SALE_WIZARD_PERSON_1"),
			"SORT" => "100",
		]
	);
}

if( in_array(Loc::getMessage("SALE_WIZARD_PERSON_2"), $arPersonTypeNames) ) {
	$arGeneralInfo["personType"]["ur"] = array_search(Loc::getMessage("SALE_WIZARD_PERSON_2"), $arPersonTypeNames);
	CSalePersonType::Update(
		array_search(Loc::getMessage("SALE_WIZARD_PERSON_2"), $arPersonTypeNames),
		[
			"ACTIVE" => $personTypeUr,
		]
	);
}
elseif( $personTypeUr == "Y" ) {
	$arGeneralInfo["personType"]["ur"] = CSalePersonType::Add(
		[
			"LID"  => WIZARD_SITE_ID,
			"NAME" => Loc::getMessage("SALE_WIZARD_PERSON_2"),
			"SORT" => "150",
		]
	);
}

if( $shopLocalization == "ua" ) {
	$personTypeFizUa = (isset($personType["fiz_ua"]) && $personType["fiz_ua"] == "Y" ? "Y" : "N");
	Option::set("iplogic.zero", "personTypeFizUa", $personTypeFizUa, false, WIZARD_SITE_ID);

	if( in_array(Loc::getMessage("SALE_WIZARD_PERSON_3"), $arPersonTypeNames) ) {
		$arGeneralInfo["personType"]["fiz_ua"] =
			array_search(Loc::getMessage("SALE_WIZARD_PERSON_3"), $arPersonTypeNames);
		CSalePersonType::Update(
			array_search(Loc::getMessage("SALE_WIZARD_PERSON_3"), $arPersonTypeNames),
			[
				"ACTIVE" => $personTypeFizUa,
			]
		);
	}
	elseif( $personTypeFizUa == "Y" ) {
		$arGeneralInfo["personType"]["fiz_ua"] = CSalePersonType::Add(
			[
				"LID"  => WIZARD_SITE_ID,
				"NAME" => Loc::getMessage("SALE_WIZARD_PERSON_3"),
				"SORT" => "100",
			]
		);
	}
}


//Set options
if( $s_count < 2 ) {

	Option::set('sale', 'default_currency', $defCurrency);
	Option::set('sale', 'delete_after', '30');
	Option::set('sale', 'order_list_date', '30');
	Option::set('sale', 'MAX_LOCK_TIME', '30');
	Option::set('sale', 'GRAPH_WEIGHT', '600');
	Option::set('sale', 'GRAPH_HEIGHT', '600');
	Option::set('sale', 'path2user_ps_files', '/bitrix/php_interface/include/sale_payment/');
	Option::set('sale', 'lock_catalog', 'Y');
	Option::set('sale', 'order_list_fields', 'ID,USER,PAY_SYSTEM,PRICE,STATUS,PAYED,PS_STATUS,CANCELED,BASKET');
	Option::set('sale', 'GROUP_DEFAULT_RIGHT', 'D');
	Option::set('sale', 'affiliate_param_name', 'partner');
	Option::set('sale', 'show_order_sum', 'N');
	Option::set('sale', 'show_order_product_xml_id', 'N');
	Option::set('sale', 'show_paysystem_action_id', 'N');
	Option::set('sale', 'affiliate_plan_type', 'N');
	Option::set('sale', 'ADDRESS_different_set', 'N');
	Option::set('sale', 'measurement_path', '/bitrix/modules/sale/measurements.php');
	Option::set('sale', 'delivery_handles_custom_path', '/bitrix/php_interface/include/sale_delivery/');
	Option::set('sale', 'recalc_product_list', 'Y');
	Option::set('sale', 'recalc_product_list_period', '4');
	Option::set('sale', 'order_email', $shopEmail);
	Option::set('sale', 'encode_fuser_id', 'Y');
	Option::set('sale', 'order_changes_cleaner_active', 'Y');
	if( $bRus ) {
		Option::set('sale', '1C_SALE_SITE_LIST', WIZARD_SITE_ID);
		Option::set('sale', '1C_EXPORT_PAYED_ORDERS', 'N');
		Option::set('sale', '1C_EXPORT_ALLOW_DELIVERY_ORDERS', 'N');
		Option::set('sale', '1C_EXPORT_FINAL_ORDERS', '');
		Option::set('sale', '1C_FINAL_STATUS_ON_DELIVERY', 'F');
		Option::set('sale', '1C_REPLACE_CURRENCY', Loc::getMessage("SALE_WIZARD_PS_BILL_RUB"));
		Option::set('sale', '1C_SALE_USE_ZIP', 'Y');
	}

}
Option::set('sale', 'ADDRESS_different_set', "Y");
Option::set('sale', 'WEIGHT_different_set', "Y");

Option::set('sale', 'weight_unit', Loc::getMessage("SALE_WIZARD_WEIGHT_UNIT"), WIZARD_SITE_ID);
Option::set('sale', 'weight_koef', '1000', WIZARD_SITE_ID);
if( $bRus ) {
	Option::set('sale', 'location_zip', $shopZip, WIZARD_SITE_ID);
}

if( !$bRus ) {
	$shopLocation = Loc::getMessage("WIZ_CITY");
}

if( \Bitrix\Main\Config\Option::get('sale', 'sale_locationpro_migrated', '') == 'Y' ) {
	$location = '';

	if( strlen($shopLocation) ) {
		// get city with name equal to $shopLocation
		$item = \Bitrix\Sale\Location\LocationTable::getList(
			[
				'filter' => [
					'=NAME.LANGUAGE_ID' => $lang,
					'=NAME.NAME'        => $shopLocation,
					'=TYPE.CODE'        => 'CITY',
				],
				'select' => [
					'CODE',
				],
			]
		)->fetch();

		if( $item ) {
			$location = $item['CODE'];
		} // city found, simply take it`s code an proceed with it
		else {
			// city were not found, create it

			require($_SERVER['DOCUMENT_ROOT'] . WIZARD_SERVICE_RELATIVE_PATH . "/locations/pro/country_codes.php");

			$countryCode = $LOCALIZATION_COUNTRY_CODE_MAP[$shopLocalization];
			$countryId = false;

			if( strlen($countryCode) ) {
				// get country which matches the current localization
				$countryId = 0;
				$item = \Bitrix\Sale\Location\LocationTable::getList(
					[
						'filter' => [
							'=CODE'      => $countryCode,
							'=TYPE.CODE' => 'COUNTRY',
						],
						'select' => [
							'ID',
						],
					]
				)->fetch();

				// country found
				if( $item ) {
					$countryId = $item['ID'];
				}
			}

			// at this point types must exist
			$types = [];
			$res = \Bitrix\Sale\Location\TypeTable::getList();
			while( $item = $res->fetch() )
				$types[$item['CODE']] = $item['ID'];

			if( isset($types['COUNTRY']) && isset($types['CITY']) ) {
				if( !$countryId ) {
					// such country were not found, create it

					$data = [
						'CODE'    => 'demo_country_' . WIZARD_SITE_ID,
						'TYPE_ID' => $types['COUNTRY'],
						'NAME'    => [],
					];
					foreach( $arLanguages as $langID ) {
						$data["NAME"][$langID] = [
							'NAME' => Loc::getMessage("WIZ_COUNTRY_" . ToUpper($shopCountry)),
						];
					}

					$res = \Bitrix\Sale\Location\LocationTable::add($data);
					if( $res->isSuccess() ) {
						$countryId = $res->getId();
					}
				}

				if( $countryId ) {
					// ok, so country were created, now create demo-city

					$data = [
						'CODE'      => 'shop_city_' . WIZARD_SITE_ID,
						'TYPE_ID'   => $types['CITY'],
						'NAME'      => [],
						'PARENT_ID' => $countryId,
					];
					foreach( $arLanguages as $langID ) {
						$data["NAME"][$langID] = [
							'NAME' => $shopLocation,
						];
					}

					$res = \Bitrix\Sale\Location\LocationTable::add($data);
					if( $res->isSuccess() ) {
						$location = 'shop_city_' . WIZARD_SITE_ID;
					}
				}

			}
		}
	}
}
else {
	$location = 0;
	$dbLocation = CSaleLocation::GetList(["ID" => "ASC"], ["LID" => $lang, "CITY_NAME" => $shopLocation]);
	if( $arLocation = $dbLocation->Fetch() )//if there are no data in module
	{
		$location = $arLocation["ID"];
	}
	if( IntVal($location) <= 0 ) {
		$CurCountryID = 0;
		$db_contList = CSaleLocation::GetList(
			[],
			[
				"COUNTRY_NAME" => Loc::getMessage("WIZ_COUNTRY_" . ToUpper($shopLocalization)),
				"LID"          => $lang,
			]
		);
		if( $arContList = $db_contList->Fetch() ) {
			$LLL = IntVal($arContList["ID"]);
			$CurCountryID = IntVal($arContList["COUNTRY_ID"]);
		}

		if( IntVal($CurCountryID) <= 0 ) {
			$arArrayTmp = [];
			$arArrayTmp["NAME"] = Loc::getMessage("WIZ_COUNTRY_" . ToUpper($shopLocalization));
			foreach( $arLanguages as $langID ) {
				WizardServices::IncludeServiceLang("step1.php", $langID);
				$arArrayTmp[$langID] = [
					"LID"  => $langID,
					"NAME" => Loc::getMessage("WIZ_COUNTRY_" . ToUpper($shopLocalization)),
				];
			}
			$CurCountryID = CSaleLocation::AddCountry($arArrayTmp);
		}

		$arArrayTmp = [];
		$arArrayTmp["NAME"] = $shopLocation;
		foreach( $arLanguages as $langID ) {
			$arArrayTmp[$langID] = [
				"LID"  => $langID,
				"NAME" => $shopLocation,
			];
		}
		$city_id = CSaleLocation::AddCity($arArrayTmp);

		$location = CSaleLocation::AddLocation(
			[
				"COUNTRY_ID" => $CurCountryID,
				"CITY_ID"    => $city_id,
			]
		);
		if( $bRus ) {
			CSaleLocation::AddLocationZIP($location, $shopZip);
		}

		WizardServices::IncludeServiceLang("step1.php", $lang);
	}

}
Option::set('sale', 'location', $location, WIZARD_SITE_ID);


//Order Prop Group
if( $fizExist ) {
	$dbSaleOrderPropsGroup = CSaleOrderPropsGroup::GetList(
		[],
		[
			"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["fiz"],
			"NAME"           => Loc::getMessage("SALE_WIZARD_PROP_GROUP_FIZ1"),
		],
		false,
		false,
		["ID"]
	);
	if( $arSaleOrderPropsGroup = $dbSaleOrderPropsGroup->GetNext() ) {
		$arGeneralInfo["propGroup"]["user_fiz"] = $arSaleOrderPropsGroup["ID"];
	}

	$dbSaleOrderPropsGroup = CSaleOrderPropsGroup::GetList(
		[],
		[
			"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["fiz"],
			"NAME"           => Loc::getMessage("SALE_WIZARD_PROP_GROUP_FIZ2"),
		],
		false,
		false,
		["ID"]
	);
	if( $arSaleOrderPropsGroup = $dbSaleOrderPropsGroup->GetNext() ) {
		$arGeneralInfo["propGroup"]["adres_fiz"] = $arSaleOrderPropsGroup["ID"];
	}

}
elseif( $personType["fiz"] == "Y" ) {
	$arGeneralInfo["propGroup"]["user_fiz"] =
		CSaleOrderPropsGroup::Add(
			[
				"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["fiz"],
				"NAME"           => Loc::getMessage("SALE_WIZARD_PROP_GROUP_FIZ1"),
				"SORT"           => 100,
			]
		);
	$arGeneralInfo["propGroup"]["adres_fiz"] =
		CSaleOrderPropsGroup::Add(
			[
				"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["fiz"],
				"NAME"           => Loc::getMessage("SALE_WIZARD_PROP_GROUP_FIZ2"),
				"SORT"           => 200,
			]
		);
}

if( $urExist ) {
	$dbSaleOrderPropsGroup = CSaleOrderPropsGroup::GetList(
		[],
		[
			"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
			"NAME"           => Loc::getMessage("SALE_WIZARD_PROP_GROUP_UR1"),
		],
		false,
		false,
		["ID"]
	);
	if( $arSaleOrderPropsGroup = $dbSaleOrderPropsGroup->GetNext() ) {
		$arGeneralInfo["propGroup"]["user_ur"] = $arSaleOrderPropsGroup["ID"];
	}

	$dbSaleOrderPropsGroup = CSaleOrderPropsGroup::GetList(
		[],
		[
			"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
			"NAME"           => Loc::getMessage("SALE_WIZARD_PROP_GROUP_UR2"),
		],
		false,
		false,
		["ID"]
	);
	if( $arSaleOrderPropsGroup = $dbSaleOrderPropsGroup->GetNext() ) {
		$arGeneralInfo["propGroup"]["adres_ur"] = $arSaleOrderPropsGroup["ID"];
	}
}
elseif( $personType["ur"] == "Y" ) {
	$arGeneralInfo["propGroup"]["user_ur"] =
		CSaleOrderPropsGroup::Add(
			[
				"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
				"NAME"           => Loc::getMessage("SALE_WIZARD_PROP_GROUP_UR1"),
				"SORT"           => 300,
			]
		);
	$arGeneralInfo["propGroup"]["adres_ur"] =
		CSaleOrderPropsGroup::Add(
			[
				"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
				"NAME"           => Loc::getMessage("SALE_WIZARD_PROP_GROUP_UR2"),
				"SORT"           => 400,
			]
		);
}

if( $shopLocalization == "ua" ) {
	if( $fizUaExist ) {
		$dbSaleOrderPropsGroup = CSaleOrderPropsGroup::GetList(
			[],
			[
				"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["fiz_ua"],
				"NAME"           => Loc::getMessage("SALE_WIZARD_PROP_GROUP_FIZ1"),
			],
			false,
			false,
			["ID"]
		);
		if( $arSaleOrderPropsGroup = $dbSaleOrderPropsGroup->GetNext() ) {
			$arGeneralInfo["propGroup"]["user_fiz_ua"] = $arSaleOrderPropsGroup["ID"];
		}

		$dbSaleOrderPropsGroup = CSaleOrderPropsGroup::GetList(
			[],
			[
				"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["fiz_ua"],
				"NAME"           => Loc::getMessage("SALE_WIZARD_PROP_GROUP_FIZ2"),
			],
			false,
			false,
			["ID"]
		);
		if( $arSaleOrderPropsGroup = $dbSaleOrderPropsGroup->GetNext() ) {
			$arGeneralInfo["propGroup"]["adres_fiz_ua"] = $arSaleOrderPropsGroup["ID"];
		}
	}
	elseif( $personType["fiz_ua"] == "Y" ) {
		$arGeneralInfo["propGroup"]["user_fiz_ua"] =
			CSaleOrderPropsGroup::Add(
				[
					"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["fiz_ua"],
					"NAME"           => Loc::getMessage("SALE_WIZARD_PROP_GROUP_FIZ1"),
					"SORT"           => 100,
				]
			);
		$arGeneralInfo["propGroup"]["adres_fiz_ua"] =
			CSaleOrderPropsGroup::Add(
				[
					"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["fiz_ua"],
					"NAME"           => Loc::getMessage("SALE_WIZARD_PROP_GROUP_FIZ2"),
					"SORT"           => 200,
				]
			);
	}
}

$businessValuePersonDomain = [];

$businessValueGroups = [
	'COMPANY'        => ['SORT' => 100],
	'CLIENT'         => ['SORT' => 200],
	'CLIENT_COMPANY' => ['SORT' => 300],
];

$businessValueCodes = [];

$arProps = [];

if( $personType["fiz"] == "Y" ) {
	$businessValuePersonDomain[$arGeneralInfo["personType"]["fiz"]] = $BIZVAL_INDIVIDUAL_DOMAIN;

	$businessValueCodes['CLIENT_NAME'] = ['GROUP' => 'CLIENT', 'SORT' => 100, 'DOMAIN' => $BIZVAL_INDIVIDUAL_DOMAIN];
	$arProps[] = [
		"PERSON_TYPE_ID"  => $arGeneralInfo["personType"]["fiz"],
		"NAME"            => Loc::getMessage("SALE_WIZARD_PROP_6"),
		"TYPE"            => "TEXT",
		"REQUIED"         => "Y",
		"DEFAULT_VALUE"   => "",
		"SORT"            => 100,
		"USER_PROPS"      => "Y",
		"IS_LOCATION"     => "N",
		"PROPS_GROUP_ID"  => $arGeneralInfo["propGroup"]["user_fiz"],
		"SIZE1"           => 40,
		"SIZE2"           => 0,
		"DESCRIPTION"     => "",
		"IS_EMAIL"        => "N",
		"IS_PROFILE_NAME" => "Y",
		"IS_PAYER"        => "Y",
		"IS_LOCATION4TAX" => "N",
		"CODE"            => "FIO",
		"IS_FILTERED"     => "Y",
	];

	$businessValueCodes['CLIENT_EMAIL'] = ['GROUP' => 'CLIENT', 'SORT' => 110, 'DOMAIN' => $BIZVAL_INDIVIDUAL_DOMAIN];
	$arProps[] = [
		"PERSON_TYPE_ID"  => $arGeneralInfo["personType"]["fiz"],
		"NAME"            => "E-Mail",
		"TYPE"            => "TEXT",
		"REQUIED"         => "Y",
		"DEFAULT_VALUE"   => "",
		"SORT"            => 110,
		"USER_PROPS"      => "Y",
		"IS_LOCATION"     => "N",
		"PROPS_GROUP_ID"  => $arGeneralInfo["propGroup"]["user_fiz"],
		"SIZE1"           => 40,
		"SIZE2"           => 0,
		"DESCRIPTION"     => "",
		"IS_EMAIL"        => "Y",
		"IS_PROFILE_NAME" => "N",
		"IS_PAYER"        => "N",
		"IS_LOCATION4TAX" => "N",
		"CODE"            => "EMAIL",
		"IS_FILTERED"     => "Y",
	];

	$businessValueCodes['CLIENT_PHONE'] = ['GROUP' => 'CLIENT', 'SORT' => 120, 'DOMAIN' => $BIZVAL_INDIVIDUAL_DOMAIN];
	$arProps[] = [
		"PERSON_TYPE_ID"  => $arGeneralInfo["personType"]["fiz"],
		"NAME"            => Loc::getMessage("SALE_WIZARD_PROP_9"),
		"TYPE"            => "TEXT",
		"REQUIED"         => "Y",
		"DEFAULT_VALUE"   => "",
		"SORT"            => 120,
		"USER_PROPS"      => "Y",
		"IS_LOCATION"     => "N",
		"PROPS_GROUP_ID"  => $arGeneralInfo["propGroup"]["user_fiz"],
		"SIZE1"           => 0,
		"SIZE2"           => 0,
		"DESCRIPTION"     => "",
		"IS_EMAIL"        => "N",
		"IS_PROFILE_NAME" => "N",
		"IS_PAYER"        => "N",
		"IS_LOCATION4TAX" => "N",
		"CODE"            => "PHONE",
		"IS_PHONE"        => "Y",
		"IS_FILTERED"     => "N",
	];

	$businessValueCodes['CLIENT_ZIP'] = ['GROUP' => 'CLIENT', 'SORT' => 130, 'DOMAIN' => $BIZVAL_INDIVIDUAL_DOMAIN];
	$arProps[] = [
		"PERSON_TYPE_ID"  => $arGeneralInfo["personType"]["fiz"],
		"NAME"            => Loc::getMessage("SALE_WIZARD_PROP_4"),
		"TYPE"            => "TEXT",
		"REQUIED"         => "N",
		"DEFAULT_VALUE"   => "101000",
		"SORT"            => 130,
		"USER_PROPS"      => "Y",
		"IS_LOCATION"     => "N",
		"PROPS_GROUP_ID"  => $arGeneralInfo["propGroup"]["adres_fiz"],
		"SIZE1"           => 8,
		"SIZE2"           => 0,
		"DESCRIPTION"     => "",
		"IS_EMAIL"        => "N",
		"IS_PROFILE_NAME" => "N",
		"IS_PAYER"        => "N",
		"IS_LOCATION4TAX" => "N",
		"CODE"            => "ZIP",
		"IS_FILTERED"     => "N",
		"IS_ZIP"          => "Y",
	];

	$businessValueCodes['CLIENT_CITY'] = ['GROUP' => 'CLIENT', 'SORT' => 145, 'DOMAIN' => $BIZVAL_INDIVIDUAL_DOMAIN];
	$arProps[] = [
		"PERSON_TYPE_ID"  => $arGeneralInfo["personType"]["fiz"],
		"NAME"            => Loc::getMessage("SALE_WIZARD_PROP_21"),
		"TYPE"            => "TEXT",
		"REQUIED"         => "N",
		"DEFAULT_VALUE"   => $shopLocation,
		"SORT"            => 145,
		"USER_PROPS"      => "Y",
		"IS_LOCATION"     => "N",
		"PROPS_GROUP_ID"  => $arGeneralInfo["propGroup"]["adres_fiz"],
		"SIZE1"           => 40,
		"SIZE2"           => 0,
		"DESCRIPTION"     => "",
		"IS_EMAIL"        => "N",
		"IS_PROFILE_NAME" => "N",
		"IS_PAYER"        => "N",
		"IS_LOCATION4TAX" => "N",
		"CODE"            => "CITY",
		"IS_FILTERED"     => "Y",
	];

	$businessValueCodes['CLIENT_LOCATION'] =
		['GROUP' => 'CLIENT', 'SORT' => 140, 'DOMAIN' => $BIZVAL_INDIVIDUAL_DOMAIN];
	$arProps[] = [
		"PERSON_TYPE_ID"       => $arGeneralInfo["personType"]["fiz"],
		"NAME"                 => Loc::getMessage("SALE_WIZARD_PROP_2"),
		"TYPE"                 => "LOCATION",
		"REQUIED"              => "Y",
		"DEFAULT_VALUE"        => $location,
		"SORT"                 => 140,
		"USER_PROPS"           => "Y",
		"IS_LOCATION"          => "Y",
		"PROPS_GROUP_ID"       => $arGeneralInfo["propGroup"]["adres_fiz"],
		"SIZE1"                => 40,
		"SIZE2"                => 0,
		"DESCRIPTION"          => "",
		"IS_EMAIL"             => "N",
		"IS_PROFILE_NAME"      => "N",
		"IS_PAYER"             => "N",
		"IS_LOCATION4TAX"      => "N",
		"CODE"                 => "LOCATION",
		"IS_FILTERED"          => "N",
		"INPUT_FIELD_LOCATION" => "",
	];

	$businessValueCodes['CLIENT_ADDRESS'] = ['GROUP' => 'CLIENT', 'SORT' => 150, 'DOMAIN' => $BIZVAL_INDIVIDUAL_DOMAIN];
	$arProps[] = [
		"PERSON_TYPE_ID"  => $arGeneralInfo["personType"]["fiz"],
		"NAME"            => Loc::getMessage("SALE_WIZARD_PROP_5"),
		"TYPE"            => "TEXTAREA",
		"REQUIED"         => "Y",
		"DEFAULT_VALUE"   => "",
		"SORT"            => 150,
		"USER_PROPS"      => "Y",
		"IS_LOCATION"     => "N",
		"PROPS_GROUP_ID"  => $arGeneralInfo["propGroup"]["adres_fiz"],
		"SIZE1"           => 30,
		"SIZE2"           => 3,
		"DESCRIPTION"     => "",
		"IS_EMAIL"        => "N",
		"IS_PROFILE_NAME" => "N",
		"IS_PAYER"        => "N",
		"IS_LOCATION4TAX" => "N",
		"CODE"            => "ADDRESS",
		"IS_FILTERED"     => "N",
		"IS_ADDRESS"      => "Y",
	];
}

if( $personType["ur"] == "Y" ) {
	$businessValuePersonDomain[$arGeneralInfo["personType"]["ur"]] = $BIZVAL_ENTITY_DOMAIN;

	if( $shopLocalization != "ua" ) {
		$businessValueCodes['COMPANY_NAME'] = ['GROUP' => 'COMPANY', 'SORT' => 200, 'DOMAIN' => $BIZVAL_ENTITY_DOMAIN];
		$arProps[] = [
			"PERSON_TYPE_ID"  => $arGeneralInfo["personType"]["ur"],
			"NAME"            => Loc::getMessage("SALE_WIZARD_PROP_8"),
			"TYPE"            => "TEXT",
			"REQUIED"         => "Y",
			"DEFAULT_VALUE"   => "",
			"SORT"            => 200,
			"USER_PROPS"      => "Y",
			"IS_LOCATION"     => "N",
			"PROPS_GROUP_ID"  => $arGeneralInfo["propGroup"]["user_ur"],
			"SIZE1"           => 40,
			"SIZE2"           => 0,
			"DESCRIPTION"     => "",
			"IS_EMAIL"        => "N",
			"IS_PROFILE_NAME" => "Y",
			"IS_PAYER"        => "N",
			"IS_LOCATION4TAX" => "N",
			"CODE"            => "COMPANY",
			"IS_FILTERED"     => "Y",
		];

		$businessValueCodes['COMPANY_ADDRESS'] =
			['GROUP' => 'COMPANY', 'SORT' => 210, 'DOMAIN' => $BIZVAL_ENTITY_DOMAIN];
		$arProps[] = [
			"PERSON_TYPE_ID"  => $arGeneralInfo["personType"]["ur"],
			"NAME"            => Loc::getMessage("SALE_WIZARD_PROP_7"),
			"TYPE"            => "TEXTAREA",
			"REQUIED"         => "N",
			"DEFAULT_VALUE"   => "",
			"SORT"            => 210,
			"USER_PROPS"      => "Y",
			"IS_LOCATION"     => "N",
			"PROPS_GROUP_ID"  => $arGeneralInfo["propGroup"]["user_ur"],
			"SIZE1"           => 40,
			"SIZE2"           => 0,
			"DESCRIPTION"     => "",
			"IS_EMAIL"        => "N",
			"IS_PROFILE_NAME" => "N",
			"IS_PAYER"        => "N",
			"IS_LOCATION4TAX" => "N",
			"CODE"            => "COMPANY_ADR",
			"IS_FILTERED"     => "N",
			"IS_ADDRESS"      => "Y",
		];

		$businessValueCodes['COMPANY_INN'] = ['GROUP' => 'COMPANY', 'SORT' => 220, 'DOMAIN' => $BIZVAL_ENTITY_DOMAIN];
		$arProps[] = [
			"PERSON_TYPE_ID"  => $arGeneralInfo["personType"]["ur"],
			"NAME"            => Loc::getMessage("SALE_WIZARD_PROP_13"),
			"TYPE"            => "TEXT",
			"REQUIED"         => "N",
			"DEFAULT_VALUE"   => "",
			"SORT"            => 220,
			"USER_PROPS"      => "Y",
			"IS_LOCATION"     => "N",
			"PROPS_GROUP_ID"  => $arGeneralInfo["propGroup"]["user_ur"],
			"SIZE1"           => 0,
			"SIZE2"           => 0,
			"DESCRIPTION"     => "",
			"IS_EMAIL"        => "N",
			"IS_PROFILE_NAME" => "N",
			"IS_PAYER"        => "N",
			"IS_LOCATION4TAX" => "N",
			"CODE"            => "INN",
			"IS_FILTERED"     => "N",
		];

		$businessValueCodes['COMPANY_KPP'] = ['GROUP' => 'COMPANY', 'SORT' => 230, 'DOMAIN' => $BIZVAL_ENTITY_DOMAIN];
		$arProps[] = [
			"PERSON_TYPE_ID"  => $arGeneralInfo["personType"]["ur"],
			"NAME"            => Loc::getMessage("SALE_WIZARD_PROP_14"),
			"TYPE"            => "TEXT",
			"REQUIED"         => "N",
			"DEFAULT_VALUE"   => "",
			"SORT"            => 230,
			"USER_PROPS"      => "Y",
			"IS_LOCATION"     => "N",
			"PROPS_GROUP_ID"  => $arGeneralInfo["propGroup"]["user_ur"],
			"SIZE1"           => 0,
			"SIZE2"           => 0,
			"DESCRIPTION"     => "",
			"IS_EMAIL"        => "N",
			"IS_PROFILE_NAME" => "N",
			"IS_PAYER"        => "N",
			"IS_LOCATION4TAX" => "N",
			"CODE"            => "KPP",
			"IS_FILTERED"     => "N",
		];

		$businessValueCodes['COMPANY_CONTACT_NAME'] =
			['GROUP' => 'COMPANY', 'SORT' => 240, 'DOMAIN' => $BIZVAL_ENTITY_DOMAIN];
		$arProps[] = [
			"PERSON_TYPE_ID"  => $arGeneralInfo["personType"]["ur"],
			"NAME"            => Loc::getMessage("SALE_WIZARD_PROP_10"),
			"TYPE"            => "TEXT",
			"REQUIED"         => "Y",
			"DEFAULT_VALUE"   => "",
			"SORT"            => 240,
			"USER_PROPS"      => "Y",
			"IS_LOCATION"     => "N",
			"PROPS_GROUP_ID"  => $arGeneralInfo["propGroup"]["adres_ur"],
			"SIZE1"           => 0,
			"SIZE2"           => 0,
			"DESCRIPTION"     => "",
			"IS_EMAIL"        => "N",
			"IS_PROFILE_NAME" => "N",
			"IS_PAYER"        => "Y",
			"IS_LOCATION4TAX" => "N",
			"CODE"            => "CONTACT_PERSON",
			"IS_FILTERED"     => "N",
		];

		$businessValueCodes['COMPANY_EMAIL'] = ['GROUP' => 'COMPANY', 'SORT' => 250, 'DOMAIN' => $BIZVAL_ENTITY_DOMAIN];
		$arProps[] = [
			"PERSON_TYPE_ID"  => $arGeneralInfo["personType"]["ur"],
			"NAME"            => "E-Mail",
			"TYPE"            => "TEXT",
			"REQUIED"         => "Y",
			"DEFAULT_VALUE"   => "",
			"SORT"            => 250,
			"USER_PROPS"      => "Y",
			"IS_LOCATION"     => "N",
			"PROPS_GROUP_ID"  => $arGeneralInfo["propGroup"]["adres_ur"],
			"SIZE1"           => 40,
			"SIZE2"           => 0,
			"DESCRIPTION"     => "",
			"IS_EMAIL"        => "Y",
			"IS_PROFILE_NAME" => "N",
			"IS_PAYER"        => "N",
			"IS_LOCATION4TAX" => "N",
			"CODE"            => "EMAIL",
			"IS_FILTERED"     => "N",
		];

		$businessValueCodes['COMPANY_PHONE'] = ['GROUP' => 'COMPANY', 'SORT' => 260, 'DOMAIN' => $BIZVAL_ENTITY_DOMAIN];
		$arProps[] = [
			"PERSON_TYPE_ID"  => $arGeneralInfo["personType"]["ur"],
			"NAME"            => Loc::getMessage("SALE_WIZARD_PROP_9"),
			"TYPE"            => "TEXT",
			"REQUIED"         => "N",
			"DEFAULT_VALUE"   => "",
			"SORT"            => 260,
			"USER_PROPS"      => "Y",
			"IS_LOCATION"     => "N",
			"PROPS_GROUP_ID"  => $arGeneralInfo["propGroup"]["adres_ur"],
			"SIZE1"           => 0,
			"SIZE2"           => 0,
			"DESCRIPTION"     => "",
			"IS_EMAIL"        => "N",
			"IS_PROFILE_NAME" => "N",
			"IS_PAYER"        => "N",
			"IS_LOCATION4TAX" => "N",
			"IS_PHONE"        => "Y",
			"CODE"            => "PHONE",
			"IS_FILTERED"     => "N",
		];

		$businessValueCodes['COMPANY_ZIP'] = ['GROUP' => 'COMPANY', 'SORT' => 280, 'DOMAIN' => $BIZVAL_ENTITY_DOMAIN];
		$arProps[] = [
			"PERSON_TYPE_ID"  => $arGeneralInfo["personType"]["ur"],
			"NAME"            => Loc::getMessage("SALE_WIZARD_PROP_4"),
			"TYPE"            => "TEXT",
			"REQUIED"         => "N",
			"DEFAULT_VALUE"   => "101000",
			"SORT"            => 280,
			"USER_PROPS"      => "Y",
			"IS_LOCATION"     => "N",
			"PROPS_GROUP_ID"  => $arGeneralInfo["propGroup"]["adres_ur"],
			"SIZE1"           => 8,
			"SIZE2"           => 0,
			"DESCRIPTION"     => "",
			"IS_EMAIL"        => "N",
			"IS_PROFILE_NAME" => "N",
			"IS_PAYER"        => "N",
			"IS_LOCATION4TAX" => "N",
			"CODE"            => "ZIP",
			"IS_FILTERED"     => "N",
			"IS_ZIP"          => "Y",
		];

		$businessValueCodes['COMPANY_CITY'] = ['GROUP' => 'COMPANY', 'SORT' => 285, 'DOMAIN' => $BIZVAL_ENTITY_DOMAIN];
		$arProps[] = [
			"PERSON_TYPE_ID"  => $arGeneralInfo["personType"]["ur"],
			"NAME"            => Loc::getMessage("SALE_WIZARD_PROP_21"),
			"TYPE"            => "TEXT",
			"REQUIED"         => "N",
			"DEFAULT_VALUE"   => $shopLocation,
			"SORT"            => 285,
			"USER_PROPS"      => "Y",
			"IS_LOCATION"     => "N",
			"PROPS_GROUP_ID"  => $arGeneralInfo["propGroup"]["adres_ur"],
			"SIZE1"           => 40,
			"SIZE2"           => 0,
			"DESCRIPTION"     => "",
			"IS_EMAIL"        => "N",
			"IS_PROFILE_NAME" => "N",
			"IS_PAYER"        => "N",
			"IS_LOCATION4TAX" => "N",
			"CODE"            => "CITY",
			"IS_FILTERED"     => "Y",
		];

		$businessValueCodes['COMPANY_LOCATION'] =
			['GROUP' => 'COMPANY', 'SORT' => 290, 'DOMAIN' => $BIZVAL_ENTITY_DOMAIN];
		$arProps[] = [
			"PERSON_TYPE_ID"  => $arGeneralInfo["personType"]["ur"],
			"NAME"            => Loc::getMessage("SALE_WIZARD_PROP_2"),
			"TYPE"            => "LOCATION",
			"REQUIED"         => "Y",
			"DEFAULT_VALUE"   => "",
			"SORT"            => 290,
			"USER_PROPS"      => "Y",
			"IS_LOCATION"     => "Y",
			"PROPS_GROUP_ID"  => $arGeneralInfo["propGroup"]["adres_ur"],
			"SIZE1"           => 40,
			"SIZE2"           => 0,
			"DESCRIPTION"     => "",
			"IS_EMAIL"        => "N",
			"IS_PROFILE_NAME" => "N",
			"IS_PAYER"        => "N",
			"IS_LOCATION4TAX" => "Y",
			"CODE"            => "LOCATION",
			"IS_FILTERED"     => "N",
		];

		$businessValueCodes['COMPANY_OPERATION_ADDRESS'] =
			['GROUP' => 'COMPANY', 'SORT' => 300, 'DOMAIN' => $BIZVAL_ENTITY_DOMAIN];
		$arProps[] = [
			"PERSON_TYPE_ID"  => $arGeneralInfo["personType"]["ur"],
			"NAME"            => Loc::getMessage("SALE_WIZARD_PROP_12"),
			"TYPE"            => "TEXTAREA",
			"REQUIED"         => "Y",
			"DEFAULT_VALUE"   => "",
			"SORT"            => 300,
			"USER_PROPS"      => "Y",
			"IS_LOCATION"     => "N",
			"PROPS_GROUP_ID"  => $arGeneralInfo["propGroup"]["adres_ur"],
			"SIZE1"           => 30,
			"SIZE2"           => 10,
			"DESCRIPTION"     => "",
			"IS_EMAIL"        => "N",
			"IS_PROFILE_NAME" => "N",
			"IS_PAYER"        => "N",
			"IS_LOCATION4TAX" => "N",
			"CODE"            => "ADDRESS",
			"IS_FILTERED"     => "N",
			"IS_ADDRESS"      => "Y",
		];
	}
	else {
		/*
		$businessValueCodes['COMPANY_CONTACT_NAME'] = array('GROUP' => 'COMPANY', 'SORT' =>  100, 'DOMAIN' => $BIZVAL_ENTITY_DOMAIN);
		$arProps[] = Array(
			"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"],
			"NAME" => Loc::getMessage("SALE_WIZARD_PROP_41"),
			"TYPE" => "TEXT",
			"REQUIED" => "Y",
			"DEFAULT_VALUE" => "",
			"SORT" => 100,
			"USER_PROPS" => "Y",
			"IS_LOCATION" => "N",
			"PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["user_ur"],
			"SIZE1" => 40,
			"SIZE2" => 0,
			"DESCRIPTION" => "",
			"IS_EMAIL" => "N",
			"IS_PROFILE_NAME" => "Y",
			"IS_PAYER" => "Y",
			"IS_LOCATION4TAX" => "N",
			"CODE" => "CONTACT_NAME",
			"IS_FILTERED" => "Y",
		);*/

		$businessValueCodes['COMPANY_EMAIL'] = ['GROUP' => 'COMPANY', 'SORT' => 110, 'DOMAIN' => $BIZVAL_ENTITY_DOMAIN];
		$arProps[] = [
			"PERSON_TYPE_ID"  => $arGeneralInfo["personType"]["ur"],
			"NAME"            => "E-Mail",
			"TYPE"            => "TEXT",
			"REQUIED"         => "Y",
			"DEFAULT_VALUE"   => "",
			"SORT"            => 110,
			"USER_PROPS"      => "Y",
			"IS_LOCATION"     => "N",
			"PROPS_GROUP_ID"  => $arGeneralInfo["propGroup"]["adres_ur"],
			"SIZE1"           => 40,
			"SIZE2"           => 0,
			"DESCRIPTION"     => "",
			"IS_EMAIL"        => "Y",
			"IS_PROFILE_NAME" => "N",
			"IS_PAYER"        => "N",
			"IS_LOCATION4TAX" => "N",
			"CODE"            => "EMAIL",
			"IS_FILTERED"     => "Y",
		];

		$businessValueCodes['COMPANY_NAME'] = ['GROUP' => 'COMPANY', 'SORT' => 130, 'DOMAIN' => $BIZVAL_ENTITY_DOMAIN];
		$arProps[] = [
			"PERSON_TYPE_ID"  => $arGeneralInfo["personType"]["ur"],
			"NAME"            => Loc::getMessage("SALE_WIZARD_PROP_40"),
			"TYPE"            => "TEXT",
			"REQUIED"         => "Y",
			"DEFAULT_VALUE"   => "",
			"SORT"            => 130,
			"USER_PROPS"      => "Y",
			"IS_LOCATION"     => "N",
			"PROPS_GROUP_ID"  => $arGeneralInfo["propGroup"]["user_ur"],
			"SIZE1"           => 40,
			"SIZE2"           => 0,
			"DESCRIPTION"     => "",
			"IS_EMAIL"        => "N",
			"IS_PROFILE_NAME" => "Y",
			"IS_PAYER"        => "N",
			"IS_LOCATION4TAX" => "N",
			"CODE"            => "COMPANY_NAME",
			"IS_FILTERED"     => "Y",
		];

		$businessValueCodes['COMPANY_ADDRESS'] =
			['GROUP' => 'COMPANY', 'SORT' => 140, 'DOMAIN' => $BIZVAL_ENTITY_DOMAIN];
		$arProps[] = [
			"PERSON_TYPE_ID"  => $arGeneralInfo["personType"]["ur"],
			"NAME"            => Loc::getMessage("SALE_WIZARD_PROP_47"),
			"TYPE"            => "TEXTAREA",
			"REQUIED"         => "Y",
			"DEFAULT_VALUE"   => "",
			"SORT"            => 140,
			"USER_PROPS"      => "Y",
			"IS_LOCATION"     => "N",
			"PROPS_GROUP_ID"  => $arGeneralInfo["propGroup"]["adres_ur"],
			"SIZE1"           => 40,
			"SIZE2"           => 0,
			"DESCRIPTION"     => "",
			"IS_EMAIL"        => "N",
			"IS_PROFILE_NAME" => "N",
			"IS_PAYER"        => "N",
			"IS_LOCATION4TAX" => "N",
			"CODE"            => "COMPANY_ADR",
			"IS_FILTERED"     => "N",
			"IS_ADDRESS"      => "Y",
		];

		$businessValueCodes['COMPANY_EGRPU'] = ['GROUP' => 'COMPANY', 'SORT' => 150, 'DOMAIN' => $BIZVAL_ENTITY_DOMAIN];
		$arProps[] = [
			"PERSON_TYPE_ID"  => $arGeneralInfo["personType"]["ur"],
			"NAME"            => Loc::getMessage("SALE_WIZARD_PROP_48"),
			"TYPE"            => "TEXT",
			"REQUIED"         => "Y",
			"DEFAULT_VALUE"   => "",
			"SORT"            => 150,
			"USER_PROPS"      => "Y",
			"IS_LOCATION"     => "N",
			"PROPS_GROUP_ID"  => $arGeneralInfo["propGroup"]["adres_ur"],
			"SIZE1"           => 30,
			"SIZE2"           => 0,
			"DESCRIPTION"     => "",
			"IS_EMAIL"        => "N",
			"IS_PROFILE_NAME" => "N",
			"IS_PAYER"        => "N",
			"IS_LOCATION4TAX" => "N",
			"CODE"            => "EGRPU",
			"IS_FILTERED"     => "N",
		];

		$businessValueCodes['COMPANY_INN'] = ['GROUP' => 'COMPANY', 'SORT' => 160, 'DOMAIN' => $BIZVAL_ENTITY_DOMAIN];
		$arProps[] = [
			"PERSON_TYPE_ID"  => $arGeneralInfo["personType"]["ur"],
			"NAME"            => Loc::getMessage("SALE_WIZARD_PROP_49"),
			"TYPE"            => "TEXT",
			"REQUIED"         => "N",
			"DEFAULT_VALUE"   => "",
			"SORT"            => 160,
			"USER_PROPS"      => "Y",
			"IS_LOCATION"     => "N",
			"PROPS_GROUP_ID"  => $arGeneralInfo["propGroup"]["adres_ur"],
			"SIZE1"           => 30,
			"SIZE2"           => 0,
			"DESCRIPTION"     => "",
			"IS_EMAIL"        => "N",
			"IS_PROFILE_NAME" => "N",
			"IS_PAYER"        => "N",
			"IS_LOCATION4TAX" => "N",
			"CODE"            => "INN",
			"IS_FILTERED"     => "N",
		];

		$businessValueCodes['COMPANY_NDS'] = ['GROUP' => 'COMPANY', 'SORT' => 170, 'DOMAIN' => $BIZVAL_ENTITY_DOMAIN];
		$arProps[] = [
			"PERSON_TYPE_ID"  => $arGeneralInfo["personType"]["ur"],
			"NAME"            => Loc::getMessage("SALE_WIZARD_PROP_46"),
			"TYPE"            => "TEXT",
			"REQUIED"         => "N",
			"DEFAULT_VALUE"   => "",
			"SORT"            => 170,
			"USER_PROPS"      => "Y",
			"IS_LOCATION"     => "N",
			"PROPS_GROUP_ID"  => $arGeneralInfo["propGroup"]["adres_ur"],
			"SIZE1"           => 30,
			"SIZE2"           => 0,
			"DESCRIPTION"     => "",
			"IS_EMAIL"        => "N",
			"IS_PROFILE_NAME" => "N",
			"IS_PAYER"        => "N",
			"IS_LOCATION4TAX" => "N",
			"CODE"            => "NDS",
			"IS_FILTERED"     => "N",
		];

		$businessValueCodes['COMPANY_ZIP'] = ['GROUP' => 'COMPANY', 'SORT' => 180, 'DOMAIN' => $BIZVAL_ENTITY_DOMAIN];
		$arProps[] = [
			"PERSON_TYPE_ID"  => $arGeneralInfo["personType"]["ur"],
			"NAME"            => Loc::getMessage("SALE_WIZARD_PROP_44"),
			"TYPE"            => "TEXT",
			"REQUIED"         => "N",
			"DEFAULT_VALUE"   => "",
			"SORT"            => 180,
			"USER_PROPS"      => "Y",
			"IS_LOCATION"     => "N",
			"PROPS_GROUP_ID"  => $arGeneralInfo["propGroup"]["adres_ur"],
			"SIZE1"           => 8,
			"SIZE2"           => 0,
			"DESCRIPTION"     => "",
			"IS_EMAIL"        => "N",
			"IS_PROFILE_NAME" => "N",
			"IS_PAYER"        => "N",
			"IS_LOCATION4TAX" => "N",
			"CODE"            => "ZIP",
			"IS_FILTERED"     => "N",
			"IS_ZIP"          => "Y",
		];

		$businessValueCodes['COMPANY_CITY'] = ['GROUP' => 'COMPANY', 'SORT' => 190, 'DOMAIN' => $BIZVAL_ENTITY_DOMAIN];
		$arProps[] = [
			"PERSON_TYPE_ID"  => $arGeneralInfo["personType"]["ur"],
			"NAME"            => Loc::getMessage("SALE_WIZARD_PROP_43"),
			"TYPE"            => "TEXT",
			"REQUIED"         => "Y",
			"DEFAULT_VALUE"   => $shopLocation,
			"SORT"            => 190,
			"USER_PROPS"      => "Y",
			"IS_LOCATION"     => "N",
			"PROPS_GROUP_ID"  => $arGeneralInfo["propGroup"]["adres_ur"],
			"SIZE1"           => 30,
			"SIZE2"           => 0,
			"DESCRIPTION"     => "",
			"IS_EMAIL"        => "N",
			"IS_PROFILE_NAME" => "N",
			"IS_PAYER"        => "N",
			"IS_LOCATION4TAX" => "N",
			"CODE"            => "CITY",
			"IS_FILTERED"     => "Y",
		];

		$businessValueCodes['COMPANY_OPERATION_ADDRESS'] =
			['GROUP' => 'COMPANY', 'SORT' => 200, 'DOMAIN' => $BIZVAL_ENTITY_DOMAIN];
		$arProps[] = [
			"PERSON_TYPE_ID"  => $arGeneralInfo["personType"]["ur"],
			"NAME"            => Loc::getMessage("SALE_WIZARD_PROP_42"),
			"TYPE"            => "TEXTAREA",
			"REQUIED"         => "Y",
			"DEFAULT_VALUE"   => "",
			"SORT"            => 200,
			"USER_PROPS"      => "Y",
			"IS_LOCATION"     => "N",
			"PROPS_GROUP_ID"  => $arGeneralInfo["propGroup"]["adres_ur"],
			"SIZE1"           => 30,
			"SIZE2"           => 3,
			"DESCRIPTION"     => "",
			"IS_EMAIL"        => "N",
			"IS_PROFILE_NAME" => "N",
			"IS_PAYER"        => "N",
			"IS_LOCATION4TAX" => "N",
			"CODE"            => "ADDRESS",
			"IS_FILTERED"     => "N",
			"IS_ADDRESS"      => "Y",
		];

		$businessValueCodes['COMPANY_PHONE'] = ['GROUP' => 'COMPANY', 'SORT' => 210, 'DOMAIN' => $BIZVAL_ENTITY_DOMAIN];
		$arProps[] = [
			"PERSON_TYPE_ID"  => $arGeneralInfo["personType"]["ur"],
			"NAME"            => Loc::getMessage("SALE_WIZARD_PROP_45"),
			"TYPE"            => "TEXT",
			"REQUIED"         => "Y",
			"DEFAULT_VALUE"   => "",
			"SORT"            => 210,
			"USER_PROPS"      => "Y",
			"IS_LOCATION"     => "N",
			"PROPS_GROUP_ID"  => $arGeneralInfo["propGroup"]["adres_ur"],
			"SIZE1"           => 30,
			"SIZE2"           => 0,
			"DESCRIPTION"     => "",
			"IS_EMAIL"        => "N",
			"IS_PROFILE_NAME" => "N",
			"IS_PAYER"        => "N",
			"IS_LOCATION4TAX" => "N",
			"CODE"            => "PHONE",
			"IS_FILTERED"     => "N",
		];
	}
}

if( $shopLocalization == "ua" && $personType["fiz_ua"] == "Y" ) {
	/*
	$businessValueCodes['CLIENT_NAME'] = array('GROUP' => 'CLIENT', 'SORT' =>  100, 'DOMAIN' => $BIZVAL_INDIVIDUAL_DOMAIN);
	$arProps[] = Array(
		"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["fiz_ua"],
		"NAME" => Loc::getMessage("SALE_WIZARD_PROP_31"),
		"TYPE" => "TEXT",
		"REQUIED" => "Y",
		"DEFAULT_VALUE" => "",
		"SORT" => 100,
		"USER_PROPS" => "Y",
		"IS_LOCATION" => "N",
		"PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["user_fiz_ua"],
		"SIZE1" => 40,
		"SIZE2" => 0,
		"DESCRIPTION" => "",
		"IS_EMAIL" => "N",
		"IS_PROFILE_NAME" => "Y",
		"IS_PAYER" => "Y",
		"IS_LOCATION4TAX" => "N",
		"CODE" => "FIO",
		"IS_FILTERED" => "Y",
	);
	*/

	$businessValuePersonDomain[$arGeneralInfo["personType"]["fiz_ua"]] = $BIZVAL_INDIVIDUAL_DOMAIN;

	$businessValueCodes['CLIENT_EMAIL'] = ['GROUP' => 'CLIENT', 'SORT' => 110, 'DOMAIN' => $BIZVAL_INDIVIDUAL_DOMAIN];
	$arProps[] = [
		"PERSON_TYPE_ID"  => $arGeneralInfo["personType"]["fiz_ua"],
		"NAME"            => "E-Mail",
		"TYPE"            => "TEXT",
		"REQUIED"         => "Y",
		"DEFAULT_VALUE"   => "",
		"SORT"            => 110,
		"USER_PROPS"      => "Y",
		"IS_LOCATION"     => "N",
		"PROPS_GROUP_ID"  => $arGeneralInfo["propGroup"]["user_fiz_ua"],
		"SIZE1"           => 40,
		"SIZE2"           => 0,
		"DESCRIPTION"     => "",
		"IS_EMAIL"        => "Y",
		"IS_PROFILE_NAME" => "N",
		"IS_PAYER"        => "N",
		"IS_LOCATION4TAX" => "N",
		"CODE"            => "EMAIL",
		"IS_FILTERED"     => "Y",
	];

	$businessValueCodes['CLIENT_NAME'] = ['GROUP' => 'CLIENT', 'SORT' => 130, 'DOMAIN' => $BIZVAL_INDIVIDUAL_DOMAIN];
	$arProps[] = [
		"PERSON_TYPE_ID"  => $arGeneralInfo["personType"]["fiz_ua"],
		"NAME"            => Loc::getMessage("SALE_WIZARD_PROP_30"),
		"TYPE"            => "TEXT",
		"REQUIED"         => "Y",
		"DEFAULT_VALUE"   => "",
		"SORT"            => 130,
		"USER_PROPS"      => "Y",
		"IS_LOCATION"     => "N",
		"PROPS_GROUP_ID"  => $arGeneralInfo["propGroup"]["user_fiz_ua"],
		"SIZE1"           => 40,
		"SIZE2"           => 0,
		"DESCRIPTION"     => "",
		"IS_EMAIL"        => "N",
		"IS_PROFILE_NAME" => "Y",
		"IS_PAYER"        => "N",
		"IS_LOCATION4TAX" => "N",
		"CODE"            => "FIO",
		"IS_FILTERED"     => "Y",
	];

	$businessValueCodes['CLIENT_COMPANY_ADDRESS'] =
		['GROUP' => 'CLIENT', 'SORT' => 140, 'DOMAIN' => $BIZVAL_INDIVIDUAL_DOMAIN];
	$arProps[] = [
		"PERSON_TYPE_ID"  => $arGeneralInfo["personType"]["fiz_ua"],
		"NAME"            => Loc::getMessage("SALE_WIZARD_PROP_37"),
		"TYPE"            => "TEXTAREA",
		"REQUIED"         => "Y",
		"DEFAULT_VALUE"   => "",
		"SORT"            => 140,
		"USER_PROPS"      => "Y",
		"IS_LOCATION"     => "N",
		"PROPS_GROUP_ID"  => $arGeneralInfo["propGroup"]["user_fiz_ua"],
		"SIZE1"           => 40,
		"SIZE2"           => 0,
		"DESCRIPTION"     => "",
		"IS_EMAIL"        => "N",
		"IS_PROFILE_NAME" => "N",
		"IS_PAYER"        => "N",
		"IS_LOCATION4TAX" => "N",
		"CODE"            => "COMPANY_ADR",
		"IS_FILTERED"     => "N",
	];

	$businessValueCodes['CLIENT_EGRPU'] = ['GROUP' => 'CLIENT', 'SORT' => 150, 'DOMAIN' => $BIZVAL_INDIVIDUAL_DOMAIN];
	$arProps[] = [
		"PERSON_TYPE_ID"  => $arGeneralInfo["personType"]["fiz_ua"],
		"NAME"            => Loc::getMessage("SALE_WIZARD_PROP_38"),
		"TYPE"            => "TEXT",
		"REQUIED"         => "Y",
		"DEFAULT_VALUE"   => "",
		"SORT"            => 150,
		"USER_PROPS"      => "Y",
		"IS_LOCATION"     => "N",
		"PROPS_GROUP_ID"  => $arGeneralInfo["propGroup"]["adres_fiz_ua"],
		"SIZE1"           => 30,
		"SIZE2"           => 0,
		"DESCRIPTION"     => "",
		"IS_EMAIL"        => "N",
		"IS_PROFILE_NAME" => "N",
		"IS_PAYER"        => "N",
		"IS_LOCATION4TAX" => "N",
		"CODE"            => "EGRPU",
		"IS_FILTERED"     => "N",
	];

	/*
	$businessValueCodes['CLIENT_INN'] = array('GROUP' => 'CLIENT', 'SORT' =>  160, 'DOMAIN' => $BIZVAL_INDIVIDUAL_DOMAIN);
	$arProps[] = Array(
		"PERSON_TYPE_ID" => $arGeneralInfo["personType"]["fiz_ua"],
		"NAME" => Loc::getMessage("SALE_WIZARD_PROP_39"),
		"TYPE" => "TEXT",
		"REQUIED" => "N",
		"DEFAULT_VALUE" => "",
		"SORT" => 160,
		"USER_PROPS" => "Y",
		"IS_LOCATION" => "N",
		"PROPS_GROUP_ID" => $arGeneralInfo["propGroup"]["adres_fiz_ua"],
		"SIZE1" => 30,
		"SIZE2" => 0,
		"DESCRIPTION" => "",
		"IS_EMAIL" => "N",
		"IS_PROFILE_NAME" => "N",
		"IS_PAYER" => "N",
		"IS_LOCATION4TAX" => "N",
		"CODE" => "INN",
		"IS_FILTERED" => "N",
	);
	*/

	$businessValueCodes['CLIENT_NDS'] = ['GROUP' => 'CLIENT', 'SORT' => 170, 'DOMAIN' => $BIZVAL_INDIVIDUAL_DOMAIN];
	$arProps[] = [
		"PERSON_TYPE_ID"  => $arGeneralInfo["personType"]["fiz_ua"],
		"NAME"            => Loc::getMessage("SALE_WIZARD_PROP_36"),
		"TYPE"            => "TEXT",
		"REQUIED"         => "N",
		"DEFAULT_VALUE"   => "",
		"SORT"            => 170,
		"USER_PROPS"      => "Y",
		"IS_LOCATION"     => "N",
		"PROPS_GROUP_ID"  => $arGeneralInfo["propGroup"]["adres_fiz_ua"],
		"SIZE1"           => 30,
		"SIZE2"           => 0,
		"DESCRIPTION"     => "",
		"IS_EMAIL"        => "N",
		"IS_PROFILE_NAME" => "N",
		"IS_PAYER"        => "N",
		"IS_LOCATION4TAX" => "N",
		"CODE"            => "NDS",
		"IS_FILTERED"     => "N",
	];

	$businessValueCodes['CLIENT_ZIP'] = ['GROUP' => 'CLIENT', 'SORT' => 180, 'DOMAIN' => $BIZVAL_INDIVIDUAL_DOMAIN];
	$arProps[] = [
		"PERSON_TYPE_ID"  => $arGeneralInfo["personType"]["fiz_ua"],
		"NAME"            => Loc::getMessage("SALE_WIZARD_PROP_34"),
		"TYPE"            => "TEXT",
		"REQUIED"         => "N",
		"DEFAULT_VALUE"   => "",
		"SORT"            => 180,
		"USER_PROPS"      => "Y",
		"IS_LOCATION"     => "N",
		"PROPS_GROUP_ID"  => $arGeneralInfo["propGroup"]["adres_fiz_ua"],
		"SIZE1"           => 8,
		"SIZE2"           => 0,
		"DESCRIPTION"     => "",
		"IS_EMAIL"        => "N",
		"IS_PROFILE_NAME" => "N",
		"IS_PAYER"        => "N",
		"IS_LOCATION4TAX" => "N",
		"CODE"            => "ZIP",
		"IS_FILTERED"     => "N",
		"IS_ZIP"          => "Y",
	];

	$businessValueCodes['CLIENT_CITY'] = ['GROUP' => 'CLIENT', 'SORT' => 190, 'DOMAIN' => $BIZVAL_INDIVIDUAL_DOMAIN];
	$arProps[] = [
		"PERSON_TYPE_ID"  => $arGeneralInfo["personType"]["fiz_ua"],
		"NAME"            => Loc::getMessage("SALE_WIZARD_PROP_33"),
		"TYPE"            => "TEXT",
		"REQUIED"         => "Y",
		"DEFAULT_VALUE"   => $shopLocation,
		"SORT"            => 190,
		"USER_PROPS"      => "Y",
		"IS_LOCATION"     => "N",
		"PROPS_GROUP_ID"  => $arGeneralInfo["propGroup"]["adres_fiz_ua"],
		"SIZE1"           => 30,
		"SIZE2"           => 0,
		"DESCRIPTION"     => "",
		"IS_EMAIL"        => "N",
		"IS_PROFILE_NAME" => "N",
		"IS_PAYER"        => "N",
		"IS_LOCATION4TAX" => "N",
		"CODE"            => "CITY",
		"IS_FILTERED"     => "Y",
	];

	$businessValueCodes['CLIENT_ADDRESS'] = ['GROUP' => 'CLIENT', 'SORT' => 200, 'DOMAIN' => $BIZVAL_INDIVIDUAL_DOMAIN];
	$arProps[] = [
		"PERSON_TYPE_ID"  => $arGeneralInfo["personType"]["fiz_ua"],
		"NAME"            => Loc::getMessage("SALE_WIZARD_PROP_32"),
		"TYPE"            => "TEXTAREA",
		"REQUIED"         => "Y",
		"DEFAULT_VALUE"   => "",
		"SORT"            => 200,
		"USER_PROPS"      => "Y",
		"IS_LOCATION"     => "N",
		"PROPS_GROUP_ID"  => $arGeneralInfo["propGroup"]["adres_fiz_ua"],
		"SIZE1"           => 30,
		"SIZE2"           => 3,
		"DESCRIPTION"     => "",
		"IS_EMAIL"        => "N",
		"IS_PROFILE_NAME" => "N",
		"IS_PAYER"        => "N",
		"IS_LOCATION4TAX" => "N",
		"CODE"            => "ADDRESS",
		"IS_FILTERED"     => "N",
		"IS_ADDRESS"      => "Y",
	];

	$businessValueCodes['CLIENT_PHONE'] = ['GROUP' => 'CLIENT', 'SORT' => 210, 'DOMAIN' => $BIZVAL_INDIVIDUAL_DOMAIN];
	$arProps[] = [
		"PERSON_TYPE_ID"  => $arGeneralInfo["personType"]["fiz_ua"],
		"NAME"            => Loc::getMessage("SALE_WIZARD_PROP_35"),
		"TYPE"            => "TEXT",
		"REQUIED"         => "Y",
		"DEFAULT_VALUE"   => "",
		"SORT"            => 210,
		"USER_PROPS"      => "Y",
		"IS_LOCATION"     => "N",
		"PROPS_GROUP_ID"  => $arGeneralInfo["propGroup"]["adres_fiz_ua"],
		"SIZE1"           => 30,
		"SIZE2"           => 0,
		"DESCRIPTION"     => "",
		"IS_EMAIL"        => "N",
		"IS_PROFILE_NAME" => "N",
		"IS_PAYER"        => "N",
		"IS_LOCATION4TAX" => "N",
		"CODE"            => "PHONE",
		"IS_PHONE"        => "Y",
		"IS_FILTERED"     => "N",
	];
}

$propCityId = 0;
reset($businessValueCodes);

foreach( $arProps as $prop ) {
	$variants = [];
	if( !empty($prop["VARIANTS"]) ) {
		$variants = $prop["VARIANTS"];
		unset($prop["VARIANTS"]);
	}

	if( $prop["CODE"] == "LOCATION" && $propCityId > 0 ) {
		$prop["INPUT_FIELD_LOCATION"] = $propCityId;
		$propCityId = 0;
	}

	$dbSaleOrderProps =
		CSaleOrderProps::GetList([], ["PERSON_TYPE_ID" => $prop["PERSON_TYPE_ID"], "CODE" => $prop["CODE"]]);
	if( $arSaleOrderProps = $dbSaleOrderProps->GetNext() ) {
		$id = $arSaleOrderProps["ID"];
	}
	else {
		$id = CSaleOrderProps::Add($prop);
	}

	if( $prop["CODE"] == "CITY" ) {
		$propCityId = $id;
	}
	if( strlen($prop["CODE"]) > 0 ) {
		//$arGeneralInfo["propCode"][$prop["CODE"]] = $prop["CODE"];
		$arGeneralInfo["propCodeID"][$prop["CODE"]] = $id;
		$arGeneralInfo["properies"][$prop["PERSON_TYPE_ID"]][$prop["CODE"]] = $prop;
		$arGeneralInfo["properies"][$prop["PERSON_TYPE_ID"]][$prop["CODE"]]["ID"] = $id;
	}

	if( !empty($variants) ) {
		foreach( $variants as $val ) {
			$val["ORDER_PROPS_ID"] = $id;
			CSaleOrderPropsVariant::Add($val);
		}
	}

	// add business value mapping to property
	$businessValueCodes[key($businessValueCodes)]['MAP'] = [$prop['PERSON_TYPE_ID'] => ['PROPERTY', $id]];
	next($businessValueCodes);
}

/*
	$propReplace = "";
	foreach($arGeneralInfo["properies"] as $key => $val)
	{
		if(IntVal($val["LOCATION"]["ID"]) > 0)
			$propReplace .= '"PROP_'.$key.'" => Array(0 => "'.$val["LOCATION"]["ID"].'"), ';
	}
	WizardServices::ReplaceMacrosRecursive(WIZARD_SITE_PATH."personal/order/", Array("PROPS" => $propReplace));
*/


//1C export
if( $personType["fiz"] == "Y" && !$fizExist ) {
	$val = serialize(
		[
			"AGENT_NAME"     => [
				"TYPE"  => "PROPERTY",
				"VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["fiz"]]["FIO"]["ID"],
			],
			"FULL_NAME"      => [
				"TYPE"  => "PROPERTY",
				"VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["fiz"]]["FIO"]["ID"],
			],
			"SURNAME"        => ["TYPE" => "USER", "VALUE" => "LAST_NAME"],
			"NAME"           => ["TYPE" => "USER", "VALUE" => "NAME"],
			"ADDRESS_FULL"   => [
				"TYPE"  => "PROPERTY",
				"VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["fiz"]]["ADDRESS"]["ID"],
			],
			"INDEX"          => [
				"TYPE"  => "PROPERTY",
				"VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["fiz"]]["ZIP"]["ID"],
			],
			"COUNTRY"        => [
				"TYPE"  => "PROPERTY",
				"VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["fiz"]]["LOCATION"]["ID"] .
					"_COUNTRY",
			],
			"CITY"           => [
				"TYPE"  => "PROPERTY",
				"VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["fiz"]]["LOCATION"]["ID"] . "_CITY",
			],
			"STREET"         => [
				"TYPE"  => "PROPERTY",
				"VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["fiz"]]["ADDRESS"]["ID"],
			],
			"EMAIL"          => [
				"TYPE"  => "PROPERTY",
				"VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["fiz"]]["EMAIL"]["ID"],
			],
			"CONTACT_PERSON" => [
				"TYPE"  => "PROPERTY",
				"VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["fiz"]]["CONTACT_PERSON"]["ID"],
			],
			"IS_FIZ"         => "Y",
		]
	);

	$allPersonTypes = BusinessValue::getPersonTypes(true);
	$personTypeId = $arGeneralInfo["personType"]["fiz"];
	$domain = BusinessValue::INDIVIDUAL_DOMAIN;

	if( !isset($allPersonTypes[$personTypeId]['DOMAIN']) ) {
		$r = Bitrix\Sale\Internals\BusinessValuePersonDomainTable::add(
			[
				'PERSON_TYPE_ID' => $personTypeId,
				'DOMAIN'         => $domain,
			]
		);
		if( $r->isSuccess() ) {
			$allPersonTypes[$personTypeId]['DOMAIN'] = $domain;
			BusinessValue::getPersonTypes(true, $allPersonTypes);
		}
	}

	CSaleExport::Add(["PERSON_TYPE_ID" => $arGeneralInfo["personType"]["fiz"], "VARS" => $val]);
}
if( $personType["ur"] == "Y" && !$urExist ) {
	$val = serialize(
		[
			"AGENT_NAME"     => [
				"TYPE"  => "PROPERTY",
				"VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ur"]]["COMPANY"]["ID"],
			],
			"FULL_NAME"      => [
				"TYPE"  => "PROPERTY",
				"VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ur"]]["COMPANY"]["ID"],
			],
			"ADDRESS_FULL"   => [
				"TYPE"  => "PROPERTY",
				"VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ur"]]["COMPANY_ADR"]["ID"],
			],
			"COUNTRY"        => [
				"TYPE"  => "PROPERTY",
				"VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ur"]]["LOCATION"]["ID"] .
					"_COUNTRY",
			],
			"CITY"           => [
				"TYPE"  => "PROPERTY",
				"VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ur"]]["LOCATION"]["ID"] . "_CITY",
			],
			"STREET"         => [
				"TYPE"  => "PROPERTY",
				"VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ur"]]["COMPANY_ADR"]["ID"],
			],
			"INN"            => [
				"TYPE"  => "PROPERTY",
				"VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ur"]]["INN"]["ID"],
			],
			"KPP"            => [
				"TYPE"  => "PROPERTY",
				"VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ur"]]["KPP"]["ID"],
			],
			"PHONE"          => [
				"TYPE"  => "PROPERTY",
				"VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ur"]]["PHONE"]["ID"],
			],
			"EMAIL"          => [
				"TYPE"  => "PROPERTY",
				"VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ur"]]["EMAIL"]["ID"],
			],
			"CONTACT_PERSON" => [
				"TYPE"  => "PROPERTY",
				"VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ur"]]["NAME"]["ID"],
			],
			"F_ADDRESS_FULL" => [
				"TYPE"  => "PROPERTY",
				"VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ur"]]["ADDRESS"]["ID"],
			],
			"F_COUNTRY"      => [
				"TYPE"  => "PROPERTY",
				"VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ur"]]["LOCATION"]["ID"] .
					"_COUNTRY",
			],
			"F_CITY"         => [
				"TYPE"  => "PROPERTY",
				"VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ur"]]["LOCATION"]["ID"] . "_CITY",
			],
			"F_INDEX"        => [
				"TYPE"  => "PROPERTY",
				"VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ur"]]["ZIP"]["ID"],
			],
			"F_STREET"       => [
				"TYPE"  => "PROPERTY",
				"VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["ur"]]["ADDRESS"]["ID"],
			],
			"IS_FIZ"         => "N",
		]
	);

	$allPersonTypes = BusinessValue::getPersonTypes(true);
	$personTypeId = $arGeneralInfo["personType"]["ur"];
	$domain = BusinessValue::ENTITY_DOMAIN;

	if( !isset($allPersonTypes[$personTypeId]['DOMAIN']) ) {
		$r = Bitrix\Sale\Internals\BusinessValuePersonDomainTable::add(
			[
				'PERSON_TYPE_ID' => $personTypeId,
				'DOMAIN'         => $domain,
			]
		);
		if( $r->isSuccess() ) {
			$allPersonTypes[$personTypeId]['DOMAIN'] = $domain;
			BusinessValue::getPersonTypes(true, $allPersonTypes);
		}
	}

	CSaleExport::Add(["PERSON_TYPE_ID" => $arGeneralInfo["personType"]["ur"], "VARS" => $val]);
}
if( $shopLocalization == "ua" && !$fizUaExist ) {
	$val = serialize(
		[
			"AGENT_NAME"     => [
				"TYPE"  => "PROPERTY",
				"VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["fiz_ua"]]["FIO"]["ID"],
			],
			"FULL_NAME"      => [
				"TYPE"  => "PROPERTY",
				"VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["fiz_ua"]]["FIO"]["ID"],
			],
			"SURNAME"        => ["TYPE" => "USER", "VALUE" => "LAST_NAME"],
			"NAME"           => ["TYPE" => "USER", "VALUE" => "NAME"],
			"ADDRESS_FULL"   => [
				"TYPE"  => "PROPERTY",
				"VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["fiz_ua"]]["ADDRESS"]["ID"],
			],
			"INDEX"          => [
				"TYPE"  => "PROPERTY",
				"VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["fiz_ua"]]["ZIP"]["ID"],
			],
			"COUNTRY"        => [
				"TYPE"  => "PROPERTY",
				"VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["fiz_ua"]]["LOCATION"]["ID"] .
					"_COUNTRY",
			],
			"CITY"           => [
				"TYPE"  => "PROPERTY",
				"VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["fiz_ua"]]["LOCATION"]["ID"] .
					"_CITY",
			],
			"STREET"         => [
				"TYPE"  => "PROPERTY",
				"VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["fiz_ua"]]["ADDRESS"]["ID"],
			],
			"EMAIL"          => [
				"TYPE"  => "PROPERTY",
				"VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["fiz_ua"]]["EMAIL"]["ID"],
			],
			"CONTACT_PERSON" => [
				"TYPE"  => "PROPERTY",
				"VALUE" => $arGeneralInfo["properies"][$arGeneralInfo["personType"]["fiz_ua"]]["CONTACT_PERSON"]["ID"],
			],
			"IS_FIZ"         => "Y",
		]
	);
	CSaleExport::Add(["PERSON_TYPE_ID" => $arGeneralInfo["personType"]["fiz"], "VARS" => $val]);
}


//PaySystem
$arPaySystems = [];

if( $paysystem["cash"] == "Y" ) {
	$logo = $_SERVER["DOCUMENT_ROOT"] . WIZARD_SERVICE_RELATIVE_PATH . "/images/cash.png";
	$arPicture = CFile::MakeFileArray($logo);
	$arPaySystems[] = [
		'PAYSYSTEM'   => [
			"NAME"                 => Loc::getMessage("SALE_WIZARD_PS_CASH"),
			"PSA_NAME"             => Loc::getMessage("SALE_WIZARD_PS_CASH"),
			"SORT"                 => 10,
			"ACTIVE"               => "Y",
			"IS_CASH"              => "Y",
			"DESCRIPTION"          => Loc::getMessage("SALE_WIZARD_PS_CASH_DESCR"),
			"ACTION_FILE"          => "cash",
			"RESULT_FILE"          => "",
			"NEW_WINDOW"           => "N",
			"PARAMS"               => "",
			"HAVE_PAYMENT"         => "Y",
			"HAVE_ACTION"          => "N",
			"HAVE_RESULT"          => "N",
			"HAVE_PREPAY"          => "N",
			"HAVE_RESULT_RECEIVE"  => "N",
			"LOGOTIP"              => $arPicture,
			'ENTITY_REGISTRY_TYPE' => Sale\Registry::REGISTRY_TYPE_ORDER,
		],
		'PERSON_TYPE' => [$arGeneralInfo["personType"]["fiz"]],
	];
}

if( $paysystem["collect"] == "Y" ) {
	$arPaySystems[] = [
		'PAYSYSTEM'   => [
			"NAME"                 => Loc::getMessage("SALE_WIZARD_PS_COLLECT"),
			"SORT"                 => 20,
			"ACTIVE"               => "Y",
			"DESCRIPTION"          => Loc::getMessage("SALE_WIZARD_PS_COLLECT_DESCR"),
			"PSA_NAME"             => Loc::getMessage("SALE_WIZARD_PS_COLLECT"),
			"ACTION_FILE"          => "cashondeliverycalc",
			"RESULT_FILE"          => "",
			"NEW_WINDOW"           => "N",
			"HAVE_PAYMENT"         => "Y",
			"HAVE_ACTION"          => "N",
			"HAVE_RESULT"          => "N",
			"HAVE_PREPAY"          => "N",
			"HAVE_RESULT_RECEIVE"  => "N",
			'ENTITY_REGISTRY_TYPE' => Sale\Registry::REGISTRY_TYPE_ORDER,
		],
		'PERSON_TYPE' => [$arGeneralInfo["personType"]["fiz"], $arGeneralInfo["personType"]["ur"]],
	];
}

if( $personType["fiz"] == "Y" && $shopLocalization != "ua" ) {
	if( $paysystem["sber"] == "Y" ) {
		$arPaySystems[] = [
			'PAYSYSTEM'   => [
				"NAME"                 => Loc::getMessage("SALE_WIZARD_PS_SB"),
				"SORT"                 => 30,
				"DESCRIPTION"          => Loc::getMessage("SALE_WIZARD_PS_SB_DESCR"),
				"PSA_NAME"             => Loc::getMessage("SALE_WIZARD_PS_SB"),
				"ACTION_FILE"          => "sberbank",
				"RESULT_FILE"          => "",
				"NEW_WINDOW"           => "Y",
				"HAVE_PAYMENT"         => "Y",
				"HAVE_ACTION"          => "N",
				"HAVE_RESULT"          => "N",
				"HAVE_PREPAY"          => "N",
				"HAVE_RESULT_RECEIVE"  => "N",
				'ENTITY_REGISTRY_TYPE' => Sale\Registry::REGISTRY_TYPE_ORDER,
			],
			"PERSON_TYPE" => [$arGeneralInfo["personType"]["fiz"]],
			"BIZVAL"      => [
				'' => [
					"SELLER_COMPANY_NAME"              => ["TYPE" => "", "VALUE" => $shopOfName],
					"SELLER_COMPANY_INN"               => ["TYPE" => "", "VALUE" => $shopINN],
					"SELLER_COMPANY_KPP"               => ["TYPE" => "", "VALUE" => $shopKPP],
					"SELLER_COMPANY_BANK_ACCOUNT"      => ["TYPE" => "", "VALUE" => $shopNS],
					"SELLER_COMPANY_BANK_NAME"         => ["TYPE" => "", "VALUE" => $shopBANK],
					"SELLER_COMPANY_BANK_BIC"          => ["TYPE" => "", "VALUE" => $shopBANKREKV],
					"SELLER_COMPANY_BANK_ACCOUNT_CORR" => ["TYPE" => "", "VALUE" => $shopKS],
					"PAYMENT_ID"                       => ["TYPE" => "PAYMENT", "VALUE" => "ACCOUNT_NUMBER"],
					"PAYMENT_DATE_INSERT"              => ["TYPE" => "PAYMENT", "VALUE" => "DATE_INSERT_DATE"],
					"BUYER_PERSON_FIO"                 => ["TYPE" => "PROPERTY", "VALUE" => "FIO"],
					"BUYER_PERSON_ZIP"                 => ["TYPE" => "PROPERTY", "VALUE" => "ZIP"],
					"BUYER_PERSON_COUNTRY"             => ["TYPE" => "PROPERTY", "VALUE" => "LOCATION_COUNTRY"],
					"BUYER_PERSON_REGION"              => ["TYPE" => "PROPERTY", "VALUE" => "LOCATION_REGION"],
					"BUYER_PERSON_CITY"                => ["TYPE" => "PROPERTY", "VALUE" => "LOCATION_CITY"],
					"BUYER_PERSON_ADDRESS_FACT"        => ["TYPE" => "PROPERTY", "VALUE" => "ADDRESS"],
					"PAYMENT_SHOULD_PAY"               => ["TYPE" => "PAYMENT", "VALUE" => "SUM"],
				],
			],
		];
	}
}
if( $shopLocalization == "ua" ) {
	if( ($personType["fiz"] == "Y" || $personType["fiz_ua"] == "Y") && $paysystem["oshad"] == "Y" ) {
		$arPaySystems[] = [
			'PAYSYSTEM'   => [
				"NAME"                 => Loc::getMessage("SALE_WIZARD_PS_OS"),
				"SORT"                 => 30,
				"DESCRIPTION"          => Loc::getMessage("SALE_WIZARD_PS_OS_DESCR"),
				"PSA_NAME"             => Loc::getMessage("SALE_WIZARD_PS_OS"),
				"ACTION_FILE"          => "/bitrix/modules/sale/payment/oshadbank",
				"RESULT_FILE"          => "",
				"NEW_WINDOW"           => "Y",
				"HAVE_PAYMENT"         => "Y",
				"HAVE_ACTION"          => "N",
				"HAVE_RESULT"          => "N",
				"HAVE_PREPAY"          => "N",
				"HAVE_RESULT_RECEIVE"  => "N",
				'ENTITY_REGISTRY_TYPE' => Sale\Registry::REGISTRY_TYPE_ORDER,
			],
			"PERSON_TYPE" => [$arGeneralInfo["personType"]["fiz"], $arGeneralInfo["personType"]["fiz_ua"]],
			"BIZVAL"      => [
				'' => [
					"RECIPIENT_NAME"       => ["TYPE" => "", "VALUE" => $shopOfName],
					"RECIPIENT_ID"         => ["TYPE" => "", "VALUE" => $shopEGRPU_ua],
					"RECIPIENT_NUMBER"     => ["TYPE" => "", "VALUE" => $shopNS_ua],
					"RECIPIENT_BANK"       => ["TYPE" => "", "VALUE" => $shopBank_ua],
					"RECIPIENT_CODE_BANK"  => ["TYPE" => "", "VALUE" => $shopMFO_ua],
					"PAYER_FIO"            => ["TYPE" => "PROPERTY", "VALUE" => "FIO"],
					"PAYER_ADRES"          => ["TYPE" => "PROPERTY", "VALUE" => "ADDRESS"],
					"ORDER_ID"             => ["TYPE" => "ORDER", "VALUE" => "ID"],
					"DATE_INSERT"          => ["TYPE" => "ORDER", "VALUE" => "DATE_INSERT_DATE"],
					"PAYER_CONTACT_PERSON" => ["TYPE" => "PROPERTY", "VALUE" => "FIO"],
					"PAYER_INDEX"          => ["TYPE" => "PROPERTY", "VALUE" => "ZIP"],
					"PAYER_COUNTRY"        => ["TYPE" => "PROPERTY", "VALUE" => "LOCATION_COUNTRY"],
					"PAYER_TOWN"           => ["TYPE" => "PROPERTY", "VALUE" => "LOCATION_CITY"],
					"SHOULD_PAY"           => ["TYPE" => "ORDER", "VALUE" => "PRICE"],
				],
			],
		];
	}
}

if( $personType["fiz"] == "Y" ) {

	if( $paysystem["paypal"] == "Y" ) {
		$arPaySystems[] = [
			'PAYSYSTEM'   => [
				"NAME"                 => "PayPal",
				"SORT"                 => 90,
				"DESCRIPTION"          => "",
				"PSA_NAME"             => "PayPal",
				"ACTION_FILE"          => "paypal",
				"RESULT_FILE"          => "",
				"NEW_WINDOW"           => "N",
				"HAVE_PAYMENT"         => "Y",
				"HAVE_ACTION"          => "N",
				"HAVE_RESULT"          => "N",
				"HAVE_PREPAY"          => "N",
				"HAVE_RESULT_RECEIVE"  => "Y",
				'ENTITY_REGISTRY_TYPE' => Sale\Registry::REGISTRY_TYPE_ORDER,
			],
			"BIZVAL"      => [
				'' => [
					"PAYMENT_ID"          => ["TYPE" => "PAYMENT", "VALUE" => "ID"],
					"PAYMENT_DATE_INSERT" => ["TYPE" => "PAYMENT", "VALUE" => "DATE_BILL_DATE"],
					"PAYMENT_SHOULD_PAY"  => ["TYPE" => "PAYMENT", "VALUE" => "SUM"],
					"PAYMENT_CURRENCY"    => ["TYPE" => "PAYMENT", "VALUE" => "CURRENCY"],
				],
			],
			"PERSON_TYPE" => [$arGeneralInfo["personType"]["fiz"]],
		];
	}

}

// bill
if( $paysystem["bill"] == "Y" ) {
	$logo = $_SERVER["DOCUMENT_ROOT"] . WIZARD_SERVICE_RELATIVE_PATH . "/images/bill.png";
	$arPicture = CFile::MakeFileArray($logo);
}
if( $personType["ur"] == "Y" && $paysystem["bill"] == "Y" && $shopLocalization != "ua" ) {
	$arPaySystems[] = [
		'PAYSYSTEM'   => [
			"NAME"                 => Loc::getMessage("SALE_WIZARD_PS_BILL"),
			"SORT"                 => 100,
			"DESCRIPTION"          => "",
			"PSA_NAME"             => Loc::getMessage("SALE_WIZARD_PS_BILL"),
			"ACTION_FILE"          => "bill",
			"RESULT_FILE"          => "",
			"NEW_WINDOW"           => "Y",
			"HAVE_PAYMENT"         => "Y",
			"HAVE_ACTION"          => "N",
			"HAVE_RESULT"          => "N",
			"HAVE_PREPAY"          => "N",
			"HAVE_RESULT_RECEIVE"  => "N",
			'ENTITY_REGISTRY_TYPE' => Sale\Registry::REGISTRY_TYPE_ORDER,
			"LOGOTIP"              => $arPicture,
		],
		"PERSON_TYPE" => [$arGeneralInfo["personType"]["ur"]],
		"BIZVAL"      => [
			'' => [
				"PAYMENT_DATE_INSERT"               => ["TYPE" => "PAYMENT", "VALUE" => "DATE_BILL_DATE"],
				"SELLER_COMPANY_NAME"               => ["TYPE" => "", "VALUE" => $shopOfName],
				"SELLER_COMPANY_ADDRESS"            => ["TYPE" => "", "VALUE" => $shopAdr],
				///"SELLER_COMPANY_PHONE" => ["TYPE" => "", "VALUE" => $siteTelephone],
				"SELLER_COMPANY_INN"                => ["TYPE" => "", "VALUE" => $shopINN],
				"SELLER_COMPANY_KPP"                => ["TYPE" => "", "VALUE" => $shopKPP],
				"SELLER_COMPANY_BANK_NAME"          => ["TYPE" => "", "VALUE" => $shopBANK],
				"SELLER_COMPANY_BANK_ACCOUNT"       => ["TYPE" => "", "VALUE" => $shopNS],
				"SELLER_COMPANY_BANK_ACCOUNT_CORR"  => ["TYPE" => "", "VALUE" => $shopKS],
				"SELLER_COMPANY_BANK_BIC"           => ["TYPE" => "", "VALUE" => $shopBANKREKV],
				"BUYER_PERSON_COMPANY_NAME"         => ["TYPE" => "PROPERTY", "VALUE" => "COMPANY_NAME"],
				"BUYER_PERSON_COMPANY_INN"          => ["TYPE" => "PROPERTY", "VALUE" => "INN"],
				"BUYER_PERSON_COMPANY_ADDRESS"      => ["TYPE" => "PROPERTY", "VALUE" => "COMPANY_ADR"],
				"BUYER_PERSON_COMPANY_PHONE"        => ["TYPE" => "PROPERTY", "VALUE" => "PHONE"],
				"BUYER_PERSON_COMPANY_NAME_CONTACT" => ["TYPE" => "PROPERTY", "VALUE" => "CONTACT_PERSON"],
				//"BILL_PATH_TO_STAMP" => ["TYPE" => "", "VALUE" => $siteStamp],
			],
		],
	];
}
if( $shopLocalization == "ua" && $paysystem["bill"] == "Y" ) {
	$arPaySystem['PAYSYSTEM'] = [
		"NAME"                 => Loc::getMessage("SALE_WIZARD_PS_BILL"),
		"PSA_NAME"             => Loc::getMessage("SALE_WIZARD_PS_BILL"),
		"ACTION_FILE"          => "billua",
		"RESULT_FILE"          => "",
		"NEW_WINDOW"           => "Y",
		"HAVE_PAYMENT"         => "Y",
		"HAVE_ACTION"          => "N",
		"HAVE_RESULT"          => "N",
		"HAVE_PREPAY"          => "N",
		"HAVE_RESULT_RECEIVE"  => "N",
		'ENTITY_REGISTRY_TYPE' => Sale\Registry::REGISTRY_TYPE_ORDER,
		"LOGOTIP"              => $arPicture,
	];

	$arPaySystem['PERSON_TYPE'] = [];
	$arPaySystem['BIZVAL'] = [];

	if( $personType["ur"] == "Y" ) {
		$arPaySystem['PERSON_TYPE'][] = $arGeneralInfo["personType"]["ur"];
		$arPaySystem['BIZVAL'][$arGeneralInfo["personType"]["ur"]] = [
			"PAYMENT_DATE_INSERT"          => ["TYPE" => "ORDER", "VALUE" => "DATE_INSERT_DATE"],
			"SELLER_COMPANY_NAME"          => ["TYPE" => "", "VALUE" => $shopOfName],
			"SELLER_COMPANY_ADDRESS"       => ["TYPE" => "", "VALUE" => $shopAdr],
			"SELLER_COMPANY_PHONE"         => ["TYPE" => "", "VALUE" => $siteTelephone],
			"SELLER_COMPANY_IPN"           => ["TYPE" => "", "VALUE" => $shopINN_ua],
			"SELLER_COMPANY_EDRPOY"        => ["TYPE" => "", "VALUE" => $shopEGRPU_ua],
			"SELLER_COMPANY_BANK_ACCOUNT"  => ["TYPE" => "", "VALUE" => $shopNS_ua],
			"SELLER_COMPANY_BANK_NAME"     => ["TYPE" => "", "VALUE" => $shopBank_ua],
			"SELLER_COMPANY_MFO"           => ["TYPE" => "", "VALUE" => $shopMFO_ua],
			"SELLER_COMPANY_PDV"           => ["TYPE" => "", "VALUE" => $shopNDS_ua],
			"PAYMENT_ID"                   => ["TYPE" => "ORDER", "VALUE" => "ID"],
			"SELLER_COMPANY_SYS"           => ["TYPE" => "", "VALUE" => $shopTax_ua],
			"BUYER_PERSON_COMPANY_NAME"    => ["TYPE" => "PROPERTY", "VALUE" => "COMPANY_NAME"],
			"BUYER_PERSON_COMPANY_ADDRESS" => ["TYPE" => "PROPERTY", "VALUE" => "COMPANY_ADR"],
			"BUYER_PERSON_COMPANY_PHONE"   => ["TYPE" => "PROPERTY", "VALUE" => "PHONE"],
			//"BILLUA_PATH_TO_STAMP" => ["TYPE" => "", "VALUE" => $siteStamp],
		];
	}

	if( $personType["fiz"] == "Y" ) {
		$arPaySystem['PERSON_TYPE'][] = $arGeneralInfo["personType"]["fiz"];
		$arPaySystem['BIZVAL'][$arGeneralInfo["personType"]["fiz"]] = [
			"PAYMENT_DATE_INSERT"          => ["TYPE" => "ORDER", "VALUE" => "DATE_INSERT_DATE"],
			"SELLER_COMPANY_NAME"          => ["TYPE" => "", "VALUE" => $shopOfName],
			"SELLER_COMPANY_ADDRESS"       => ["TYPE" => "", "VALUE" => $shopAdr],
			"SELLER_COMPANY_PHONE"         => ["TYPE" => "", "VALUE" => $siteTelephone],
			"SELLER_COMPANY_IPN"           => ["TYPE" => "", "VALUE" => $shopINN_ua],
			"SELLER_COMPANY_EDRPOY"        => ["TYPE" => "", "VALUE" => $shopEGRPU_ua],
			"SELLER_COMPANY_BANK_ACCOUNT"  => ["TYPE" => "", "VALUE" => $shopNS_ua],
			"SELLER_COMPANY_BANK_NAME"     => ["TYPE" => "", "VALUE" => $shopBank_ua],
			"SELLER_COMPANY_MFO"           => ["TYPE" => "", "VALUE" => $shopMFO_ua],
			"SELLER_COMPANY_PDV"           => ["TYPE" => "", "VALUE" => $shopNDS_ua],
			"PAYMENT_ID"                   => ["TYPE" => "ORDER", "VALUE" => "ID"],
			"SELLER_COMPANY_SYS"           => ["TYPE" => "", "VALUE" => $shopTax_ua],
			"BUYER_PERSON_COMPANY_NAME"    => ["TYPE" => "PROPERTY", "VALUE" => "FIO"],
			"BUYER_PERSON_COMPANY_ADDRESS" => ["TYPE" => "PROPERTY", "VALUE" => "ADDRESS"],
			"BUYER_PERSON_COMPANY_PHONE"   => ["TYPE" => "PROPERTY", "VALUE" => "PHONE"],
			//"BILLUA_PATH_TO_STAMP" => ["TYPE" => "", "VALUE" => $siteStamp],
		];
	}

	if( $personType["fiz_ua"] == "Y" ) {
		$arPaySystem['PERSON_TYPE'][] = $arGeneralInfo["personType"]["fiz_ua"];
		$arPaySystem['BIZVAL'][$arGeneralInfo["personType"]["fiz_ua"]] = [
			"PAYMENT_DATE_INSERT"          => ["TYPE" => "ORDER", "VALUE" => "DATE_INSERT_DATE"],
			"SELLER_COMPANY_NAME"          => ["TYPE" => "", "VALUE" => $shopOfName],
			"SELLER_COMPANY_ADDRESS"       => ["TYPE" => "", "VALUE" => $shopAdr],
			"SELLER_COMPANY_PHONE"         => ["TYPE" => "", "VALUE" => $siteTelephone],
			"SELLER_COMPANY_IPN"           => ["TYPE" => "", "VALUE" => $shopINN_ua],
			"SELLER_COMPANY_EDRPOY"        => ["TYPE" => "", "VALUE" => $shopEGRPU_ua],
			"SELLER_COMPANY_BANK_ACCOUNT"  => ["TYPE" => "", "VALUE" => $shopNS_ua],
			"SELLER_COMPANY_BANK_NAME"     => ["TYPE" => "", "VALUE" => $shopBank_ua],
			"SELLER_COMPANY_MFO"           => ["TYPE" => "", "VALUE" => $shopMFO_ua],
			"SELLER_COMPANY_PDV"           => ["TYPE" => "", "VALUE" => $shopNDS_ua],
			"PAYMENT_ID"                   => ["TYPE" => "ORDER", "VALUE" => "ID"],
			"SELLER_COMPANY_SYS"           => ["TYPE" => "", "VALUE" => $shopTax_ua],
			"BUYER_PERSON_COMPANY_NAME"    => ["TYPE" => "PROPERTY", "VALUE" => "FIO"],
			"BUYER_PERSON_COMPANY_ADDRESS" => ["TYPE" => "PROPERTY", "VALUE" => "COMPANY_ADR"],
			"BUYER_PERSON_COMPANY_PHONE"   => ["TYPE" => "PROPERTY", "VALUE" => "PHONE"],
			//"BILLUA_PATH_TO_STAMP" => ["TYPE" => "", "VALUE" => $siteStamp],
		];
	}

	$arPaySystems[] = $arPaySystem;
}

foreach( $arPaySystems as $arPaySystem ) {
	$updateFields = [];

	$val = $arPaySystem['PAYSYSTEM'];
	if( array_key_exists('LOGOTIP', $val) && is_array($val['LOGOTIP']) ) {
		$updateFields['LOGOTIP'] = $val['LOGOTIP'];
		unset($val['LOGOTIP']);
	}

	$dbRes =
		\Bitrix\Sale\PaySystem\Manager::getList(['select' => ["ID", "NAME"], 'filter' => ["NAME" => $val["NAME"]]]);
	$tmpPaySystem = $dbRes->fetch();
	if( !$tmpPaySystem ) {
		$resultAdd = \Bitrix\Sale\Internals\PaySystemActionTable::add($val);
		if( $resultAdd->isSuccess() ) {
			$id = $resultAdd->getId();

			if( array_key_exists('BIZVAL', $arPaySystem) && $arPaySystem['BIZVAL'] ) {
				$arGeneralInfo["paySystem"][$arPaySystem["ACTION_FILE"]] = $id;
				foreach( $arPaySystem['BIZVAL'] as $personType => $codes ) {
					foreach( $codes as $code => $map ) {
						\Bitrix\Sale\BusinessValue::setMapping(
							$code,
							'PAYSYSTEM_' . $id,
							$personType,
							['PROVIDER_KEY' => $map['TYPE'] ?: 'VALUE', 'PROVIDER_VALUE' => $map['VALUE']],
							true
						);
					}
				}
			}

			if( $arPaySystem['PERSON_TYPE'] ) {
				$params = [
					'filter' => [
						"SERVICE_ID"   => $id,
						"SERVICE_TYPE" => Sale\Services\PaySystem\Restrictions\Manager::SERVICE_TYPE_PAYMENT,
						"=CLASS_NAME"  => '\\' . Sale\Services\PaySystem\Restrictions\PersonType::class,
					],
				];

				$dbRes = \Bitrix\Sale\Internals\ServiceRestrictionTable::getList($params);
				if( !$dbRes->fetch() ) {
					$fields = [
						"SERVICE_ID"   => $id,
						"SERVICE_TYPE" => \Bitrix\Sale\Services\PaySystem\Restrictions\Manager::SERVICE_TYPE_PAYMENT,
						"SORT"         => 100,
						"PARAMS"       => [
							'PERSON_TYPE_ID' => $arPaySystem['PERSON_TYPE'],
						],
					];
					\Bitrix\Sale\Services\PaySystem\Restrictions\PersonType::save($fields);
				}
			}

			$res = \Bitrix\Sale\Internals\ServiceRestrictionTable::add(
				[
					"SERVICE_ID"   => $id,
					"SERVICE_TYPE" => \Bitrix\Sale\Services\PaySystem\Restrictions\Manager::SERVICE_TYPE_PAYMENT,
					"CLASS_NAME"   => '\Bitrix\Sale\Services\PaySystem\Restrictions\Site',
					"PARAMS"       => [
						"SITE_ID" => [WIZARD_SITE_ID],
					],
				]
			);

			$updateFields['PARAMS'] = serialize(['BX_PAY_SYSTEM_ID' => $id]);
			$updateFields['PAY_SYSTEM_ID'] = $id;

			$image = '/bitrix/modules/sale/install/images/sale_payments/' . $val['ACTION_FILE'] . '.png';
			if(
				(!array_key_exists('LOGOTIP', $updateFields) || !is_array($updateFields['LOGOTIP'])) &&
				\Bitrix\Main\IO\File::isFileExists($_SERVER['DOCUMENT_ROOT'] . $image)
			) {
				$updateFields['LOGOTIP'] = CFile::MakeFileArray($image);
				$updateFields['LOGOTIP']['MODULE_ID'] = "sale";
			}

			CFile::SaveForDB($updateFields, 'LOGOTIP', 'sale/paysystem/logotip');
			\Bitrix\Sale\Internals\PaySystemActionTable::update($id, $updateFields);
		}
	}
	else {
		$flag = false;

		$params = [
			'filter' => [
				"SERVICE_ID"   => $tmpPaySystem['ID'],
				"SERVICE_TYPE" => Sale\Services\PaySystem\Restrictions\Manager::SERVICE_TYPE_PAYMENT,
				"=CLASS_NAME"  => '\\' . Sale\Services\PaySystem\Restrictions\PersonType::class,
			],
		];

		$dbRes = \Bitrix\Sale\Internals\ServiceRestrictionTable::getList($params);
		$restriction = $dbRes->fetch();

		if( $restriction ) {
			foreach( $restriction['PARAMS']['PERSON_TYPE_ID'] as $personTypeId ) {
				if( array_search($personTypeId, $arPaySystem['PERSON_TYPE']) === false ) {
					$arPaySystem['PERSON_TYPE'][] = $personTypeId;
					$flag = true;
				}
			}

			$restrictionId = $restriction['ID'];
		}

		if( $flag ) {
			$fields = [
				"SERVICE_ID"   => $restrictionId,
				"SERVICE_TYPE" => \Bitrix\Sale\Services\PaySystem\Restrictions\Manager::SERVICE_TYPE_PAYMENT,
				"SORT"         => 100,
				"PARAMS"       => [
					'PERSON_TYPE_ID' => $arPaySystem['PERSON_TYPE'],
				],
			];

			\Bitrix\Sale\Services\PaySystem\Restrictions\PersonType::save($fields, $restrictionId);
		}
	}
}


// statuses
if( $s_count < 2 ) {
	Sale\Notify::setNotifyDisable(true);

	if( $saleConverted15 ) {
		$orderPaidStatus = 'P';
		$deliveryAssembleStatus = 'DA';
		$deliveryGoodsStatus = 'DG';
		$deliveryTransportStatus = 'DT';
		$deliveryShipmentStatus = 'DS';

		$statusIds = [
			$orderPaidStatus,
			$deliveryAssembleStatus,
			$deliveryGoodsStatus,
			$deliveryTransportStatus,
			$deliveryShipmentStatus,
		];

		$statusLanguages = [];

		foreach( $arLanguages as $langID ) {
			Loc::loadLanguageFile($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/sale/lib/status.php', $langID);

			foreach( $statusIds as $statusId ) {
				if( $statusName = Loc::getMessage("SALE_STATUS_{$statusId}") ) {
					$statusLanguages[$statusId] [] = [
						'LID'         => $langID,
						'NAME'        => $statusName,
						'DESCRIPTION' => Loc::getMessage("SALE_STATUS_{$statusId}_DESCR"),
					];
				}
			}
		}

		OrderStatus::install(
			[
				'ID'     => $orderPaidStatus,
				'SORT'   => 150,
				'NOTIFY' => 'Y',
				'LANG'   => $statusLanguages[$orderPaidStatus],
			]
		);
		CSaleStatus::CreateMailTemplate($orderPaidStatus);

		DeliveryStatus::install(
			[
				'ID'     => $deliveryAssembleStatus,
				'SORT'   => 310,
				'NOTIFY' => 'Y',
				'LANG'   => $statusLanguages[$deliveryAssembleStatus],
			]
		);

		DeliveryStatus::install(
			[
				'ID'     => $deliveryGoodsStatus,
				'SORT'   => 320,
				'NOTIFY' => 'Y',
				'LANG'   => $statusLanguages[$deliveryGoodsStatus],
			]
		);

		DeliveryStatus::install(
			[
				'ID'     => $deliveryTransportStatus,
				'SORT'   => 330,
				'NOTIFY' => 'Y',
				'LANG'   => $statusLanguages[$deliveryTransportStatus],
			]
		);

		DeliveryStatus::install(
			[
				'ID'     => $deliveryShipmentStatus,
				'SORT'   => 340,
				'NOTIFY' => 'Y',
				'LANG'   => $statusLanguages[$deliveryShipmentStatus],
			]
		);
	}
	else {
		$bStatusP = false;
		$dbStatus = CSaleStatus::GetList(["SORT" => "ASC"]);
		while( $arStatus = $dbStatus->Fetch() ) {
			$arFields = [];
			foreach( $arLanguages as $langID ) {
				WizardServices::IncludeServiceLang("step1.php", $langID);
				$arFields["LANG"][] =
					[
						"LID"         => $langID,
						"NAME"        => Loc::getMessage("WIZ_SALE_STATUS_" . $arStatus["ID"]),
						"DESCRIPTION" => Loc::getMessage("WIZ_SALE_STATUS_DESCRIPTION_" . $arStatus["ID"]),
					];
			}
			$arFields["ID"] = $arStatus["ID"];
			CSaleStatus::Update($arStatus["ID"], $arFields);
			if( $arStatus["ID"] == "P" ) {
				$bStatusP = true;
			}
		}
		if( !$bStatusP ) {
			$arFields = ["ID" => "P", "SORT" => 150];
			foreach( $arLanguages as $langID ) {
				WizardServices::IncludeServiceLang("step1.php", $langID);
				$arFields["LANG"][] = [
					"LID"         => $langID,
					"NAME"        => Loc::getMessage("WIZ_SALE_STATUS_P"),
					"DESCRIPTION" => Loc::getMessage("WIZ_SALE_STATUS_DESCRIPTION_P"),
				];
			}

			$ID = CSaleStatus::Add($arFields);
			if( $ID !== '' ) {
				CSaleStatus::CreateMailTemplate($ID);
			}
		}
	}

	WizardServices::IncludeServiceLang("step1.php", $lang);

	CAgent::RemoveAgent("CSaleProduct::RefreshProductList();", "sale");
	CAgent::AddAgent("CSaleProduct::RefreshProductList();", "sale", "N", 60 * 60 * 24 * 4, "", "Y");

	Sale\Notify::setNotifyDisable(false);

}
return true;