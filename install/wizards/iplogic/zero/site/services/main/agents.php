<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use \Bitrix\Main\Config\Option;

if ($wizard->GetVar("agentCBRF") == "Y") {
	CAgent::AddAgent( "\Iplogic\Zero\Agent::GetCurrencyRateAgent();", "iplogic.zero", "N", 86400, "", "Y");
	Option::set("iplogic.zero","turn_on_сr_agent",'Y');
}
if ($wizard->GetVar("agentUC") == "Y") {
	CAgent::AddAgent( "\Iplogic\Zero\Agent::CleanUpUploadAgent();", "iplogic.zero", "N", 86400, "", "Y");
	Option::set("iplogic.zero","turn_on_сu_agent",'Y');
}