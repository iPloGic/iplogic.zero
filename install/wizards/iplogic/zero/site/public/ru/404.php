<?
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');
CHTTP::SetStatus("404 Not Found");
@define("ERROR_404","Y");
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Страница не найдена");
?>
<section>
	<div class="section-block content">
		<h2>Ошибка 404<br></h2>
		<h3>
			Неправильно набран адрес<br>
			или такой страницы на сайте больше не существует.
		</h3>
	</div>
	<div class="spacer40"></div>
</section>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>