<? if( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true ) die();

use Bitrix\Main\Config\Option,
	Bitrix\Main\Localization\Loc,
	Bitrix\Main\Loader;

$dbSite = CSite::GetByID(WIZARD_SITE_ID);
if( $arSite = $dbSite->Fetch() ) {
	$lid = $arSite["LANGUAGE_ID"];
}
if( strlen($lid) <= 0 ) {
	$lid = "ru";
}

$dbEvent =
	CEventMessage::GetList($b = "ID", $order = "ASC", ["EVENT_NAME" => "SALE_NEW_ORDER", "SITE_ID" => WIZARD_SITE_ID]);
if( !($dbEvent->Fetch()) ) {
	Loc::loadMessages($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/sale/install/events.php", $lid);

	$dbEvent = CEventType::GetList(["TYPE_ID" => "SALE_NEW_ORDER"]);
	if( !($dbEvent->Fetch()) ) {
		$et = new CEventType;
		$et->Add([
			"LID"         => $lid,
			"EVENT_NAME"  => "SALE_NEW_ORDER",
			"NAME"        => Loc::getMessage("SALE_NEW_ORDER_NAME"),
			"DESCRIPTION" => Loc::getMessage("SALE_NEW_ORDER_DESC"),
		]);

		$et = new CEventType;
		$et->Add([
			"LID"         => $lid,
			"EVENT_NAME"  => "SALE_NEW_ORDER_RECURRING",
			"NAME"        => Loc::getMessage("SALE_NEW_ORDER_RECURRING_NAME"),
			"DESCRIPTION" => Loc::getMessage("SALE_NEW_ORDER_RECURRING_DESC"),
		]);

		$et = new CEventType;
		$et->Add([
			"LID"         => $lid,
			"EVENT_NAME"  => "SALE_ORDER_REMIND_PAYMENT",
			"NAME"        => Loc::getMessage("SALE_ORDER_REMIND_PAYMENT_NAME"),
			"DESCRIPTION" => Loc::getMessage("SALE_ORDER_REMIND_PAYMENT_DESC"),
		]);

		$et = new CEventType;
		$et->Add([
			"LID"         => $lid,
			"EVENT_NAME"  => "SALE_ORDER_CANCEL",
			"NAME"        => Loc::getMessage("SALE_ORDER_CANCEL_NAME"),
			"DESCRIPTION" => Loc::getMessage("SALE_ORDER_CANCEL_DESC"),
		]);

		$et = new CEventType;
		$et->Add([
			"LID"         => $lid,
			"EVENT_NAME"  => "SALE_ORDER_PAID",
			"NAME"        => Loc::getMessage("SALE_ORDER_PAID_NAME"),
			"DESCRIPTION" => Loc::getMessage("SALE_ORDER_PAID_DESC"),
		]);

		$et = new CEventType;
		$et->Add([
			"LID"         => $lid,
			"EVENT_NAME"  => "SALE_ORDER_DELIVERY",
			"NAME"        => Loc::getMessage("SALE_ORDER_DELIVERY_NAME"),
			"DESCRIPTION" => Loc::getMessage("SALE_ORDER_DELIVERY_DESC"),
		]);

		$et = new CEventType;
		$et->Add([
			"LID"         => $lid,
			"EVENT_NAME"  => "SALE_RECURRING_CANCEL",
			"NAME"        => Loc::getMessage("SALE_RECURRING_CANCEL_NAME"),
			"DESCRIPTION" => Loc::getMessage("SALE_RECURRING_CANCEL_DESC"),
		]);

		$et = new CEventType;
		$et->Add([
			"LID"         => $lid,
			"EVENT_NAME"  => "SALE_SUBSCRIBE_PRODUCT",
			"NAME"        => Loc::getMessage("UP_TYPE_SUBJECT"),
			"DESCRIPTION" => Loc::getMessage("UP_TYPE_SUBJECT_DESC"),
		]);
	}

	$emess = new CEventMessage;
	$emess->Add([
		"ACTIVE"     => "Y",
		"EVENT_NAME" => "SALE_NEW_ORDER",
		"LID"        => WIZARD_SITE_ID,
		"EMAIL_FROM" => "#SALE_EMAIL#",
		"EMAIL_TO"   => "#EMAIL#",
		"BCC"        => "#BCC#",
		"SUBJECT"    => Loc::getMessage("SALE_NEW_ORDER_SUBJECT"),
		"MESSAGE"    => Loc::getMessage("SALE_NEW_ORDER_MESSAGE"),
		"BODY_TYPE"  => "text",
	]);

	$emess = new CEventMessage;
	$emess->Add([
		"ACTIVE"     => "Y",
		"EVENT_NAME" => "SALE_NEW_ORDER_RECURRING",
		"LID"        => WIZARD_SITE_ID,
		"EMAIL_FROM" => "#SALE_EMAIL#",
		"EMAIL_TO"   => "#EMAIL#",
		"BCC"        => "#BCC#",
		"SUBJECT"    => Loc::getMessage("SALE_NEW_ORDER_RECURRING_SUBJECT"),
		"MESSAGE"    => Loc::getMessage("SALE_NEW_ORDER_RECURRING_MESSAGE"),
		"BODY_TYPE"  => "text",
	]);

	$emess = new CEventMessage;
	$emess->Add([
		"ACTIVE"     => "Y",
		"EVENT_NAME" => "SALE_ORDER_CANCEL",
		"LID"        => WIZARD_SITE_ID,
		"EMAIL_FROM" => "#SALE_EMAIL#",
		"EMAIL_TO"   => "#EMAIL#",
		"BCC"        => "#BCC#",
		"SUBJECT"    => Loc::getMessage("SALE_ORDER_CANCEL_SUBJECT"),
		"MESSAGE"    => Loc::getMessage("SALE_ORDER_CANCEL_MESSAGE"),
		"BODY_TYPE"  => "text",
	]);

	$emess = new CEventMessage;
	$emess->Add([
		"ACTIVE"     => "Y",
		"EVENT_NAME" => "SALE_ORDER_DELIVERY",
		"LID"        => WIZARD_SITE_ID,
		"EMAIL_FROM" => "#SALE_EMAIL#",
		"EMAIL_TO"   => "#EMAIL#",
		"BCC"        => "#BCC#",
		"SUBJECT"    => Loc::getMessage("SALE_ORDER_DELIVERY_SUBJECT"),
		"MESSAGE"    => Loc::getMessage("SALE_ORDER_DELIVERY_MESSAGE"),
		"BODY_TYPE"  => "text",
	]);

	$emess = new CEventMessage;
	$emess->Add([
		"ACTIVE"     => "Y",
		"EVENT_NAME" => "SALE_ORDER_PAID",
		"LID"        => WIZARD_SITE_ID,
		"EMAIL_FROM" => "#SALE_EMAIL#",
		"EMAIL_TO"   => "#EMAIL#",
		"BCC"        => "#BCC#",
		"SUBJECT"    => Loc::getMessage("SALE_ORDER_PAID_SUBJECT"),
		"MESSAGE"    => Loc::getMessage("SALE_ORDER_PAID_MESSAGE"),
		"BODY_TYPE"  => "text",
	]);

	$emess = new CEventMessage;
	$emess->Add([
		"ACTIVE"     => "Y",
		"EVENT_NAME" => "SALE_RECURRING_CANCEL",
		"LID"        => WIZARD_SITE_ID,
		"EMAIL_FROM" => "#SALE_EMAIL#",
		"EMAIL_TO"   => "#EMAIL#",
		"BCC"        => "#BCC#",
		"SUBJECT"    => Loc::getMessage("SALE_RECURRING_CANCEL_SUBJECT"),
		"MESSAGE"    => Loc::getMessage("SALE_RECURRING_CANCEL_MESSAGE"),
		"BODY_TYPE"  => "text",
	]);

	$emess = new CEventMessage;
	$emess->Add([
		"ACTIVE"     => "Y",
		"EVENT_NAME" => "SALE_ORDER_REMIND_PAYMENT",
		"LID"        => WIZARD_SITE_ID,
		"EMAIL_FROM" => "#SALE_EMAIL#",
		"EMAIL_TO"   => "#EMAIL#",
		"BCC"        => "#BCC#",
		"SUBJECT"    => Loc::getMessage("SALE_ORDER_REMIND_PAYMENT_SUBJECT"),
		"MESSAGE"    => Loc::getMessage("SALE_ORDER_REMIND_PAYMENT_MESSAGE"),
		"BODY_TYPE"  => "text",
	]);
	$emess = new CEventMessage;
	$emess->Add([
		"ACTIVE"     => "Y",
		"EVENT_NAME" => "SALE_SUBSCRIBE_PRODUCT",
		"LID"        => WIZARD_SITE_ID,
		"EMAIL_FROM" => "#SALE_EMAIL#",
		"EMAIL_TO"   => "#EMAIL#",
		"BCC"        => "#BCC#",
		"SUBJECT"    => Loc::getMessage("UP_SUBJECT"),
		"MESSAGE"    => Loc::getMessage("UP_MESSAGE"),
		"BODY_TYPE"  => "text",
	]);

	if( CModule::IncludeModule("sale") ) {
		$dbStatus = CSaleStatus::GetList(
			[$by => $order],
			[],
			false,
			false,
			["ID", "SORT", "LID", "NAME", "DESCRIPTION"]
		);
		while( $arStatus = $dbStatus->Fetch() ) {

			$ID = $arStatus["ID"];
			$eventType = new CEventType;
			$eventMessage = new CEventMessage;


			IncludeModuleLangFile($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/sale/general/status.php", $lid);
			$arStatusLang = CSaleStatus::GetLangByID($ID, $lid);

			$dbEventType = $eventType->GetList(
				[
					"EVENT_NAME" => "SALE_STATUS_CHANGED_" . $ID,
					"LID"        => $lid,
				]
			);
			if( !($arEventType = $dbEventType->Fetch()) ) {
				$str = "";
				$str .= "#ORDER_ID# - " . Loc::getMessage("SKGS_ORDER_ID") . "\n";
				$str .= "#ORDER_DATE# - " . Loc::getMessage("SKGS_ORDER_DATE") . "\n";
				$str .= "#ORDER_STATUS# - " . Loc::getMessage("SKGS_ORDER_STATUS") . "\n";
				$str .= "#EMAIL# - " . Loc::getMessage("SKGS_ORDER_EMAIL") . "\n";
				$str .= "#ORDER_DESCRIPTION# - " . Loc::getMessage("SKGS_STATUS_DESCR") . "\n";
				$str .= "#TEXT# - " . Loc::getMessage("SKGS_STATUS_TEXT") . "\n";
				$str .= "#SALE_EMAIL# - " . Loc::getMessage("SKGS_SALE_EMAIL") . "\n";

				$eventTypeID = $eventType->Add(
					[
						"LID"         => $lid,
						"EVENT_NAME"  => "SALE_STATUS_CHANGED_" . $ID,
						"NAME"        => Loc::getMessage("SKGS_CHANGING_STATUS_TO") . " \"" . $arStatusLang["NAME"] .
							"\"",
						"DESCRIPTION" => $str,
					]
				);
			}

			$dbEventMessage = $eventMessage->GetList(
				($b = ""),
				($o = ""),
				[
					"EVENT_NAME" => "SALE_STATUS_CHANGED_" . $ID,
					"SITE_ID"    => WIZARD_SITE_ID,
				]
			);
			if( !($arEventMessage = $dbEventMessage->Fetch()) ) {
				$subject = Loc::getMessage("SKGS_STATUS_MAIL_SUBJ");

				$message = Loc::getMessage("SKGS_STATUS_MAIL_BODY1");
				$message .= "------------------------------------------\n\n";
				$message .= Loc::getMessage("SKGS_STATUS_MAIL_BODY2");
				$message .= Loc::getMessage("SKGS_STATUS_MAIL_BODY3");
				$message .= "#ORDER_STATUS#\n";
				$message .= "#ORDER_DESCRIPTION#\n";
				$message .= "#TEXT#\n\n";
				$message .= Loc::getMessage("SKGS_STATUS_MAIL_BODY4");
				$message .= "#SITE_NAME#\n";

				$arFields = [
					"ACTIVE"     => "Y",
					"EVENT_NAME" => "SALE_STATUS_CHANGED_" . $ID,
					"LID"        => WIZARD_SITE_ID,
					"EMAIL_FROM" => "#SALE_EMAIL#",
					"EMAIL_TO"   => "#EMAIL#",
					"SUBJECT"    => $subject,
					"MESSAGE"    => $message,
					"BODY_TYPE"  => "text",
				];
				$eventMessageID = $eventMessage->Add($arFields);
			}
		}
	}
}
?>