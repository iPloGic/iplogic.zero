<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;
?>

<?if($arParams["INCLUDED"] != "Y"):?>
<section>
	<div class="section-block content bx-z-auth">
<?endif?>

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

		<?if($arParams["ERROR_MESSAGE"]):?>
			<div class="form_error">
				<?=$arParams["ERROR_MESSAGE"]?>
			</div>
		<?endif;?>

		<form name="form_auth" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">

			<input type="hidden" name="AUTH_FORM" value="Y" />
			<input type="hidden" name="TYPE" value="AUTH" />
			<?if ($arResult["BACKURL"] <> ''):?>
			<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
			<?endif?>
			<?foreach ($arResult["POST"] as $key => $value):?>
			<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
			<?endforeach?>

			<div class="z-form">
				<div class="form-group row align-items-center">
					<div class="col-sm-12 col-md-3 text-md-right">
						<?=Loc::getMessage("AUTH_LOGIN")?>
					</div>
					<div class="col-sm-12 col-md-9">
						<input type="text" name="USER_LOGIN" maxlength="255" value="<?=$arResult["LAST_LOGIN"]?>">
					</div>
				</div>
				<div class="form-group row align-items-center">
					<div class="col-sm-12 col-md-3 text-md-right">
						<?=Loc::getMessage("AUTH_PASSWORD")?>
					</div>
					<div class="col-sm-12 col-md-9">
						<input type="password" name="USER_PASSWORD" maxlength="255" autocomplete="off">
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
				<?if($arResult["CAPTCHA_CODE"]):?>
					<div class="form-group row align-items-center">
						<div class="col-sm-12 col-md-3 text-md-right">
							<?=Loc::getMessage("AUTH_CAPTCHA_PROMT")?>:
						</div>
						<div class="col-sm-12 col-md-9">
							<input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>">
							<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA">
						</div>
					</div>
					<div class="form-group row align-items-center">
						<div class="col-sm-12 col-md-9 offset-md-3 short">
							<input type="text" name="captcha_word" maxlength="50" value="" autocomplete="off">
						</div>
					</div>
				<?endif;?>
				<?if ($arResult["STORE_PASSWORD"] == "Y"):?>
					<div class="form-group row align-items-center">
						<div class="col-sm-12 offset-md-3 col-md-9">
							<input type="checkbox" id="USER_REMEMBER_frm" name="USER_REMEMBER" value="Y">
							<label for="USER_REMEMBER" title="<?=Loc::getMessage("AUTH_REMEMBER_ME")?>">
								<?echo Loc::getMessage("AUTH_REMEMBER_SHORT")?>
							</label>
						</div>
					</div>
				<?endif?>
				<div class="form-group buttons text-md-center">
					<input type="submit" name="Login" value="<?=Loc::getMessage("AUTH_AUTHORIZE")?>">
				</div>
				<?if ($arParams["NOT_SHOW_LINKS"] != "Y"):?>
						<noindex>
							<p>
								<a href="<?=$arResult["AUTH_FORGOT_PASSWORD_URL"]?>" rel="nofollow"><?=Loc::getMessage("AUTH_FORGOT_PASSWORD_2")?></a>
							</p>
						</noindex>
				<?endif?>
				<?if($arParams["NOT_SHOW_LINKS"] != "Y" && $arResult["NEW_USER_REGISTRATION"] == "Y" && $arParams["AUTHORIZE_REGISTRATION"] != "Y"):?>
						<noindex>
							<p>
								<a href="<?=$arResult["AUTH_REGISTER_URL"]?>" rel="nofollow"><?=Loc::getMessage("AUTH_REGISTER")?></a><br />
								<?//=Loc::getMessage("AUTH_FIRST_ONE")?>
							</p>
						</noindex>
				<?endif?>
				<?if($arResult["AUTH_SERVICES"]):?>
					<br>
					<?
					$APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "",
					                               array(
						                               "AUTH_SERVICES" => $arResult["AUTH_SERVICES"],
						                               "CURRENT_SERVICE" => $arResult["CURRENT_SERVICE"],
						                               "AUTH_URL" => $arResult["AUTH_URL"],
						                               "POST" => $arResult["POST"],
						                               "SHOW_TITLES" => $arResult["FOR_INTRANET"]?'N':'Y',
						                               "FOR_SPLIT" => $arResult["FOR_INTRANET"]?'Y':'N',
						                               "AUTH_LINE" => $arResult["FOR_INTRANET"]?'N':'Y',
					                               ),
					                               $component,
					                               array("HIDE_ICONS"=>"Y")
					);
					?>
				<?endif?>
			</div>
		</form>
<?if($arParams["INCLUDED"] != "Y"):?>
	</div>
</section>
<?endif?>

<script type="text/javascript">
<?if ($arResult["LAST_LOGIN"] <> ''):?>
	try{document.form_auth.USER_PASSWORD.focus();}catch(e){}
<?else:?>
	try{document.form_auth.USER_LOGIN.focus();}catch(e){}
<?endif?>
</script>
