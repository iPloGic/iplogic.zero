<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;
?>
<section>
	<div class="section-block content bx-auth">

		<?if($arParams["~AUTH_RESULT"] && !is_array($arParams["~AUTH_RESULT"])):?>
			<div class=""><?=$arParams["~AUTH_RESULT"]?></div>
		<?elseif($arParams["~AUTH_RESULT"] && is_array($arParams["~AUTH_RESULT"])):?>
			<?// echo "<pre>"; print_r($arParams["~AUTH_RESULT"]); echo "</pre>"; ?>
			<?if($arParams["~AUTH_RESULT"]["TYPE"] == "ERROR"):?>
				<div class="form_error">
					<?=$arParams["~AUTH_RESULT"]["MESSAGE"]?>
				</div>
			<?else:?>
				<div class="form_success">
					<?=$arParams["~AUTH_RESULT"]["MESSAGE"]?>
				</div>
			<?endif;?>
		<?endif;?>

		<form name="bform" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
		<? if ($arResult["BACKURL"] <> '') { ?>
			<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
		<? } ?>
			<input type="hidden" name="AUTH_FORM" value="Y">
			<input type="hidden" name="TYPE" value="SEND_PWD">

			<div class="z-form">
				<p><?echo Loc::getMessage("sys_forgot_pass_label")?></p>

				<div class="form-group row align-items-center" style="margin-top: 16px">
					<div class="col-sm-12 col-md-3 text-md-right">
						<?=Loc::getMessage("sys_forgot_pass_login1")?>
					</div>
					<div class="col-sm-12 col-md-9">
						<input type="text" name="USER_LOGIN" value="<?=$arResult["USER_LOGIN"]?>" />
						<input type="hidden" name="USER_EMAIL" />
					</div>
				</div>
				<div class="form-group row align-items-center">
					<div class="col-sm-12 col-md-9 offset-md-3 short">
						<?echo Loc::getMessage("sys_forgot_pass_note_email")?>
					</div>
				</div>
				<?if($arResult["PHONE_REGISTRATION"]):?>
					<div class="form-group row align-items-center">
						<div class="col-sm-12 col-md-3 text-md-right">
							<?=Loc::getMessage("sys_forgot_pass_phone")?>
						</div>
						<div class="col-sm-12 col-md-9">
							<input type="text" name="USER_PHONE_NUMBER" value="<?=$arResult["USER_PHONE_NUMBER"]?>">
						</div>
					</div>
					<div class="form-group row align-items-center">
						<div class="col-sm-12 col-md-9 offset-md-3 short">
							<?echo Loc::getMessage("sys_forgot_pass_note_phone")?>
						</div>
					</div>
				<?endif;?>
				<?if($arResult["USE_CAPTCHA"]):?>
					<div class="form-group row align-items-center">
						<div class="col-sm-12 col-md-3 text-md-right">
							<?=Loc::getMessage("system_auth_captcha")?>:
						</div>
						<div class="col-sm-12 col-md-9">
							<input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>">
							<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA">
						</div>
					</div>
					<div class="form-group row align-items-center">
						<div class="col-sm-12 col-md-6 offset-md-3 short">
							<input type="text" name="captcha_word" maxlength="50" value="" autocomplete="off">
						</div>
					</div>
				<?endif?>
				<div class="form-group buttons text-md-center">
					<input type="submit" name="send_account_info" value="<?=Loc::getMessage("AUTH_SEND")?>">
				</div>

				<div style="margin-top: 16px">
					<p><a href="<?=$arResult["AUTH_AUTH_URL"]?>"><?=Loc::getMessage("AUTH_AUTH")?></a></p>
				</div>
			</div>
		</form>

	</div>
</section>

<script type="text/javascript">
document.bform.onsubmit = function(){document.bform.USER_EMAIL.value = document.bform.USER_LOGIN.value;};
document.bform.USER_LOGIN.focus();
</script>
