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

	function InstallDB()
	{
		global $DB, $DBType, $APPLICATION;
		RegisterModule(self::MODULE_ID);
		Option::set(self::MODULE_ID,"istalled",'Y');
		Option::set(self::MODULE_ID,"cli_execute_method",'WGET');
		Option::set(self::MODULE_ID,"cli_php",'/usr/bin/php');
		Option::set(self::MODULE_ID,"cli_wget_miss_cert",'Y');
		Option::set(self::MODULE_ID,"agent_currencies",'USD,EUR');
		Option::set(self::MODULE_ID,"agent_delete_files",'Y');
		Option::set(self::MODULE_ID,"agent_save_backup",'Y');
		Option::set(self::MODULE_ID,"agent_search_path",'/iblock');
		Option::set(self::MODULE_ID,"agent_backup_folder",'/iblock_Backup/');
		return true;
	}

	function UnInstallDB($arParams = array())
	{
		global $DB, $DBType, $APPLICATION;
		CAgent::RemoveModuleAgents(self::MODULE_ID);
		Option::delete(self::MODULE_ID);
		UnRegisterModule(self::MODULE_ID);
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
		CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".self::MODULE_ID."/install/wizards/", Application::getDocumentRoot().'/bitrix/wizards', true, true);
		//CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".self::MODULE_ID."/install/components/", Application::getDocumentRoot().'/bitrix/components', true, true);
		return true;
	}

	function UnInstallFiles()
	{
		DeleteDirFilesEx("/bitrix/wizards/iplogic/zero", true, true);
		return true;
	}

	function DoInstall()
	{
		$this->InstallFiles();
		$this->InstallDB();
		return true;
	}

	function DoUninstall()
	{
		global $APPLICATION, $step;
		$step = IntVal($step);
		if($step<2)
		{
			$APPLICATION->IncludeAdminFile(Loc::getMessage("IPLOGIC_MODULE_UNINSTALLED_TITLE"), Application::getDocumentRoot()."/bitrix/modules/".$this->MODULE_ID."/install/unstep1.php");
		}
		elseif($step==2) {
			$this->UnInstallDB();
			$this->UnInstallFiles();
			$APPLICATION->IncludeAdminFile(Loc::getMessage("IPLOGIC_MODULE_UNINSTALLED_TITLE"),
				Application::getDocumentRoot() . "/bitrix/modules/" . $this->MODULE_ID . "/install/unstep2.php");
		}
		return true;
	}
}
?>
