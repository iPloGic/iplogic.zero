<?
use Bitrix\Main\Localization\Loc;

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
		$this->MODULE_NAME = Loc::getMessage("IPLOGIC_ZERO_MODULE_NAME");
		$this->MODULE_DESCRIPTION = Loc::getMessage("IPLOGIC_ZERO_MODULE_DESC");

		$this->PARTNER_NAME = Loc::getMessage("IPLOGIC_ZERO_PARTNER_NAME");
		$this->PARTNER_URI = Loc::getMessage("IPLOGIC_ZERO_PARTNER_URI");
	}

	function InstallDB($arParams = array())
	{
		RegisterModuleDependences('sale', 'onSaleDeliveryRestrictionsClassNamesBuildList', self::MODULE_ID, 'Iplogic\Zero\CIplogicZero', 'exclLocationsDeliveryRestrictions');
		CAgent::AddAgent( "Iplogic\Zero\CIplogicZero::GetCurrencyRateAgent();", self::MODULE_ID, "N", 86400, "", "Y");
		CAgent::AddAgent( "Iplogic\Zero\CIplogicZero::CleanUpUploadAgent();", self::MODULE_ID, "N", 86400, "", "Y");
		return true;
	}

	function UnInstallDB($arParams = array())
	{
		UnRegisterModuleDependences('sale', 'onSaleDeliveryRestrictionsClassNamesBuildList', self::MODULE_ID, 'Iplogic\Zero\CIplogicZero', 'exclLocationsDeliveryRestrictions');
		CAgent::RemoveModuleAgents(self::MODULE_ID);
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
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/iplogic.zero/install/wizards", $_SERVER["DOCUMENT_ROOT"]."/bitrix/wizards", true, true);
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/iplogic.zero/install/components", $_SERVER["DOCUMENT_ROOT"]."/bitrix/components", true, true);
		return true;
	}

	function UnInstallFiles()
	{
		DeleteDirFilesEx($_SERVER["DOCUMENT_ROOT"]."/bitrix/wizards/iplogic/zero", true, true);
		return true;
	}

	function DoInstall()
	{
		global $APPLICATION;
		$this->InstallFiles();
		$this->InstallDB();
		RegisterModule(self::MODULE_ID);
	}

	function DoUninstall()
	{
		global $APPLICATION;
		UnRegisterModule(self::MODULE_ID);
		$this->UnInstallDB();
		$this->UnInstallFiles();
	}
}
?>
