<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Personal Area");

global $USER;
if(!$USER->IsAuthorized()){ 
	LocalRedirect("/auth/");
}
?>

<?$APPLICATION->IncludeComponent(
	"bitrix:sale.personal.section", 
	".default", 
	array(
		"ACCOUNT_PAYMENT_ELIMINATED_PAY_SYSTEMS" => array(
			0 => "0",
		),
		"ACCOUNT_PAYMENT_SELL_CURRENCY" => "RUB",
		"ACCOUNT_PAYMENT_SELL_SHOW_FIXED_VALUES" => "Y",
		"ACCOUNT_PAYMENT_SELL_TOTAL" => array(
			0 => "100",
			1 => "200",
			2 => "500",
			3 => "1000",
			4 => "5000",
			5 => "",
		),
		"ACCOUNT_PAYMENT_SELL_USER_INPUT" => "Y",
		"ACTIVE_DATE_FORMAT" => "d.m.Y",
		"ALLOW_INNER" => "N",
		"CACHE_GROUPS" => "Y",
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"CHECK_RIGHTS_PRIVATE" => "Y",
		"COMPATIBLE_LOCATION_MODE_PROFILE" => "N",
		"CUSTOM_PAGES" => "",
		"CUSTOM_SELECT_PROPS" => array(
		),
		"MAIN_CHAIN_NAME" => "",
		"NAV_TEMPLATE" => "",
		"ONLY_INNER_FULL" => "N",
		"ORDERS_PER_PAGE" => "20",
		"ORDER_DEFAULT_SORT" => "STATUS",
		"ORDER_HIDE_USER_INFO" => array(
			0 => "0",
		),
		"ORDER_HISTORIC_STATUSES" => array(
			0 => "F",
		),
		"ORDER_REFRESH_PRICES" => "N",
		"ORDER_RESTRICT_CHANGE_PAYSYSTEM" => array(
			0 => "F",
		),
		"PATH_TO_BASKET" => "/cart/",
		"PATH_TO_CATALOG" => "/catalog/",
		"PATH_TO_CONTACT" => "/about/contacts/",
		"PATH_TO_PAYMENT" => "/cart/order/payment/",
		"PROFILES_PER_PAGE" => "20",
		"SAVE_IN_SESSION" => "Y",
		"SEF_MODE" => "Y",
		"SEND_INFO_PRIVATE" => "N",
		"SET_TITLE" => "Y",
		"SHOW_ACCOUNT_COMPONENT" => "Y",
		"SHOW_ACCOUNT_PAGE" => "Y",
		"SHOW_ACCOUNT_PAY_COMPONENT" => "Y",
		"SHOW_BASKET_PAGE" => "Y",
		"SHOW_CONTACT_PAGE" => "N",
		"SHOW_ORDER_PAGE" => "Y",
		"SHOW_PRIVATE_PAGE" => "Y",
		"SHOW_PROFILE_PAGE" => "Y",
		"SHOW_SUBSCRIBE_PAGE" => "Y",
		"USE_AJAX_LOCATIONS_PROFILE" => "N",
		"COMPONENT_TEMPLATE" => ".default",
		"SEF_FOLDER" => "/personal/",
		"SEF_URL_TEMPLATES" => array(
			"index" => "",
			"orders" => "orders/",
			"account" => "account/",
			"subscribe" => "subscribe/",
			"profile" => "profiles/",
			"profile_detail" => "profiles/#ID#",
			"private" => "private/",
			"order_detail" => "orders/#ID#",
			"order_cancel" => "cancel/#ID#",
		)
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>