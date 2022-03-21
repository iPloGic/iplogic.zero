<?
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2014 Bitrix
 */

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponentTemplate $this
 */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

if($arResult["SHOW_SMS_FIELD"] == true)
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

		<?if($arResult["SHOW_EMAIL_SENT_CONFIRMATION"]):?>
			<div class="form_success"><?echo Loc::getMessage("AUTH_EMAIL_SENT")?></div>
		<?endif;?>

		<?if(!$arResult["SHOW_EMAIL_SENT_CONFIRMATION"] && $arResult["USE_EMAIL_CONFIRMATION"] === "Y"):?>
			<div class=""><?echo Loc::getMessage("AUTH_EMAIL_WILL_BE_SENT")?></div>
		<?endif?>

		<noindex>

		<?if($arResult["SHOW_SMS_FIELD"] == true):?>

			<form method="post" action="<?=$arResult["AUTH_URL"]?>" name="regform">
			<input type="hidden" name="SIGNED_DATA" value="<?=htmlspecialcharsbx($arResult["SIGNED_DATA"])?>">
			<div class="z-form">
				<div class="form-group row align-items-center">
					<div class="col-sm-12 col-md-3 text-md-right">
						<span class="starrequired">*</span><?=Loc::getMessage("main_register_sms_code")?>
					</div>
					<div class="col-sm-12 col-md-9">
						<input type="text" name="SMS_CODE" value="<?=htmlspecialcharsbx($arResult["SMS_CODE"])?>" autocomplete="off">
					</div>
				</div>
				<div class="form-group buttons text-md-center">
					<input type="submit" name="code_submit_button" value="<?=Loc::getMessage("main_register_sms_send")?>">
				</div>
			</div>
			</form>

			<script>
			new BX.PhoneAuth({
				containerId: 'bx_register_resend',
				errorContainerId: 'bx_register_error',
				interval: <?=$arResult["PHONE_CODE_RESEND_INTERVAL"]?>,
				data:
					<?=CUtil::PhpToJSObject([
						'signedData' => $arResult["SIGNED_DATA"],
					])?>,
				onError:
					function(response)
					{
						var errorDiv = BX('bx_register_error');
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

			<div id="bx_register_error form_error" style="display:none"><?ShowError("error")?></div>
			<div id="bx_register_resend"></div>

		<?elseif(!$arResult["SHOW_EMAIL_SENT_CONFIRMATION"]):?>

			<form method="post" action="<?=$arResult["AUTH_URL"]?>" name="bform" enctype="multipart/form-data">
				<input type="hidden" name="AUTH_FORM" value="Y">
				<input type="hidden" name="TYPE" value="REGISTRATION">

				<div class="z-form">

					<div class="form-group sub-title text-md-center">
						<h3><?=Loc::getMessage("AUTH_REGISTER")?></h3>
					</div>

					<div class="form-group row align-items-center">
						<div class="col-sm-12 col-md-3 text-md-right">
							<?=Loc::getMessage("AUTH_NAME")?>
						</div>
						<div class="col-sm-12 col-md-9">
							<input type="text" name="USER_NAME" maxlength="50" value="<?=$arResult["USER_NAME"]?>">
						</div>
					</div>
					<!--<div class="form-group row align-items-center">
						<div class="col-sm-12 col-md-3 text-md-right">
							<?=Loc::getMessage("AUTH_LAST_NAME")?>
						</div>
						<div class="col-sm-12 col-md-9">
							<input type="text" name="USER_LAST_NAME" maxlength="50" value="<?=$arResult["USER_LAST_NAME"]?>">
						</div>
					</div>-->
					<div class="form-group row align-items-center">
						<div class="col-sm-12 col-md-3 text-md-right">
							<span class="starrequired">*</span><?=Loc::getMessage("AUTH_LOGIN_MIN")?>
						</div>
						<div class="col-sm-12 col-md-9">
							<input type="text" name="USER_LOGIN" maxlength="50" value="<?=$arResult["USER_LOGIN"]?>">
						</div>
					</div>
					<div class="form-group row align-items-center">
						<div class="col-sm-12 col-md-3 text-md-right">
							<span class="starrequired">*</span><?=Loc::getMessage("AUTH_PASSWORD_REQ")?>
						</div>
						<div class="col-sm-12 col-md-9">
							<input type="password" name="USER_PASSWORD" maxlength="255" value="<?=$arResult["USER_PASSWORD"]?>" autocomplete="off">
							<?if($arResult["SECURE_AUTH"]):?>
								<span class="bx-auth-secure" id="bx_auth_secure" title="<?echo Loc::getMessage("AUTH_SECURE_NOTE")?>" style="display:none">
									<div class="bx-auth-secure-icon"></div>
								</span>
								<noscript>
									<span class="bx-auth-secure" title="<?echo Loc::getMessage("AUTH_NONSECURE_NOTE")?>">
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
							<span class="starrequired">*</span><?=Loc::getMessage("AUTH_CONFIRM")?>
						</div>
						<div class="col-sm-12 col-md-9">
							<input type="password" name="USER_CONFIRM_PASSWORD" maxlength="255" value="<?=$arResult["USER_CONFIRM_PASSWORD"]?>" autocomplete="off">
						</div>
					</div>

					<?if($arResult["EMAIL_REGISTRATION"]):?>
						<div class="form-group row align-items-center">
							<div class="col-sm-12 col-md-3 text-md-right">
								<?if($arResult["EMAIL_REQUIRED"]):?><span class="starrequired">*</span><?endif?><?=Loc::getMessage("AUTH_EMAIL")?>
							</div>
							<div class="col-sm-12 col-md-9">
								<input type="text" name="USER_EMAIL" maxlength="255" value="<?=$arResult["USER_EMAIL"]?>">
							</div>
						</div>
					<?endif?>

					<?if($arResult["PHONE_REGISTRATION"]):?>
						<div class="form-group row align-items-center">
							<div class="col-sm-12 col-md-3 text-md-right">
								<?if($arResult["PHONE_REQUIRED"]):?><span class="starrequired">*</span><?endif?><?=Loc::getMessage("main_register_phone_number")?>
							</div>
							<div class="col-sm-12 col-md-9">
								<input type="text" name="USER_PHONE_NUMBER" maxlength="255" value="<?=$arResult["USER_PHONE_NUMBER"]?>">
							</div>
						</div>
					<?endif?>

					<?// ********************* User properties ***************************************************?>
					<?if($arResult["USER_PROPERTIES"]["SHOW"] == "Y"):?>
						<div class="form-group sub-title text-md-center">
							<h3><?=trim($arParams["USER_PROPERTY_NAME"]) <> '' ? $arParams["USER_PROPERTY_NAME"] : Loc::getMessage("USER_TYPE_EDIT_TAB")?></h3>
						</div>
						<?foreach ($arResult["USER_PROPERTIES"]["DATA"] as $FIELD_NAME => $arUserField):?>
							<div class="form-group row align-items-center">
								<div class="col-sm-12 col-md-3 text-md-right">
									<?if ($arUserField["MANDATORY"]=="Y"):?><span class="starrequired">*</span><?endif;
									?><?=$arUserField["EDIT_FORM_LABEL"]?>:
								</div>
								<div class="col-sm-12 col-md-9">
									<?$APPLICATION->IncludeComponent(
											"bitrix:system.field.edit",
											$arUserField["USER_TYPE"]["USER_TYPE_ID"],
											array("bVarsFromForm" => $arResult["bVarsFromForm"], "arUserField" => $arUserField, "form_name" => "bform"), null, array("HIDE_ICONS"=>"Y"));?>
								</div>
							</div>
						<?endforeach;?>
					<?endif;?>
					<?// ******************** /User properties ***************************************************

					/* CAPTCHA */
					if ($arResult["USE_CAPTCHA"] == "Y")
					{ ?>
						<div class="form-group sub-title text-md-center">
							<h3><?=Loc::getMessage("CAPTCHA_REGF_TITLE")?></h3>
						</div>
						<div class="form-group row align-items-center">
							<div class="col-sm-12 col-md-6 text-md-right">
								<span class="starrequired">*</span><?=Loc::getMessage("CAPTCHA_REGF_PROMT")?>:
							</div>
							<div class="col-sm-12 col-md-6">
								<input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>">
								<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA">
							</div>
						</div>
						<div class="form-group row align-items-center">
							<div class="col-sm-12 col-md-6 offset-md-6 short">
								<input type="text" name="captcha_word" maxlength="50" value="" autocomplete="off">
							</div>
						</div>
					<? }
					/* CAPTCHA */
					?>
					<div class="form-group buttons text-md-center">
						<?$APPLICATION->IncludeComponent("bitrix:main.userconsent.request", "",
							array(
								"ID" => COption::getOptionString("main", "new_user_agreement", ""),
								"IS_CHECKED" => "Y",
								"AUTO_SAVE" => "N",
								"IS_LOADED" => "Y",
								"ORIGINATOR_ID" => $arResult["AGREEMENT_ORIGINATOR_ID"],
								"ORIGIN_ID" => $arResult["AGREEMENT_ORIGIN_ID"],
								"INPUT_NAME" => $arResult["AGREEMENT_INPUT_NAME"],
								"REPLACE" => array(
									"button_caption" => Loc::getMessage("AUTH_REGISTER"),
									"fields" => array(
										rtrim(Loc::getMessage("AUTH_NAME"), ":"),
										rtrim(Loc::getMessage("AUTH_LAST_NAME"), ":"),
										rtrim(Loc::getMessage("AUTH_LOGIN_MIN"), ":"),
										rtrim(Loc::getMessage("AUTH_PASSWORD_REQ"), ":"),
										rtrim(Loc::getMessage("AUTH_EMAIL"), ":"),
									)
								),
							)
						);?>
					</div>
					<div class="form-group buttons text-md-center">
						<input type="submit" name="Register" value="<?=Loc::getMessage("AUTH_REGISTER")?>">
					</div>
				</div>
			</form>

			<p><?=$arResult["GROUP_POLICY"]["PASSWORD_REQUIREMENTS"];?></p>
			<p><span class="starrequired">*</span> <?=Loc::getMessage("AUTH_REQ")?></p><br>

			<p><a href="<?=$arResult["AUTH_AUTH_URL"]?>" rel="nofollow"><?=Loc::getMessage("AUTH_AUTH")?></a></p>

			<script type="text/javascript">
			document.bform.USER_NAME.focus();
			</script>

		<?endif?>

		</noindex>
	</div>
</section>