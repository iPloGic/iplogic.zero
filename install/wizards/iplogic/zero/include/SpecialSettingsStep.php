<?php

use \Bitrix\Main\Config\Option;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;

class SpecialSettingsStep extends CWizardStep
{
	function InitStep()
	{
		$this->SetStepID("special_settings");

		$IS_SHOP = (Loader::includeModule("sale") && Loader::includeModule("catalog"));

		if( $IS_SHOP ) {
			$this->SetPrevStep("payer_and_loc");
		}
		else {
			$this->SetPrevStep("site_settings");
		}
		$this->SetNextStep("data_install");

		$this->SetTitle(Loc::getMessage("wiz_agents_settings"));
		$this->SetNextCaption(Loc::getMessage("wiz_install"));
		$this->SetPrevCaption(Loc::getMessage("PREVIOUS_BUTTON"));

		$wizard =& $this->GetWizard();

		$siteID = getSite($wizard)["ID"];

		$wizard->SetDefaultVars(
			[
				"phpInterface"  => "Y",
				"pubAuth"       => "Y",
				"pubPrivate"    => "Y",
				"pubNews"       => "Y",
				"pubCatalog"    => "Y",
				"pubCart"       => "Y",
				"pubIndex"      => "Y",
				"pub404"        => "Y",
				"menuTop"       => "Y",
				"menuSide"      => "Y",
				"menuBottom"    => "Y",
				"menuCatalog"   => "Y",
				"iblockNews"    => "Y",
				"iblockCatalog" => "Y",
				"iblockOffer"   => "Y",
				"iblockBrand"   => "Y",
				"iblockBanner"  => "Y",
			]
		);
	}

