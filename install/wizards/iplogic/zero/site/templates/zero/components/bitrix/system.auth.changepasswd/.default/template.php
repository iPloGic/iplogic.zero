<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

if($arResult["PHONE_REGISTRATION"])
{
	CJSCore::Init('phone_auth');
}
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

		<?if($arResult["SHOW_FORM"]):?>

			<form method="post" action="<?=$arResult["AUTH_URL"]?>" name="bform">
				<?if ($arResult["BACKURL"] <> ''): ?>
					<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>">
				<? endif ?>
				<input type="hidden" name="AUTH_FORM" value="Y">
				<input type="hidden" name="TYPE" value="CHANGE_PWD">

				<div class="z-form">

					<?if($arResult["PHONE_REGISTRATION"]):?>
						<div class="form-group row align-items-center">
							<div class="col-sm-12 col-md-3 text-md-right">
								<?=Loc::getMessage("sys_auth_chpass_phone_number")?>
							</div>
							<div class="col-sm-12 col-md-9">
								<input type="text" value="<?=htmlspecialcharsbx($arResult["USER_PHONE_NUMBER"])?>" disabled="disabled">
								<input type="hidden" name="USER_PHONE_NUMBER" value="<?=htmlspecialcharsbx($arResult["USER_PHONE_NUMBER"])?>">
							</div>
						</div>
						<div class="form-group row align-items-center">
							<div class="col-sm-12 col-md-3 text-md-right">
								<span class="starrequired">*</span><?=Loc::getMessage("sys_auth_chpass_code")?>
							</div>
							<div class="col-sm-12 col-md-9">
								<input type="text" name="USER_CHECKWORD" maxlength="50" value="<?=$arResult["USER_CHECKWORD"]?>" autocomplete="off">
							</div>
						</div>
					<?else:?>
						<div class="form-group row align-items-center">
							<div class="col-sm-12 col-md-3 text-md-right" for="USER_PASSWORD">
								<span class="starrequired">*</span><?=Loc::getMessage("AUTH_LOGIN")?>
							</div>
							<div class="col-sm-12 col-md-9">
								<input type="text" name="USER_LOGIN" maxlength="50" value="<?=$arResult["LAST_LOGIN"]?>">
							</div>
						</div>
						<?if($arResult["USE_PASSWORD"]):?>
							<div class="form-group row align-items-center">
								<div class="main-profile-form-label col-sm-12 col-md-3 text-md-right" for="USER_PASSWORD">
									<span class="starrequired">*</span><?=Loc::getMessage("sys_auth_changr_pass_current_pass")?>
								</div>
								<div class="col-sm-12 col-md-9">
									<input type="password" name="USER_CURRENT_PASSWORD" maxlength="255" value="<?=$arResult["USER_CURRENT_PASSWORD"]?>" autocomplete="new-password">
								</div>
							</div>
						<?else:?>
							<div class="form-group row align-items-center">
								<div class="col-sm-12 col-md-3 text-md-right">
									<span class="starrequired">*</span><?=Loc::getMessage("AUTH_CHECKWORD")?>
								</div>
								<div class="col-sm-12 col-md-9">
									<input type="text" name="USER_CHECKWORD" maxlength="50" value="<?=$arResult["USER_CHECKWORD"]?>" autocomplete="off">
								</div>
							</div>
						<?endif?>
					<?endif?>
					<div class="form-group row align-items-center">
						<div class="col-sm-12 col-md-3 text-md-right" for="USER_PASSWORD">
							<span class="starrequired">*</span><?=Loc::getMessage("AUTH_NEW_PASSWORD_REQ")?>
						</div>
						<div class="col-sm-12 col-md-9">
							<input type="password" name="USER_PASSWORD" maxlength="255" value="<?=$arResult["USER_PASSWORD"]?>" autocomplete="new-password">
							<?if($arResult["SECURE_AUTH"]):?>
								<span class="bx-auth-secure" id="bx_auth_secure" title="<?=Loc::getMessage("AUTH_SECURE_NOTE")?>" style="display:none">
									<div class="bx-auth-secure-icon"></div>
								</span>
								<noscript>
									<span class="bx-auth-secure" title="<?=Loc::getMessage("AUTH_NONSECURE_NOTE")?>">
										<div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
									</span>
								</noscript>
								<script type="text/javascript">
									document.getElementById('bx_auth_secure').style.display = 'inline-block';
								</script>
							<?endif?>
						</div>
					</div>
					<div class="form-group row align-items-center">
						<div class="col-sm-12 col-md-3 text-md-right">
							<span class="starrequired">*</span><?=Loc::getMessage("AUTH_NEW_PASSWORD_CONFIRM")?>
						</div>
						<div class="col-sm-12 col-md-9">
							<input type="password" name="USER_CONFIRM_PASSWORD" maxlength="255" value="<?=$arResult["USER_CONFIRM_PASSWORD"]?>" autocomplete="new-password">
						</div>
					</div>
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
						<input type="submit" name="change_pwd" value="<?=Loc::getMessage("AUTH_CHANGE")?>">
					</div>
				</div>
			</form>

			<p><?=$arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?></p>
			<p><span class="starrequired">*</span> <?=Loc::getMessage("AUTH_REQ")?></p>

			<?if($arResult["PHONE_REGISTRATION"]):?>
				<script type="text/javascript">
				new BX.PhoneAuth({
					containerId: 'bx_chpass_resend',
					errorContainerId: 'bx_chpass_error',
					interval: <?=$arResult["PHONE_CODE_RESEND_INTERVAL"]?>,
					data:
						<?=CUtil::PhpToJSObject([
							'signedData' => $arResult["SIGNED_DATA"]
						])?>,
					onError:
						function(response)
						{
							var errorDiv = BX('bx_chpass_error');
							var errorNode = BX.findChildByClassName(errorDiv, 'errortext');
							errorNode.innerHTML = '';
							for(var i = 0; i < response.errors.length; i++)
							{
								errorNode.innerHTML = errorNode.innerHTML + BX.util.htmlspecialchars(response.errors[i].message) + '<br>';
							}
							errorDiv.style.display = '';
						}
				});
				</script>
				<div id="bx_chpass_error" style="display:none"><?ShowError("error")?></div>
				<div id="bx_chpass_resend"></div>
			<?endif?>
		<?endif?>

		<p><a href="<?=$arResult["AUTH_AUTH_URL"]?>"><?=Loc::getMessage("AUTH_AUTH")?></a></p>

	</div>
</section>