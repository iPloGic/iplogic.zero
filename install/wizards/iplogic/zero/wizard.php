<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;

require_once($_SERVER['DOCUMENT_ROOT']."/bitrix/modules/main/install/wizard_sol/wizard.php");

Loc::loadMessages(__FILE__);

function getSite($wizard) {
	$site = [];
	if (strlen($wizard->GetVar("siteID")) == 2) {
		$siteID = $wizard->GetVar("siteID");
	}
	elseif (defined("WIZARD_DEFAULT_SITE_ID")) {
		$siteID = WIZARD_DEFAULT_SITE_ID;
	}
	if ($siteID) {
		$rsSites = CSite::GetByID($siteID);
		if ($arSite = $rsSites->Fetch()) {
			$site = [
				"ID" => $siteID,
				"DIR" => $arSite["DIR"],
			];
		}
	}
	if (strlen($wizard->GetVar("siteNewID")) == 2) {
		$site = [
			"ID" => $wizard->GetVar("siteNewID"),
			"DIR" => $wizard->GetVar("siteFolder"),
		];
	}
	return $site;
}

class SelectSiteStep extends CSelectSiteWizardStep
{
	function InitStep()
	{
		parent::InitStep();

		$wizard =& $this->GetWizard();
		$wizard->solutionName = "zero";

		$this->SetStepID("select_site");

		$this->SetNextStep("select_template");

		$this->SetTitle(Loc::getMessage("SELECT_SITE_TITLE"));
		$this->SetSubTitle(Loc::getMessage("SELECT_SITE_SUBTITLE"));
	}
}

include(__DIR__."/include/SelectTemplateStep.php");

include(__DIR__."/include/SiteSettingsStep.php");

include(__DIR__."/include/ShopSettingsStep.php");

include(__DIR__."/include/PayerAndLocStep.php");

include(__DIR__."/include/SpecialSettingsStep.php");

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