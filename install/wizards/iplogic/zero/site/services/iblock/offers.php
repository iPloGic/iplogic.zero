<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Config\Option,
	Bitrix\Main\Loader;

if(!Loader::includeModule("iblock") || !Loader::includeModule("catalog"))
	return;
$iblockXMLFile = WIZARD_SERVICE_RELATIVE_PATH."/xml/".LANGUAGE_ID."/offers.xml"; 
$iblockCode = "catalog_offers_".WIZARD_SITE_ID;
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

$catalogIblockId = Option::get("zero", "catalog_iblock_id", "", WIZARD_SITE_ID);
if ( $catalogIblockId != "" ) {
	$properties = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), Array("CODE"=>"CML2_LINK", "IBLOCK_ID"=>$iblockID));
	$prop_fields = $properties->GetNext();
	$arFields = Array(
		'LINK_IBLOCK_ID'=>$catalogIblockId
	);
	$ibp = new CIBlockProperty;
	if(!$ibp->Update($prop_fields['ID'], $arFields))
		echo $ibp->LAST_ERROR;
	$ID_SKU = CCatalog::LinkSKUIBlock($catalogIblockId, $iblockID);
	CCatalog::Update($iblockID,array('PRODUCT_IBLOCK_ID' => $catalogIblockId,'SKU_PROPERTY_ID' => $ID_SKU));
}

CWizardUtil::ReplaceMacros(WIZARD_SITE_PATH."/bitrix/templates/zero/header.php", array("OFFERS_IBLOCK_ID" => $iblockID));

?>