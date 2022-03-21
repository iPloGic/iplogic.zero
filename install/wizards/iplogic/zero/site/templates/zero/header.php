<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?

use \Bitrix\Main\Config\Option;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\UI\Extension;

Extension::load('ui.bootstrap4');

Loc::loadMessages(__FILE__);

$page_param = 'PAGEN_1';
$pagenum = 1;
if(isset($_GET[$page_param]) && !empty($_GET[$page_param]) )
	$pagenum = $_GET[$page_param];

if(!defined("SITE_TEMPLATE_PATH"))
	define("SITE_TEMPLATE_PATH", "/local/templates/".SITE_TEMPLATE_ID);

// init $IS_MAIN_PAGE variable
$IS_MAIN_PAGE = false;
if (strtolower( $APPLICATION->GetCurPage(true)) == LANG_DIR."index.php") $IS_MAIN_PAGE = true;
// section
$arURILine = explode('/',$APPLICATION->GetCurPage());
$SECTION = $arURILine[1];

$APPLICATION->SetAdditionalCss(SITE_TEMPLATE_PATH.'/css/common.css');
$APPLICATION->SetAdditionalCss(SITE_TEMPLATE_PATH.'/css/solid.css');
$APPLICATION->SetAdditionalCss(SITE_TEMPLATE_PATH.'/css/fontawesome.css');
$APPLICATION->SetAdditionalCss(SITE_TEMPLATE_PATH.'/assets/slick/slick.css');
$APPLICATION->SetAdditionalCss(SITE_TEMPLATE_PATH.'/assets/slick/slick-theme.css');

$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/jquery-3.3.1.min.js');
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/jquery.equalheights.min.js');
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/jquery.cookie.js');
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/assets/slick/slick.min.js');
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/script.js', 1);
/*$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH . '/js/shop.js', 1);*/

$globalJS = [
	"siteTemplateDir" => SITE_TEMPLATE_PATH,
];

?><!DOCTYPE html>
<html>
	<head>

		<title><? $APPLICATION->ShowTitle(); echo ($pagenum > 1 ? ' - '.Loc::getMessage("PAGE").' '.$pagenum : ""); ?></title>
		<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE = edge"><![endif]-->
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<? //$APPLICATION->ShowHead() ?>
		<?$APPLICATION->ShowMeta("keywords")?>
		<?$APPLICATION->ShowMeta("description")?>
		<?$APPLICATION->ShowMeta("robots")?>
		<?$APPLICATION->ShowCSS();?>
		<?$APPLICATION->ShowHeadStrings()?>
		<?$APPLICATION->ShowHeadScripts()?>
		<!--[if lt IE 9]>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv-printshiv.min.js" data-skip-moving="true"></script>
		<script src="http://css3-mediaqueries-js.googlecode.com/files/css3-mediaqueries.js" data-skip-moving="true"></script>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js" data-skip-moving="true"></script>
		<script data-skip-moving="true">
			document.createElement('header');
			document.createElement('nav');
			document.createElement('section');
			document.createElement('article');
			document.createElement('aside');
			document.createElement('footer');
			document.createElement('figure');
			document.createElement('hgroup');
			document.createElement('menu');
			document.createElement('time');
		</script>
		<![endif]-->

	</head>
	<body>
		<script>
			var globalJS = <?=CUtil::PhpToJSObject( $globalJS )?>
		</script>
		<div id="panel"><?$APPLICATION->ShowPanel();?></div>
		<header>
			<div class="section-block">

<?
$APPLICATION->IncludeComponent("bitrix:menu", "top", array(
		"ROOT_MENU_TYPE" => "top",
		"MAX_LEVEL" => "1",
		"CHILD_MENU_TYPE" => "",
		"USE_EXT" => "N",
		"MENU_CACHE_TYPE" => "A",
		"MENU_CACHE_TIME" => "3600",
		"MENU_CACHE_USE_GROUPS" => "Y",
		"MENU_CACHE_GET_VARS" => ""
	),
	false,
	array(
	"ACTIVE_COMPONENT" => "Y"
	)
);
?>

<?// add file before including
	/*$APPLICATION->IncludeFile(
		SITE_DIR."local/include/phones.php",
		[],
		["MODE" => "html", "NAME" => Loc::getMessage("PHONE_INCLUDE_AREA")]
	);*/
