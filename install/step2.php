<?if(!check_bitrix_sessid()) return;?>
<? use \Bitrix\Main\Localization\Loc; ?>
<?
echo CAdminMessage::ShowNote(Loc::getMessage("IPLOGIC_MODULE_INSTALLED"));
?>
<a href="/bitrix/admin/partner_modules.php"><button style="padding:7px;"><?=Loc::getMessage("BACK")?></button></a>