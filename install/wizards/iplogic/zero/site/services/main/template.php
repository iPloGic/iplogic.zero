<?
if( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true ) {
	die();
}

use Bitrix\Main\Config\Option;

$bitrixTemplateDir =
	$_SERVER["DOCUMENT_ROOT"] . "/local/templates/" . $wizard->GetVar("templateDir");
if( !is_dir($bitrixTemplateDir) ) {
	mkdir($bitrixTemplateDir, 0755, true);
}

CopyDirFiles(
	$_SERVER["DOCUMENT_ROOT"] . WizardServices::GetTemplatesPath(WIZARD_RELATIVE_PATH . "/site") . "/" .
		$wizard->GetVar("wizTemplateID"),
	$bitrixTemplateDir,
	$rewrite = true,
	$recursive = true,
	$delete_after_copy = false,
	$exclude = "themes"
);

$desc = '<?
$arTemplate = [
	"NAME" => "' . $wizard->GetVar("templateName") . '",
	"DESCRIPTION" => "' . $wizard->GetVar("templateDescription") . '",
	"SORT" => 100,
];
';
file_put_contents($bitrixTemplateDir . "/description.php", $desc);

?>
