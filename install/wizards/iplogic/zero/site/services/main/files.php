<?
if( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true ) {
	die();
}

if( !defined("WIZARD_SITE_PATH") ) {
	return;
}

$siteID = getSite($wizard)["ID"];

$s_count = 0;
$sites = \CSite::GetList($by = "sort", $order = "desc");
while( $sites_f = $sites->Fetch() )
	$s_count++;

if( $wizard->GetVar("pubAuth") == "Y" ) {
	$dir = WIZARD_SITE_PATH . "/auth";
	if( !is_dir($dir) ) {
		mkdir($dir, 0755, true);
	}
	CopyDirFiles(
		WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . "/auth",
		WIZARD_SITE_PATH . "/auth",
		$rewrite = true,
		$recursive = true,
		$delete_after_copy = false
	);
}

if( $wizard->GetVar("pubPrivate") == "Y" ) {
	$dir = WIZARD_SITE_PATH . "/personal";
	if( !is_dir($dir) ) {
		mkdir($dir, 0755, true);
	}
	CopyDirFiles(
		WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . "/personal",
		WIZARD_SITE_PATH . "/personal",
		$rewrite = true,
		$recursive = true,
		$delete_after_copy = false
	);
}

if( $wizard->GetVar("pubNews") == "Y" ) {
	$dir = WIZARD_SITE_PATH . "/news";
	if( !is_dir($dir) ) {
		mkdir($dir, 0755, true);
	}
	CopyDirFiles(
		WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . "/news",
		WIZARD_SITE_PATH . "/news",
		$rewrite = true,
		$recursive = true,
		$delete_after_copy = false
	);
}

if( $wizard->GetVar("pubCatalog") == "Y" ) {
	$dir = WIZARD_SITE_PATH . "/catalog";
	if( !is_dir($dir) ) {
		mkdir($dir, 0755, true);
	}
	CopyDirFiles(
		WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . "/catalog",
		WIZARD_SITE_PATH . "/catalog",
		$rewrite = true,
		$recursive = true,
		$delete_after_copy = false
	);
}

if( $wizard->GetVar("pubCart") == "Y" ) {
	$dir = WIZARD_SITE_PATH . "/cart";
	if( !is_dir($dir) ) {
		mkdir($dir, 0755, true);
	}
	CopyDirFiles(
		WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . "/cart",
		WIZARD_SITE_PATH . "/cart",
		$rewrite = true,
		$recursive = true,
		$delete_after_copy = false
	);
}

unlink(WIZARD_SITE_PATH . "/favicon.ico");
copy(
	WIZARD_ABSOLUTE_PATH . "/site/public/favicon.svg",
	WIZARD_SITE_PATH . "/favicon.svg"
);
copy(
	WIZARD_ABSOLUTE_PATH . "/site/public/favicon.ico",
	WIZARD_SITE_PATH . "/favicon.ico"
);

if( $wizard->GetVar("pubIndex") == "Y" ) {
	copy(
		WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . "/_index.php",
		WIZARD_SITE_PATH . "/_index.php"
	);
}

if( $wizard->GetVar("pub404") == "Y" ) {
	copy(
		WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . "/404.php",
		WIZARD_SITE_PATH . "/404.php"
	);
}

if( $wizard->GetVar("menuTop") == "Y" ) {
	copy(
		WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . "/.top.menu.php",
		WIZARD_SITE_PATH . "/.top.menu.php"
	);
}

if( $wizard->GetVar("menuBottom") == "Y" ) {
	copy(
		WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . "/.bottom.menu.php",
		WIZARD_SITE_PATH . "/.bottom.menu.php"
	);
}

if( $wizard->GetVar("menuSide") == "Y" ) {
	copy(
		WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . "/.side.menu.php",
		WIZARD_SITE_PATH . "/.side.menu.php"
	);
}

