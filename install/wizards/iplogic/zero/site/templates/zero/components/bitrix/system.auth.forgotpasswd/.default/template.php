<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?><?

use Bitrix\Main\Localization\Loc;

ShowMessage($arParams["~AUTH_RESULT"]);

?>
<form name="bform" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
<?
if (strlen($arResult["BACKURL"]) > 0)
{
?>
	<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
<?
}
?>
	<input type="hidden" name="AUTH_FORM" value="Y">
	<input type="hidden" name="TYPE" value="SEND_PWD">
	<p>
	<?=Loc::getMessage("AUTH_FORGOT_PASSWORD_1")?>
	</p>

<table class="data-table bx-forgotpass-table">
	<thead>
		<tr> 
			<td colspan="2"><b><?=Loc::getMessage("AUTH_GET_CHECK_STRING")?></b></td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><?=Loc::getMessage("AUTH_LOGIN")?></td>
			<td><input type="text" name="USER_LOGIN" maxlength="50" value="<?=$arResult["LAST_LOGIN"]?>" />&nbsp;<?=Loc::getMessage("AUTH_OR")?>
			</td>
		</tr>
		<tr> 
			<td><?=Loc::getMessage("AUTH_EMAIL")?></td>
			<td>
				<input type="text" name="USER_EMAIL" maxlength="255" />
			</td>
		</tr>
	<?if($arResult["USE_CAPTCHA"]):?>
		<tr>
			<td></td>
			<td>
				<input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
				<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
			</td>
		</tr>
		<tr>
			<td><?echo Loc::getMessage("system_auth_captcha")?></td>
			<td><input type="text" name="captcha_word" maxlength="50" value="" /></td>
		</tr>
	<?endif?>
	</tbody>
	<tfoot>
		<tr> 
			<td colspan="2">
				<input type="submit" name="send_account_info" value="<?=Loc::getMessage("AUTH_SEND")?>" />
			</td>
		</tr>
	</tfoot>
</table>
<p>
<a href="/auth/"><b><?=Loc::getMessage("AUTH_AUTH")?></b></a>
</p> 
</form>
<script type="text/javascript">
document.bform.USER_LOGIN.focus();
</script>
