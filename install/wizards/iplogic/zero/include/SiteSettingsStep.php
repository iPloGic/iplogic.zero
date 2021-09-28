<?php
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;

class SiteSettingsStep extends CSiteSettingsWizardStep
{
	protected $siteID;

	function InitStep()
	{
		$this->SetStepID("site_settings");

		$this->SetPrevStep("select_template");
		$this->SetNextStep("shop_settings");

		$this->SetTitle(Loc::getMessage("wiz_site_settings"));
		$this->SetNextCaption(Loc::getMessage("NEXT_BUTTON"));
		$this->SetPrevCaption(Loc::getMessage("PREVIOUS_BUTTON"));

		$wizard =& $this->GetWizard();

		$this->siteID = getSite($wizard)["ID"];

		$wizard->SetDefaultVars(
			/*[
				"siteName" => Option::get("zero", "site_zero_name", Loc::getMessage("wiz_name"), $this->siteID),
				"siteEmail" => Option::get("zero", "site_zero_email_from", "info@".$_SERVER["SERVER_NAME"], $this->siteID),
				"siteFolder" => Option::get("zero", "site_zero_folder", getSite($wizard)["DIR"], $this->siteID),
				"serverName" => Option::get("zero", "site_zero_server_name", "ff".$_SERVER["SERVER_NAME"], $this->siteID),
				"domains" => Option::get("zero", "site_zero_domains", "", $this->siteID),
				//"installDemoData" => Option::get("zero", "wizard_demo_data", "N")
			]*/
			[
				"siteName" => Loc::getMessage("wiz_name"),
				"siteEmail" => "info@".$_SERVER["SERVER_NAME"],
				"siteFolder" => getSite($wizard)["DIR"],
				"serverName" => $_SERVER["SERVER_NAME"],
				"domains" => ""
				//"installDemoData" => Option::get("zero", "wizard_demo_data", "N")
			]
		);
	}

	function OnPostForm()
	{
		$wizard =& $this->GetWizard();

		/*if ($wizard->IsNextButtonClick())
		{
			Option::set("zero", "site_zero_name", str_replace(Array("<"), Array("&lt;"), $wizard->GetVar("siteName")), $this->siteID);
			Option::set("zero", "site_zero_email_from", str_replace(Array("<"), Array("&lt;"), $wizard->GetVar("siteEmail")), $this->siteID);
			Option::set("zero", "site_zero_folder", str_replace(Array("<"), Array("&lt;"), $wizard->GetVar("siteFolder")), $this->siteID);
			Option::set("zero", "site_zero_server_name", str_replace(Array("<"), Array("&lt;"), $wizard->GetVar("serverName")), $this->siteID);
			Option::set("zero", "site_zero_domains", str_replace(Array("<"), Array("&lt;"), $wizard->GetVar("domains")), $this->siteID);
		}*/
	}

	function ShowStep()
	{
		$wizard =& $this->GetWizard();

		$this->content .= '<div class="wizard-input-form-block"><div class="wizard-catalog-title">'.Loc::getMessage("wiz_company_name").'</div>';
		$this->content .= $this->ShowInputField("text", "siteName", ["id" => "site-name", "class" => "wizard-field"])."</div>";

		$this->content .= '<div class="wizard-input-form-block"><div class="wizard-catalog-title">'.Loc::getMessage("wiz_email_from").'</div>';
		$this->content .= $this->ShowInputField("text", "siteEmail", ["id" => "email-from", "class" => "wizard-field"])."</div>";

		$this->content .= '<div class="wizard-input-form-block"><div class="wizard-catalog-title">'.Loc::getMessage("wiz_site_folder").'</div>';
		$this->content .= $this->ShowInputField("text", "siteFolder", ["id" => "site-folder", "class" => "wizard-field"])."</div>";

		$this->content .= '<div class="wizard-input-form-block"><div class="wizard-catalog-title">'.Loc::getMessage("wiz_server_name").'</div>';
		$this->content .= $this->ShowInputField("text", "serverName", ["id" => "server-name", "class" => "wizard-field"])."</div>";

		$this->content .= '<div class="wizard-input-form-block"><div class="wizard-catalog-title">'.Loc::getMessage("wiz_domains").'</div>';
		$this->content .= $this->ShowInputField("textarea", "domains", ["id" => "domains", "class" => "wizard-field"])."</div>";

		/*$firstStep = Option::get("main", "wizard_first" . substr($wizard->GetID(), 7)  . "_" . $this->siteID, false, $this->siteID);
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
		}
		$this->content .= $this->ShowHiddenField("installDemoData","Y");

		$formName = $wizard->GetFormName();
		$installCaption = $this->GetNextCaption();
		$nextCaption = Loc::getMessage("NEXT_BUTTON");*/
	}
}