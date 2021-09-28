<?php
use Bitrix\Main\Config\Option;
use Bitrix\Main\Localization\Loc;

class SpecialSettingsStep extends CWizardStep
{
	function InitStep()
	{
		$this->SetStepID("special_settings");

		$this->SetPrevStep("payer_and_loc");
		$this->SetNextStep("data_install");

		$this->SetTitle(Loc::getMessage("wiz_agents_settings"));
		$this->SetNextCaption(Loc::getMessage("wiz_install"));
		$this->SetPrevCaption(Loc::getMessage("PREVIOUS_BUTTON"));

		$wizard =& $this->GetWizard();

		$siteID = getSite($wizard)["ID"];

		$wizard->SetDefaultVars(
			[
				"phpInterface" => "Y"
			]
		);
	}

	function ShowStep()
	{
		$wizard =& $this->GetWizard();

		$this->content .= '<div class="wizard-catalog-title">'.Loc::getMessage('WIZ_SYSTEM_FILES').'</div>';

		$this->content .= '<div class="wizard-input-form">';
		$this->content .= '
		<div class="wizard-input-form-block">
			<div style="padding-top:10px">
				<div class="wizard-input-form-field wizard-input-form-field-checkbox">
					<div class="wizard-catalog-form-item">
						'.$this->ShowCheckboxField('phpInterface', 'Y', (["id" => "phpInterface"])).
			' <label for="phpInterface">'.Loc::getMessage("WIZ_PHP_INTERFACE").'</label><br />
					</div>
				</div>
			</div>
		</div>';
		$this->content .= '</div>';

		$this->content .= '<div class="wizard-catalog-title">'.Loc::getMessage('WIZ_AGENTS').'</div>';

		$this->content .= '<div class="wizard-input-form">';
		$this->content .= '
		<div class="wizard-input-form-block">
			<div style="padding-top:10px">
				<div class="wizard-input-form-field wizard-input-form-field-checkbox">
					<div class="wizard-catalog-form-item">
						'.$this->ShowCheckboxField('agent[CBRF]', 'Y', (["id" => "agentCBRF"])).
			' <label for="agentCBRF">'.Loc::getMessage("WIZ_AGENT_CBRF").'</label><br />
					</div>
					<div class="wizard-catalog-form-item">
						'.$this->ShowCheckboxField('agent[UC]', 'Y', (["id" => "agentUC"])).
			' <label for="agentUC">'.Loc::getMessage("WIZ_AGENT_UPLOAD_CLEAN").'</label><br />
					</div>
				</div>
			</div>
		</div>';
		$this->content .= '</div>';

	}

}