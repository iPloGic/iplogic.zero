<?

use Bitrix\Main\Loader;

Loader::includeModule("iplogic.zero");
define("ZERO_IBLOCK_MODULE_INCLUDED", Loader::includeModule("iblock"));
define("ZERO_CATALOG_MODULE_INCLUDED", Loader::includeModule("catalog"));
define("ZERO_SALE_MODULE_INCLUDED", Loader::includeModule("sale"));

if (file_exists(__DIR__."/functions.php"))
	require_once(__DIR__."/functions.php");

if (file_exists(__DIR__."/events.php"))
	require_once(__DIR__."/events.php");