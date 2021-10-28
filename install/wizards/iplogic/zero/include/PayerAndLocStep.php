<?php
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;

class PayerAndLocStep extends CWizardStep
{
	function InitStep()
	{
		$this->SetStepID("payer_and_loc");

		$this->SetPrevStep("shop_settings");
		$this->SetNextStep("special_settings");

		$this->SetTitle(Loc::getMessage("WIZ_STEP_PL"));
		$this->SetNextCaption(Loc::getMessage("NEXT_BUTTON"));
		$this->SetPrevCaption(Loc::getMessage("PREVIOUS_BUTTON"));

		$wizard =& $this->GetWizard();

		$shopLocalization = $wizard->GetVar("shopLocalization", true);
		$siteID = getSite($wizard)["ID"];

		if ($shopLocalization == "ua")
			$wizard->SetDefaultVars(
				[
					"personType" => [
						"fiz" => "Y",
						"fiz_ua" => "Y",
						"ur" => "Y",
					]
				]
			);
		else
			$wizard->SetDefaultVars(
				[
					"personType" => [
						"fiz" =>  Option::get("zero", "personTypeFiz", "Y", $siteID),
						"ur" => Option::get("zero", "personTypeUr", "Y", $siteID),
					]
				]
			);

	}

	function ShowStep()
	{
		$wizard =& $this->GetWizard();

		$siteID = getSite($wizard)["ID"];

		$shopLocalization = $wizard->GetVar("shopLocalization", true);

		$this->content .= '<div class="wizard-input-form">';
		$this->content .= '
		<div class="wizard-input-form-block">
			<div class="wizard-catalog-title">'.Loc::getMessage("WIZ_PERSON_TYPE_TITLE").'</div>
			<div style="padding-top:15px">
				<div class="wizard-input-form-field wizard-input-form-field-checkbox">
					<div class="wizard-catalog-form-item">
						'.$this->ShowCheckboxField('personType[fiz]', 'Y', (array("id" => "personTypeF"))).
			' <label for="personTypeF">'.Loc::getMessage("WIZ_PERSON_TYPE_FIZ").'</label><br />
					</div>
					<div class="wizard-catalog-form-item">
						'.$this->ShowCheckboxField('personType[ur]', 'Y', (array("id" => "personTypeU"))).
			' <label for="personTypeU">'.Loc::getMessage("WIZ_PERSON_TYPE_UR").'</label><br />
					</div>';
		if ($shopLocalization == "ua")
			$this->content .=
				'<div class="wizard-catalog-form-item">'
				.$this->ShowCheckboxField('personType[fiz_ua]', 'Y', (array("id" => "personTypeFua"))).
				' <label for="personTypeFua">'.Loc::getMessage("WIZ_PERSON_TYPE_FIZ_UA").'</label>
					</div>';
		$this->content .= '
				</div>
			</div>
			<div class="wizard-catalog-form-item">'.Loc::getMessage("WIZ_PERSON_TYPE").'<div>
		</div>';
		$this->content .= '</div>';

		$this->content .= '
		<div>
			<div class="wizard-catalog-title">'.Loc::getMessage("WIZ_LOCATION_TITLE").'</div>
			<div>
				<div class="wizard-input-form-field wizard-input-form-field-checkbox">';
		$this->content .=
			'<div class="wizard-catalog-form-item">'.
			$this->ShowRadioField("locations_csv", "loc_ussr.csv", array("id" => "loc_ussr", "checked" => "checked"))
			." <label for=\"loc_ussr\">".Loc::getMessage('WSL_STEP2_GFILE_USSR')."</label>
			</div>";
		$this->content .=
			'<div class="wizard-catalog-form-item">'.
			$this->ShowRadioField("locations_csv", "loc_ua.csv", array("id" => "loc_ua"))
			." <label for=\"loc_ua\">".Loc::getMessage('WSL_STEP2_GFILE_UA')."</label>
			</div>";
		$this->content .=
			'<div class="wizard-catalog-form-item">'.
			$this->ShowRadioField("locations_csv", "loc_kz.csv", array("id" => "loc_kz"))
			." <label for=\"loc_kz\">".Loc::getMessage('WSL_STEP2_GFILE_KZ')."</label>
			</div>";
		$this->content .=
			'<div class="wizard-catalog-form-item">'.
			$this->ShowRadioField("locations_csv", "loc_usa.csv", array("id" => "loc_usa"))
			." <label for=\"loc_usa\">".Loc::getMessage('WSL_STEP2_GFILE_USA')."</label>
			</div>";
		$this->content .=
			'<div class="wizard-catalog-form-item">'.
			$this->ShowRadioField("locations_csv", "loc_cntr.csv", array("id" => "loc_cntr"))
			." <label for=\"loc_cntr\">".Loc::getMessage('WSL_STEP2_GFILE_CNTR')."</label>
			</div>";
		$this->content .=
			'<div class="wizard-catalog-form-item">'.
			$this->ShowRadioField("locations_csv", "", array("id" => "none"))
			." <label for=\"none\">".Loc::getMessage('WSL_STEP2_GFILE_NONE')."</label>
			</div>";


		$this->content .= '
		<div>
			<div class="wizard-catalog-title">'.Loc::getMessage("WIZ_PAYMENT_TITLE").'</div>
			<div>
				<div class="wizard-input-form-field wizard-input-form-field-checkbox">';
		$this->content .=
			'<div class="wizard-catalog-form-item">'
			.$this->ShowCheckboxField('paysystem[bill]', 'Y', (array("id" => "paysystemBill"))).
			' <label for="paysystemBill">'.Loc::getMessage("WIZ_PAYSYSTEM_BILL").'</label>
			</div>';
		$this->content .=
			'<div class="wizard-catalog-form-item">'
			.$this->ShowCheckboxField('paysystem[collect]', 'Y', (array("id" => "paysystemCollect"))).
			' <label for="paysystemCollect">'.Loc::getMessage("WIZ_PAYSYSTEM_COLLECT").'</label>
			</div>';
		if ($shopLocalization != "ua") {
			$this->content .=
				'<div class="wizard-catalog-form-item">'
				.$this->ShowCheckboxField('paysystem[sber]', 'Y', (array("id" => "paysystemSber"))).
				' <label for="paysystemSber">'.Loc::getMessage("WIZ_PAYSYSTEM_SBER").'</label>
				</div>';
		}
		else {
			$this->content .=
				'<div class="wizard-catalog-form-item">'
				.$this->ShowCheckboxField('paysystem[oshad]', 'Y', (array("id" => "paysystemOshad"))).
				' <label for="paysystemOshad">'.Loc::getMessage("WIZ_PAYSYSTEM_OSHAD").'</label>
				</div>';
		}
		$this->content .=
			'<div class="wizard-catalog-form-item">'
			.$this->ShowCheckboxField('paysystem[YMoney]', 'Y', (array("id" => "paysystemYMoney"))).
			' <label for="paysystemYMoney">'.Loc::getMessage("WIZ_PAYSYSTEM_YMONEY").'</label>
			</div>';
		$this->content .=
			'<div class="wizard-catalog-form-item">'
			.$this->ShowCheckboxField('paysystem[YCards]', 'Y', (array("id" => "paysystemYCards"))).
			' <label for="paysystemYCards">'.Loc::getMessage("WIZ_PAYSYSTEM_YCARDS").'</label>
			</div>';
		$this->content .=
			'<div class="wizard-catalog-form-item">'
			.$this->ShowCheckboxField('paysystem[YTerminals]', 'Y', (array("id" => "paysystemYTerminals"))).
			' <label for="paysystemYTerminals">'.Loc::getMessage("WIZ_PAYSYSTEM_YTERMINALS").'</label>
			</div>';
		$this->content .=
			'<div class="wizard-catalog-form-item">'
			.$this->ShowCheckboxField('paysystem[webmoney]', 'Y', (array("id" => "paysystemWebmoney"))).
			' <label for="paysystemWebmoney">'.Loc::getMessage("WIZ_PAYSYSTEM_WEBMONEY").'</label>
			</div>';
		$this->content .=
			'<div class="wizard-catalog-form-item">'
			.$this->ShowCheckboxField('paysystem[paypal]', 'Y', (array("id" => "paysystemPaypal"))).
			' <label for="paysystemPaypal">PayPal</label>
			</div>';


		$this->content .= '
		<div>
			<div class="wizard-catalog-title">'.Loc::getMessage("WIZ_DELIVERY_TITLE").'</div>
			<div>
				<div class="wizard-input-form-field wizard-input-form-field-checkbox">';
		$this->content .=
			'<div class="wizard-catalog-form-item">'
			.$this->ShowCheckboxField('delivery[pickup]', 'Y', (array("id" => "deliveryPickup"))).
			' <label for="deliveryPickup">'.Loc::getMessage("WIZ_DELIVERY_PICKUP").'</label>
			</div>';
		$this->content .=
			'<div class="wizard-catalog-form-item">'
			.$this->ShowCheckboxField('delivery[courier]', 'Y', (array("id" => "deliveryCourier"))).
			' <label for="deliveryCourier">'.Loc::getMessage("WIZ_DELIVERY_COURIER").'</label>
			</div>';
		if ($shopLocalization == "ru") {
			$this->content .=
				'<div class="wizard-catalog-form-item">'
				. $this->ShowCheckboxField('delivery[rus_post]', 'Y', (["id" => "deliveryRusPost"])) .
				' <label for="deliveryRusPost">' . Loc::getMessage("WIZ_DELIVERY_RUS_POST") . '</label>
				</div>';
		}
		if ($shopLocalization == "ua") {
			$this->content .=
				'<div class="wizard-catalog-form-item">'
				.$this->ShowCheckboxField('delivery[ua_post]', 'Y', (array("id" => "deliveryUaPost"))).
				' <label for="deliveryUaPost">'.Loc::getMessage("WIZ_DELIVERY_UA_POST").'</label>
				</div>';
		}
		if ($shopLocalization == "kz") {
			$this->content .=
				'<div class="wizard-catalog-form-item">'
				. $this->ShowCheckboxField('delivery[kaz_post]', 'Y', (["id" => "deliveryKazPost"])) .
				' <label for="deliveryKazPost">' . Loc::getMessage("WIZ_DELIVERY_KAZ_POST") . '</label>
				</div>';
		}
		$this->content .=
			'<div class="wizard-catalog-form-item">'
			.$this->ShowCheckboxField('delivery[ups]', 'Y', (array("id" => "deliveryUps"))).
			' <label for="deliveryUps">'.Loc::getMessage("WIZ_DELIVERY_UPS").'</label>
			</div>';

		$this->content .=
			'<div class="wizard-catalog-form-item">'
			.$this->ShowCheckboxField('delivery[dhlusa]', 'Y', (array("id" => "deliveryDhlusa"))).
			' <label for="deliveryDhlusa">'.Loc::getMessage("WIZ_DELIVERY_DHLUSA").'</label>
			</div>';


		$this->content .= '
				</div>
			</div>
		</div>';

	}

}