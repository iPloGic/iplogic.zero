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

		$wizard->SetDefaultVars(
			[
				"personType" => [
					"fiz" => Option::get("iplogic.zero", "personTypeFiz", "Y", $siteID),
					"ur"  => Option::get("iplogic.zero", "personTypeUr", "Y", $siteID),
				],
				"delivery" => [
					"pickup" => Option::get("iplogic.zero", "deliveryPickup", "Y", $siteID),
					"courier" => Option::get("iplogic.zero", "deliveryCourier", "Y", $siteID),
				],
				"paysystem" => [
					"cash" => Option::get("iplogic.zero", "paysystemCash", "Y", $siteID),
					"bill" => Option::get("iplogic.zero", "paysystemBill", "Y", $siteID),
				],
			]
		);
	}


	function ShowStep()
	{
		$wizard =& $this->GetWizard();

		$siteID = getSite($wizard)["ID"];

		$shopLocalization = $wizard->GetVar("shopLocalization", true);

		$locExists = false;
		$res = \Bitrix\Sale\Location\LocationTable::getList(['select' => ["ID"]]);
		if( $loc = $res->fetch() ) {
			$locExists = true;
		}
		unset($res);

		$this->content .= '<div class="wizard-input-form">';
		$this->content .= '
		<div class="wizard-input-form-block">
			<div class="wizard-catalog-title">' . Loc::getMessage("WIZ_PERSON_TYPE_TITLE") . '</div>
			<div style="padding-top:15px">
				<div class="wizard-input-form-field wizard-input-form-field-checkbox">
					<div class="wizard-catalog-form-item">
						' . $this->ShowCheckboxField('personType[fiz]', 'Y', (["id" => "personTypeF"])) .
			' <label for="personTypeF">' . Loc::getMessage("WIZ_PERSON_TYPE_FIZ") . '</label><br />
					</div>
					<div class="wizard-catalog-form-item">
						' . $this->ShowCheckboxField('personType[ur]', 'Y', (["id" => "personTypeU"])) .
			' <label for="personTypeU">' . Loc::getMessage("WIZ_PERSON_TYPE_UR") . '</label><br />
					</div>';
		$this->content .= '
				</div>
			</div>
			<div class="wizard-catalog-form-item">' . Loc::getMessage("WIZ_PERSON_TYPE") . '<div>
		</div>';
		$this->content .= '</div>';

		if( !$locExists ) {
			$this->content .= '
			<div>
				<div class="wizard-catalog-title">' . Loc::getMessage("WIZ_LOCATION_TITLE") . '</div>
				<div>
					<div class="wizard-input-form-field wizard-input-form-field-checkbox">';
			$this->content .=
				'<div class="wizard-catalog-form-item">' .
				$this->ShowRadioField("locations_csv", "loc_ussr.csv", ["id" => "loc_ussr", "checked" => "checked"])
				. " <label for=\"loc_ussr\">" . Loc::getMessage('WSL_STEP2_GFILE_USSR') . "</label>
				</div>";
			$this->content .=
				'<div class="wizard-catalog-form-item">' .
				$this->ShowRadioField("locations_csv", "loc_kz.csv", ["id" => "loc_kz"])
				. " <label for=\"loc_kz\">" . Loc::getMessage('WSL_STEP2_GFILE_KZ') . "</label>
				</div>";
			$this->content .=
				'<div class="wizard-catalog-form-item">' .
				$this->ShowRadioField("locations_csv", "loc_usa.csv", ["id" => "loc_usa"])
				. " <label for=\"loc_usa\">" . Loc::getMessage('WSL_STEP2_GFILE_USA') . "</label>
				</div>";
			$this->content .=
				'<div class="wizard-catalog-form-item">' .
				$this->ShowRadioField("locations_csv", "loc_cntr.csv", ["id" => "loc_cntr"])
				. " <label for=\"loc_cntr\">" . Loc::getMessage('WSL_STEP2_GFILE_CNTR') . "</label>
				</div>";
			$this->content .=
				'<div class="wizard-catalog-form-item">' .
				$this->ShowRadioField("locations_csv", "", ["id" => "none"])
				. " <label for=\"none\">" . Loc::getMessage('WSL_STEP2_GFILE_NONE') . "</label>
				</div>";
		}


		$this->content .= '
		<br><br><div>
			<div class="wizard-catalog-title">' . Loc::getMessage("WIZ_PAYMENT_TITLE") . '</div>
			<div>
				<div class="wizard-input-form-field wizard-input-form-field-checkbox">';
		$this->content .=
			'<div class="wizard-catalog-form-item">'
			. $this->ShowCheckboxField('paysystem[cash]', 'Y', (["id" => "paysystemCash"])) .
			' <label for="paysystemCash">' . Loc::getMessage("WIZ_PAYSYSTEM_CASH") . '</label>
			</div>';
		$this->content .=
			'<div class="wizard-catalog-form-item">'
			. $this->ShowCheckboxField('paysystem[bill]', 'Y', (["id" => "paysystemBill"])) .
			' <label for="paysystemBill">' . Loc::getMessage("WIZ_PAYSYSTEM_BILL") . '</label>
			</div>';
		$this->content .=
			'<div class="wizard-catalog-form-item">'
			. $this->ShowCheckboxField('paysystem[collect]', 'Y', (["id" => "paysystemCollect"])) .
			' <label for="paysystemCollect">' . Loc::getMessage("WIZ_PAYSYSTEM_COLLECT") . '</label>
			</div>';

		$this->content .=
			'<div class="wizard-catalog-form-item">'
			. $this->ShowCheckboxField('paysystem[sber]', 'Y', (["id" => "paysystemSber"])) .
			' <label for="paysystemSber">' . Loc::getMessage("WIZ_PAYSYSTEM_SBER") . '</label>
			</div>';

		/*$this->content .=
			'<div class="wizard-catalog-form-item">'
			.$this->ShowCheckboxField('paysystem[paypal]', 'Y', (array("id" => "paysystemPaypal"))).
			' <label for="paysystemPaypal">PayPal</label>
			</div>';*/


		$this->content .= '
		<br><br><div>
			<div class="wizard-catalog-title">' . Loc::getMessage("WIZ_DELIVERY_TITLE") . '</div>
			<div>
				<div class="wizard-input-form-field wizard-input-form-field-checkbox">';
		$this->content .=
			'<div class="wizard-catalog-form-item">'
			. $this->ShowCheckboxField('delivery[pickup]', 'Y', (["id" => "deliveryPickup"])) .
			' <label for="deliveryPickup">' . Loc::getMessage("WIZ_DELIVERY_PICKUP") . '</label>
			</div>';
		$this->content .=
			'<div class="wizard-catalog-form-item">'
			. $this->ShowCheckboxField('delivery[courier]', 'Y', (["id" => "deliveryCourier"])) .
			' <label for="deliveryCourier">' . Loc::getMessage("WIZ_DELIVERY_COURIER") . '</label>
			</div>';
		if( $shopLocalization == "ru" ) {
			$this->content .=
				'<div class="wizard-catalog-form-item">'
				. $this->ShowCheckboxField('delivery[spsr]', 'Y', (["id" => "deliverySpsr"])) .
				' <label for="deliverySpsr">' . Loc::getMessage("WIZ_DELIVERY_SPSR") . '</label>
			</div>';

			$this->content .=
				'<div class="wizard-catalog-form-item">'
				. $this->ShowCheckboxField('delivery[rus_post]', 'Y', (["id" => "deliveryRusPost"])) .
				' <label for="deliveryRusPost">' . Loc::getMessage("WIZ_DELIVERY_RUS_POST") . '</label>
				</div>';
		}
		if( $shopLocalization == "kz" ) {
			$this->content .=
				'<div class="wizard-catalog-form-item">'
				. $this->ShowCheckboxField('delivery[kaz_post]', 'Y', (["id" => "deliveryKazPost"])) .
				' <label for="deliveryKazPost">' . Loc::getMessage("WIZ_DELIVERY_KAZ_POST") . '</label>
				</div>';
		}
		$this->content .=
			'<div class="wizard-catalog-form-item">'
			. $this->ShowCheckboxField('delivery[ups]', 'Y', (["id" => "deliveryUps"])) .
			' <label for="deliveryUps">' . Loc::getMessage("WIZ_DELIVERY_UPS") . '</label>
			</div>';


		$this->content .= '
				</div>
			</div>
		</div>';

	}

}