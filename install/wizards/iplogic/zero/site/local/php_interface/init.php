<?

use Bitrix\Main\Loader;

//$SALE_MODULE_INCLUDED = Loader::includeModule("sale");
//Loader::includeModule("catalog");
//Loader::includeModule("iblock");

$eventManager = \Bitrix\Main\EventManager::getInstance();

if (file_exists(__DIR__."/functions.php"))
	require_once(__DIR__."/functions.php");