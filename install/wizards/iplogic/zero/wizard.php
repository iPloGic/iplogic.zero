<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Config\Option,
	Bitrix\Main\Localization\Loc;

require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/install/wizard_sol/wizard.php");

class SelectSiteStep extends CSelectSiteWizardStep
{
	function InitStep()
	{
		parent::InitStep();

		$wizard =& $this->GetWizard();
		$wizard->solutionName = "zero";
		$this->SetStepID("select_site");
		$this->SetNextStep("site_settings");
	}
}

//class SelectTemplateStep extends CSelectTemplateWizardStep { }

//class SelectThemeStep extends CSelectThemeWizardStep { }

class SiteSettingsStep extends CSiteSettingsWizardStep
{
	function InitStep()
	{
		$wizard =& $this->GetWizard();
		$wizard->solutionName = "zero";
		parent::InitStep();

		$this->SetStepID("site_settings");

		$this->SetPrevStep("select_site");
		$this->SetNextStep("shop_settings");

		$this->SetTitle(Loc::getMessage("wiz_site_settings"));
		$this->SetNextCaption(Loc::getMessage("NEXT_BUTTON"));
		$this->SetPrevCaption(Loc::getMessage("PREVIOUS_BUTTON"));

		$siteID = $wizard->GetVar("siteID");

		$wizard->SetDefaultVars(
			Array(
				"siteName" => Option::get("zero", "site_zero_name", Loc::getMessage("wiz_name"), $wizard->GetVar("siteID")),
				"siteEmail" => Option::get("zero", "site_zero_email_from", "", $wizard->GetVar("siteID")),
				"siteFolder" => Option::get("zero", "site_zero_folder", "/", $wizard->GetVar("siteID")),
				"serverName" => Option::get("zero", "site_zero_server_name", $_SERVER["SERVER_NAME"], $wizard->GetVar("siteID")),
				"domains" => Option::get("zero", "site_zero_domains", "", $wizard->GetVar("siteID")),
				"installDemoData" => Option::get("zero", "wizard_demo_data", "N")
			)
		);
	}

	function OnPostForm()
	{
		$wizard =& $this->GetWizard();

		if ($wizard->IsNextButtonClick())
		{
			Option::set("zero", "site_zero_name", str_replace(Array("<"), Array("&lt;"), $wizard->GetVar("siteName")), $wizard->GetVar("siteID"));
			Option::set("zero", "site_zero_email_from", str_replace(Array("<"), Array("&lt;"), $wizard->GetVar("siteEmail")), $wizard->GetVar("siteID"));
			Option::set("zero", "site_zero_folder", str_replace(Array("<"), Array("&lt;"), $wizard->GetVar("siteFolder")), $wizard->GetVar("siteID"));
			Option::set("zero", "site_zero_server_name", str_replace(Array("<"), Array("&lt;"), $wizard->GetVar("serverName")), $wizard->GetVar("siteID"));
			Option::set("zero", "site_zero_domains", str_replace(Array("<"), Array("&lt;"), $wizard->GetVar("domains")), $wizard->GetVar("siteID"));
		}
	}

