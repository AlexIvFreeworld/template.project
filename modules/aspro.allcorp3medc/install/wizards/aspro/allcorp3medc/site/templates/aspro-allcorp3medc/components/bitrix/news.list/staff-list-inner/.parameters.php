<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;

$arTemplateParameters = [
	'SHOW_DETAIL_LINK' => [
		'NAME' => Loc::getMessage('SHOW_DETAIL_LINK'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
	],
	'SHOW_SECTION_PREVIEW_DESCRIPTION' => [
		'NAME' => Loc::getMessage('SHOW_SECTION_PREVIEW_DESCRIPTION'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
	],
	'USE_SHARE' => [
		'NAME' => Loc::getMessage('USE_SHARE'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
	],
	'HIDE_RECORD_BUTTON' => [
		'ADDITIONAL_SETTINGS' => 'Y',
		'NAME' => Loc::getMessage('HIDE_RECORD_BUTTON'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
	],
];