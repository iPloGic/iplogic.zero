<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Catalog");
?>
<section>
	<div class="section-block content">
<?$APPLICATION->IncludeComponent(
	"bitrix:catalog", 
	".default", 
	array(
		"IBLOCK_TYPE" => "catalog",
		"IBLOCK_ID" => "#CATALOG_IBLOCK_ID#",
		"TEMPLATE_THEME" => "site",
		"HIDE_NOT_AVAILABLE" => "N",
		"BASKET_URL" => "/cart/",
		"ACTION_VARIABLE" => "action",
		"PRODUCT_ID_VARIABLE" => "id",
		"SECTION_ID_VARIABLE" => "SECTION_ID",
		"PRODUCT_QUANTITY_VARIABLE" => "quantity",
		"PRODUCT_PROPS_VARIABLE" => "prop",
		"SEF_MODE" => "Y",
		"SEF_FOLDER" => "/catalog/",
		"AJAX_MODE" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"AJAX_OPTION_HISTORY" => "N",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_FILTER" => "Y",
		"CACHE_GROUPS" => "Y",
		"SET_TITLE" => "Y",
		"ADD_SECTION_CHAIN" => "Y",
		"ADD_ELEMENT_CHAIN" => "Y",
		"SET_STATUS_404" => "Y",
		"DETAIL_DISPLAY_NAME" => "N",
		"USE_ELEMENT_COUNTER" => "Y",
		"USE_FILTER" => "Y",
		"FILTER_NAME" => "",
		"FILTER_VIEW_MODE" => "VERTICAL",
		"USE_COMPARE" => "N",
		"PRICE_CODE" => array(
			0 => "retail",
		),
		"USE_PRICE_COUNT" => "N",
		"SHOW_PRICE_COUNT" => "1",
		"PRICE_VAT_INCLUDE" => "Y",
		"PRICE_VAT_SHOW_VALUE" => "N",
		"PRODUCT_PROPERTIES" => array(
		),
		"USE_PRODUCT_QUANTITY" => "Y",
		"CONVERT_CURRENCY" => "N",
		"QUANTITY_FLOAT" => "N",
		"OFFERS_CART_PROPERTIES" => array(
		),
		"SHOW_TOP_ELEMENTS" => "N",
		"SECTION_COUNT_ELEMENTS" => "N",
		"SECTION_TOP_DEPTH" => "1",
		"SECTIONS_VIEW_MODE" => "TILE",
		"SECTIONS_SHOW_PARENT_NAME" => "N",
		"PAGE_ELEMENT_COUNT" => "15",
		"LINE_ELEMENT_COUNT" => "3",
		"ELEMENT_SORT_FIELD" => "sort",
		"ELEMENT_SORT_ORDER" => "asc",
		"ELEMENT_SORT_FIELD2" => "shows",
		"ELEMENT_SORT_ORDER2" => "desc",
		"LIST_PROPERTY_CODE" => array(
			0 => "ARTICLE",
			1 => "BRAND",
			2 => "",
		),
		"INCLUDE_SUBSECTIONS" => "Y",
		"LIST_META_KEYWORDS" => "-",
		"LIST_META_DESCRIPTION" => "-",
		"LIST_BROWSER_TITLE" => "-",
		"LIST_OFFERS_FIELD_CODE" => array(
			0 => "NAME",
			1 => "PREVIEW_PICTURE",
			2 => "DETAIL_PICTURE",
			3 => "",
		),
		"LIST_OFFERS_PROPERTY_CODE" => array(
			0 => "ARTICLE",
			1 => "",
		),
		"LIST_OFFERS_LIMIT" => "0",
		"SECTION_BACKGROUND_IMAGE" => "-",
		"DETAIL_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"DETAIL_META_KEYWORDS" => "-",
		"DETAIL_META_DESCRIPTION" => "-",
		"DETAIL_BROWSER_TITLE" => "-",
		"DETAIL_OFFERS_FIELD_CODE" => array(
			0 => "",
			1 => "",
		),
		"DETAIL_OFFERS_PROPERTY_CODE" => array(
			0 => "",
			1 => "",
		),
		"DETAIL_BACKGROUND_IMAGE" => "-",
		"LINK_IBLOCK_TYPE" => "catalog",
		"LINK_IBLOCK_ID" => "1",
		"LINK_PROPERTY_SID" => "",
		"LINK_ELEMENTS_URL" => "link.php?PARENT_ELEMENT_ID=#ELEMENT_ID#",
		"USE_ALSO_BUY" => "Y",
		"ALSO_BUY_ELEMENT_COUNT" => "4",
		"ALSO_BUY_MIN_BUYES" => "1",
		"OFFERS_SORT_FIELD" => "sort",
		"OFFERS_SORT_ORDER" => "desc",
		"OFFERS_SORT_FIELD2" => "id",
		"OFFERS_SORT_ORDER2" => "desc",
		"PAGER_TEMPLATE" => "round",
		"DISPLAY_TOP_PAGER" => "N",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"PAGER_TITLE" => "������",
		"PAGER_SHOW_ALWAYS" => "N",
		"PAGER_DESC_NUMBERING" => "N",
		"PAGER_DESC_NUMBERING_CACHE_TIME" => "36000000",
		"PAGER_SHOW_ALL" => "N",
		"ADD_PICT_PROP" => "MORE_PHOTO",
		"LABEL_PROP" => array(
		),
		"PRODUCT_DISPLAY_MODE" => "Y",
		"OFFER_ADD_PICT_PROP" => "MORE_PHOTO",
		"OFFER_TREE_PROPS" => array(
		),
		"SHOW_DISCOUNT_PERCENT" => "Y",
		"SHOW_OLD_PRICE" => "Y",
		"MESS_BTN_BUY" => "������",
		"MESS_BTN_ADD_TO_BASKET" => "To cart",
		"MESS_BTN_COMPARE" => "Compare",
		"MESS_BTN_DETAIL" => "Details",
		"MESS_NOT_AVAILABLE" => "Not in stock",
		"DETAIL_USE_VOTE_RATING" => "Y",
		"DETAIL_VOTE_DISPLAY_AS_RATING" => "rating",
		"DETAIL_USE_COMMENTS" => "N",
		"DETAIL_BLOG_USE" => "Y",
		"DETAIL_VK_USE" => "N",
		"DETAIL_FB_USE" => "Y",
		"AJAX_OPTION_ADDITIONAL" => "",
		"USE_STORE" => "N",
		"BIG_DATA_RCM_TYPE" => "personal",
		"FIELDS" => array(
			0 => "STORE",
			1 => "SCHEDULE",
		),
		"USE_MIN_AMOUNT" => "N",
		"STORE_PATH" => "#SITE_DIR#store/#store_id#",
		"MAIN_TITLE" => "Availability in warehouses",
		"MIN_AMOUNT" => "10",
		"DETAIL_BRAND_USE" => "Y",
		"DETAIL_BRAND_PROP_CODE" => array(
			0 => "",
			1 => "",
		),
		"COMPATIBLE_MODE" => "N",
		"SIDEBAR_SECTION_SHOW" => "N",
		"SIDEBAR_DETAIL_SHOW" => "N",
		"SIDEBAR_PATH" => "#SITE_DIR#catalog/sidebar.php",
		"COMPONENT_TEMPLATE" => ".default",
		"HIDE_NOT_AVAILABLE_OFFERS" => "N",
		"COMMON_SHOW_CLOSE_POPUP" => "N",
		"PRODUCT_SUBSCRIPTION" => "Y",
		"DISCOUNT_PERCENT_POSITION" => "bottom-right",
		"SHOW_MAX_QUANTITY" => "Y",
		"MESS_BTN_SUBSCRIBE" => "Subscribe",
		"USER_CONSENT" => "N",
		"USER_CONSENT_ID" => "0",
		"USER_CONSENT_IS_CHECKED" => "Y",
		"USER_CONSENT_IS_LOADED" => "N",
		"USE_MAIN_ELEMENT_SECTION" => "N",
		"DETAIL_STRICT_SECTION_CHECK" => "N",
		"SET_LAST_MODIFIED" => "N",
		"ADD_SECTIONS_CHAIN" => "Y",
		"USE_SALE_BESTSELLERS" => "Y",
		"FILTER_HIDE_ON_MOBILE" => "N",
		"INSTANT_RELOAD" => "N",
		"ADD_PROPERTIES_TO_BASKET" => "Y",
		"PARTIAL_PRODUCT_PROPERTIES" => "N",
		"USE_COMMON_SETTINGS_BASKET_POPUP" => "N",
		"COMMON_ADD_TO_BASKET_ACTION" => "ADD",
		"TOP_ADD_TO_BASKET_ACTION" => "ADD",
		"SECTION_ADD_TO_BASKET_ACTION" => "ADD",
		"DETAIL_ADD_TO_BASKET_ACTION" => array(
			0 => "BUY",
		),
		"DETAIL_ADD_TO_BASKET_ACTION_PRIMARY" => array(
			0 => "BUY",
		),
		"SEARCH_PAGE_RESULT_COUNT" => "30",
		"SEARCH_RESTART" => "N",
		"SEARCH_NO_WORD_LOGIC" => "Y",
		"SEARCH_USE_LANGUAGE_GUESS" => "Y",
		"SEARCH_CHECK_DATES" => "Y",
		"SECTIONS_HIDE_SECTION_NAME" => "N",
		"LIST_PROPERTY_CODE_MOBILE" => array(
		),
		"LIST_PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons",
		"LIST_PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false}]",
		"LIST_ENLARGE_PRODUCT" => "STRICT",
		"LIST_SHOW_SLIDER" => "Y",
		"LIST_SLIDER_INTERVAL" => "3000",
		"LIST_SLIDER_PROGRESS" => "N",
		"DETAIL_SET_CANONICAL_URL" => "N",
		"DETAIL_CHECK_SECTION_ID_VARIABLE" => "N",
		"SHOW_DEACTIVATED" => "N",
		"DETAIL_MAIN_BLOCK_PROPERTY_CODE" => array(
		),
		"DETAIL_MAIN_BLOCK_OFFERS_PROPERTY_CODE" => array(
		),
		"DETAIL_BLOG_URL" => "catalog_comments",
		"DETAIL_BLOG_EMAIL_NOTIFY" => "N",
		"DETAIL_FB_APP_ID" => "",
		"DETAIL_IMAGE_RESOLUTION" => "16by9",
		"DETAIL_PRODUCT_INFO_BLOCK_ORDER" => "sku,props",
		"DETAIL_PRODUCT_PAY_BLOCK_ORDER" => "rating,price,priceRanges,quantityLimit,quantity,buttons",
		"DETAIL_SHOW_SLIDER" => "N",
		"DETAIL_DETAIL_PICTURE_MODE" => array(
			0 => "POPUP",
			1 => "MAGNIFIER",
		),
		"DETAIL_ADD_DETAIL_TO_SLIDER" => "N",
		"DETAIL_DISPLAY_PREVIEW_TEXT_MODE" => "E",
		"MESS_PRICE_RANGES_TITLE" => "Prices",
		"MESS_DESCRIPTION_TAB" => "Description",
		"MESS_PROPERTIES_TAB" => "Properties",
		"MESS_COMMENTS_TAB" => "Comments",
		"DETAIL_SHOW_POPULAR" => "Y",
		"DETAIL_SHOW_VIEWED" => "Y",
		"USE_GIFTS_DETAIL" => "Y",
		"USE_GIFTS_SECTION" => "Y",
		"USE_GIFTS_MAIN_PR_SECTION_LIST" => "Y",
		"GIFTS_DETAIL_PAGE_ELEMENT_COUNT" => "4",
		"GIFTS_DETAIL_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_DETAIL_BLOCK_TITLE" => "Choose one of the gifts",
		"GIFTS_DETAIL_TEXT_LABEL_GIFT" => "Gift",
		"GIFTS_SECTION_LIST_PAGE_ELEMENT_COUNT" => "4",
		"GIFTS_SECTION_LIST_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_SECTION_LIST_BLOCK_TITLE" => "Gifts for products in this section",
		"GIFTS_SECTION_LIST_TEXT_LABEL_GIFT" => "Gift",
		"GIFTS_SHOW_DISCOUNT_PERCENT" => "Y",
		"GIFTS_SHOW_OLD_PRICE" => "Y",
		"GIFTS_SHOW_NAME" => "Y",
		"GIFTS_SHOW_IMAGE" => "Y",
		"GIFTS_MESS_BTN_BUY" => "Choose",
		"GIFTS_MAIN_PRODUCT_DETAIL_PAGE_ELEMENT_COUNT" => "4",
		"GIFTS_MAIN_PRODUCT_DETAIL_HIDE_BLOCK_TITLE" => "N",
		"GIFTS_MAIN_PRODUCT_DETAIL_BLOCK_TITLE" => "Select one of the products to get a gift",
		"USE_BIG_DATA" => "Y",
		"USE_ENHANCED_ECOMMERCE" => "N",
		"PAGER_BASE_LINK_ENABLE" => "N",
		"LAZY_LOAD" => "N",
		"LOAD_ON_SCROLL" => "N",
		"SHOW_404" => "N",
		"MESSAGE_404" => "",
		"DISABLE_INIT_JS_IN_COMPONENT" => "N",
		"DETAIL_SET_VIEWED_IN_COMPONENT" => "N",
		"MESS_SHOW_MAX_QUANTITY" => "�������",
		"SEF_URL_TEMPLATES" => array(
			"sections" => "",
			"section" => "#SECTION_CODE_PATH#/",
			"element" => "#SECTION_CODE_PATH#/#ELEMENT_CODE#/",
			"compare" => "compare/",
			"smart_filter" => "#SECTION_CODE_PATH#/filter/#SMART_FILTER_PATH#/apply/",
		)
	),
	false
);?>
	</div>
	<div class="spacer40"></div>
</section>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>