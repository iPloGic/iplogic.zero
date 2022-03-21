<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>

<? //echo "<pre>"; print_r($arResult); echo "</pre>"; ?>

<?
function subsectionsLeftMenu($items, $selected){
	$str = '';
	$str .= '<ul class="menu-v dropdown"'.($selected ? 'style="display:block;"' : '').'>';
	foreach($items as $arRow) {
		$str .= '<li class="'.(!empty($arRow['IS_PARENT']) ? 'drop' : '').'">';
		$openclose = "";
		if(!empty($arRow['IS_PARENT'])) {
			$openclose = "<div class=\"open-icon\">";
			if($arRow["SELECTED"]) {
				$openclose .= '<i class="fas fa-angle-up"></i>';
			}
			else {
				$openclose .= '<i class="fas fa-angle-down"></i>';
			}
			$openclose .= "</div>";
		}
		$str .= '<a class="" href="'.$arRow["LINK"].'">'.$arRow["TEXT"].'</a>';
		$str .= $openclose;
		if(!empty($arRow['IS_PARENT'])) {
			$str .= subsectionsLeftMenu($arRow['CHILDREN'], $arRow["SELECTED"]);
		}
		$str .= '</li>';
	}
	$str .= '</ul>';
	return $str;
}
?>

<?if (!empty($arResult)):?>

	<div class="left-menu">
		<ul class="menu-v dropdown clickable">
			<?foreach($arResult as $arItem):?>
				<?
				$openclose = "";
				if(!empty($arItem['IS_PARENT'])) {
					$openclose = "<div class=\"open-icon\">";
					if($arItem["SELECTED"]) {
						$openclose .= '<i class="fas fa-angle-up"></i>';
					}
					else {
						$openclose .= '<i class="fas fa-angle-down"></i>';
					}
					$openclose .= "</div>";
				}
				?>
				<li class="drop sections-menu-item">
					<img src="<?=$arItem['ICON']?>">
					<a class="" href="<?=$arItem["LINK"]?>"><?=$arItem["TEXT"]?></a>
					<?=$openclose?>
					<?if(!empty($arItem['IS_PARENT'])):?>
						<? echo subsectionsLeftMenu($arItem['CHILDREN'], $arItem["SELECTED"]); ?>
					<?endif?>
				</li>
			<?endforeach;?>
		</ul>
	</div>

<?endif?>