<?
define ("NEED_AUTH", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Authorization");
?>

<?
$redirect = "/";
if( !empty( $_REQUEST["backurl"] ) ){
	$redirect = $_REQUEST["backurl"];
}else{
	$redirect = SITE_DIR.'personal/';
	if ( !empty( $_REQUEST["logout"]) && $_REQUEST["logout"] == "yes" ) {
		$redirect = $_SERVER['HTTP_REFERER'];
	}
}
if( !empty( $_REQUEST["logout"] ) && $_REQUEST["logout"] == "yes" ){
	$USER->Logout();
	LocalRedirect($redirect);
}else{
	LocalRedirect($redirect);
}?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>