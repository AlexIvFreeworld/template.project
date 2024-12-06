<?
if (!$templateData['ITEMS']) {
	$GLOBALS['APPLICATION']->SetPageProperty('BLOCK_BRANDS', 'hidden');
}

$arExtensions = ['swiper'];
if ($arExtensions) {
	\Aspro\Allcorp3Medc\Functions\Extensions::init($arExtensions);
}
?>