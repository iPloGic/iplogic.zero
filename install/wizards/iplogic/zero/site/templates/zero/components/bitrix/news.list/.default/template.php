<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */

use Bitrix\Main\Localization\Loc;

$this->setFrameMode(true);
?>
<div class="news-list">
<?if($arParams["DISPLAY_TOP_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?>
<?endif;?>
<?foreach($arResult["ITEMS"] as $arItem):?>
	<?
	$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_EDIT"));
	$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => Loc::getMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));
	?>
	<div class="news-item row" id="<?=$this->GetEditAreaId($arItem['ID']);?>">
		<? $textDivWide = "" ?>
		<?if($arParams["DISPLAY_PREVIEW_PICTURE"]!="N"):?>
			<? $textDivWide = " col-sm-9 col-md-10" ?>
			<?if(is_array($arItem["PREVIEW_PICTURE"])) {
				$src = $arItem["PREVIEW_PICTURE"]["SRC"];
				$alt = $arItem["PREVIEW_PICTURE"]["ALT"];
			}
			else {
				$src = $templateFolder."/img/no-im.jpg";
				$alt = "";
			}?>
			<div class="col-12 col-sm-3 col-md-2">
				<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
					<a href="<?=$arItem["DETAIL_PAGE_URL"]?>">
				<?endif;?>
				<img src="<?=$src?>" alt="<?=$alt?>">
				<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
					</a>
				<?endif;?>
			</div>
		<?endif?>

		<div class="col-12<?=$textDivWide?>">
			<?if($arParams["DISPLAY_DATE"]!="N" && $arItem["DISPLAY_ACTIVE_FROM"]):?>
				<span class="news-date-time"><?echo $arItem["DISPLAY_ACTIVE_FROM"]?></span>
			<?endif?>
			<?if($arParams["DISPLAY_NAME"]!="N" && $arItem["NAME"]):?>
				<?if(!$arParams["HIDE_LINK_WHEN_NO_DETAIL"] || ($arItem["DETAIL_TEXT"] && $arResult["USER_HAVE_ACCESS"])):?>
					<h3><a href="<?echo $arItem["DETAIL_PAGE_URL"]?>"><?echo $arItem["NAME"]?></a></h3>
				<?else:?>
					<h3><?echo $arItem["NAME"]?></h3>
				<?endif;?>
			<?endif;?>
			<?if($arParams["DISPLAY_PREVIEW_TEXT"]!="N" && $arItem["PREVIEW_TEXT"]):?>
				<?echo $arItem["PREVIEW_TEXT"];?>
			<?endif;?>
			<?if($arParams["DISPLAY_PREVIEW_PICTURE"]!="N" && is_array($arItem["PREVIEW_PICTURE"])):?>
				<div style="clear:both"></div>
			<?endif?>
			<?foreach($arItem["FIELDS"] as $code=>$value):?>
				<small>
				<?=Loc::getMessage("IBLOCK_FIELD_".$code)?>:&nbsp;<?=$value;?>
				</small><br />
			<?endforeach;?>
			<?foreach($arItem["DISPLAY_PROPERTIES"] as $pid=>$arProperty):?>
				<small>
				<?=$arProperty["NAME"]?>:&nbsp;
				<?if(is_array($arProperty["DISPLAY_VALUE"])):?>
					<?=implode("&nbsp;/&nbsp;", $arProperty["DISPLAY_VALUE"]);?>
				<?else:?>
					<?=$arProperty["DISPLAY_VALUE"];?>
				<?endif?>
				</small><br />
			<?endforeach;?>
		</div>

	</div>
<?endforeach;?>
<?if($arParams["DISPLAY_BOTTOM_PAGER"]):?>
	<?=$arResult["NAV_STRING"]?>
<?endif;?>
</div>
