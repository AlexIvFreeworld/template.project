<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

$arTemplateParameters = array(
	'DISPLAY_DATE' => Array(
		'NAME' => GetMessage('T_IBLOCK_DESC_NEWS_DATE'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
	),
	'DISPLAY_PICTURE' => Array(
		'NAME' => GetMessage('T_IBLOCK_DESC_NEWS_PICTURE'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
	),
	'DISPLAY_PREVIEW_TEXT' => Array(
		'NAME' => GetMessage('T_IBLOCK_DESC_NEWS_TEXT'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
	),
	'USE_SHARE' => array(
		'NAME' => GetMessage('USE_SHARE'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
	),
	'S_ASK_QUESTION' => array(
		'SORT' => 700,
		'NAME' => GetMessage('S_ASK_QUESTION'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	'HIDE_RECORD_BUTTON' => array(
		'SORT' => 700,
		'NAME' => GetMessage('HIDE_RECORD_BUTTON'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
	),
);
?>