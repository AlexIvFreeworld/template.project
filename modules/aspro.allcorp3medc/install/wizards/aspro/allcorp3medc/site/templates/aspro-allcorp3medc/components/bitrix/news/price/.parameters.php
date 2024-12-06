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

$arTemplateParametersParts = [];

ExtComponentParameter::init(__DIR__, $arCurrentValues);
ExtComponentParameter::addBaseParameters(array(
	array(
		array(),
		'SECTIONS_TYPE_VIEW',
	),
	array(
		array(),
		'SECTION_TYPE_VIEW',
	),
	array(
		array(),
		'SECTION_ELEMENTS_TYPE_VIEW'
	),
	array(
		array(),
		'ELEMENT_TYPE_VIEW'
	),
));

ExtComponentParameter::addRelationBlockParameters(array(
	ExtComponentParameter::RELATION_BLOCK_DESC,
	ExtComponentParameter::RELATION_BLOCK_CHAR,
	array(
		ExtComponentParameter::RELATION_BLOCK_DOPS,
		'additionalParams' => [
			'toggle' => false,
		],		
	),
));

ExtComponentParameter::addOrderAllParameters(array(
	ExtComponentParameter::ORDER_BLOCK_DESC,
	ExtComponentParameter::ORDER_BLOCK_ORDER_FORM,
	ExtComponentParameter::ORDER_BLOCK_CHAR,
	ExtComponentParameter::ORDER_BLOCK_DOPS,
));

ExtComponentParameter::addCheckBoxParameter('USE_SHARE', [
	"DEFAULT" => "N"
]);

ExtComponentParameter::appendTo($arTemplateParameters);

if (strpos($arCurrentValues['SECTIONS_TYPE_VIEW'], 'sections_1') !== false) {
	$arTemplateParametersParts[] = array(
		'SECTIONS_ITEMS_OFFSET' => array(
			'PARENT' => 'LIST_SETTINGS',
			'NAME' => GetMessage('SECTIONS_ITEMS_OFFSET'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'Y',
		),
	);
}

if (strpos($arCurrentValues['SECTION_TYPE_VIEW'], 'section_1') !== false) {
	$arTemplateParametersParts[] = array(
		'SECTION_ITEMS_OFFSET' => array(
			'PARENT' => 'LIST_SETTINGS',
			'NAME' => GetMessage('SECTION_ITEMS_OFFSET'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'Y',
		),
	);
}

if(strpos($arCurrentValues['SECTION_ELEMENTS_TYPE_VIEW'], 'list_elements_1') !== false) {
	$arTemplateParametersParts[] = array(
		'ELEMENTS_ITEMS_OFFSET' => array(
			'PARENT' => 'LIST_SETTINGS',
			'NAME' => GetMessage('SECTION-ELEMENTS_ITEMS_OFFSET'),
			'TYPE' => 'CHECKBOX',
			'DEFAULT' => 'Y',
		),
	);
}

$arTemplateParameters['SHOW_DETAIL_LINK'] = [
	'PARENT' => ExtComponentParameter::PARENT_GROUP_LIST,
	'NAME' => Loc::getMessage('SHOW_DETAIL_LINK'),
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'Y',
];

$arTemplateParameters = array_merge($arTemplateParameters, array(
	'SHOW_SECTION_PREVIEW_DESCRIPTION' => array(
		'PARENT' => ExtComponentParameter::PARENT_GROUP_LIST,
		'SORT' => 700,
		'NAME' => Loc::getMessage('T_SHOW_SECTION_PREVIEW_DESCRIPTION'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
	),
	'SHOW_SECTION_DESCRIPTION' => array(
		'PARENT' => ExtComponentParameter::PARENT_GROUP_LIST,
		'SORT' => 700,
		'NAME' => Loc::getMessage('T_SHOW_SECTION_DESCRIPTION'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
	),
	"INCLUDE_SUBSECTIONS" => array(
		"PARENT" => ExtComponentParameter::PARENT_GROUP_LIST,
		"NAME" => Loc::getMessage("CP_BC_INCLUDE_SUBSECTIONS"),
		"TYPE" => "LIST",
		"VALUES" => array(
			"Y" => Loc::getMessage('CP_BC_INCLUDE_SUBSECTIONS_ALL'),
			"A" => Loc::getMessage('CP_BC_INCLUDE_SUBSECTIONS_ACTIVE'),
			"N" => Loc::getMessage('CP_BC_INCLUDE_SUBSECTIONS_NO'),
		),
		"DEFAULT" => "N",
	),
	'S_ASK_QUESTION' => array(
		'PARENT' => ExtComponentParameter::PARENT_GROUP_DETAIL,
		'SORT' => 700,
		'NAME' => Loc::getMessage('S_ASK_QUESTION'),
		'TYPE' => 'TEXT',
		'DEFAULT' => '',
	),
	'SHOW_CHILD_SECTIONS' => array(
		'PARENT' => ExtComponentParameter::PARENT_GROUP_LIST,
		'SORT' => 700,
		'NAME' => Loc::getMessage('SHOW_CHILD_SECTIONS'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
	),
	'SHOW_CHILD_ELEMENTS' => array(
		'PARENT' => ExtComponentParameter::PARENT_GROUP_LIST,
		'SORT' => 700,
		'NAME' => Loc::getMessage('SHOW_CHILD_ELEMENTS'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
		'REFRESH' => 'Y',
	),
	'HIDE_RECORD_BUTTON' => array(
		'SORT' => 701,
		'NAME' => Loc::getMessage('HIDE_RECORD_BUTTON'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
		'ADDITIONAL_SETTINGS' => 'Y',
	),
));

if($arCurrentValues['SHOW_CHILD_ELEMENTS'] == 'Y'){
	$arTemplateParameters['SHOW_ELEMENTS_IN_LAST_SECTION'] = array(
		'PARENT' => ExtComponentParameter::PARENT_GROUP_LIST,
		'SORT' => 700,
		'NAME' => Loc::getMessage('SHOW_ELEMENTS_IN_LAST_SECTION'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'N',
	);
}

//merge parameters to one array
foreach ($arTemplateParametersParts as $i => $part) {
	$arTemplateParameters = array_merge($arTemplateParameters, $part);
}?>