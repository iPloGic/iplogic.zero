<?
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');
CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Page not found");
?>
<section>
	<div class="section-block content">
		<h2>Error 404<br></h2>
		<h3>
			Address typed incorrectly<br>
			or such a page no longer exists on the site.
		</h3>
	</div>
	<div class="spacer40"></div>
</section>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>