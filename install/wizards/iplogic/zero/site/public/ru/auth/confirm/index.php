<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Подтверждение регистрации");

if(!$USER->IsAuthorized()) {?>

<?$APPLICATION->IncludeComponent(
	"bitrix:system.auth.confirmation",
	"",
	Array(
		"CONFIRM_CODE" => "confirm_code",
		"LOGIN" => "login",
		"USER_ID" => "confirm_user_id"
	)
);?>

<?} else { LocalRedirect(SITE_DIR.'personal/'); } ?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>