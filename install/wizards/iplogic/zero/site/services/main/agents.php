<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

if ($wizard->GetVar("agentCBRF") == "Y") {
	CAgent::AddAgent( "\Iplogic\Zero\Agent::GetCurrencyRateAgent();", "iplogic.zero", "N", 86400, "", "Y");
}
if ($wizard->GetVar("agentUC") == "Y") {
	CAgent::AddAgent( "\Iplogic\Zero\Agent::CleanUpUploadAgent();", "iplogic.zero", "N", 86400, "", "Y");
}