<?
if( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true ) {
	die();
}

use Bitrix\Main\Config\Option,
	Bitrix\Main\Localization\Loc,
	Bitrix\Main\Loader;

if( !CModule::IncludeModule('sale') ) {
	return;
}

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

$shopLocalization = $wizard->GetVar("shopLocalization");
$delivery = $wizard->GetVar("delivery");

$defCurrency = "EUR";
if( $lang == "ru" ) {
	if( $shopLocalization == "bl" ) {
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

WizardServices::IncludeServiceLang("step2.php", $lang);

//Init delivery handlers classes
\Bitrix\Sale\Delivery\Services\Manager::getHandlersList();
$deliveryItems = [];
$arLocation4Delivery = [];

if( !empty($delivery["pickup"]) || !empty($delivery["courier"]) ) {
	$locationGroupID = 0;
	$arLocationArr = [];

	if(
		\Bitrix\Main\Config\Option::get('sale', 'sale_locationpro_migrated', '') == 'Y'
	) // CSaleLocation::isLocationProMigrated()
	{
		$res =
			\Bitrix\Sale\Location\LocationTable::getList(['filter' => ['=TYPE.CODE' => 'COUNTRY'], 'select' => ['ID']]);
		while( $item = $res->fetch() ) {
			$arLocation4Delivery[] = ["LOCATION_ID" => $item["ID"], "LOCATION_TYPE" => "L"];
		}
	}
	else {
		$dbLocation = CSaleLocation::GetList([], ["LID" => $lang]);
		while( $arLocation = $dbLocation->Fetch() ) {
			$arLocation4Delivery[] = ["LOCATION_ID" => $arLocation["ID"], "LOCATION_TYPE" => "L"];
			$arLocationArr[] = $arLocation["ID"];
		}

		$dbGroup = CSaleLocationGroup::GetList();
		if( $arGroup = $dbGroup->Fetch() ) {
			$arLocation4Delivery[] = ["LOCATION_ID" => $arGroup["ID"], "LOCATION_TYPE" => "G"];
		}
		else {
			$groupLang = [
				["LID" => "en", "NAME" => "Group 1"],
			];

			if( $bRus ) {
				$groupLang[] = ["LID" => $lang, "NAME" => Loc::getMessage("SALE_WIZARD_GROUP")];
			}

			$locationGroupID = CSaleLocationGroup::Add(
				[
					"SORT"        => 150,
					"LOCATION_ID" => $arLocationArr,
					"LANG"        => $groupLang,
				]
			);
		}
		//Location group
		if( intval($locationGroupID) > 0 ) {
			$arLocation4Delivery[] = ["LOCATION_ID" => $locationGroupID, "LOCATION_TYPE" => "G"];
		}
	}

	$arIds = [];
	$existConfDlv = [];

	$dbRes = \Bitrix\Sale\Internals\ServiceRestrictionTable::getList(
		[
			'filter' => [
				'=CLASS_NAME'   => '\Bitrix\Sale\Delivery\Restrictions\BySite',
				'=SERVICE_TYPE' => \Bitrix\Sale\Delivery\Restrictions\Manager::SERVICE_TYPE_SHIPMENT,
			],
		]
	);

	while( $rstr = $dbRes->fetch() ) {
		$lids = $rstr["PARAMS"]["SITE_ID"];

		if( is_array($lids) ) {
			if( in_array(WIZARD_SITE_ID, $lids) ) {
				$arIds[] = $rstr["SERVICE_ID"];
			}
		}
		else {
			if( WIZARD_SITE_ID == $lids ) {
				$arIds[] = $rstr["SERVICE_ID"];
			}
		}
	}

	if( count($arIds) ) {
		$dbRes = \Bitrix\Sale\Delivery\Services\Table::getList(
			[
				'filter' => [
					'=CLASS_NAME' => [
						'\Bitrix\Sale\Delivery\Services\Configurable',
					],
					'=ID'         => $arIds,
				],
				'select' => ['ID', 'NAME'],
			]
		);

		while( $dlv = $dbRes->fetch() ) {
			$existConfDlv[] = $dlv['NAME'];
		}
	}

	if( !in_array(Loc::getMessage("SALE_WIZARD_COUR"), $existConfDlv) && !empty($delivery["courier"]) ) {
		$deliveryItems[] = [
			"NAME"        => Loc::getMessage("SALE_WIZARD_COUR"),
			"DESCRIPTION" => Loc::getMessage("SALE_WIZARD_COUR_DESCR"),
			"CLASS_NAME"  => '\Bitrix\Sale\Delivery\Services\Configurable',
			"CURRENCY"    => $defCurrency,
			"SORT"        => 100,
			"ACTIVE"      => $delivery["courier"] == "Y" ? "Y" : "N",
			"LOGOTIP"     => WIZARD_SERVICE_RELATIVE_PATH . "/images/delivery.png",
			"CONFIG"      => [
				"MAIN" => [
					"PRICE"    => ($bRus ? "500" : "30"),
					"CURRENCY" => $defCurrency,
					"PERIOD"   => [
						"FROM" => 0,
						"TO"   => 0,
						"TYPE" => "D",
					],
				],
			],
		];
	}

	if( !in_array(Loc::getMessage("SALE_WIZARD_PICK"), $existConfDlv) && !empty($delivery["pickup"]) ) {
		$deliveryItems[] = [
			"NAME"        => Loc::getMessage("SALE_WIZARD_PICK"),
			"DESCRIPTION" => Loc::getMessage("SALE_WIZARD_PICK_DESCR"),
			"CLASS_NAME"  => '\Bitrix\Sale\Delivery\Services\Configurable',
			"CURRENCY"    => $defCurrency,
			"SORT"        => 200,
			"ACTIVE"      => $delivery["pickup"] == "Y" ? "Y" : "N",
			"LOGOTIP"     => WIZARD_SERVICE_RELATIVE_PATH . "/images/picup.png",
			"CONFIG"      => [
				"MAIN" => [
					"PRICE"    => 0,
					"CURRENCY" => $defCurrency,
					"PERIOD"   => [
						"FROM" => 0,
						"TO"   => 0,
						"TYPE" => "D",
					],
				],
			],
		];
	}
}


if( empty($existAutoDlv["spsr"]) ) {
	$deliveryItems["spsr"] = [
		"NAME"        => Loc::getMessage("SALE_WIZARD_SPSR"),
		"CODE"        => "spsr",
		"DESCRIPTION" => Loc::getMessage("SALE_WIZARD_SPSR_DESCR"),
		"CLASS_NAME"  => '\Sale\Handlers\Delivery\SpsrHandler',
		"CURRENCY"    => $defCurrency,
		"SORT"        => 300,
		"LOGOTIP"     => "/bitrix/modules/sale/handlers/delivery/spsr/logo.png",
		"ACTIVE"      => $delivery["spsr"] == "Y" ? "Y" : "N",
		"CONFIG"      => [
			"MAIN" => [
				"CALCULATE_IMMEDIATELY" => "Y",
				"DEFAULT_WEIGHT"        => 1000,
				"AMOUNT_CHECK"          => 1,
				"NATURE"                => 1,
				"LOGIN"                 => "",
				"PASS"                  => "",
				"ICN"                   => "",
			],
		],
	];
}


//new russian post
if( !empty($delivery["rus_post"]) ) {
	$deliveryItems["rus_post"] = [
		"NAME"        => Loc::getMessage("SALE_WIZARD_MAIL2"),
		"DESCRIPTION" => Loc::getMessage("SALE_WIZARD_MAIL_DESC2"),
		"CLASS_NAME"  => '\Bitrix\Sale\Delivery\Services\Automatic',
		"CURRENCY"    => $defCurrency,
		"SORT"        => 400,
		"LOGOTIP"     => "/bitrix/modules/sale/ru/delivery/rus_post_logo.png",
		"ACTIVE"      => $delivery["rus_post"] == "Y" ? "Y" : "N",
		"CONFIG"      => [
			"MAIN" => [
				"SID"          => "rus_post",
				"MARGIN_VALUE" => 0,
				"MARGIN_TYPE"  => "%",
			],
		],
	];
}


if( !empty($delivery["kaz_post"]) ) {
	$deliveryItems["kaz_post"] = [
		"NAME"        => Loc::getMessage("SALE_WIZARD_KAZ_POST"),
		"DESCRIPTION" => "",
		"CLASS_NAME"  => '\Bitrix\Sale\Delivery\Services\Automatic',
		"CURRENCY"    => $defCurrency,
		"SORT"        => 400,
		"ACTIVE"      => $delivery["kaz_post"] == "Y" ? "Y" : "N",
		"LOGOTIP"     => "/bitrix/modules/sale/ru/delivery/kaz_post_logo.png",
		"CONFIG"      => [
			"MAIN" => [
				"SID"          => "kaz_post",
				"MARGIN_VALUE" => 0,
				"MARGIN_TYPE"  => "%",
			],
		],
	];
}


if( !empty($delivery["ups"]) ) {
	$deliveryItems["ups"] = [
		"NAME"        => "UPS",
		"DESCRIPTION" => Loc::getMessage("SALE_WIZARD_UPS"),
		"CLASS_NAME"  => '\Bitrix\Sale\Delivery\Services\Automatic',
		"CURRENCY"    => $defCurrency,
		"SORT"        => 500,
		"ACTIVE"      => $delivery["ups"] == "Y" ? "Y" : "N",
		"LOGOTIP"     => "/bitrix/modules/sale/delivery/ups_logo.gif",
		"CONFIG"      => [
			"MAIN" => [
				"SID"          => "ups",
				"MARGIN_VALUE" => 0,
				"MARGIN_TYPE"  => "%",
				"OLD_SETTINGS" => "/bitrix/modules/sale/delivery/ups/ru_csv_zones.csv;/bitrix/modules/sale/delivery/ups/ru_csv_export.csv",
			],
		],
	];
}


foreach( $deliveryItems as $code => $fields ) {

	if( !empty($fields["LOGOTIP"]) ) {
		if( file_exists($_SERVER["DOCUMENT_ROOT"] . $fields["LOGOTIP"]) ) {
			$fields["LOGOTIP"] = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"] . $fields["LOGOTIP"]);
			$fields["LOGOTIP"]["MODULE_ID"] = "sale";
			CFile::SaveForDB($fields, "LOGOTIP", "sale/delivery/logotip");
		}
	}

	try {
		if( $service = \Bitrix\Sale\Delivery\Services\Manager::createObject($fields) ) {
			$fields = $service->prepareFieldsForSaving($fields);
		}
	} catch( \Bitrix\Main\SystemException $e ) {
		continue;
	}

	$res = \Bitrix\Sale\Delivery\Services\Manager::add($fields);

	if( $res->isSuccess() ) {
		if( !$fields["CLASS_NAME"]::isInstalled() ) {
			$fields["CLASS_NAME"]::install();
		}

		$newId = $res->getId();

		$res = \Bitrix\Sale\Internals\ServiceRestrictionTable::add(
			[
				"SERVICE_ID"   => $newId,
				"SERVICE_TYPE" => \Bitrix\Sale\Services\Base\RestrictionManager::SERVICE_TYPE_SHIPMENT,
				"CLASS_NAME"   => '\Bitrix\Sale\Delivery\Restrictions\BySite',
				"PARAMS"       => [
					"SITE_ID" => [WIZARD_SITE_ID],
				],
			]
		);

		//Link delivery "pickup" to store
		if( $fields["NAME"] == Loc::getMessage("SALE_WIZARD_PICK") ) {
			\Bitrix\Main\Loader::includeModule('catalog');
			$dbStores = CCatalogStore::GetList([], ["ACTIVE" => 'Y'], false, false, ["ID"]);
			if( $store = $dbStores->Fetch() ) {
				\Bitrix\Sale\Delivery\ExtraServices\Manager::saveStores(
					$newId,
					[$store['ID']]
				);
			}
		}
	}

}


?>