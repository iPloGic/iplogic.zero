<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<? //echo "<pre>"; print_r($arResult); echo "</pre>"; ?>

<?
function subsectionsTopMenu($items){
	$str = '';
	$str .= '<ul class="menu-v dropdown">';
	foreach($items as $arRow) {
		$str .= '<li class="'.(!empty($arRow['IS_PARENT']) ? 'drop' : '').'">';
		$str .= '<a class="" href="'.$arRow["LINK"].'">'.$arRow["TEXT"].'</a>';
		if(!empty($arRow['IS_PARENT'])) {
			$str .= "<div class=\"open-icon\"><i class=\"fas fa-angle-down\"></i></div>";
		}
		if(!empty($arRow['IS_PARENT'])) {
			$str .= subsectionsTopMenu($arRow['CHILDREN']);
		}
		$str .= '</li>';
	}
	$str .= '</ul>';
	return $str;
}
?>

<?if (!empty($arResult)):?>

	<div class="top-menu">
		<div class="mob-menu-title">
			<div class="mobile-menu-toggler"><i class="fas fa-angle-left"></i></div>
		</div>
		<ul class="menu-h dropdown">
			<?foreach($arResult as $arItem):?>
				<li class="drop sections-menu-item">
					<img src="<?=$arItem['ICON']?>">
					<a class="" href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
					<? if(!empty($arItem['IS_PARENT'])) { ?>
					<div class="open-icon"><i class="fas fa-angle-down"></i></div>
					<? } ?>
					<?if(!empty($arItem['IS_PARENT'])):?>
						<? echo subsectionsTopMenu($arItem['CHILDREN']); ?>
					<?endif?>
				</li>
			<?endforeach;?>
		</ul>
	</div>

<?endif?>