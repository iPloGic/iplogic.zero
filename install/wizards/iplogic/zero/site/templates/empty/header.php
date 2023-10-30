<?if( !defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true ) {
	die();
}?><!DOCTYPE html>
<html>
<head>
	<title><? $APPLICATION->ShowTitle(); ?></title>
	<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE = edge"><![endif]-->
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<link rel="shortcut icon" type="image/x-icon" href="/favicon.ico">
	<? //$APPLICATION->ShowHead() ?>
	<? $APPLICATION->ShowMeta("keywords") ?>
	<? $APPLICATION->ShowMeta("description") ?>
	<? $APPLICATION->ShowMeta("robots") ?>
	<? $APPLICATION->ShowCSS(); ?>
	<? $APPLICATION->ShowHeadStrings() ?>
	<? $APPLICATION->ShowHeadScripts() ?>
</head>
<body>
	<div id="panel"><? $APPLICATION->ShowPanel(); ?></div>