	function ShowStep()
	{
		$wizard =& $this->GetWizard();

		$IS_SHOP = (Loader::includeModule("sale") && Loader::includeModule("catalog"));

		$this->content .= '<div style="text-align: right;" ><a href="javascript:void(0);" class="uncheck">' .
			Loc::getMessage('WIZ_UNCHECK_ALL') . '</a></div>';

		$this->content .= '<div class="wizard-catalog-title">' . Loc::getMessage('WIZ_SYSTEM_FILES') . '</div>';
		$this->content .= '<div class="wizard-input-form">';
		$this->content .= '
		<div class="wizard-input-form-block">
			<div style="padding-top:10px">
				<div class="wizard-input-form-field wizard-input-form-field-checkbox">
					<div class="wizard-catalog-form-item">
						' . $this->ShowCheckboxField('phpInterface', 'Y', (["id" => "phpInterface"])) .
			' <label for="phpInterface">' . Loc::getMessage("WIZ_PHP_INTERFACE") . '</label><br />
					</div>
				</div>
			</div>
		</div>';
		$this->content .= '</div>';

		$this->content .= '<div class="wizard-catalog-title">' . Loc::getMessage('WIZ_PUBLIC') . '</div>';
		$this->content .= '<div class="wizard-input-form">
		<div class="wizard-input-form-block">
			<div style="padding-top:10px">
				<div class="wizard-input-form-field wizard-input-form-field-checkbox">';
		$this->content .= '			<div class="wizard-catalog-form-item">
						' . $this->ShowCheckboxField('pubAuth', 'Y', (["id" => "pubAuth"])) .
			' <label for="pubAuth">' . Loc::getMessage("WIZ_PUB_AUTH") . '</label><br />
					</div>';
		$this->content .= '			<div class="wizard-catalog-form-item">
						' . $this->ShowCheckboxField('pubPrivate', 'Y', (["id" => "pubPrivate"])) .
			' <label for="pubPrivate">' . Loc::getMessage("WIZ_PUB_PRIVATE") . '</label><br />
					</div>';
		$this->content .= '			<div class="wizard-catalog-form-item">
						' . $this->ShowCheckboxField('pubNews', 'Y', (["id" => "pubNews"])) .
			' <label for="pubNews">' . Loc::getMessage("WIZ_PUB_NEWS") . '</label><br />
					</div>';
		$this->content .= '			<div class="wizard-catalog-form-item">
						' . $this->ShowCheckboxField('pubCatalog', 'Y', (["id" => "pubCatalog"])) .
			' <label for="pubCatalog">' . Loc::getMessage("WIZ_PUB_CATALOG") . '</label><br />
					</div>';
		if( $IS_SHOP ) {
			$this->content .= '			<div class="wizard-catalog-form-item">
						' . $this->ShowCheckboxField('pubCart', 'Y', (["id" => "pubCart"])) .
				' <label for="pubCart">' . Loc::getMessage("WIZ_PUB_CART") . '</label><br />
					</div>';
		}
		$this->content .= '			<div class="wizard-catalog-form-item">
						' . $this->ShowCheckboxField('pubIndex', 'Y', (["id" => "pubIndex"])) .
			' <label for="pubIndex">' . Loc::getMessage("WIZ_PUB_INDEX") . '</label><br />
					</div>';
		$this->content .= '			<div class="wizard-catalog-form-item">
						' . $this->ShowCheckboxField('pub404', 'Y', (["id" => "pub404"])) .
			' <label for="pub404">' . Loc::getMessage("WIZ_PUB_404") . '</label><br />
					</div>';
		$this->content .= '		</div>
			</div>
		</div>
		</div>';

		$this->content .= '<div class="wizard-catalog-title">' . Loc::getMessage('WIZ_MENU') . '</div>';
		$this->content .= '<div class="wizard-input-form">
		<div class="wizard-input-form-block">
			<div style="padding-top:10px">
				<div class="wizard-input-form-field wizard-input-form-field-checkbox">';
		$this->content .= '			<div class="wizard-catalog-form-item">
						' . $this->ShowCheckboxField('menuTop', 'Y', (["id" => "menuTop"])) .
			' <label for="menuTop">' . Loc::getMessage("WIZ_MENU_TOP") . '</label><br />
					</div>';
		$this->content .= '			<div class="wizard-catalog-form-item">
						' . $this->ShowCheckboxField('menuBottom', 'Y', (["id" => "menuBottom"])) .
			' <label for="menuBottom">' . Loc::getMessage("WIZ_MENU_BOTTOM") . '</label><br />
					</div>';
		$this->content .= '			<div class="wizard-catalog-form-item">
						' . $this->ShowCheckboxField('menuSide', 'Y', (["id" => "menuSide"])) .
			' <label for="menuSide">' . Loc::getMessage("WIZ_MENU_SIDE") . '</label><br />
					</div>';
		$this->content .= '			<div class="wizard-catalog-form-item">
						' . $this->ShowCheckboxField('menuCatalog', 'Y', (["id" => "menuCatalog"])) .
			' <label for="menuCatalog">' . Loc::getMessage("WIZ_MENU_CATALOG") . '</label><br />
					</div>';
		$this->content .= '		</div>
			</div>
		</div>
		</div>';

		$this->content .= '<div class="wizard-catalog-title">' . Loc::getMessage('WIZ_IBLOCKS') . '</div>';
		$this->content .= '<div class="wizard-input-form">
		<div class="wizard-input-form-block">
			<div style="padding-top:10px">
				<div class="wizard-input-form-field wizard-input-form-field-checkbox">';
		$this->content .= '			<div class="wizard-catalog-form-item">
						' . $this->ShowCheckboxField('iblockNews', 'Y', (["id" => "iblockNews"])) .
			' <label for="iblockNews">' . Loc::getMessage("WIZ_IBLOCKS_NEWS") . '</label><br />
					</div>';
		$this->content .= '			<div class="wizard-catalog-form-item">
						' . $this->ShowCheckboxField('iblockCatalog', 'Y', (["id" => "iblockCatalog"])) .
			' <label for="iblockCatalog">' . Loc::getMessage("WIZ_IBLOCKS_CATALOG") . '</label><br />
					</div>';
		if( $IS_SHOP ) {
			$this->content .= '			<div class="wizard-catalog-form-item">
						' . $this->ShowCheckboxField('iblockOffer', 'Y', (["id" => "iblockOffer"])) .
				' <label for="iblockOffer">' . Loc::getMessage("WIZ_IBLOCKS_OFFER") . '</label><br />
					</div>';
		}
		$this->content .= '			<div class="wizard-catalog-form-item">
						' . $this->ShowCheckboxField('iblockBrand', 'Y', (["id" => "iblockBrand"])) .
			' <label for="iblockBrand">' . Loc::getMessage("WIZ_IBLOCKS_BRANDS") . '</label><br />
					</div>';
		$this->content .= '			<div class="wizard-catalog-form-item">
						' . $this->ShowCheckboxField('iblockBanner', 'Y', (["id" => "iblockBanner"])) .
			' <label for="iblockBanner">' . Loc::getMessage("WIZ_IBLOCKS_BANNER") . '</label><br />
					</div>';
		$this->content .= '		</div>
			</div>
		</div>
		</div>';

		$this->content .= '<div class="wizard-catalog-title">' . Loc::getMessage('WIZ_AGENTS') . '</div>';
		$this->content .= '<div class="wizard-input-form">';
		$this->content .= '
		<div class="wizard-input-form-block">
			<div style="padding-top:10px">
				<div class="wizard-input-form-field wizard-input-form-field-checkbox">';
		if( $IS_SHOP ) {
			$this->content .= '			<div class="wizard-catalog-form-item">
						' . $this->ShowCheckboxField('agentCBRF', 'Y', (["id" => "agentCBRF"])) .
				' <label for="agentCBRF">' . Loc::getMessage("WIZ_AGENT_CBRF") . '</label><br />
					</div>';
		}
		$this->content .= '			<div class="wizard-catalog-form-item">
						' . $this->ShowCheckboxField('agentUC', 'Y', (["id" => "agentUC"])) .
			' <label for="agentUC">' . Loc::getMessage("WIZ_AGENT_UPLOAD_CLEAN") . '</label><br />
					</div>
				</div>
			</div>
		</div>';
		$this->content .= '</div>';

		$this->content .= "
			<script
			  src=\"https://code.jquery.com/jquery-3.6.0.min.js\"
			  integrity=\"sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=\"
			  crossorigin=\"anonymous\"></script>
				<script>
					$('.uncheck').on('click', function(){
						$('input[type=\'checkbox\']').prop('checked', false);

					});
				</script>
			";

	}

}