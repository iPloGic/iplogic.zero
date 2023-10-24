<?if(!check_bitrix_sessid()) return;?>
<? use \Bitrix\Main\Localization\Loc; ?>
<?
echo CAdminMessage::ShowNote(Loc::getMessage("IPLOGIC_MODULE_UNINSTALLED"));
?>
<form action="<?echo $APPLICATION->GetCurPage()?>">
	<?=bitrix_sessid_post()?>
	<input type="hidden" name="lang" value="<?echo LANG?>">
	<input type="submit" name="inst" value="<?echo Loc::getMessage("BACK")?>">
</form>