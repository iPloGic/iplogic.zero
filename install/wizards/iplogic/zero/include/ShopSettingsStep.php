<?php

use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;

class ShopSettingsStep extends CWizardStep
{
	function InitStep()
	{
		$this->SetStepID("shop_settings");

		$this->SetPrevStep("site_settings");
		$this->SetNextStep("payer_and_loc");

		$this->SetTitle(Loc::getMessage("WIZ_STEP_SS"));
		$this->SetNextCaption(Loc::getMessage("NEXT_BUTTON"));
		$this->SetPrevCaption(Loc::getMessage("PREVIOUS_BUTTON"));

		$wizard =& $this->GetWizard();

		$siteLogo = "";
		$siteStamp = "";
		$siteDirSign = "";
		$siteAccSign = "";

		$siteID = getSite($wizard)["ID"];

		$wizard->SetDefaultVars(
			[
				"shopLocalization" => Option::get("iplogic.zero", "shopLocalization", "ru", $siteID),
				"shopEmail"        => Option::get(
					"iplogic.zero",
					"shopEmail",
					"sale@" . $_SERVER["SERVER_NAME"],
					$siteID
				),
				"shopOfName"       => Option::get(
					"iplogic.zero",
					"shopOfName",
					Loc::getMessage("WIZ_SHOP_OF_NAME_DEF"),
					$siteID
				),
				"shopLocation"     => Option::get(
					"iplogic.zero",
					"shopLocation",
					Loc::getMessage("WIZ_SHOP_LOCATION_DEF"),
					$siteID
				),
				"shopZip"          => Option::get("iplogic.zero", "shopZip", "101000", $siteID),
				"shopAdr"          => Option::get(
					"iplogic.zero",
					"shopAdr",
					Loc::getMessage("WIZ_SHOP_ADR_DEF"),
					$siteID
				),
				"shopINN"          => Option::get("iplogic.zero", "shopINN", "1234567890", $siteID),
				"shopKPP"          => Option::get("iplogic.zero", "shopKPP", "123456789", $siteID),
				"shopNS"           => Option::get("iplogic.zero", "shopNS", "0000 0000 0000 0000 0000", $siteID),
				"shopBANK"         => Option::get(
					"iplogic.zero",
					"shopBANK",
					Loc::getMessage("WIZ_SHOP_BANK_DEF"),
					$siteID
				),
				"shopBANKREKV"     => Option::get(
					"iplogic.zero",
					"shopBANKREKV",
					Loc::getMessage("WIZ_SHOP_BANKREKV_DEF"),
					$siteID
				),
				"shopKS"           => Option::get("iplogic.zero", "shopKS", "30101 810 4 0000 0000225", $siteID),
				"siteLogo"         => Option::get("iplogic.zero", "siteLogo", $siteLogo, $siteID),
				"siteStamp"        => Option::get("iplogic.zero", "siteStamp", $siteStamp, $siteID),
				"siteDirSign"      => Option::get("iplogic.zero", "siteDirSign", $siteDirSign, $siteID),
				"siteAccSign"      => Option::get("iplogic.zero", "siteAccSign", $siteAccSign, $siteID),

			]
		);
	}

