<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("�����������");
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
?>

<?if(!$USER->IsAuthorized()){ ?>
	<?$APPLICATION->IncludeComponent("bitrix:system.auth.form", "", Array(
			"REGISTER_URL" => SITE_DIR."auth/registration/",	// �������� �����������
			"FORGOT_PASSWORD_URL" => SITE_DIR."auth/restore/",	// �������� �������� ������
			"PROFILE_URL" => SITE_DIR."personal/",	// �������� �������
			"SHOW_ERRORS" => "Y",	// ���������� ������
		),
		false
	);?>
<?
}elseif( !empty( $_REQUEST["logout"] ) && $_REQUEST["logout"] == "yes" ){
	$USER->Logout();
	LocalRedirect($redirect);
}else{
	LocalRedirect($redirect);
}?>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>