<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("��������� ������");
?>

<?
if(!$USER->IsAuthorized())
{
	$APPLICATION->IncludeComponent( "bitrix:system.auth.changepasswd","",false );
}
else
{
	LocalRedirect(SITE_DIR.'personal/');
}
?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>