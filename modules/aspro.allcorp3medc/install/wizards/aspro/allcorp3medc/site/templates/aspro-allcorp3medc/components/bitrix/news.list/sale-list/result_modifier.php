<?
foreach($arResult['ITEMS'] as $key => &$arItem){
	$arItem['DETAIL_PAGE_URL'] = CAllcorp3Medc::FormatNewsUrl($arItem);

	CAllcorp3Medc::getFieldImageData($arResult['ITEMS'][$key], array('PREVIEW_PICTURE'));
}
unset($arItem);
?>