<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

global $APPLICATION;
$IBLOCK_ID = 24;

$arSelect = array('NAME', 'CODE', 'ID', 'IBLOCK_ID');
$arFilter = array('IBLOCK_ID' => $IBLOCK_ID, 'ACTIVE' => 'Y');
$aMenuLinksExt = array();
$arSections = CIBlockSection::GetList(array('SORT' => 'ASC'), $arFilter, true, $arSelect);

while ($arSection = $arSections->GetNext(false, false)) {
	// R52::debug($arSection);
	if (!$arSection['ELEMENT_CNT'])
		continue;

	$aMenuLinksExt[] = array(
		$arSection['NAME'],
		SITE_DIR . 'services/' . $arSection['CODE'] . '/',
		array(0 => SITE_DIR . 'services/' . $arSection['CODE'] . '/'),
		array('DEPTH_LEVEL' => '1', 'IS_PARENT' => true, "FROM_IBLOCK" => 1),
	);

	$elementFilter = array_merge($arFilter, array('SECTION_ID' => $arSection['ID']));
	$elementSelect = array_merge($arSelect, array('DETAIL_PAGE_URL'));

	$arElement = CIBlockElement::GetList(array('SORT' => 'ASC'), $elementFilter, false, false, $elementSelect);
	while ($arItem = $arElement->GetNext(false, false)) {
		$aMenuLinksExt[] = array(
			$arItem['NAME'],
			$arItem['DETAIL_PAGE_URL'],
			array(0 => $arItem['DETAIL_PAGE_URL']),
			array('DEPTH_LEVEL' => '2', 'IS_PARENT' => false, "FROM_IBLOCK" => false),
		);
	}
}
// R52::debug($aMenuLinksExt);
$aMenuLinks = array_merge($aMenuLinks, $aMenuLinksExt);
