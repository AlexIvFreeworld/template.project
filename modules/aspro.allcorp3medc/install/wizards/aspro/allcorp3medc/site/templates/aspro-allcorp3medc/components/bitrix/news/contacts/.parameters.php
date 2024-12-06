<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader,
	Bitrix\Main\ModuleManager,
	Bitrix\Main\Web\Json,
	Bitrix\Iblock,
	Bitrix\Main\Localization\Loc,
	Aspro\Allcorp3Medc\Functions\ExtComponentParameter;

if(
	!Loader::includeModule('iblock') ||
	!Loader::includeModule('aspro.allcorp3medc')
){
	return;
}

ExtComponentParameter::init(__DIR__, $arCurrentValues);
ExtComponentParameter::addBaseParameters(array(
	array(
		array('SECTION' => 'SECTION', 'OPTION' => 'PAGE_CONTACTS'),
		'SECTIONS_TYPE_VIEW'
	),
));

ExtComponentParameter::appendTo($arTemplateParameters);

if (strpos($arCurrentValues['SECTIONS_TYPE_VIEW'], 'sections_1') === false) {
	$arTemplateParameters = array_merge(
		$arTemplateParameters,
		[
			'CHOOSE_REGION_TEXT' => [
				'PARENT' => 'LIST_SETTINGS',
				'SORT' => 100,
				'NAME' => GetMessage('CHOOSE_REGION_TEXT'),
				'TYPE' => 'TEXT',
				'DEFAULT' => '',
			],
			'CHOOSE_SPECIALIZATION_TEXT' => [
				'PARENT' => 'LIST_SETTINGS',
				'SORT' => 100,
				'NAME' => GetMessage('CHOOSE_SPECIALIZATION_TEXT'),
				'TYPE' => 'TEXT',
				'DEFAULT' => '',
			],
		]
	);
}
?>