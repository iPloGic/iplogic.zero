<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("title", "Корзина");
$APPLICATION->SetTitle("Корзина");

CModule::IncludeModule('sale');

$cntBasketItems = CSaleBasket::GetList(
	array(),
	array( 
		"FUSER_ID" => CSaleBasket::GetBasketUserID(),
		"LID" => SITE_ID,
		"ORDER_ID" => "NULL",
		"!CAN_BUY" => "N"
	), 
	array()
);
?>
<? if (!isset($_GET['ORDER_ID']) || $_GET['ORDER_ID'] == '') { ?>
Выбранные товары
<?$APPLICATION->IncludeComponent(
	"bitrix:sale.basket.basket", 
	".default", 
	array(
		"ACTION_VARIABLE" => "basketAction",
		"ADDITIONAL_PICT_PROP_1" => "-",
		"ADDITIONAL_PICT_PROP_2" => "-",
		"AUTO_CALCULATION" => "Y",
		"BASKET_IMAGES_SCALING" => "adaptive",
		"COLUMNS_LIST_EXT" => array(
			0 => "PREVIEW_PICTURE",
			1 => "DISCOUNT",
			2 => "DELETE",
			3 => "DELAY",
			4 => "TYPE",
			5 => "SUM",
		),
		"COLUMNS_LIST_MOBILE" => array(
			0 => "PREVIEW_PICTURE",
			1 => "DISCOUNT",
			2 => "DELETE",
			3 => "DELAY",
			4 => "TYPE",
			5 => "SUM",
		),
		"COMPATIBLE_MODE" => "Y",
		"CORRECT_RATIO" => "Y",
		"DEFERRED_REFRESH" => "N",
		"DISCOUNT_PERCENT_POSITION" => "bottom-right",
		"DISPLAY_MODE" => "extended",
		"EMPTY_BASKET_HINT_PATH" => "/catalog/",
		"GIFTS_BLOCK_TITLE" => "Выберите один из подарков",
		"GIFTS_CONVERT_CURRENCY" => "N",
		"GIFTS_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_HIDE_NOT_AVAILABLE" => "N",
		"GIFTS_MESS_BTN_BUY" => "Выбрать",
		"GIFTS_MESS_BTN_DETAIL" => "Подробнее",
		"GIFTS_PAGE_ELEMENT_COUNT" => "4",
		"GIFTS_PLACE" => "BOTTOM",
		"GIFTS_PRODUCT_PROPS_VARIABLE" => "prop",
		"GIFTS_PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",
		"GIFTS_SHOW_OLD_PRICE" => "N",
		"GIFTS_TEXT_LABEL_GIFT" => "Подарок",
		"HIDE_COUPON" => "Y",
		"LABEL_PROP" => array(
		),
		"OFFERS_PROPS" => array(
		),
		"PATH_TO_ORDER" => "/cart/order/",
		"PRICE_DISPLAY_MODE" => "Y",
		"PRICE_VAT_SHOW_VALUE" => "N",
		"PRODUCT_BLOCKS_ORDER" => "props,sku,columns",
		"QUANTITY_FLOAT" => "Y",
		"SET_TITLE" => "Y",
		"SHOW_DISCOUNT_PERCENT" => "Y",
		"SHOW_FILTER" => "Y",
		"SHOW_RESTORE" => "Y",
		"TEMPLATE_THEME" => "green",
		"TOTAL_BLOCK_DISPLAY" => array(
			0 => "bottom",
		),
		"USE_DYNAMIC_SCROLL" => "Y",
		"USE_ENHANCED_ECOMMERCE" => "N",
		"USE_GIFTS" => "Y",
		"USE_PREPAYMENT" => "N",
		"USE_PRICE_ANIMATION" => "Y",
		"COMPONENT_TEMPLATE" => ".default"
	),
	false
);?>
<? } ?>
<? if ($cntBasketItems > 0 || isset($_GET['ORDER_ID'])) { ?>
Оформление заказа
<?$APPLICATION->IncludeComponent("bitrix:sale.order.ajax", ".default", Array(
	"ACTION_VARIABLE" => "soa-action",
		"ADDITIONAL_PICT_PROP_1" => "-",
		"ADDITIONAL_PICT_PROP_2" => "-",
		"ALLOW_APPEND_ORDER" => "Y",
		"ALLOW_AUTO_REGISTER" => "Y",
		"ALLOW_NEW_PROFILE" => "N",
		"ALLOW_USER_PROFILES" => "N",
		"BASKET_IMAGES_SCALING" => "adaptive",
		"BASKET_POSITION" => "after",
		"COMPATIBLE_MODE" => "Y",
		"DELIVERIES_PER_PAGE" => "9",
		"DELIVERY_FADE_EXTRA_SERVICES" => "N",
		"DELIVERY_NO_AJAX" => "Y",
		"DELIVERY_NO_SESSION" => "N",
		"DELIVERY_TO_PAYSYSTEM" => "d2p",
		"DISABLE_BASKET_REDIRECT" => "N",
		"EMPTY_BASKET_HINT_PATH" => "/catalog/",
		"HIDE_ORDER_DESCRIPTION" => "N",
		"ONLY_FULL_PAY_FROM_ACCOUNT" => "N",
		"PATH_TO_AUTH" => "/auth/",
		"PATH_TO_BASKET" => "/cart/",
		"PATH_TO_PAYMENT" => "payment.php",
		"PATH_TO_PERSONAL" => "/personal/",
		"PAY_FROM_ACCOUNT" => "N",
		"PAY_SYSTEMS_PER_PAGE" => "9",
		"PICKUPS_PER_PAGE" => "5",
		"PICKUP_MAP_TYPE" => "yandex",
		"PRODUCT_COLUMNS_HIDDEN" => "",
		"PRODUCT_COLUMNS_VISIBLE" => array(
			0 => "PREVIEW_PICTURE",
			1 => "PROPS",
		),
		"SEND_NEW_USER_NOTIFY" => "Y",
		"SERVICES_IMAGES_SCALING" => "adaptive",
		"SET_TITLE" => "Y",
		"SHOW_BASKET_HEADERS" => "N",
		"SHOW_COUPONS_BASKET" => "Y",
		"SHOW_COUPONS_DELIVERY" => "Y",
		"SHOW_COUPONS_PAY_SYSTEM" => "Y",
		"SHOW_DELIVERY_INFO_NAME" => "Y",
		"SHOW_DELIVERY_LIST_NAMES" => "Y",
		"SHOW_DELIVERY_PARENT_NAMES" => "Y",
		"SHOW_MAP_IN_PROPS" => "N",
		"SHOW_NEAREST_PICKUP" => "N",
		"SHOW_NOT_CALCULATED_DELIVERIES" => "L",
		"SHOW_ORDER_BUTTON" => "final_step",
		"SHOW_PAY_SYSTEM_INFO_NAME" => "Y",
		"SHOW_PAY_SYSTEM_LIST_NAMES" => "Y",
		"SHOW_PICKUP_MAP" => "Y",
		"SHOW_STORES_IMAGES" => "Y",
		"SHOW_TOTAL_ORDER_BUTTON" => "N",
		"SHOW_VAT_PRICE" => "Y",
		"SKIP_USELESS_BLOCK" => "Y",
		"SPOT_LOCATION_BY_GEOIP" => "Y",
		"TEMPLATE_LOCATION" => "popup",
		"TEMPLATE_THEME" => "green",
		"USER_CONSENT" => "N",
		"USER_CONSENT_ID" => "0",
		"USER_CONSENT_IS_CHECKED" => "Y",
		"USER_CONSENT_IS_LOADED" => "N",
		"USE_CUSTOM_ADDITIONAL_MESSAGES" => "N",
		"USE_CUSTOM_ERROR_MESSAGES" => "N",
		"USE_CUSTOM_MAIN_MESSAGES" => "N",
		"USE_ENHANCED_ECOMMERCE" => "N",
		"USE_PHONE_NORMALIZATION" => "Y",
		"USE_PRELOAD" => "Y",
		"USE_PREPAYMENT" => "N",
		"USE_YM_GOALS" => "N",
		"COMPONENT_TEMPLATE" => ".default"
	),
	false
);?>
<? } ?>
<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>