<?

use Bitrix\Main\Loader;

Loader::includeModule("iplogic.zero");
define("ZERO_IBLOCK_MODULE_INCLUDED", Loader::includeModule("iblock"));
define("ZERO_CATALOG_MODULE_INCLUDED", Loader::includeModule("catalog"));
define("ZERO_SALE_MODULE_INCLUDED", Loader::includeModule("sale"));

$eventManager = \Bitrix\Main\EventManager::getInstance();

if( file_exists($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/iplogic.zero/functions.php") ) {
	require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/iplogic.zero/functions.php");
}