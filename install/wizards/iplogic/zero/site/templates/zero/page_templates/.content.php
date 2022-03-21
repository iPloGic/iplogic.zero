<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
use Bitrix\Main\Localization\Loc;

$TEMPLATE["standard.php"] = ["name"=>Loc::getMessage("STANDARD"), "sort"=>1];
$TEMPLATE["clear.php"] = ["name"=>Loc::getMessage("CLEAR"), "sort"=>2];
?>
