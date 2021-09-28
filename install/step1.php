<?if(!check_bitrix_sessid()) return;?>
<? use \Bitrix\Main\Localization\Loc; ?>
<form action="<?echo $APPLICATION->GetCurPage()?>">
	<?=bitrix_sessid_post()?>
	<input type="hidden" name="lang" value="<?echo LANG?>">
	<input type="hidden" name="id" value="iplogic.zero">
	<input type="hidden" name="install" value="Y">
	<input type="hidden" name="step" value="2">
	<?
	echo CAdminMessage::ShowNote(Loc::getMessage("IPLOGIC_MODULE_INSTALLED_TEXT"));
	?>
	<p><input type="checkbox" name="installcuagent" id="installcuagent" value="Y">
		<label for="installcuagent"><?echo Loc::getMessage("MOD_INSTALL_CU_AGENT")?></label></p>
	<p><input type="checkbox" name="installcragent" id="installcragent" value="Y">
		<label for="installcragent"><?echo Loc::getMessage("MOD_INSTALL_CR_AGENT")?></label></p>
	<input type="submit" name="inst" value="<?echo Loc::getMessage("MOD_INSTALL")?><?//echo Loc::getMessage("FINISH")?>">
</form>