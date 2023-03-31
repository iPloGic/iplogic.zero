<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();?>
<?
use \Bitrix\Main\Localization\Loc;
?>
		<footer>
			<div class="section-block">
				<?
				$APPLICATION->IncludeComponent("bitrix:menu", "bottom",
					Array(
						"ROOT_MENU_TYPE" => "bottom",
						"MAX_LEVEL" => "1",
						"CHILD_MENU_TYPE" => "",
						"USE_EXT" => "N",
						"MENU_CACHE_TYPE" => "A",
						"MENU_CACHE_TIME" => "3600",
						"MENU_CACHE_USE_GROUPS" => "Y",
						"MENU_CACHE_GET_VARS" => "",
					),
					false,
					array(
						"ACTIVE_COMPONENT" => "Y"
					)
				);
				?>
			</div>
		</footer>

		<?$APPLICATION->IncludeComponent(
			"iplogic:site.agreements",
			"",
			Array(
				"COOKIE_EXPIRES" => "30",
				"COOKIE_SUFFIX" => "hryH45FG56",
				"CACHE_TYPE" => "N",
			),
			false
		);?>

		<!-- AJAX LOADER -->
		<div id="ajax-page-loader"></div>

		<!-- MODALS -->

		<div id="underlayer"><i class="fas fa-times"></i></div>

		<!-- Search in catalog -->
		<div class="search-form">
			<form method="get" action="/catalog/">
				<input type="text" placeholder="Искать товар" name="q" value="" autocomplete="off">
				<input type="submit" value="Искать" name="search-submit">
			</form>
		</div>

	</body>
</html>