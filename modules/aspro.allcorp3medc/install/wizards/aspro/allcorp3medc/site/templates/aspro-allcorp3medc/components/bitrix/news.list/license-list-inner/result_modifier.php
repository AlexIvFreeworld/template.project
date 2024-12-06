<?php
$bLinkedMode = (isset($arParams['LINKED_MODE']) && $arParams['LINKED_MODE'] == 'Y');

foreach($arResult['ITEMS'] as $key => $arItem){
	if(!$bLinkedMode){
		if($SID = $arItem['IBLOCK_SECTION_ID']){
			$arSectionsIDs[] = $SID;
		}
	}	

	$arResult['ITEMS'][$key]['GALLERY'] = TSolution\Functions::getSliderForItem([
		'TYPE' => 'catalog_block',
		'PROP_CODE' => 'PHOTOS',
		// 'ADD_DETAIL_SLIDER' => false,
		'ITEM' => $arResult['ITEMS'][$key],
		'PARAMS' => $arParams,
	]);
	
	if (!$arResult['ITEMS'][$key]['GALLERY'] && $arResult['ITEMS'][$key]['PREVIEW_PICTURE']) {
		$arResult['ITEMS'][$key]['GALLERY'][] = $arResult['ITEMS'][$key]['PREVIEW_PICTURE'];
	}
}

if ($arSectionsIDs) {
	$arResult['SECTIONS'] = CAllcorp3MedcCache::CIBLockSection_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CAllcorp3MedcCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'GROUP' => array('ID'), 'MULTI' => 'N')), array('ID' => $arSectionsIDs));
}

if($arResult['SECTIONS']){
	$arItemSectionsIDs = array_column($arResult['SECTIONS'], 'ID');
}

if(!$arItemSectionsIDs) {
	$bLinkedMode = true;
}

$arParams['SHOW_NAVIGATION_PAGER'] = 'N';
$arParams['LINKED_MODE'] = $bLinkedMode ? 'Y' : 'N';
if($bLinkedMode) {
	$arParams['SHOW_NAVIGATION_PAGER'] = 'Y';
}

foreach ($arResult['ITEMS'] as $arItem) {
	$SID = ($arItem['IBLOCK_SECTION_ID'] && !$bLinkedMode ? $arItem['IBLOCK_SECTION_ID'] : 0);

	if(in_array($arItem['IBLOCK_SECTION_ID'], $arItemSectionsIDs) || $bLinkedMode){
		$arResult['SECTIONS'][$SID]['ITEMS'][$arItem['ID']] = $arItem;
	}
}

// unset empty sections
if(is_array($arResult['SECTIONS'])){
	foreach($arResult['SECTIONS'] as $i => $arSection){
		if(!$arSection['ITEMS']){
			unset($arResult['SECTIONS'][$i]);
		}
	}
}
?>