if( $wizard->GetVar("menuCatalog") == "Y" ) {
	copy(
		WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . "/.catalog.menu.php",
		WIZARD_SITE_PATH . "/.catalog.menu.php"
	);
	copy(
		WIZARD_ABSOLUTE_PATH . "/site/public/" . LANGUAGE_ID . "/.catalog.menu_ext.php",
		WIZARD_SITE_PATH . "/.catalog.menu_ext.php"
	);
}

$dir = $_SERVER["DOCUMENT_ROOT"] . "/local";
if( !is_dir($dir) ) {
	mkdir($dir, 0755, true);
}

if( $wizard->GetVar("phpInterface") == "Y" ) {
	$dir = $_SERVER["DOCUMENT_ROOT"] . "/local/php_interface";
	if( !is_dir($dir) ) {
		mkdir($dir, 0755, true);
	}
	if( !file_exists($dir . "/cron_events.php") ) {
		copy(
			WIZARD_ABSOLUTE_PATH . "/site/local/cron_events.php",
			$dir . "/cron_events.php"
		);
	}
	if( $s_count <= 1 ) {
		CopyDirFiles(
			WIZARD_ABSOLUTE_PATH . "/site/local/php_interface",
			$dir,
			$rewrite = true,
			$recursive = true,
			$delete_after_copy = false
		);
	}
	$dir = $_SERVER["DOCUMENT_ROOT"] . "/local/php_interface/" . $siteID;
	if( !is_dir($dir) ) {
		mkdir($dir, 0755, true);
	}
	CopyDirFiles(
		WIZARD_ABSOLUTE_PATH . "/site/local/php_interface",
		$dir,
		$rewrite = true,
		$recursive = true,
		$delete_after_copy = false
	);
}

$dir = $_SERVER["DOCUMENT_ROOT"] . "/local/components";
if( !is_dir($dir) ) {
	mkdir($dir, 0755, true);
}
/*$dir = $_SERVER["DOCUMENT_ROOT"]."/local/components/iplogic";
if(!is_dir($dir)) {
	mkdir($dir, 0755, true);
}
CopyDirFiles(
	WIZARD_ABSOLUTE_PATH . "/site/local/components/iplogic",
	$dir,
	$rewrite = true,
	$recursive = true,
	$delete_after_copy = false
);*/

$dir = $_SERVER["DOCUMENT_ROOT"] . "/local/ajax";
if( !is_dir($dir) ) {
	mkdir($dir, 0755, true);
}
$dir = $_SERVER["DOCUMENT_ROOT"] . "/local/cli";
if( !is_dir($dir) ) {
	mkdir($dir, 0755, true);
}


$arUrlRewrite = [];
if( file_exists(WIZARD_SITE_ROOT_PATH . "/urlrewrite.php") ) {
	include(WIZARD_SITE_ROOT_PATH . "/urlrewrite.php");
}

$arNewUrlRewrite = [];
if( $wizard->GetVar("pubPrivate") == "Y" ) {
	$arNewUrlRewrite[] = [
		"CONDITION" => "#^" . WIZARD_SITE_DIR . "personal/#",
		"RULE"      => "",
		"ID"        => "bitrix:sale.personal.section",
		"PATH"      => WIZARD_SITE_DIR . "personal/index.php",
	];
}

if( $wizard->GetVar("pubNews") == "Y" ) {
	$arNewUrlRewrite[] = [
		"CONDITION" => "#^" . WIZARD_SITE_DIR . "news/#",
		"RULE"      => "",
		"ID"        => "bitrix:news",
		"PATH"      => WIZARD_SITE_DIR . "news/index.php",
	];
}

if( $wizard->GetVar("pubCatalog") == "Y" ) {
	$arNewUrlRewrite[] = [
		"CONDITION" => "#^" . WIZARD_SITE_DIR . "catalog/#",
		"RULE"      => "",
		"ID"        => "bitrix:catalog",
		"PATH"      => WIZARD_SITE_DIR . "catalog/index.php",
	];
}

foreach( $arNewUrlRewrite as $arUrl ) {
	if( !in_array($arUrl, $arUrlRewrite) ) {
		CUrlRewriter::Add($arUrl);
	}
}

?>