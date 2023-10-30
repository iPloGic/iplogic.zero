<?php

use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;

class SelectTemplateStep extends CSelectTemplateWizardStep
{
	function InitStep()
	{
		$wizard =& $this->GetWizard();
		$wizard->solutionName = "zero";

		$this->SetStepID("select_template");

		if( !defined("WIZARD_DEFAULT_SITE_ID") ) {
			$this->SetPrevStep("select_site");
		}
		$this->SetNextStep("site_settings");

		$this->SetTitle(Loc::getMessage("SELECT_TEMPLATE_TITLE"));
		$this->SetNextCaption(Loc::getMessage("NEXT_BUTTON"));
		$this->SetPrevCaption(Loc::getMessage("PREVIOUS_BUTTON"));

		$wizard->SetDefaultVars(
			[
				'templateDescription' => Loc::getMessage('WIZ_TEMPLATE_DESCRIPTION_DEFAULT'),
				'templateName'        => Loc::getMessage('WIZ_TEMPLATE_NAME_DEFAULT'),
				'templateDir'         => "new_template",
			]
		);
	}

	function OnPostForm()
	{
		$wizard =& $this->GetWizard();

		$templateDir = $wizard->GetVar('templateDir');

		if( !preg_match('#^[A-Za-z0-9_]+$#is', $templateDir) ) {
			$this->SetError(GetMessage('WIZ_TEMPLATE_DIR_ERROR'));
		}
	}

	function ShowStep()
	{
		$wizard =& $this->GetWizard();

		$siteID = getSite($wizard)["ID"];

		$templatesPath = WizardServices::GetTemplatesPath($wizard->GetPath() . "/site");
		$arTemplates = WizardServices::GetTemplates($templatesPath);

		$arTemplateOrder = [];
		foreach( $arTemplates as $template ) {
			$arTemplateOrder[$template["SORT"]] = $template["ID"];
		}
		ksort($arTemplateOrder);
		$arTemplateInfo = [];
		foreach( $arTemplateOrder as $ID ) {
			$preview = false;
			if(
			file_exists(
				$_SERVER["DOCUMENT_ROOT"] . $wizard->GetPath() . "/site/templates/" . $ID . "/images/" . LANGUAGE_ID .
				"/preview.gif"
			)
			) {
				$preview = $wizard->GetPath() . "/site/templates/" . $ID . "/images/" . LANGUAGE_ID . "/preview.gif";
			}
			elseif(
			file_exists(
				$_SERVER["DOCUMENT_ROOT"] . $wizard->GetPath() . "/site/templates/" . $ID . "/images/preview.gif"
			)
			) {
				$preview = $wizard->GetPath() . "/site/templates/" . $ID . "/images/preview.gif";
			}
			$arTemplateInfo[] = [
				"ID"          => $ID,
				"NAME"        => $arTemplates[$ID]["NAME"],
				"DESCRIPTION" => $arTemplates[$ID]["DESCRIPTION"],
				"PREVIEW"     => $preview,
			];
		}

		//$defaultTemplateID = Option::get("main", "wizard_template_id", "zero", $siteID);
		if( !in_array($defaultTemplateID, $arTemplates) ) {
			$defaultTemplateID = $arTemplateInfo[0]["ID"];
		}
		$wizard->SetDefaultVar("wizTemplateID", $defaultTemplateID);

		$this->content .= '<div class="inst-template-list-block">';
		foreach( $arTemplateInfo as $arTemplate ) {
			if( !$arTemplate ) {
				continue;
			}

			$this->content .= '<div class="inst-template-description" style="height:auto;">';
			$this->content .= $this->ShowRadioField(
				"wizTemplateID",
				$arTemplate["ID"],
				["id" => $arTemplate["ID"], "class" => "inst-template-list-inp"]
			);


			$this->content .= '<label for="' . $arTemplate["ID"] .
				'" class="inst-template-list-label" style="width:100%">';
			$this->content .= CFile::ShowImage(
				$arTemplate["PREVIEW"],
				100,
				100,
				'class="inst-template-list-img" style="border:1px solid black;"',
				"",
				false
			);
			$this->content .= $arTemplate["NAME"];
			$this->content .= '<span style="font-weight: normal;"> (' . $arTemplate["DESCRIPTION"] . ')</span>';
			$this->content .= "</label>";
			$this->content .= "</div>";
		}

		$this->content .= "</div>";
		$this->content .= '<div class="wizard-catalog-title">' . Loc::getMessage('WIZ_TEMPLATE_SETTINGS') . '</div>';
		$this->content .= '<div class="wizard-input-form">
									<div class="wizard-input-form-block">
										<label for="templateName" class="wizard-input-title">' .
			Loc::getMessage('WIZ_TEMPLATE_NAME') . '</label><br>
										' .
			$this->ShowInputField('text', 'templateName', ["id" => "templateName", "class" => "wizard-field"]) . '
									</div>
									<div class="wizard-input-form-block">
										<label for="templateDescription" class="wizard-input-title">' .
			Loc::getMessage('WIZ_TEMPLATE_DESCRIPTION') . '</label><br>
										' . $this->ShowInputField(
				'text',
				'templateDescription',
				["id" => "templateDescription", "class" => "wizard-field"]
			) . '
									</div>
									<div class="wizard-input-form-block">
										<label for="templateDir" class="wizard-input-title">' .
			Loc::getMessage('WIZ_TEMPLATE_DIR') . '</label><br>
										' .
			$this->ShowInputField('text', 'templateDir', ["id" => "templateDir", "class" => "wizard-field"]) . '
									</div>
								</div>';
	}
}
