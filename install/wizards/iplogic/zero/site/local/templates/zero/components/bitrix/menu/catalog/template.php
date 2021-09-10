<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?> 

<? //echo "<pre>"; print_r($arResult); echo "</pre>"; ?>

<?
function subsections($items){
	$str = '';
	$str .= '<ul class="menu-v dropdown">';
	foreach($items as $arRow) {
		$str .= '<li class="'.(!empty($arRow['IS_PARENT']) ? 'drop' : '').'">';
		$openclose = "";
		if(!empty($arRow['IS_PARENT'])) {
			if($arRow["IN_CHEIN"]) {
				$openclose = '<i class="fa fa-caret-down open-icon" aria-hidden="true"></i>';
			}
			else {
				$openclose = '<i class="fa fa-caret-right open-icon" aria-hidden="true"></i>';
			}
		}
		$str .= '<a class="" href="'.$arRow["LINK"].'">'.$openclose.$arRow["TEXT"].'</a>';
		if(!empty($arRow['IS_PARENT'])) {
			$str .= subsections($arRow['CHILD']);
		}
		$str .= '</li>';
	}
	$str .= '</ul>';
	return $str;
}
?>

<?if (!empty($arResult)):?>

<div class="catalog_nav">
	<ul class="menu-h dropdown clickable">
		<?foreach($arResult as $arItem):?>
			<?
			$openclose = "";
			if(!empty($arItem['IS_PARENT'])) {
				if($arItem["IN_CHEIN"]) {
					$openclose = '<i class="fa fa-caret-down open-icon" aria-hidden="true"></i>';
				}
				else {
					$openclose = '<i class="fa fa-caret-right open-icon" aria-hidden="true"></i>';
				}
			}
			?>
			<li class="drop">
				<a class="" href="<?=$arItem["LINK"]?>"><?=$openclose?><?=$arItem["TEXT"]?></a>
				<?if(!empty($arItem['IS_PARENT'])):?>
					<? echo subsections($arItem['CHILD']); ?>
				<?endif?>
			</li>
		<?endforeach;?>
	</ul>
</div>

<?endif?>