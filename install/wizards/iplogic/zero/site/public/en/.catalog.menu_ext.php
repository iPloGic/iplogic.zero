<?
if( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true ) {
	die();
}
global $APPLICATION;

$aMenuLinksExt = $APPLICATION->IncludeComponent(
	"bitrix:menu.sections",
	"",
	[
		"IS_SEF"           => "Y",
		"SEF_BASE_URL"     => "/catalog/",
		"SECTION_PAGE_URL" => "#SECTION_CODE_PATH#/",
		"DETAIL_PAGE_URL"  => "#SECTION_CODE_PATH#/#ELEMENT_CODE#/",
		"IBLOCK_TYPE"      => "catalog",
		"IBLOCK_ID"        => "#CATALOG_IBLOCK_ID#", // catalog iblock ID
		"DEPTH_LEVEL"      => "4",
		"CACHE_TYPE"       => "A",
		"CACHE_TIME"       => "3600",
	],
	false
);
$aMenuLinks = array_merge($aMenuLinks, $aMenuLinksExt);
?>