	function ShowStep()
	{
		$wizard =& $this->GetWizard();

		$this->content .= '<div class="wizard-input-form-block"><div class="wizard-catalog-title">'.Loc::getMessage("wiz_company_name").'</div>';
		$this->content .= $this->ShowInputField("text", "siteName", Array("id" => "site-name", "class" => "wizard-field"))."</div>";

		$this->content .= '<div class="wizard-input-form-block"><div class="wizard-catalog-title">'.Loc::getMessage("wiz_email_from").'</div>';
		$this->content .= $this->ShowInputField("text", "siteEmail", Array("id" => "email-from", "class" => "wizard-field"))."</div>";

		$this->content .= '<div class="wizard-input-form-block"><div class="wizard-catalog-title">'.Loc::getMessage("wiz_site_folder").'</div>';
		$this->content .= $this->ShowInputField("text", "siteFolder", Array("id" => "site-folder", "class" => "wizard-field"))."</div>";

		$this->content .= '<div class="wizard-input-form-block"><div class="wizard-catalog-title">'.Loc::getMessage("wiz_server_name").'</div>';
		$this->content .= $this->ShowInputField("text", "serverName", Array("id" => "server-name", "class" => "wizard-field"))."</div>";

		$this->content .= '<div class="wizard-input-form-block"><div class="wizard-catalog-title">'.Loc::getMessage("wiz_domains").'</div>';
		$this->content .= $this->ShowInputField("textarea", "domains", Array("id" => "domains", "class" => "wizard-field"))."</div>";

		/*$firstStep = Option::get("main", "wizard_first" . substr($wizard->GetID(), 7)  . "_" . $wizard->GetVar("siteID"), false, $wizard->GetVar("siteID")); 
		if($firstStep == "Y") {
			$this->content .= $this->ShowCheckboxField(
									"installDemoData", 
									"Y", 
									(array("id" => "installDemoData"))
								);
			$this->content .= '<label for="install-demo-data">'.Loc::getMessage("wiz_structure_data").'</label><br />';
		}
		else {
			$this->content .= $this->ShowHiddenField("installDemoData","Y");
		}*/
		$this->content .= $this->ShowHiddenField("installDemoData","Y");

		$formName = $wizard->GetFormName();
		$installCaption = $this->GetNextCaption();
		$nextCaption = Loc::getMessage("NEXT_BUTTON");
	}
}

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
		$siteStamp = "";
		$siteID = $wizard->GetVar("siteID");
		
		$wizard->SetDefaultVars(
			Array(
				"shopLocalization" => Option::get("zero", "shopLocalization", "ru", $siteID),
				"shopEmail" => Option::get("zero", "shopEmail", "sale@".$_SERVER["SERVER_NAME"], $siteID),
				"shopOfName" => Option::get("zero", "shopOfName", Loc::getMessage("WIZ_SHOP_OF_NAME_DEF"), $siteID),
				"shopLocation" => Option::get("zero", "shopLocation", Loc::getMessage("WIZ_SHOP_LOCATION_DEF"), $siteID),
				//"shopZip" => 101000,
				"shopAdr" => Option::get("zero", "shopAdr", Loc::getMessage("WIZ_SHOP_ADR_DEF"), $siteID),
				"shopINN" => Option::get("zero", "shopINN", "1234567890", $siteID),
				"shopKPP" => Option::get("zero", "shopKPP", "123456789", $siteID),
				"shopNS" => Option::get("zero", "shopNS", "0000 0000 0000 0000 0000", $siteID),
				"shopBANK" => Option::get("zero", "shopBANK", Loc::getMessage("WIZ_SHOP_BANK_DEF"), $siteID),
				"shopBANKREKV" => Option::get("zero", "shopBANKREKV", Loc::getMessage("WIZ_SHOP_BANKREKV_DEF"), $siteID),
				"shopKS" => Option::get("zero", "shopKS", "30101 810 4 0000 0000225", $siteID),
				"siteStamp" => Option::get("zero", "siteStamp", $siteStamp, $siteID),

				//"shopCompany_ua" => Option::get("zero", "shopCompany_ua", "", $siteID),
				"shopOfName_ua" => Option::get("zero", "shopOfName_ua", Loc::getMessage("WIZ_SHOP_OF_NAME_DEF_UA"), $siteID),
				"shopLocation_ua" => Option::get("zero", "shopLocation_ua", Loc::getMessage("WIZ_SHOP_LOCATION_DEF_UA"), $siteID),
				"shopAdr_ua" => Option::get("zero", "shopAdr_ua", Loc::getMessage("WIZ_SHOP_ADR_DEF_UA"), $siteID),
				"shopEGRPU_ua" =>  Option::get("zero", "shopEGRPU_ua", "", $siteID),
				"shopINN_ua" =>  Option::get("zero", "shopINN_ua", "", $siteID),
				"shopNDS_ua" =>  Option::get("zero", "shopNDS_ua", "", $siteID),
				"shopNS_ua" =>  Option::get("zero", "shopNS_ua", "", $siteID),
				"shopBank_ua" =>  Option::get("zero", "shopBank_ua", "", $siteID),
				"shopMFO_ua" =>  Option::get("zero", "shopMFO_ua", "", $siteID),
				"shopPlace_ua" =>  Option::get("zero", "shopPlace_ua", "", $siteID),
				"shopFIO_ua" =>  Option::get("zero", "shopFIO_ua", "", $siteID),
				"shopTax_ua" =>  Option::get("zero", "shopTax_ua", "", $siteID),

				"installPriceBASE" => Option::get("zero", "installPriceBASE", "Y", $siteID),
			)
		);
	}

	function ShowStep()
	{
		$wizard =& $this->GetWizard();
		$siteStamp = $wizard->GetVar("siteStamp", true);
		$firstStep = COption::GetOptionString("main", "wizard_first" . substr($wizard->GetID(), 7)  . "_" . $wizard->GetVar("siteID"), false, $wizard->GetVar("siteID"));

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
						<tr>
							<td class="wizard-input-table-left">'.Loc::getMessage("WIZ_SHOP_STAMP").':</td>
							<td class="wizard-input-table-right">'.$this->ShowFileField("siteStamp", Array("show_file_info" => "N", "id" => "siteStamp")).'<br />'.CFile::ShowImage($siteStamp, 75, 75, "border=0 vspace=5", false, false).'</td>
						</tr>
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
		$wizard =& $this->GetWizard();
		$res = $this->SaveFile("siteStamp", Array("extensions" => "gif,jpg,jpeg,png", "max_height" => 70, "max_width" => 190, "make_preview" => "Y"));
	}

}

