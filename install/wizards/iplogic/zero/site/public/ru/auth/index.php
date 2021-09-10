<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Авторизация");
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
			"REGISTER_URL" => SITE_DIR."auth/registration/",	// Страница регистрации
			"FORGOT_PASSWORD_URL" => SITE_DIR."auth/restore/",	// Страница забытого пароля
			"PROFILE_URL" => SITE_DIR."personal/",	// Страница профиля
			"SHOW_ERRORS" => "Y",	// Показывать ошибки
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