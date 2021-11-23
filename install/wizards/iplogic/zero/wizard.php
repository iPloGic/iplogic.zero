<? if( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true ) {
	die();
}

use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;

require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/install/wizard_sol/wizard.php");

Loc::loadMessages(__FILE__);

function getSite($wizard)
{
	$site = [];
	if( strlen($wizard->GetVar("siteID")) == 2 ) {
		$siteID = $wizard->GetVar("siteID");
	}
	elseif( defined("WIZARD_DEFAULT_SITE_ID") ) {
		$siteID = WIZARD_DEFAULT_SITE_ID;
	}
	if( $siteID ) {
		$rsSites = CSite::GetByID($siteID);
		if( $arSite = $rsSites->Fetch() ) {
			$site = [
				"ID"  => $siteID,
				"DIR" => $arSite["DIR"],
				"LANG" => $arSite["LANGUAGE_ID"],
			];
		}
	}
	if( strlen($wizard->GetVar("siteNewID")) == 2 ) {
		$site = [
			"ID"  => $wizard->GetVar("siteNewID"),
			"DIR" => $wizard->GetVar("siteFolder"),
			"LANG" => "ru",
		];
	}
	return $site;
}

function ImportIBlockFromXMLEx($xmlFile, $iblockCode, $iblockType, $permissions = [])
{
	if( !CModule::IncludeModule("iblock") ) {
		return false;
	}

	$rsIBlock = CIBlock::GetList([], ["CODE" => $iblockCode, "TYPE" => $iblockType, "SITE_ID" => $siteID]);
	if( $arIBlock = $rsIBlock->Fetch() ) {
		return false;
	}

	$siteID = [WIZARD_SITE_ID];

	require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/iblock/classes/" . mb_strtolower($GLOBALS["DB"]->type) .
		"/cml2.php");
	$iblockID = ImportXMLFile(
		$xmlFile,
		$iblockType,
		$siteID,
		$section_action = "N",
		$element_action = "N",
		$use_crc = false,
		$preview = false,
		$sync = true,
		$return_last_error = false,
		$return_iblock_id = true
	);

	if( $iblockID > 0 ) {

		$ib = new CIBlock;
		$arFields = [
			"CODE" => $iblockCode,
			"XML_ID" => randString(32) . "_" . WIZARD_SITE_ID,
		];
		$ib->Update($iblockID, $arFields);

		if( empty($permissions) ) {
			$permissions = [1 => "X", 2 => "R"];
		}
		CIBlock::SetPermission($iblockID, $permissions);
	}

	return $iblockID;
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

include(__DIR__ . "/include/SelectTemplateStep.php");

include(__DIR__ . "/include/SiteSettingsStep.php");

include(__DIR__ . "/include/ShopSettingsStep.php");

include(__DIR__ . "/include/PayerAndLocStep.php");

include(__DIR__ . "/include/SpecialSettingsStep.php");

class DataInstallStep extends CDataInstallWizardStep
{
	function CorrectServices(&$arServices)
	{
		$wizard =& $this->GetWizard();
		if( $wizard->GetVar("installDemoData") != "Y" ) {
		}
	}
}

class FinishStep extends CFinishWizardStep
{
}

?>