	function ShowStep()
	{
		$wizard =& $this->GetWizard();
		$siteLogo = $wizard->GetVar("siteLogo", true);
		$siteStamp = $wizard->GetVar("siteStamp", true);
		$siteDirSign = $wizard->GetVar("siteDirSign", true);
		$siteAccSign = $wizard->GetVar("siteAccSign", true);

		if( !CModule::IncludeModule("catalog") ) {
			$this->content .= "<p style='color:red'>" . Loc::getMessage("WIZ_NO_MODULE_CATALOG") . "</p>";
			$this->SetNextStep("shop_settings");
		}
		else {
			$this->content .=
				'<div class="wizard-catalog-title">' . Loc::getMessage("WIZ_SHOP_LOCALIZATION") . '</div>
				<div class="wizard-input-form-block" >' .
				$this->ShowSelectField(
					"shopLocalization",
					[
						"ru" => Loc::getMessage("WIZ_SHOP_LOCALIZATION_RUSSIA"),
						"kz" => Loc::getMessage("WIZ_SHOP_LOCALIZATION_KAZAKHSTAN"),
						"bl" => Loc::getMessage("WIZ_SHOP_LOCALIZATION_BELORUSSIA"),
						"an" => Loc::getMessage("WIZ_SHOP_LOCALIZATION_ANOTHER"),
					],
					[
						"onchange" => "langReload()",
						"id"       => "localization_select",
						"class"    => "wizard-field",
						"style"    => "padding:0 0 0 15px",
					]
				) . '
				</div>';

			$currentLocalization = $wizard->GetVar("shopLocalization");
			if( empty($currentLocalization) ) {
				$currentLocalization = $wizard->GetDefaultVar("shopLocalization");
			}

			$this->content .= '<div class="wizard-input-form">';

			$this->content .= '
				<div class="wizard-input-form-block">
					<label class="wizard-input-title" for="shopEmail">' . Loc::getMessage("WIZ_SHOP_EMAIL") . '</label><br>
					' . $this->ShowInputField('text', 'shopEmail', ["id" => "shopEmail", "class" => "wizard-field"]) . '
				</div>';

			//ru
			$this->content .= '<div id="ru_bank_details" class="wizard-input-form-block" style="display:' .
				(($currentLocalization == "ru" || $currentLocalization == "kz" || $currentLocalization == "bl") ?
					'block' : 'none') . '">';
			$this->content .= '
				<div class="wizard-input-form-block">
					<label class="wizard-input-title" for="shopOfName">' . Loc::getMessage("WIZ_SHOP_OF_NAME") .
				'</label><br>'
				. $this->ShowInputField('text', 'shopOfName', ["id" => "shopOfName", "class" => "wizard-field"]) . '
					<p style="color:grey; margin: 3px 0 7px;">' . Loc::getMessage("WIZ_SHOP_OF_NAME_DESCR") . '</p>
				</div>';

			$this->content .= '
				<div class="wizard-input-form-block">
					<label class="wizard-input-title" for="shopLocation">' . Loc::getMessage("WIZ_SHOP_LOCATION") .
				'</label><br>'
				. $this->ShowInputField('text', 'shopLocation', ["id" => "shopLocation", "class" => "wizard-field"]) . '
					<p style="color:grey; margin: 3px 0 7px;">' . Loc::getMessage("WIZ_SHOP_LOCATION_DESCR") . '</p>
				</div>';

			$this->content .= '
				<div class="wizard-input-form-block">
					<label class="wizard-input-title" for="shopZip">' . Loc::getMessage("WIZ_SHOP_ZIP") . '</label><br>'
				. $this->ShowInputField('text', 'shopZip', ["id" => "shopZip", "class" => "wizard-field"]) . '
				</div>';

			$this->content .= '
				<div class="wizard-input-form-block">
					<label class="wizard-input-title" for="shopAdr">' . Loc::getMessage("WIZ_SHOP_ADR") . '</label><br>'
				. $this->ShowInputField(
					'textarea',
					'shopAdr',
					["rows" => "3", "id" => "shopAdr", "class" => "wizard-field"]
				) . '
					<p style="color:grey; margin: 3px 0 7px;">' . Loc::getMessage("WIZ_SHOP_ADR_DESCR") . '</p>
				</div>';


			$this->content .= '
					<div class="wizard-catalog-title">' . Loc::getMessage("WIZ_SHOP_BANK_TITLE") . '</div>
					<table class="wizard-input-table">
						<tr>
							<td class="wizard-input-table-left">' . Loc::getMessage("WIZ_SHOP_INN") . ':</td>
							<td class="wizard-input-table-right">' .
				$this->ShowInputField('text', 'shopINN', ["class" => "wizard-field"]) . '</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">' . Loc::getMessage("WIZ_SHOP_KPP") . ':</td>
							<td class="wizard-input-table-right">' .
				$this->ShowInputField('text', 'shopKPP', ["class" => "wizard-field"]) . '</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">' . Loc::getMessage("WIZ_SHOP_NS") . ':</td>
							<td class="wizard-input-table-right">' .
				$this->ShowInputField('text', 'shopNS', ["class" => "wizard-field"]) . '</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">' . Loc::getMessage("WIZ_SHOP_BANK") . ':</td>
							<td class="wizard-input-table-right">' .
				$this->ShowInputField('text', 'shopBANK', ["class" => "wizard-field"]) . '</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">' . Loc::getMessage("WIZ_SHOP_BANKREKV") . ':</td>
							<td class="wizard-input-table-right">' .
				$this->ShowInputField('text', 'shopBANKREKV', ["class" => "wizard-field"]) . '</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">' . Loc::getMessage("WIZ_SHOP_KS") . ':</td>
							<td class="wizard-input-table-right">' .
				$this->ShowInputField('text', 'shopKS', ["class" => "wizard-field"]) . '</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">' . Loc::getMessage("WIZ_SHOP_LOGO") . ':</td>
							<td class="wizard-input-table-right">' .
				$this->ShowFileField("siteLogo", ["show_file_info" => "N", "id" => "siteLogo"]) . '<br />' .
				CFile::ShowImage($siteLogo, 75, 75, "border=0 vspace=5", false, false) . '</td>
						</tr>						<tr>
							<td class="wizard-input-table-left">' . Loc::getMessage("WIZ_SHOP_STAMP") . ':</td>
							<td class="wizard-input-table-right">' .
				$this->ShowFileField("siteStamp", ["show_file_info" => "N", "id" => "siteStamp"]) . '<br />' .
				CFile::ShowImage($siteStamp, 75, 75, "border=0 vspace=5", false, false) . '</td>
						</tr>						<tr>
							<td class="wizard-input-table-left">' . Loc::getMessage("WIZ_SHOP_DIR_SIG") . ':</td>
							<td class="wizard-input-table-right">' .
				$this->ShowFileField("siteDirSign", ["show_file_info" => "N", "id" => "siteDirSign"]) . '<br />' .
				CFile::ShowImage($siteDirSign, 75, 75, "border=0 vspace=5", false, false) . '</td>
						</tr>						<tr>
							<td class="wizard-input-table-left">' . Loc::getMessage("WIZ_SHOP_ACC_SIG") . ':</td>
							<td class="wizard-input-table-right">' .
				$this->ShowFileField("siteAccSign", ["show_file_info" => "N", "id" => "siteAccSign"]) . '<br />' .
				CFile::ShowImage($siteAccSign, 75, 75, "border=0 vspace=5", false, false) . '</td>
						</tr>
					</table>
				</div>
				';

			//ru
			$this->content .= '<div id="an_bank_details" class="wizard-input-form-block" style="display:' .
				(($currentLocalization == "an") ? 'block' : 'none') . '">';
			$this->content .= '
				<div class="wizard-input-form-block">
					<label class="wizard-input-title" for="shopOfName">' . Loc::getMessage("WIZ_SHOP_OF_NAME") .
				'</label><br>'
				. $this->ShowInputField('text', 'shopOfName', ["id" => "shopOfName", "class" => "wizard-field"]) . '
					<p style="color:grey; margin: 3px 0 7px;">' . Loc::getMessage("WIZ_SHOP_OF_NAME_DESCR") . '</p>
				</div>';

			$this->content .= '</div>';

			$this->content .= '</div>';

			$this->content .= '
				<script>
					function langReload()
					{
						var objSel = document.getElementById("localization_select");
						var locSelected = objSel.options[objSel.selectedIndex].value;
						document.getElementById("ru_bank_details").style.display = (locSelected == "ru" || locSelected == "kz" || locSelected == "bl") ? "block" : "none";
						document.getElementById("an_bank_details").style.display = (locSelected == "an") ? "block" : "none";
						/*document.getElementById("kz_bank_details").style.display = (locSelected == "kz") ? "block" : "none";*/
					}
				</script>
			';
		}
	}

	function OnPostForm()
	{
		$wizard =& $this->GetWizard();
		$res = $this->SaveFile(
			"siteLogo",
			["extensions" => "gif,jpg,jpeg,png", "max_height" => 150, "max_width" => 150, "make_preview" => "Y"]
		);
		$res = $this->SaveFile(
			"siteStamp",
			["extensions" => "gif,jpg,jpeg,png", "max_height" => 150, "max_width" => 150, "make_preview" => "Y"]
		);
		$res = $this->SaveFile(
			"siteDirSign",
			["extensions" => "gif,jpg,jpeg,png", "max_height" => 150, "max_width" => 150, "make_preview" => "Y"]
		);
		$res = $this->SaveFile(
			"siteAccSign",
			["extensions" => "gif,jpg,jpeg,png", "max_height" => 150, "max_width" => 150, "make_preview" => "Y"]
		);
	}

}