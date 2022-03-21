<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Localization\Loc;

$mess_class = "form_error";
if ($arResult["MESSAGE_CODE"] == "E06") {
	$mess_class = "form_success";
}
?>
<section>
	<div class="section-block content bx-z-auth">
		<div class="<?=$mess_class?>"><?=$arResult["MESSAGE_TEXT"]?></div>
<?//here you can place your own messages
	/*switch($arResult["MESSAGE_CODE"])
	{
	case "E01":
		?><? //When user not found
		break;
	case "E02":
		?><? //User was successfully authorized after confirmation
		break;
	case "E03":
		?><? //User already confirm his registration
		break;
	case "E04":
		?><? //Missed confirmation code
		break;
	case "E05":
		?><? //Confirmation code provided does not match stored one
		break;
	case "E06":
		?><? //Confirmation was successfull
		break;
	case "E07":
		?><? //Some error occured during confirmation
		break;
	}*/
?>
<?if($arResult["SHOW_FORM"]):?>
	<form method="post" action="<?=$arResult["FORM_ACTION"]?>">
		<div class="z-form">
			<div class="form-group row align-items-center">
				<div class="col-sm-12 col-md-3 text-md-right">
					<?=Loc::getMessage("CT_BSAC_LOGIN")?>:
				</div>
				<div class="col-sm-12 col-md-9">
					<input type="text" name="<?=$arParams["LOGIN"]?>" maxlength="50" value="<?=$arResult["LOGIN"]?>">
				</div>
			</div>
			<div class="form-group row align-items-center">
				<div class="col-sm-12 col-md-3 text-md-right">
					<?=Loc::getMessage("CT_BSAC_CONFIRM_CODE")?>:
				</div>
				<div class="col-sm-12 col-md-9">
					<input type="text" name="<?=$arParams["CONFIRM_CODE"]?>" maxlength="50" value="<?=$arResult["CONFIRM_CODE"]?>">
				</div>
			</div>
			<div class="form-group buttons text-md-center">
				<input type="submit" value="<?=Loc::getMessage("CT_BSAC_CONFIRM")?>">
			</div>
		</div>
		<input type="hidden" name="<?=$arParams["USER_ID"]?>" value="<?=$arResult["USER_ID"]?>">
	</form>
<?elseif(!$USER->IsAuthorized()):?>
	<?$APPLICATION->IncludeComponent(
		"bitrix:system.auth.authorize",
		"",
		["INCLUDED" => "Y"]
	);?>
<?endif?>

	</div>
</section>