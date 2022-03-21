<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

use Bitrix\Main\Loader;

function cartCount(){

	Loader::includeModule("sale");

	$rsBasketItems = CSaleBasket::GetList(
		array(),
		array( 
			"FUSER_ID" => CSaleBasket::GetBasketUserID(),
			"LID" => SITE_ID,
			"ORDER_ID" => "NULL",
			"!CAN_BUY" => "N"
		), 
		false,
		false,
		array("ID", "QUANTITY", "PRICE")
	);

	$result = Array("GOODS"=>0,"SUMM"=>0);

	while ($arItems = $rsBasketItems->Fetch()) {
		$result["GOODS"]++;
		$result["SUMM"] = $result["SUMM"] + ($arItems["PRICE"]*$arItems["QUANTITY"]);
	}
	return str_replace("'",'"',CUtil::PhpToJsObject($result));
}

if ($_POST['add2cart']) {
	$arProps = Array();
	Add2BasketByProductID($_POST['id'],$_POST['qty'],$arProps);
	echo cartCount();
}
if ($_POST['upcart']) {
	echo cartCount();
}

?>