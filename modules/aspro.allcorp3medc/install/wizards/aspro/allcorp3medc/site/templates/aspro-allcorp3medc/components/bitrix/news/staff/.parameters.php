<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
	die();
}

use Bitrix\Main\Loader,
	Bitrix\Main\ModuleManager,
	Bitrix\Main\Web\Json,
	Bitrix\Iblock,
	Bitrix\Main\Localization\Loc,
	Aspro\Allcorp3Medc\Functions\ExtComponentParameter;

if (!Loader::includeModule('iblock')) {
	return;
}

$arAscDesc = array(
	'asc' => GetMessage('IBLOCK_SORT_ASC'),
	'desc' => GetMessage('IBLOCK_SORT_DESC'),
);

$arIBlocks = [];
$rsIBlock = CIBlock::GetList(
	[
		'ID' => 'ASC'
	],
	[
		// 'TYPE' => $arCurrentValues['IBLOCK_TYPE'], 
		'ACTIVE' => 'Y'
	]
);
while ($arIBlock = $rsIBlock->Fetch()) {
	$arIBlocks[$arIBlock['ID']] = "[{$arIBlock['ID']}] {$arIBlock['NAME']}";
}

$arSKUProperty = $arSKUProperty_X = [];
if ($arCurrentValues['SKU_IBLOCK_ID']) {
	$propertyIterator = Iblock\PropertyTable::getList(array(
		'select' => array('ID', 'IBLOCK_ID', 'NAME', 'CODE', 'PROPERTY_TYPE', 'MULTIPLE', 'LINK_IBLOCK_ID', 'USER_TYPE', 'SORT'),
		'filter' => array(
			'=IBLOCK_ID' => $arCurrentValues['SKU_IBLOCK_ID'],
			'=ACTIVE' => 'Y'
		),
		'order' => array(
			'SORT' => 'ASC',
			'NAME' => 'ASC'
		)
	));
	while($property = $propertyIterator->fetch()){
		$propertyCode =(string)$property['CODE'];

		if($propertyCode == '')
			$propertyCode = $property['ID'];

		$propertyName = '['.$propertyCode.'] '.$property['NAME'];

		if($property['PROPERTY_TYPE'] != Iblock\PropertyTable::TYPE_FILE){
			$arSKUProperty[$propertyCode] = $propertyName;

			if($property['MULTIPLE'] != 'Y' && $property['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_LIST){
				$arSKUProperty_X[$propertyCode] = $propertyName;
			}
		}
	}
	unset($propertyCode, $propertyName, $property, $propertyIterator);

	$arSkuSort = CIBlockParameters::GetElementSortFields(
		array('SHOWS', 'SORT', 'TIMESTAMP_X', 'NAME', 'ID', 'ACTIVE_FROM', 'ACTIVE_TO'),
		array('KEY_LOWERCASE' => 'Y')
	);
}

ExtComponentParameter::init(__DIR__, $arCurrentValues);

ExtComponentParameter::addBaseParameters();

ExtComponentParameter::addRelationBlockParameters(array(
	ExtComponentParameter::RELATION_BLOCK_DESC,
	ExtComponentParameter::RELATION_BLOCK_DOCS,
	ExtComponentParameter::ORDER_BLOCK_SCHEDULE,
	ExtComponentParameter::RELATION_BLOCK_REVIEWS,
	ExtComponentParameter::RELATION_BLOCK_SERVICES,
	ExtComponentParameter::RELATION_BLOCK_GOODS,
));

ExtComponentParameter::addOrderBlockParameters(array(
	ExtComponentParameter::ORDER_BLOCK_TABS,
));

ExtComponentParameter::addOrderTabParameters([
	ExtComponentParameter::ORDER_BLOCK_DESC,
	ExtComponentParameter::ORDER_BLOCK_DOCS,
	ExtComponentParameter::ORDER_BLOCK_SCHEDULE,
	ExtComponentParameter::ORDER_BLOCK_REVIEWS,
	ExtComponentParameter::ORDER_BLOCK_SERVICES,
	ExtComponentParameter::ORDER_BLOCK_GOODS,
]);

ExtComponentParameter::addSelectParameter('SKU_IBLOCK_ID', [
	'PARENT' => ExtComponentParameter::PARENT_GROUP_BASE,
	"VALUES" => $arIBlocks,
	"DEFAULT" => "",
	"PARENT" => "BASE",
	"REFRESH" => "Y",
	'SORT' => 999
]);
ExtComponentParameter::addSelectParameter('SKU_PROPERTY_CODE', [
	'ADDITIONAL_VALUES' => 'Y',
	'PARENT' => ExtComponentParameter::PARENT_GROUP_ADDITIONAL,
	'VALUES' => $arSKUProperty,
	'MULTIPLE' => 'Y',
	'SORT' => 999
]);
ExtComponentParameter::addSelectParameter('SKU_TREE_PROPS', [
	'ADDITIONAL_VALUES' => 'Y',
	'PARENT' => ExtComponentParameter::PARENT_GROUP_ADDITIONAL,
	'VALUES' => $arSKUProperty_X,
	'MULTIPLE' => 'Y',
	'SORT' => 999
]);
ExtComponentParameter::addSelectParameter('SKU_SORT_FIELD', [
	'ADDITIONAL_VALUES' => 'Y',
	'PARENT' => ExtComponentParameter::PARENT_GROUP_ADDITIONAL,
	'VALUES' => $arSkuSort,
	'DEFAULT' => 'name',
	'SORT' => 999
]);
ExtComponentParameter::addSelectParameter('SKU_SORT_ORDER', [
	'ADDITIONAL_VALUES' => 'Y',
	'PARENT' => ExtComponentParameter::PARENT_GROUP_ADDITIONAL,
	'VALUES' => $arAscDesc,
	'SORT' => 999
]);
ExtComponentParameter::addSelectParameter('SKU_SORT_FIELD2', [
	'ADDITIONAL_VALUES' => 'Y',
	'PARENT' => ExtComponentParameter::PARENT_GROUP_ADDITIONAL,
	'VALUES' => $arSkuSort,
	'DEFAULT' => 'sort',
	'SORT' => 999
]);
ExtComponentParameter::addSelectParameter('SKU_SORT_ORDER2', [
	'ADDITIONAL_VALUES' => 'Y',
	'PARENT' => ExtComponentParameter::PARENT_GROUP_ADDITIONAL,
	'VALUES' => $arAscDesc,
	'SORT' => 999
]);

ExtComponentParameter::appendTo($arTemplateParameters);

$arTemplateParameters['SHOW_DETAIL_LINK'] = [
	'PARENT' => ExtComponentParameter::PARENT_GROUP_LIST,
	'NAME' => Loc::getMessage('SHOW_DETAIL_LINK'),
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'Y',
];

$arTemplateParameters['SHOW_SECTION_PREVIEW_DESCRIPTION'] = [
	'PARENT' => ExtComponentParameter::PARENT_GROUP_LIST,
	'NAME' => Loc::getMessage('SHOW_SECTION_PREVIEW_DESCRIPTION'),
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'Y',
];

$arTemplateParameters['USE_SHARE'] = [
	'PARENT' => ExtComponentParameter::PARENT_GROUP_LIST,
	'NAME' => Loc::getMessage('USE_SHARE'),
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'Y',
];
$arTemplateParameters['SHOW_BLOCK_REVIEWS'] = [
	'PARENT' => ExtComponentParameter::PARENT_GROUP_ADDITIONAL,
	'NAME' => Loc::getMessage('SHOW_BLOCK_REVIEWS'),
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'Y',
];

$arTemplateParameters['HIDE_RECORD_BUTTON'] = [
	'ADDITIONAL_SETTINGS' => 'Y',
	'PARENT' => ExtComponentParameter::PARENT_GROUP_ADDITIONAL,
	'NAME' => Loc::getMessage('HIDE_RECORD_BUTTON'),
	'TYPE' => 'CHECKBOX',
	'DEFAULT' => 'N',
];