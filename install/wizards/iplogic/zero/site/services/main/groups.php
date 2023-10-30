<?
if( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true ) {
	die();
}

use \Bitrix\Main\Config\Option;
use \Bitrix\Main\Localization\Loc;
use \Bitrix\Main\Loader;

$s_count = 0;
$sites = \CSite::GetList($by = "sort", $order = "desc");
while( $sites_f = $sites->Fetch() )
	$s_count++;

if( $s_count < 2 ) {

	/*USER GROUPS*/

	$arGroupPolicy = [
		"SESSION_TIMEOUT"      => 60 * 24 * 5,
		"MAX_STORE_NUM"        => 1,
		"STORE_TIMEOUT"        => 60 * 24 * 7,
		"CHECKWORD_TIMEOUT"    => 60,
		"PASSWORD_LENGTH"      => 6,
		"PASSWORD_UPPERCASE"   => "N",
		"PASSWORD_LOWERCASE"   => "N",
		"PASSWORD_DIGITS"      => "N",
		"PASSWORD_PUNCTUATION" => "N",
		"LOGIN_ATTEMPTS"       => 3,
	];

	$rsGroup = CGroup::GetByID(1);
	if( $res = $rsGroup->Fetch() ) {
		if( $res["SECURITY_POLICY"] == "" ) {
			$group = new CGroup();
			$arFields = ["SECURITY_POLICY" => serialize($arGroupPolicy)];
			$group->Update(1, $arFields);
		}
	}

	//Edit profile task
	$editProfileTask = false;
	$dbResult = CTask::GetList([], ["NAME" => "main_change_profile"]);
	if( $arTask = $dbResult->Fetch() ) {
		$editProfileTask = $arTask["ID"];
	}

	//Registered users group
	$dbResult = CGroup::GetList('', '', ["STRING_ID" => "REGISTERED_USERS"]);
	if( !$dbResult->Fetch() ) {
		$group = new CGroup;
		$arFields = [
			"ACTIVE"          => "Y",
			"C_SORT"          => 3,
			"NAME"            => Loc::getMessage("REGISTERED_USERS"),
			"STRING_ID"       => "REGISTERED_USERS",
			"SECURITY_POLICY" => serialize($arGroupPolicy),
		];

		$groupID = $group->Add($arFields);
		if( $groupID > 0 ) {
			Option::set("main", "new_user_registration_def_group", $groupID);
			if( $editProfileTask ) {
				CGroup::SetTasks($groupID, [$editProfileTask], true);
			}
		}
	}

	$rsGroups = CGroup::GetList("c_sort", "desc", ["ACTIVE" => "Y", "ADMIN" => "N", "ANONYMOUS" => "N"]);
	if( !($rsGroups->Fetch()) ) {
		$group = new CGroup;
		$arFields = [
			"ACTIVE"          => "Y",
			"C_SORT"          => 100,
			"NAME"            => Loc::getMessage("REGISTERED_USERS"),
			"DESCRIPTION"     => "",
			"SECURITY_POLICY" => serialize($arGroupPolicy),
		];
		$NEW_GROUP_ID = $group->Add($arFields);
		Option::set('main', 'new_user_registration_def_group', $NEW_GROUP_ID);

		$rsTasks = CTask::GetList([], ["MODULE_ID" => "main", "SYS" => "Y", "BINDIG" => "module", "LETTER" => "P"]);
		if( $arTask = $rsTasks->Fetch() ) {
			CGroup::SetModulePermission($NEW_GROUP_ID, $arTask["MODULE_ID"], $arTask["ID"]);
		}
	}


	// sale administrators group
	if( Loader::includeModule("sale") ) {
		$userGroupID = "";
		$dbGroup = CGroup::GetList('', '', ["STRING_ID" => "sale_administrator"]);
		if( $arGroup = $dbGroup->Fetch() ) {
			$userGroupID = $arGroup["ID"];
		}
		else {
			$group = new CGroup;
			$arFields = [
				"ACTIVE"          => "Y",
				"C_SORT"          => 200,
				"NAME"            => Loc::getMessage("SALE_WIZARD_ADMIN_SALE"),
				"DESCRIPTION"     => Loc::getMessage("SALE_WIZARD_ADMIN_SALE_DESCR"),
				"USER_ID"         => [],
				"STRING_ID"       => "sale_administrator",
				"SECURITY_POLICY" => serialize($arGroupPolicy),
			];
			$userGroupID = $group->Add($arFields);
		}

		if( intval($userGroupID) > 0 ) {
			WizardServices::SetFilePermission([WIZARD_SITE_ID, "/bitrix/admin"], [$userGroupID => "R"]);

			$new_task_id = CTask::Add(
				[
					"NAME"        => Loc::getMessage("SALE_WIZARD_ADMIN_SALE"),
					"DESCRIPTION" => Loc::getMessage("SALE_WIZARD_ADMIN_SALE_DESCR"),
					"LETTER"      => "Q",
					"BINDING"     => "module",
					"MODULE_ID"   => "main",
				]
			);
			if( $new_task_id ) {
				$arOps = [];
				$rsOp = COperation::GetList([], ["NAME" => "cache_control|view_own_profile|edit_own_profile"]);
				while( $arOp = $rsOp->Fetch() )
					$arOps[] = $arOp["ID"];
				CTask::SetOperations($new_task_id, $arOps);
			}

			$rsTasks = CTask::GetList([], ["MODULE_ID" => "main", "SYS" => "N", "BINDIG" => "module", "LETTER" => "Q"]);
			if( $arTask = $rsTasks->Fetch() ) {
				CGroup::SetModulePermission($userGroupID, $arTask["MODULE_ID"], $arTask["ID"]);
			}

			CMain::SetGroupRight("sale", $userGroupID, "U");

			$rsTasks =
				CTask::GetList([], ["MODULE_ID" => "catalog", "SYS" => "Y", "BINDIG" => "module", "LETTER" => "T"]);
			while( $arTask = $rsTasks->Fetch() ) {
				CGroup::SetModulePermission($userGroupID, $arTask["MODULE_ID"], $arTask["ID"]);
			}

			if( Option::get("main", "~sale_converted_15", "") == "Y" ) {
				$dbTask = Bitrix\Main\TaskTable::getList(
					[
						'select' => ['ID'],
						'filter' => ['NAME' => 'sale_status_all'],
					]
				);
				if( $task = $dbTask->Fetch() ) {
					$dbTasks = Bitrix\Sale\Internals\StatusGroupTaskTable::getList(
						[
							'filter' => [
								'GROUP_ID' => $userGroupID,
								'TASK_ID'  => $task['ID'],
							],
						]
					);
					if( !$dbTasks->Fetch() ) {
						$dbStatus = Bitrix\Sale\Internals\StatusTable::getList(
							[
								'filter' => ['TYPE' => ['O', 'D']],
								'select' => ['ID'],
							]
						);

						while( $status = $dbStatus->Fetch() ) {
							$groupTasks = [
								'STATUS_ID' => $status['ID'],
								'GROUP_ID'  => $userGroupID,
								'TASK_ID'   => $task['ID'],
							];
							Bitrix\Sale\Internals\StatusGroupTaskTable::add($groupTasks);
						}
					}
				}
			}
		}
	}


}
?>