?>

<?$APPLICATION->IncludeComponent(
	"bitrix:system.auth.form",
	"header",
	Array(
		"REGISTER_URL" => SITE_DIR."auth/?register=yes",
		"FORGOT_PASSWORD_URL" => SITE_DIR."auth/?forgot_password=yes",
		"PROFILE_URL" => SITE_DIR."personal/",
		"SHOW_ERRORS" => "N",
	),
	false
);?>

				<?  // ÇÄÅÑÜ ÂÑÒÀÂÈÒÜ ÊÎÌÏÎÍÅÍÒ ÂÌÅÑÒÎ ÄÈÂÀ  ?>
				<div id="cart">
					<a href="/cart/">
						<?=Loc::getMessage("CART")?>
						(<span class="count"></span> - <span class="summ"></span> <?=Loc::getMessage("CURRENCY")?>)
					</a>
				</div>

				<?  /* full search */  /* ?>
				<?$APPLICATION->IncludeComponent("bitrix:search.form","",Array(
					                                                     "USE_SUGGEST" => "Y",
					                                                     "PAGE" => "#SITE_DIR#search/"
				                                                     )
				);?>
				<? */ /* full search */  ?>

<?  /* catalog search */  ?>
<?$APPLICATION->IncludeComponent(
	"bitrix:search.title", 
	".default", 
	array(
		"NUM_CATEGORIES" => "1",
		"TOP_COUNT" => "5",
		"CHECK_DATES" => "Y",
		"SHOW_OTHERS" => "N",
		"PAGE" => SITE_DIR."catalog/",
		"CATEGORY_0_TITLE" => Loc::getMessage("SEARCH_GOODS"),
		"CATEGORY_0" => array(
			0 => "iblock_catalog",
		),
		"CATEGORY_0_iblock_catalog" => array(
			0 => "#CATALOG_IBLOCK_ID#",
			1 => "#OFFERS_IBLOCK_ID#",
		),
		"CATEGORY_OTHERS_TITLE" => Loc::getMessage("SEARCH_OTHER"),
		"SHOW_INPUT" => "Y",
		"INPUT_ID" => "title-search-input",
		"CONTAINER_ID" => "topsearch_container",
		"PRICE_CODE" => array(
			0 => "BASE",
		),
		"SHOW_PREVIEW" => "Y",
		"PREVIEW_WIDTH" => "75",
		"PREVIEW_HEIGHT" => "75",
		"CONVERT_CURRENCY" => "Y",
		"COMPONENT_TEMPLATE" => ".default",
		"ORDER" => "rank",
		"USE_LANGUAGE_GUESS" => "N",
		"PRICE_VAT_INCLUDE" => "N",
		"PREVIEW_TRUNCATE_LEN" => "",
		"CURRENCY_ID" => "RUB",
		"CATEGORY_0_iblock_offers" => array(
		),
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO"
	),
	false
);?>
<?  /* /catalog search */  ?>

				<div class="modal-trigger search-toogler" data-modal="search-form">
					<img src="<?=SITE_TEMPLATE_PATH?>/images/search.svg">
				</div>

<?$APPLICATION->IncludeComponent("bitrix:menu", "top", array(
	"ROOT_MENU_TYPE" => "catalog",
	"MENU_CACHE_TYPE" => "A",
	"MENU_CACHE_TIME" => "3600",
	"MENU_CACHE_USE_GROUPS" => "Y",
	"MENU_CACHE_GET_VARS" => array(
	),
	"MAX_LEVEL" => "4",
	"CHILD_MENU_TYPE" => "catalog",
	"USE_EXT" => "Y",
	"DELAY" => "N",
	"ALLOW_MULTI_SELECT" => "N"
	),
	false
);?>

			</div>
		</header>
<? if (!$IS_MAIN_PAGE) { ?>
		<section>
			<div class="section-block content-head">

<?$APPLICATION->IncludeComponent("bitrix:breadcrumb", ".default", Array(
		"START_FROM" => "0",
		"PATH" => "",
		"SITE_ID" => SITE_ID,
	),
	false
);?>
				<h1><?$APPLICATION->ShowTitle(false);?></h1>
			</div>
		</section>
<?}?>
