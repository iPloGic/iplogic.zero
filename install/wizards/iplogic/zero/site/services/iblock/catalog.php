<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Config\Option,
	Bitrix\Main\Loader;

if(!Loader::includeModule("iblock"))
	return;
$iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH."/xml/".LANGUAGE_ID."/catalog.xml"; 
$iblockCode = "catalog_".WIZARD_SITE_ID;
$iblockType = "catalog"; 
$iblockID = false; 

$rsIBlock = CIBlock::GetList(array(), array("CODE" => $iblockCode, "TYPE" => $iblockType));
if ($rsIBlock && $arIBlock = $rsIBlock->Fetch())
{
	$iblockID = $arIBlock["ID"]; 
	if (WIZARD_INSTALL_DEMO_DATA)
	{
		CIBlock::Delete($arIBlock["ID"]); 
		$iblockID = false; 
	}
}

if ($iblockID == false)
{
	$permissions = Array(
		"1" => "X",
		"2" => "R"
	);
	$dbGroup = CGroup::GetList($by = "", $order = "", Array("STRING_ID" => "sale_administrator"));
	if($arGroup = $dbGroup -> Fetch())
	{
		$permissions[$arGroup["ID"]] = 'W';
	}
	$dbGroup = CGroup::GetList($by = "", $order = "", Array("STRING_ID" => "content_editor"));
	if($arGroup = $dbGroup -> Fetch())
	{
		$permissions[$arGroup["ID"]] = 'W';
	}

	$iblockID = WizardServices::ImportIBlockFromXML(
		$iblockXMLFile,
		$iblockCode,
		$iblockType,
		WIZARD_SITE_ID,
		$permissions
	);

	if ($iblockID < 1)
		return;
}

Option::set("zero", "catalog_iblock_id", $iblockID, WIZARD_SITE_ID);

$ibp = new CIBlockProperty;

$brandsIblockId = Option::get("zero", "brands_iblock_id", "", WIZARD_SITE_ID);
if ( $brandsIblockId != "" ) {
	$properties = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("CODE"=>"BRAND", "IBLOCK_ID"=>$iblockID));
	$prop_fields = $properties->GetNext();
	$arFields = Array(
		'LINK_IBLOCK_ID'=>$brandsIblockId
	);
	if(!$ibp->Update($prop_fields['ID'], $arFields))
		echo $ibp->LAST_ERROR;
}

$properties = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("CODE"=>"LINKED", "IBLOCK_ID"=>$iblockID));
$prop_fields = $properties->GetNext();
$arFields = Array(
	'LINK_IBLOCK_ID'=>$iblockID
);
if(!$ibp->Update($prop_fields['ID'], $arFields))
	echo $ibp->LAST_ERROR;

CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/catalog/index.php", array("CATALOG_IBLOCK_ID" => $iblockID));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/bitrix/templates/zero/header.php", array("CATALOG_IBLOCK_ID" => $iblockID));
CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/.catalog.menu_ext.php", array("CATALOG_IBLOCK_ID" => $iblockID));
?>