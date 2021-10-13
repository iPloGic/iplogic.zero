<?

use Bitrix\Main\Loader;

if (file_exists(__DIR__."/functions.php"))
	require_once(__DIR__."/functions.php");

Loader::registerAutoLoadClasses(
	'iplogic.zero', 
	[
		"Iplogic\Zero\Agent"    => "lib/agent.php",
		"Iplogic\Zero\Helper"   => "lib/helper.php",
		"Iplogic\Zero\Catalog"  => "lib/catalog.php",
		"Iplogic\Zero\Cli"      => "lib/cli.php",

		"Iplogic\Zero\Exchange\Base"                    => "lib/exchange/base.php",
		"Iplogic\Zero\Exchange\ParseXML"                => "lib/exchange/parsexml.php",
		"Iplogic\Zero\Exchange\PriceAndStockFromXML"    => "lib/exchange/prstfromxml.php",
		"Iplogic\Zero\Exchange\ExportXML"               => "lib/exchange/exportxml.php",
		"Iplogic\Zero\Exchange\GoogleMerchant"          => "lib/exchange/googlemerchant.php",
	]
);