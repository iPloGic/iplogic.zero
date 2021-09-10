<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Config\Option,
	Bitrix\Main\Localization\Loc;
?>

<?if($arResult["FORM_TYPE"] == "login"):?>

	<a href="/auth/"><i class="fas fa-sign-in-alt"></i> <?=Loc::getMessage("AUTH_LOGIN_BUTTON")?></a>&nbsp;
	<a href="<?=$arParams["REGISTER_URL"]?>"><i class="fa fa-user-plus"></i> <?=Loc::getMessage("AUTH_REGISTER")?></a>

<?
else:
?>

	<a href="<?=$arParams["PROFILE_URL"]?>"><i class="fa fa-user"></i> <?=Loc::getMessage("AUTH_PRIVATE")?></a>&nbsp;
	<a href="/auth/?logout=yes"><i class="fas fa-sign-out-alt"></i> <?=Loc::getMessage("AUTH_LOGOUT_BUTTON")?></a>

<?endif?>