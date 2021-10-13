<?
$module_id = "iplogic.zero";

use \Bitrix\Main\Config\Option,
	\Bitrix\Main\Localization\Loc,
	\Bitrix\Main\Application,
	\Bitrix\Main\Loader;

Loader::includeModule($module_id);

$docRoot = $_SERVER['DOCUMENT_ROOT'];
$RIGHT = $APPLICATION->GetGroupRight($module_id);

IncludeModuleLangFile($docRoot.BX_ROOT."/modules/main/options.php");
IncludeModuleLangFile(__FILE__);

$request = Application::getInstance()->getContext()->getRequest();

if($RIGHT >= "R") {

	$arMainOptions = [
		Loc::getMessage("IPL_BACKGROUND_SCRIPTS"),
		["cli_execute_method", Loc::getMessage("IPL_CLI_METHOD"),"WGET", ["selectbox", [
			"WGET" => "WGET",
			"PHP" => "PHP"
		]]],
		["cli_php", Loc::getMessage("IPL_PHP_FILE"), "/usr/bin/php", ["text", 15]],
		["cli_wget_miss_cert", Loc::getMessage("IPL_CLI_MISS_CERT"), "Y", ["checkbox"]],
		Loc::getMessage("IPL_ACTIONS"),
		["turn_on_сu_agent", Loc::getMessage("IPL_TURN_ON_СU_AGENT"), "N", ["checkbox"]],
		["turn_on_сr_agent", Loc::getMessage("IPL_TURN_ON_СR_AGENT"), "N", ["checkbox"]],
	];

	$aTabs = [
		["DIV" => "edit1", "TAB" => Loc::getMessage("MAIN_TAB_SET"), "TITLE" => Loc::getMessage("MAIN_TAB_TITLE_SET"), "OPTIONS" => $arMainOptions],
		["DIV" => "edit10", "TAB" => Loc::getMessage("MAIN_TAB_RIGHTS"), "TITLE" => Loc::getMessage("MAIN_TAB_TITLE_RIGHTS")],
	];
	$tabControl = new CAdminTabControl("tabControl", $aTabs);

	if($request->isPost() && strlen($Update.$Apply.$RestoreDefaults) > 0 && $RIGHT=="W" && check_bitrix_sessid())
	{
		if(strlen($RestoreDefaults)>0) {
			Option::delete($module_id);
			Option::getDefaults($module_id);
		}
		else
		{
			$res = \CAgent::GetList(["ID" => "DESC"], ["NAME" => "\Iplogic\Zero\Agent::CleanUpUploadAgent();"]);
			if($arAgent = $res->Fetch()) {
				if($request->get("turn_on_сu_agent") != "Y")
					\CAgent::Delete($arAgent["ID"]);
			}
			else {
				if($request->get("turn_on_сu_agent") == "Y")
					\CAgent::AddAgent( "\Iplogic\Zero\Agent::CleanUpUploadAgent();", $module_id, "N", 86400, "", "Y");
			}

			$res = \CAgent::GetList(["ID" => "DESC"], ["NAME" => "\Iplogic\Zero\Agent::GetCurrencyRateAgent();"]);
			if($arAgent = $res->Fetch()) {
				if($request->get("turn_on_сr_agent") != "Y")
					\CAgent::Delete($arAgent["ID"]);
			}
			else {
				if($request->get("turn_on_сr_agent") == "Y")
					\CAgent::AddAgent( "\Iplogic\Zero\Agent::GetCurrencyRateAgent();", $module_id, "N", 86400, "", "Y");
			}

			foreach($aTabs as $aTab){
				if($aTab["OPTIONS"]){
					foreach($aTab["OPTIONS"] as $arOption) {
						if($arOption[0] == "cli_php" || $arOption[0] == "cli_wget_miss_cert")
							continue;
						__AdmSettingsSaveOption($module_id, $arOption);
					}
				}
			}
		}
		ob_start();
		$Update = $Update.$Apply;
		require_once($docRoot . '/bitrix/modules/main/admin/group_rights.php');
		ob_end_clean();

		if ($request->get("back_url_settings") != "")
		{
			if( $request->get("Apply") != "" || $request->get("RestoreDefaults") != "" )
				LocalRedirect($APPLICATION->GetCurPage()."?mid=".urlencode($module_id)."&lang=".urlencode(LANGUAGE_ID)."&back_url_settings=".urlencode($request->get("back_url_settings"))."&".$tabControl->ActiveTabParam());
			else
				LocalRedirect($request->get("back_url_settings"));
		}
		else
		{
			LocalRedirect($APPLICATION->GetCurPage()."?mid=".urlencode($module_id)."&lang=".urlencode(LANGUAGE_ID)."&".$tabControl->ActiveTabParam());
		}
	}

	$res = \CAgent::GetList(["ID" => "DESC"], ["NAME" => "\Iplogic\Zero\Agent::CleanUpUploadAgent();"]);
	if($arAgent = $res->Fetch()) {
		foreach($aTabs[0]["OPTIONS"] as $k => $option) {
			if($option[0] == "turn_on_сu_agent")
				$aTabs[0]["OPTIONS"][$k][2] = "Y";
		}
	}
	$res = \CAgent::GetList(["ID" => "DESC"], ["NAME" => "\Iplogic\Zero\Agent::GetCurrencyRateAgent();"]);
	if($arAgent = $res->Fetch()) {
		foreach($aTabs[0]["OPTIONS"] as $k => $option) {
			if($option[0] == "turn_on_сr_agent")
				$aTabs[0]["OPTIONS"][$k][2] = "Y";
		}
	}

	?>
	<form method="post" action="<?echo $APPLICATION->GetCurPage()?>?mid=<?=urlencode($module_id)?>&amp;lang=<?=LANGUAGE_ID?>">
		<?
		$tabControl->Begin();
		$arNotes = array();
		foreach($aTabs as $aTab){
			if($aTab["OPTIONS"]){
				$tabControl->BeginNextTab();
				__AdmSettingsDrawList($module_id, $aTab["OPTIONS"]);
			}
		}
		$tabControl->BeginNextTab();
		require_once($docRoot."/bitrix/modules/main/admin/group_rights.php");
		$tabControl->Buttons();
		?>
		<input <?if ($RIGHT<"W") echo "disabled" ?> type="submit" name="Update" value="<?=Loc::getMessage("MAIN_SAVE")?>" title="<?=Loc::getMessage("MAIN_OPT_SAVE_TITLE")?>" class="adm-btn-save">
		<?if(strlen($request->get("back_url_settings"))>0):?>
			<input <?if ($RIGHT<"W") echo "disabled" ?> type="button" name="Cancel" value="<?=Loc::getMessage("MAIN_OPT_CANCEL")?>" title="<?=Loc::getMessage("MAIN_OPT_CANCEL_TITLE")?>" onclick="window.location='<?echo htmlspecialcharsbx(CUtil::addslashes($request->get("back_url_settings")))?>'">
			<input type="hidden" name="back_url_settings" value="<?=htmlspecialcharsbx($request->get("back_url_settings"))?>">
		<?endif?>
		<input type="submit" name="RestoreDefaults" title="<?echo Loc::getMessage("MAIN_HINT_RESTORE_DEFAULTS")?>" OnClick="confirm('<?echo AddSlashes(Loc::getMessage("MAIN_HINT_RESTORE_DEFAULTS_WARNING"))?>')" value="<?echo Loc::getMessage("MAIN_RESTORE_DEFAULTS")?>">
		<?=bitrix_sessid_post();?>
		<?$tabControl->End();?>
	</form>
	 <?
		CJSCore::Init(array("jquery"));
	 ?>
	 <script>
		$(document).ready(function(){
			$("select[name='cli_execute_method']").on("change", function(){
				var m = $(this).val();  console.log(m);
				if(m == "WGET") {
					$("input[name='cli_php']").parent().parent().css("display", "none");
					$("input[name='cli_wget_miss_cert']").parent().parent().css("display", "table-row");
				}
				if(m == "PHP") {
					$("input[name='cli_php']").parent().parent().css("display", "table-row");
					$("input[name='cli_wget_miss_cert']").parent().parent().css("display", "none");
				}
			});
			$("select[name='cli_execute_method']").change();
		});
	</script>
	<?
	if(!empty($arNotes))
	{
		echo BeginNote();
		foreach($arNotes as $i => $str)
		{
			?><span class="required"><sup><?echo $i+1?></sup></span><?echo $str?><br><?
		}
		echo EndNote();
	}
}
else {
	echo Loc::getMessage("MODULE_OPTIONS_DENIED");
}
?>
