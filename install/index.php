<?
use \Bitrix\Main\Localization\Loc,
	\Bitrix\Main\Loader,
	\Bitrix\Main\Config\Option,
	\Bitrix\Main\IO\Directory,
	\Bitrix\Main\Application,
	\Bitrix\Main\EventManager,
	\Bitrix\Main\ModuleManager,
	\Iplogic\Beru\ProfileTable;

Loc::loadMessages(__FILE__);
Class iplogic_zero extends CModule
{
	const MODULE_ID = 'iplogic.zero';
	var $MODULE_ID = 'iplogic.zero';
	var $MODULE_VERSION;
	var $MODULE_VERSION_DATE;
	var $MODULE_NAME;
	var $MODULE_DESCRIPTION;
	var $MODULE_CSS;
	var $strError = '';

	function __construct()
	{
		$arModuleVersion = array();
		include(dirname(__FILE__)."/version.php");
		$this->MODULE_VERSION = $arModuleVersion["VERSION"];
		$this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
		$this->MODULE_NAME = Loc::getMessage("IPLOGIC_MODULE_NAME");
		$this->MODULE_DESCRIPTION = Loc::getMessage("IPLOGIC_MODULE_DESC");

		$this->PARTNER_NAME = Loc::getMessage("IPLOGIC_PARTNER_NAME");
		$this->PARTNER_URI = Loc::getMessage("IPLOGIC_PARTNER_URI");
	}

	function InstallDB($arParams = array())
	{
		if ($arParams["installcragent"] == "Y") {
			CAgent::AddAgent( "\Iplogic\Zero\Agent::GetCurrencyRateAgent();", self::MODULE_ID, "N", 86400, "", "Y");
		}
		if ($arParams["installcuagent"] == "Y") {
			CAgent::AddAgent( "\Iplogic\Zero\Agent::CleanUpUploadAgent();", self::MODULE_ID, "N", 86400, "", "Y");
		}
		Option::set(self::MODULE_ID,"istalled",'Y');
		return true;
	}

	function UnInstallDB($arParams = array())
	{
		CAgent::RemoveModuleAgents(self::MODULE_ID);
		Option::delete(self::MODULE_ID);
		return true;
	}

	function InstallEvents()
	{
		return true;
	}

	function UnInstallEvents()
	{
		return true;
	}

	function InstallFiles($arParams = array())
	{
		CopyDirFiles(__DIR__.'/install/wizards', Application::getDocumentRoot().'/bitrix/wizards', true, true);
		CopyDirFiles(__DIR__.'/install/components', Application::getDocumentRoot().'/bitrix/components', true, true);
		return true;
	}

	function UnInstallFiles()
	{
		DeleteDirFilesEx("/bitrix/wizards/iplogic/zero", true, true);
		return true;
	}

	function DoInstall()
	{
		global $APPLICATION, $step;
		$step = IntVal($step);
		if($step<2)
		{
			$APPLICATION->IncludeAdminFile(Loc::getMessage("IPLOGIC_MODULE_INSTALLED_TITLE"), Application::getDocumentRoot()."/bitrix/modules/".$this->MODULE_ID."/install/step1.php");
		}
		elseif($step==2)
		{
			$this->InstallFiles();
			$this->InstallDB(array(
				"installcragent" => $_REQUEST["installcragent"],
				"installcuagent" => $_REQUEST["installcuagent"],
			));
			RegisterModule(self::MODULE_ID);
			$APPLICATION->IncludeAdminFile(Loc::getMessage("IPLOGIC_MODULE_INSTALLED_TITLE"), Application::getDocumentRoot()."/bitrix/modules/".$this->MODULE_ID."/install/step2.php");
		}
		return true;
	}

	function DoUninstall()
	{
		global $APPLICATION, $step;
		$step = IntVal($step);
		if($step<2)
		{
			$APPLICATION->IncludeAdminFile(Loc::getMessage("IPLOGIC_MODULE_INSTALLED_TITLE"), Application::getDocumentRoot()."/bitrix/modules/".$this->MODULE_ID."/install/unstep1.php");
		}
		elseif($step==2) {
			UnRegisterModule(self::MODULE_ID);
			$this->UnInstallDB();
			$this->UnInstallFiles();
			$APPLICATION->IncludeAdminFile(Loc::getMessage("IPLOGIC_MODULE_UNINSTALLED_TITLE"),
				Application::getDocumentRoot() . "/bitrix/modules/" . $this->MODULE_ID . "/install/unstep2.php");
		}
		return true;
	}
}
?>
