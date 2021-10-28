<?
if( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true ) die();

use Bitrix\Main\Loader,
	Bitrix\Main\Config\Option,
	Bitrix\Main\Localization\Loc;

function exEvent($name, $arFields)
{
	$eventTypeExists = false;
	$db_res = CEventType::GetList(["TYPE_ID" => $name]);
	if( $db_res ) {
		$count = $db_res->SelectedRowsCount();
		if( $count > 0 ) {
			$eventTypeExists = true;
		}
	}
	if( !$eventTypeExists ) {
		$oEventType = new CEventType();
		$oEventTypeSrcID = $oEventType->Add($arFields);
	}
}

function exMessage($name, $arFields)
{
	$eventMessageExists = false;
	$eventMessageID = 0;
	$by = "id";
	$order = "asc";
	$db_res = CEventMessage::GetList($by, $order, ["TYPE_ID" => $name, "SITE_ID" => [WIZARD_SITE_ID]]);
	if( $db_res ) {
		$count = $db_res->SelectedRowsCount();
		if( $count > 0 ) {
			$eventMessageExists = true;
			if( $count == 1 ) {
				while( $res = $db_res->GetNext() ) {
					$eventMessageID = $res["ID"];
				}
			}
		}
	}
	$oEventMessage = new CEventMessage();
	if( !$eventMessageExists ) {
		$eventMessageID = $oEventMessage->Add($arFields);
	}
	elseif( intVal($eventMessageID) > 0 ) {
		$oEventMessage->Update($eventMessageID, $arFields);
	}
}


/*$dbSite = CSite::GetByID(WIZARD_SITE_ID);
if($arSite = $dbSite -> Fetch()) $lang = $arSite["LANGUAGE_ID"];
if(strlen($lang) <= 0) $lang = "ru";

WizardServices::IncludeServiceLang("mail.php", $lang);*/


/* NEW_USER_CONFIRM */
$arFields = [
	"ACTIVE"     => "Y",
	"EVENT_NAME" => "NEW_USER_CONFIRM",
	"LID"        => WIZARD_SITE_ID,
	"EMAIL_FROM" => "#DEFAULT_EMAIL_FROM#",
	"EMAIL_TO"   => "#EMAIL#",
	"SUBJECT"    => Loc::getMessage("NEW_USER_CONFIRM_SUBJECT"),
	"MESSAGE"    => Loc::getMessage("NEW_USER_CONFIRM_TEXT"),
	"BODY_TYPE"  => "text",
];
exMessage("NEW_USER_CONFIRM", $arFields);

/* USER_PASS_REQUEST */
$arFields = [
	"ACTIVE"     => "Y",
	"EVENT_NAME" => "USER_PASS_REQUEST",
	"LID"        => WIZARD_SITE_ID,
	"EMAIL_FROM" => "#DEFAULT_EMAIL_FROM#",
	"EMAIL_TO"   => "#EMAIL#",
	"SUBJECT"    => Loc::getMessage("USER_PASS_REQUEST_SUBJECT"),
	"MESSAGE"    => Loc::getMessage("USER_PASS_REQUEST_TEXT"),
	"BODY_TYPE"  => "text",
];
exMessage("NEW_USER_CONFIRM", $arFields);

/* USER_INFO */
$arFields = [
	"ACTIVE"     => "Y",
	"EVENT_NAME" => "USER_INFO",
	"LID"        => WIZARD_SITE_ID,
	"EMAIL_FROM" => "#DEFAULT_EMAIL_FROM#",
	"EMAIL_TO"   => "#EMAIL#",
	"SUBJECT"    => Loc::getMessage("USER_INFO_SUBJECT"),
	"MESSAGE"    => Loc::getMessage("USER_INFO_TEXT"),
	"BODY_TYPE"  => "text",
];
exMessage("USER_INFO", $arFields);

/* USER_INVITE */
$arFields = [
	"ACTIVE"     => "Y",
	"EVENT_NAME" => "USER_INVITE",
	"LID"        => WIZARD_SITE_ID,
	"EMAIL_FROM" => "#DEFAULT_EMAIL_FROM#",
	"EMAIL_TO"   => "#EMAIL#",
	"SUBJECT"    => Loc::getMessage("USER_INVITE_SUBJECT"),
	"MESSAGE"    => Loc::getMessage("USER_INVITE_TEXT"),
	"BODY_TYPE"  => "text",
];
exMessage("USER_INVITE", $arFields);

?>