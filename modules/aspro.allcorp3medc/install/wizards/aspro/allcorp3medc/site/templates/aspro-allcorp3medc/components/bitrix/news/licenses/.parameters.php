<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc,
	Bitrix\Iblock,
	CAllcorp3Medc as TSolution;

/* get component template pages & params array */
if(\Bitrix\Main\Loader::includeModule('aspro.allcorp3medc')){
	$arPageBlocks = TSolution::GetComponentTemplatePageBlocks(__DIR__);
	$arPageBlocksParams = TSolution::GetComponentTemplatePageBlocksParams($arPageBlocks);
	TSolution::AddComponentTemplateModulePageBlocksParams(__DIR__, $arPageBlocksParams); // add option value FROM_MODULE
}

$propertyIterator = Iblock\PropertyTable::getList(array(
	'select' => array('ID', 'IBLOCK_ID', 'NAME', 'CODE', 'PROPERTY_TYPE'),
	'filter' => array(
		'=IBLOCK_ID' => $arCurrentValues['IBLOCK_ID'],
		'=ACTIVE' => 'Y'
	),
	'order' => array(
		'SORT' => 'ASC',
		'NAME' => 'ASC'
	)
));

$arProperty_F = array();

while ($property = $propertyIterator->fetch()) {
	$propertyCode =(string)$property['CODE'];

	if($propertyCode == '')
	$propertyCode = $property['ID'];

	$propertyName = '['.$propertyCode.'] '.$property['NAME'];

	if($property['PROPERTY_TYPE'] == Iblock\PropertyTable::TYPE_FILE) {
		$arProperty_F[$propertyCode] = $propertyName;
	}
	
}

$arTemplateParameters = array_merge($arPageBlocksParams, [
	'SHOW_SECTION_PREVIEW_DESCRIPTION' => [
		'NAME' => Loc::getMessage('SHOW_SECTION_PREVIEW_DESCRIPTION'),
		'TYPE' => 'CHECKBOX',
		'DEFAULT' => 'Y',
	],
]);

$arTemplateParameters['ADD_PICT_PROP'] = array(
	'PARENT' => 'LIST_SETTINGS',
	'NAME' => GetMessage('ADD_PICT_PROP'),
	'TYPE' => 'LIST',
	'MULTIPLE' => 'N',
	'ADDITIONAL_VALUES' => 'N',
	'REFRESH' => 'N',
	'DEFAULT' => 'PHOTOS',
	"VALUES" => array_merge(Array("-"=>"-"), $arProperty_F),
);

?>