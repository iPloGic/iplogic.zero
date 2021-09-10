<?

use Bitrix\Main\Loader,
	Bitrix\Main\Localization\Loc;

Loader::registerAutoLoadClasses(
	'iplogic.zero', 
	array(
		"Iplogic\Zero\CIplogicZero" =>  "lib/CIplogicZero.php",
		"Iplogic\Zero\CIplogicLogger" =>  "lib/CIplogicLogger.php",
	)
);

?>