class PayerAndLocStep extends CWizardStep
{
	function InitStep()
	{
		$this->SetStepID("payer_and_loc");

		$this->SetPrevStep("shop_settings");
		$this->SetNextStep("agents_settings");

		$this->SetTitle(Loc::getMessage("WIZ_STEP_PL"));
		$this->SetNextCaption(Loc::getMessage("NEXT_BUTTON"));
		$this->SetPrevCaption(Loc::getMessage("PREVIOUS_BUTTON"));

		$wizard =& $this->GetWizard();

		$shopLocalization = $wizard->GetVar("shopLocalization", true);
		$siteID = $wizard->GetVar("siteID");

		if ($shopLocalization == "ua")
			$wizard->SetDefaultVars(
				Array(
					"personType" => Array(
						"fiz" => "Y",
						"fiz_ua" => "Y",
						"ur" => "Y",
					)
				)
			);
		else
			$wizard->SetDefaultVars(
				Array(
					"personType" => Array(
						"fiz" =>  Option::get("zero", "personTypeFiz", "Y", $siteID),
						"ur" => Option::get("zero", "personTypeUr", "Y", $siteID),
					)
				)
			);

	}

	function ShowStep()
	{
		$wizard =& $this->GetWizard();

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
		if(in_array(LANGUAGE_ID, array("ru", "ua")))
		{
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
		}
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
				</div>
			</div>
		</div>';

	}

}

class AgentsSettingsStep extends CWizardStep
{
	function InitStep()
	{
		$wizard =& $this->GetWizard();
		$wizard->solutionName = "zero";
		parent::InitStep();

		$this->SetStepID("agents_settings");

		$this->SetPrevStep("payer_and_loc");
		$this->SetNextStep("data_install");

		$this->SetTitle(Loc::getMessage("wiz_agents_settings"));
		//$this->SetSubTitle(Loc::getMessage("wiz_settings"));
		$this->SetNextCaption(Loc::getMessage("wiz_install"));
		$this->SetPrevCaption(Loc::getMessage("PREVIOUS_BUTTON"));


		/*$siteID = $wizard->GetVar("siteID");


		$wizard->SetDefaultVars(
			Array(
				"siteName" => Option::get("main", "site_zero_name", Loc::getMessage("wiz_name"), $wizard->GetVar("siteID")),
				"siteEmail" => Option::get("main", "site_zero_email_from", "", $wizard->GetVar("siteID")),
				"siteFolder" => Option::get("main", "site_zero_folder", "/", $wizard->GetVar("siteID")),
				"serverName" => Option::get("main", "site_zero_server_name", $_SERVER["SERVER_NAME"], $wizard->GetVar("siteID")),
				"domains" => Option::get("main", "site_zero_domains", "", $wizard->GetVar("siteID")),
				"installDemoData" => Option::get("main", "wizard_demo_data", "N")
			)
		);*/
	}
}

class DataInstallStep extends CDataInstallWizardStep
{
	function CorrectServices(&$arServices)
	{
		$wizard =& $this->GetWizard();
		if($wizard->GetVar("installDemoData") != "Y") {}
	}
}

class FinishStep extends CFinishWizardStep {}
?>