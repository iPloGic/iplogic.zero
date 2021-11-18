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

		// replace by real if it needed
		//$siteStamp =$wizard->GetPath()."/site/templates/minimal/images/pechat.gif";
		//$siteStamp = "";
		$siteID = getSite($wizard)["ID"];

		$wizard->SetDefaultVars(
			[
				"shopLocalization" => Option::get("iplogic.zero", "shopLocalization", "ru", $siteID),
				"shopEmail" => Option::get("iplogic.zero", "shopEmail", "sale@".$_SERVER["SERVER_NAME"], $siteID),
				"shopOfName" => Option::get("iplogic.zero", "shopOfName", Loc::getMessage("WIZ_SHOP_OF_NAME_DEF"), $siteID),
				"shopLocation" => Option::get("iplogic.zero", "shopLocation", Loc::getMessage("WIZ_SHOP_LOCATION_DEF"), $siteID),
				"shopZip" => Option::get("iplogic.zero", "shopZip", "101000", $siteID),
				"shopAdr" => Option::get("iplogic.zero", "shopAdr", Loc::getMessage("WIZ_SHOP_ADR_DEF"), $siteID),
				"shopINN" => Option::get("iplogic.zero", "shopINN", "1234567890", $siteID),
				"shopKPP" => Option::get("iplogic.zero", "shopKPP", "123456789", $siteID),
				"shopNS" => Option::get("iplogic.zero", "shopNS", "0000 0000 0000 0000 0000", $siteID),
				"shopBANK" => Option::get("iplogic.zero", "shopBANK", Loc::getMessage("WIZ_SHOP_BANK_DEF"), $siteID),
				"shopBANKREKV" => Option::get("iplogic.zero", "shopBANKREKV", Loc::getMessage("WIZ_SHOP_BANKREKV_DEF"), $siteID),
				"shopKS" => Option::get("iplogic.zero", "shopKS", "30101 810 4 0000 0000225", $siteID),
				//"siteStamp" => Option::get("iplogic.zero", "siteStamp", $siteStamp, $siteID),

				//"shopCompany_ua" => Option::get("iplogic.zero", "shopCompany_ua", "", $siteID),
				"shopOfName_ua" => Option::get("iplogic.zero", "shopOfName_ua", Loc::getMessage("WIZ_SHOP_OF_NAME_DEF_UA"), $siteID),
				"shopLocation_ua" => Option::get("iplogic.zero", "shopLocation_ua", Loc::getMessage("WIZ_SHOP_LOCATION_DEF_UA"), $siteID),
				"shopZip_ua" => Option::get("iplogic.zero", "shopZip_ua", "10100", $siteID),
				"shopAdr_ua" => Option::get("iplogic.zero", "shopAdr_ua", Loc::getMessage("WIZ_SHOP_ADR_DEF_UA"), $siteID),
				"shopEGRPU_ua" =>  Option::get("iplogic.zero", "shopEGRPU_ua", "", $siteID),
				"shopINN_ua" =>  Option::get("iplogic.zero", "shopINN_ua", "", $siteID),
				"shopNDS_ua" =>  Option::get("iplogic.zero", "shopNDS_ua", "", $siteID),
				"shopNS_ua" =>  Option::get("iplogic.zero", "shopNS_ua", "", $siteID),
				"shopBank_ua" =>  Option::get("iplogic.zero", "shopBank_ua", "", $siteID),
				"shopMFO_ua" =>  Option::get("iplogic.zero", "shopMFO_ua", "", $siteID),
				"shopPlace_ua" =>  Option::get("iplogic.zero", "shopPlace_ua", "", $siteID),
				"shopFIO_ua" =>  Option::get("iplogic.zero", "shopFIO_ua", "", $siteID),
				"shopTax_ua" =>  Option::get("iplogic.zero", "shopTax_ua", "", $siteID),

				"installPriceBASE" => Option::get("iplogic.zero", "installPriceBASE", "Y", $siteID),
			]
		);
	}

	function ShowStep()
	{
		$wizard =& $this->GetWizard();
		//$siteStamp = $wizard->GetVar("siteStamp", true);
		$firstStep = "N"; //COption::GetOptionString("main", "wizard_first" . substr($wizard->GetID(), 7)  . "_" . $wizard->GetVar("siteID"), false, $wizard->GetVar("siteID"));

		if (!CModule::IncludeModule("catalog"))
		{
			$this->content .= "<p style='color:red'>".Loc::getMessage("WIZ_NO_MODULE_CATALOG")."</p>";
			$this->SetNextStep("shop_settings");
		}
		else
		{
			$this->content .=
				'<div class="wizard-catalog-title">'.Loc::getMessage("WIZ_SHOP_LOCALIZATION").'</div>
				<div class="wizard-input-form-block" >'.
				$this->ShowSelectField("shopLocalization", array(
					"ru" => Loc::getMessage("WIZ_SHOP_LOCALIZATION_RUSSIA"),
					"ua" => Loc::getMessage("WIZ_SHOP_LOCALIZATION_UKRAINE"),
					"kz" => Loc::getMessage("WIZ_SHOP_LOCALIZATION_KAZAKHSTAN"),
					"bl" => Loc::getMessage("WIZ_SHOP_LOCALIZATION_BELORUSSIA")
				), array("onchange" => "langReload()", "id" => "localization_select","class" => "wizard-field", "style"=>"padding:0 0 0 15px")).'
				</div>';

			$currentLocalization = $wizard->GetVar("shopLocalization");
			if (empty($currentLocalization))
				$currentLocalization = $wizard->GetDefaultVar("shopLocalization");

			$this->content .= //'<div class="wizard-catalog-title">'.Loc::getMessage("WIZ_STEP_SS").'</div>
				'<div class="wizard-input-form">';

			$this->content .= '
				<div class="wizard-input-form-block">
					<label class="wizard-input-title" for="shopEmail">'.Loc::getMessage("WIZ_SHOP_EMAIL").'</label>
					'.$this->ShowInputField('text', 'shopEmail', array("id" => "shopEmail", "class" => "wizard-field")).'
				</div>';

			//ru
			$this->content .= '<div id="ru_bank_details" class="wizard-input-form-block" style="display:'.(($currentLocalization == "ru" || $currentLocalization == "kz" || $currentLocalization == "bl") ? 'block':'none').'">
				<div class="wizard-input-form-block">
					<label class="wizard-input-title" for="shopOfName">'.Loc::getMessage("WIZ_SHOP_OF_NAME").'</label>'
				.$this->ShowInputField('text', 'shopOfName', array("id" => "shopOfName", "class" => "wizard-field")).'
				</div>';

			$this->content .= '
				<div class="wizard-input-form-block">
					<label class="wizard-input-title" for="shopLocation">'.Loc::getMessage("WIZ_SHOP_LOCATION").'</label>'
				.$this->ShowInputField('text', 'shopLocation', array("id" => "shopLocation", "class" => "wizard-field")).'
				</div>';

			$this->content .= '
				<div class="wizard-input-form-block">
					<label class="wizard-input-title" for="shopZip">'.Loc::getMessage("WIZ_SHOP_ZIP").'</label>'
				.$this->ShowInputField('text', 'shopZip', array("id" => "shopZip", "class" => "wizard-field")).'
				</div>';

			$this->content .= '
				<div class="wizard-input-form-block">
					<label class="wizard-input-title" for="shopAdr">'.Loc::getMessage("WIZ_SHOP_ADR").'</label>'
				.$this->ShowInputField('textarea', 'shopAdr', array("rows"=>"3", "id" => "shopAdr", "class" => "wizard-field")).'
				</div>';

			if($firstStep != "Y")
			{
				$this->content .= '
					<div class="wizard-catalog-title">'.Loc::getMessage("WIZ_SHOP_BANK_TITLE").'</div>
					<table class="wizard-input-table">
						<tr>
							<td class="wizard-input-table-left">'.Loc::getMessage("WIZ_SHOP_INN").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopINN', array("class" => "wizard-field")).'</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">'.Loc::getMessage("WIZ_SHOP_KPP").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopKPP', array("class" => "wizard-field")).'</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">'.Loc::getMessage("WIZ_SHOP_NS").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopNS', array("class" => "wizard-field")).'</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">'.Loc::getMessage("WIZ_SHOP_BANK").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopBANK', array("class" => "wizard-field")).'</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">'.Loc::getMessage("WIZ_SHOP_BANKREKV").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopBANKREKV', array("class" => "wizard-field")).'</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">'.Loc::getMessage("WIZ_SHOP_KS").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopKS', array("class" => "wizard-field")).'</td>
						</tr>
						<!--<tr>
							<td class="wizard-input-table-left">'.Loc::getMessage("WIZ_SHOP_STAMP").':</td>
							<td class="wizard-input-table-right">'.$this->ShowFileField("siteStamp", Array("show_file_info" => "N", "id" => "siteStamp")).'<br />'.CFile::ShowImage($siteStamp, 75, 75, "border=0 vspace=5", false, false).'</td>
						</tr>-->
					</table>
				</div><!--ru-->
				';
			}
			$this->content .= '<div id="ua_bank_details" class="wizard-input-form-block" style="display:'.(($currentLocalization == "ua") ? 'block':'none').'">
				<div class="wizard-input-form-block">
					<label class="wizard-input-title" for="shopOfName_ua">'.Loc::getMessage("WIZ_SHOP_OF_NAME").'</label>'
				.$this->ShowInputField('text', 'shopOfName_ua', array("id" => "shopOfName_ua", "class" => "wizard-field")).'
					<p style="color:grey; margin: 3px 0 7px;">'.Loc::getMessage("WIZ_SHOP_OF_NAME_DESCR_UA").'</p>
				</div>';

			$this->content .= '<div class="wizard-input-form-block">
					<label class="wizard-input-title" for="shopLocation_ua">'.Loc::getMessage("WIZ_SHOP_LOCATION").'</label>'
				.$this->ShowInputField('text', 'shopLocation_ua', array("id" => "shopLocation_ua", "class" => "wizard-field")).'
					<p style="color:grey; margin: 3px 0 7px;">'.Loc::getMessage("WIZ_SHOP_LOCATION_DESCR_UA").'</p>
				</div>';

			$this->content .= '
				<div class="wizard-input-form-block">
					<label class="wizard-input-title" for="shopZip_ua">'.Loc::getMessage("WIZ_SHOP_ZIP").'</label>'
				.$this->ShowInputField('text', 'shopZip_ua', array("id" => "shopZip_ua", "class" => "wizard-field")).'
				</div>';

			$this->content .= '
				<div class="wizard-input-form-block">
					<label class="wizard-input-title" for="shopAdr_ua">'.Loc::getMessage("WIZ_SHOP_ADR").'</label>'.
				$this->ShowInputField('textarea', 'shopAdr_ua', array("rows"=>"3", "id" => "shopAdr_ua", "class" => "wizard-field")).'
					<p style="color:grey; margin: 3px 0 7px;">'.Loc::getMessage("WIZ_SHOP_ADR_DESCR_UA").'</p>
				</div>';

			if($firstStep != "Y")
			{
				$this->content .= '
					<div class="wizard-catalog-title">'.Loc::getMessage("WIZ_SHOP_RECV_UA").'</div>
					<p>'.Loc::getMessage("WIZ_SHOP_RECV_UA_DESC").'</p>
					<table class="wizard-input-table">
						<tr>
							<td class="wizard-input-table-left">'.Loc::getMessage("WIZ_SHOP_EGRPU_UA").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopEGRPU_ua', array("class" => "wizard-field")).'</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">'.Loc::getMessage("WIZ_SHOP_INN_UA").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopINN_ua', array("class" => "wizard-field")).'</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">'.Loc::getMessage("WIZ_SHOP_NDS_UA").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopNDS_ua', array("class" => "wizard-field")).'</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">'.Loc::getMessage("WIZ_SHOP_NS_UA").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopNS_ua', array("class" => "wizard-field")).'</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">'.Loc::getMessage("WIZ_SHOP_BANK_UA").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopBank_ua', array("class" => "wizard-field")).'</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">'.Loc::getMessage("WIZ_SHOP_MFO_UA").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopMFO_ua', array("class" => "wizard-field")).'</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">'.Loc::getMessage("WIZ_SHOP_PLACE_UA").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopPlace_ua', array("class" => "wizard-field")).'</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">'.Loc::getMessage("WIZ_SHOP_FIO_UA").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopFIO_ua', array("class" => "wizard-field")).'</td>
						</tr>
						<tr>
							<td class="wizard-input-table-left">'.Loc::getMessage("WIZ_SHOP_TAX_UA").':</td>
							<td class="wizard-input-table-right">'.$this->ShowInputField('text', 'shopTax_ua', array("class" => "wizard-field")).'</td>
						</tr>
					</table>
				</div>
				';
			}

			/*if (CModule::IncludeModule("catalog"))
			{
				$db_res = CCatalogGroup::GetGroupsList(array("CATALOG_GROUP_ID"=>'1', "BUY"=>"Y", "GROUP_ID"=>2));
				if (!$db_res->Fetch())
				{
					$this->content .= '
					<div class="wizard-input-form-block">
						<div class="wizard-catalog-title">'.Loc::getMessage("WIZ_SHOP_PRICE_BASE_TITLE").'</div>
						<div class="wizard-input-form-block-content">
							'. Loc::getMessage("WIZ_SHOP_PRICE_BASE_TEXT1") .'<br><br>
							'. $this->ShowCheckboxField("installPriceBASE", "Y",
							(array("id" => "install-demo-data")))
							. ' <label for="install-demo-data">'.Loc::getMessage("WIZ_SHOP_PRICE_BASE_TEXT2").'</label><br />

						</div>
					</div>';
				}
			}*/

			$this->content .= '</div>';

			$this->content .= '
				<script>
					function langReload()
					{
						var objSel = document.getElementById("localization_select");
						var locSelected = objSel.options[objSel.selectedIndex].value;
						document.getElementById("ru_bank_details").style.display = (locSelected == "ru" || locSelected == "kz" || locSelected == "bl") ? "block" : "none";
						document.getElementById("ua_bank_details").style.display = (locSelected == "ua") ? "block" : "none";
						/*document.getElementById("kz_bank_details").style.display = (locSelected == "kz") ? "block" : "none";*/
					}
				</script>
			';
		}
	}

	function OnPostForm()
	{
		/*$wizard =& $this->GetWizard();
		$res = $this->SaveFile("siteStamp", Array("extensions" => "gif,jpg,jpeg,png", "max_height" => 70, "max_width" => 190, "make_preview" => "Y"));*/
	}

}