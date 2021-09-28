<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

/**
 * @global CMain $APPLICATION
 */

//global $APPLICATION;

$strReturn = '<nav class="breadcrumbs"><a href="/"><i class="fas fa-home"></i></a>';
$itemSize = count($arResult);
for($index = 0; $index < $itemSize; $index++)
{
	$title = htmlspecialcharsex($arResult[$index]["TITLE"]);

	if($arResult[$index]["LINK"] <> "" && $index != $itemSize-1)
	{
		$strReturn .= '
					&nbsp;<i class="fa fa-angle-right"></i>&nbsp;
					<a class="s-breadcrumb-link" href="'.$arResult[$index]["LINK"].'">'.$title.'</a>';
	}
	else
	{
		$strReturn .= '
			&nbsp;<i class="fa fa-angle-right"></i>&nbsp;<span class="last">'.$title.'</span>';
	}
}

$strReturn .= '</nav>';

return $strReturn;