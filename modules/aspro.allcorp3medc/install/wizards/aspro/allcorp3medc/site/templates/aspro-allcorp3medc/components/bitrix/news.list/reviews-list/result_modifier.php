<?
foreach($arResult['ITEMS'] as $key => $arItem){
	CAllcorp3Medc::getFieldImageData($arResult['ITEMS'][$key], array('PREVIEW_PICTURE'));
}
?>