<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Config\Option,
	Bitrix\Main\Localization\Loc;

$s_count = 0;

$sites = \CSite::GetList($by = "sort", $order = "desc");
while($sites_f = $sites->Fetch())
	$s_count++;

if($s_count < 2) {
	/* deny public access */
	Option::set("main", "site_stopped", "Y");

	$siteEmail = $wizard->GetVar("siteEmail");
	$siteName = $wizard->GetVar("siteName");
	Option::set('main', 'email_from', $siteEmail);
	Option::set('main', 'new_user_registration', 'Y');
	Option::set('main', 'captcha_registration', 'Y');
	Option::set('main', 'site_name', $siteName);
	Option::set("main", "vendor", "iPloGic");
	Option::set("main", "move_js_to_body", "Y");
	Option::set("main", "session_show_message", "N");

	if(strlen(Option::get('main', 'CAPTCHA_presets', '')) <= 0)
	{
		Option::set('main', 'CAPTCHA_transparentTextPercent', '0');
		Option::set('main', 'CAPTCHA_arBGColor_1', 'FFFFFF');
		Option::set('main', 'CAPTCHA_arBGColor_2', 'FFFFFF');
		Option::set('main', 'CAPTCHA_numEllipses', '0');
		Option::set('main', 'CAPTCHA_arEllipseColor_1', '7F7F7F');
		Option::set('main', 'CAPTCHA_arEllipseColor_2', 'FFFFFF');
		Option::set('main', 'CAPTCHA_bLinesOverText', 'Y');
		Option::set('main', 'CAPTCHA_numLines', '0');
		Option::set('main', 'CAPTCHA_arLineColor_1', 'FFFFFF');
		Option::set('main', 'CAPTCHA_arLineColor_2', 'FFFFFF');
		Option::set('main', 'CAPTCHA_textStartX', '40');
		Option::set('main', 'CAPTCHA_textFontSize', '26');
		Option::set('main', 'CAPTCHA_arTextColor_1', '000000');
		Option::set('main', 'CAPTCHA_arTextColor_2', '000000');
		Option::set('main', 'CAPTCHA_textAngel_1', '-15');
		Option::set('main', 'CAPTCHA_textAngel_2', '15');
		Option::set('main', 'CAPTCHA_textDistance_1', '-2');
		Option::set('main', 'CAPTCHA_textDistance_2', '-2');
		Option::set('main', 'CAPTCHA_bWaveTransformation', 'Y');
		Option::set('main', 'CAPTCHA_arBorderColor', '000000');
		Option::set('main', 'CAPTCHA_arTTFFiles', 'bitrix_captcha.ttf');
		Option::set('main', 'CAPTCHA_letters', 'ABCDEFGHJKLMNPQRSTWXYZ23456789');
		Option::set('main', 'CAPTCHA_presets', '2');
	}

	Option::set("fileman", "propstypes", serialize(array("description"=>Loc::getMessage("MAIN_OPT_DESCRIPTION"), "keywords"=>Loc::getMessage("MAIN_OPT_KEYWORDS"), "title"=>Loc::getMessage("MAIN_OPT_TITLE"), "keywords_inner"=>Loc::getMessage("MAIN_OPT_KEYWORDS_INNER"))));

	Option::set("search", "suggest_save_days", 250);
	Option::set("search", "use_tf_cache", "Y");
	Option::set("search", "use_word_distance", "Y");
	Option::set("search", "use_social_rating", "Y");

	Option::set("iblock", "use_htmledit", "Y");
	Option::set("iblock", "combined_list_mode", "Y");
	Option::set("iblock", "show_xml_id", "Y");

	// social auth services
	if (Option::get("socialservices", "auth_services") == ""){
		$bRu = (LANGUAGE_ID == 'ru');
		$arServices = array(
			"VKontakte" => "Y",
			"MyMailRu" => "Y",
			"Twitter" => "Y",
			"Facebook" => "Y",
			"Livejournal" => "Y",
			"YandexOpenID" => ($bRu? "Y":"N"),
			"Rambler" => ($bRu? "Y":"N"),
			"MailRuOpenID" => ($bRu? "Y":"N"),
			"Liveinternet" => ($bRu? "Y":"N"),
			"Blogger" => "N",
			"OpenID" => "Y",
			"LiveID" => "N",
		);
		Option::set("socialservices", "auth_services", serialize($arServices));
	}
	Option::set('socialnetwork', 'allow_tooltip', 'N', false, WIZARD_SITE_ID);
}

?>