			</div>
		</section>
		<footer>
			<div class="container-block">
<?
$APPLICATION->IncludeComponent("bitrix:menu", "bottom", Array(
	"ROOT_MENU_TYPE" => "bottom",	// Тип меню для первого уровня
		"MAX_LEVEL" => "1",	// Уровень вложенности меню
		"CHILD_MENU_TYPE" => "",	// Тип меню для остальных уровней
		"USE_EXT" => "N",	// Подключать файлы с именами вида .тип_меню.menu_ext.php
		"MENU_CACHE_TYPE" => "A",	// Тип кеширования
		"MENU_CACHE_TIME" => "3600",	// Время кеширования (сек.)
		"MENU_CACHE_USE_GROUPS" => "Y",	// Учитывать права доступа
		"MENU_CACHE_GET_VARS" => "",	// Значимые переменные запроса
	),
	false,
	array(
	"ACTIVE_COMPONENT" => "Y"
	)
);
?>
			</div>
		</footer>

		<div id="underlayer"><i class="fas fa-times"></i></div>

		<!-- Search -->
		<div class="search-form">
			<form method="get" action="/catalog/">
				<input type="text" placeholder="Искать товар" name="q" value="" autocomplete="off">&nbsp;
				<input type="submit" value="Искать" name="search-submit">
			</form>
		</div>
			<?
			$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery-3.3.1.min.js');
			$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/assets/bootstrap/js/bootstrap.js');
			$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/assets/jquery.equalheights.min.js');
			$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/assets/jquery.cookie.js');
			$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/script.js',1);
			?>
	</body>
</html>