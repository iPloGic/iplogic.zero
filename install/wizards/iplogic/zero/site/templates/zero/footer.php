			</div>
		</section>
		<footer>
			<div class="container-block">
<?
$APPLICATION->IncludeComponent("bitrix:menu", "bottom", Array(
	"ROOT_MENU_TYPE" => "bottom",	// ��� ���� ��� ������� ������
		"MAX_LEVEL" => "1",	// ������� ����������� ����
		"CHILD_MENU_TYPE" => "",	// ��� ���� ��� ��������� �������
		"USE_EXT" => "N",	// ���������� ����� � ������� ���� .���_����.menu_ext.php
		"MENU_CACHE_TYPE" => "A",	// ��� �����������
		"MENU_CACHE_TIME" => "3600",	// ����� ����������� (���.)
		"MENU_CACHE_USE_GROUPS" => "Y",	// ��������� ����� �������
		"MENU_CACHE_GET_VARS" => "",	// �������� ���������� �������
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
				<input type="text" placeholder="������ �����" name="q" value="" autocomplete="off">&nbsp;
				<input type="submit" value="������" name="search-submit">
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