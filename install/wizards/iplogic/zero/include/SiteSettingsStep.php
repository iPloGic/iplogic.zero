<?php

use \Bitrix\Main\Config\Option;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;

class SiteSettingsStep extends CSiteSettingsWizardStep
{
	protected $siteID;

	function InitStep()
	{
		$this->SetStepID("site_settings");

		$IS_SHOP = (Loader::includeModule("sale") && Loader::includeModule("catalog"));

		$this->SetPrevStep("select_template");
		if( $IS_SHOP ) {
			$this->SetNextStep("shop_settings");
		}
		else {
			$this->SetNextStep("special_settings");
		}

		$this->SetTitle(Loc::getMessage("wiz_site_settings"));
		$this->SetNextCaption(Loc::getMessage("NEXT_BUTTON"));
		$this->SetPrevCaption(Loc::getMessage("PREVIOUS_BUTTON"));

		$wizard =& $this->GetWizard();

		$this->siteID = getSite($wizard)["ID"];

		$arDefValues = [
			"siteName"   => Loc::getMessage("wiz_name"),
			"siteEmail"  => "info@" . $_SERVER["SERVER_NAME"],
			"siteFolder" => getSite($wizard)["DIR"],
			"serverName" => $_SERVER["SERVER_NAME"],
			"domains"    => "",
		];
		if( !$IS_SHOP ) {
			$arDefValues["siteName"] = Loc::getMessage("wiz_name_site");
		}

		$wizard->SetDefaultVars($arDefValues);
	}

	function OnPostForm()
	{
		$wizard =& $this->GetWizard();
	}

	function ShowStep()
	{

		$wizard =& $this->GetWizard();

		$this->content .= '<div class="wizard-input-form-block"><div class="wizard-catalog-title">' .
			Loc::getMessage("wiz_company_name") . '</div>';
		$this->content .= $this->ShowInputField("text", "siteName", ["id" => "site-name", "class" => "wizard-field"]) .
			"</div>";

		$this->content .= '<div class="wizard-input-form-block"><div class="wizard-catalog-title">' .
			Loc::getMessage("wiz_email_from") . '</div>';
		$this->content .= $this->ShowInputField(
				"text",
				"siteEmail",
				["id" => "email-from", "class" => "wizard-field"]
			) . "</div>";

		$attr = ["id" => "site-folder", "class" => "wizard-field"];
		if( getSite($wizard)["DIR"] != "" ) {
			$attr["disabled"] = "disabled";
		}
		$this->content .= '<div class="wizard-input-form-block"><div class="wizard-catalog-title">' .
			Loc::getMessage("wiz_site_folder") . '</div>';
		$this->content .= $this->ShowInputField("text", "siteFolder", $attr) . "</div>";

		$this->content .= '<div class="wizard-input-form-block"><div class="wizard-catalog-title">' .
			Loc::getMessage("wiz_server_name") . '</div>';
		$this->content .= $this->ShowInputField(
				"text",
				"serverName",
				["id" => "server-name", "class" => "wizard-field"]
			) . "</div>";

		$this->content .= '<div class="wizard-input-form-block"><div class="wizard-catalog-title">' .
			Loc::getMessage("wiz_domains") . '</div>';
		$this->content .= $this->ShowInputField("textarea", "domains", ["id" => "domains", "class" => "wizard-field"]) .
			"</div>";

	}
}