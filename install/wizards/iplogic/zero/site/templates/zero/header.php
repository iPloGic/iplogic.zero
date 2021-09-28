<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?

use Bitrix\Main\Config\Option,
	Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

if(isset($_GET['PAGEN_3']) && !empty($_GET['PAGEN_3']) )
	$pagenum = $_GET['PAGEN_3'];

// Let's init $IS_MAIN_PAGE variable
$IS_MAIN_PAGE = false;
if (strtolower($APPLICATION->GetCurPage(true)) == LANG_DIR."index.php") $IS_MAIN_PAGE = true;
// section
$arURILine = explode('/',$APPLICATION->GetCurPage());
$SECTION = $arURILine[1];
// static
/*$nonstatic = Array('catalog','personal','brands');
$IS_STATIC = true;
if ($IS_MAIN_PAGE || in_array($SECTION, $nonstatic))
	$IS_STATIC = false;*/

?><!DOCTYPE html>
<html lang="ru-RU">
	<head>
		<meta charset="utf-8">
		<title><? if($pagenum > 1) { $APPLICATION->ShowTitle(); echo ' : '.$_GET['PAGEN_3'].' страница'; } else { $APPLICATION->ShowTitle(); }?></title><!--[if IE]>
		<meta http-equiv="X-UA-Compatible" content="IE = edge"><![endif]-->
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<? $APPLICATION->ShowHead() ?>
		<?// $APPLICATION->ShowMeta("keywords") ?> <?// $APPLICATION->ShowMeta("description") ?> <?// $APPLICATION->ShowCSS();?> <?//$APPLICATION->ShowHeadScripts()?>
		<link href="<?=SITE_TEMPLATE_PATH?>/assets/bootstrap/css/bootstrap.css" rel="stylesheet" />
		<link rel="stylesheet" type="text/css" href="<?=SITE_TEMPLATE_PATH?>/styles.css"><!--[if lt IE 9]>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv-printshiv.min.js" data-skip-moving="true"></script><![endif]-->
		<? $APPLICATION->ShowHeadStrings() ?>

		<!--[if lt IE 9]>
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
		<div id="panel"><?$APPLICATION->ShowPanel();?></div>
		<header>
			<div class="container-block">

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

<?$APPLICATION->IncludeFile(SITE_DIR."bitrix/include/phones.php", Array(), Array("MODE" => "html", "NAME" => Loc::getMessage("PHONE_INCLUDE_AREA"), ));?>

<?$APPLICATION->IncludeComponent("bitrix:system.auth.form", "header", Array(
		"REGISTER_URL" => SITE_DIR."auth/registration/",	// —траница регистрации
		"FORGOT_PASSWORD_URL" => SITE_DIR."auth/restore/",	// —траница забытого парол€
		"PROFILE_URL" => SITE_DIR."personal/",	// —траница профил€
		"SHOW_ERRORS" => "N",	// ѕоказывать ошибки
	),
	false
);?>

				<div id="cart"><a href="/cart/"><?=Loc::getMessage("CART")?> (<span class="count"></span> - <span class="summ"></span> <?=Loc::getMessage("CURRENCY")?>)</a></div>

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

<?$APPLICATION->IncludeComponent("bitrix:menu", "catalog", array(
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
		<section>
			<div class="container-block">
<? if (!$IS_MAIN_PAGE) { ?>
<?$APPLICATION->IncludeComponent("bitrix:breadcrumb", ".default", Array(
		"START_FROM" => "0",	// Ќомер пункта, начина€ с которого будет построена навигационна€ цепочка
		"PATH" => "",	// ѕуть, дл€ которого будет построена навигационна€ цепочка (по умолчанию, текущий путь)
		"SITE_ID" => SITE_ID,	// Cайт (устанавливаетс€ в случае многосайтовой версии, когда DOCUMENT_ROOT у сайтов разный)
	),
	false
);?>
				<h1><?$APPLICATION->ShowTitle(false);?></h1>
<?}?>
