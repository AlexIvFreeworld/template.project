<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<?
// get element
$arItemFilter = CAllcorp3Medc::GetCurrentElementFilter($arResult['VARIABLES'], $arParams);

global $APPLICATION, $arTheme;
$APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/animation/animate.min.css');
$APPLICATION->AddHeadScript(SITE_TEMPLATE_PATH.'/js/jquery.history.js');

// cart
$bOrderViewBasket = (trim($arTheme['ORDER_VIEW']['VALUE']) === 'Y');

$arElement = CAllcorp3MedcCache::CIblockElement_GetList(
	array(
		'CACHE' => array(
			'TAG' => CAllcorp3MedcCache::GetIBlockCacheTag($arParams['IBLOCK_ID']),
			'MULTI' => 'N'
		)
	), 
	CAllcorp3Medc::makeElementFilterInRegion($arItemFilter), 
	false, 
	false, 
	array('ID', 'IBLOCK_SECTION_ID')
);

//bug fix bitrix for search element
if ($arElement) {
	$strict_check = $arParams["DETAIL_STRICT_SECTION_CHECK"] === "Y";
	if(!CIBlockFindTools::checkElement($arParams["IBLOCK_ID"], $arResult["VARIABLES"], $strict_check))
		$arElement = array();
}
?>
<?if(!$arElement && $arParams['SET_STATUS_404'] !== 'Y'):?>
	<div class="alert alert-warning"><?=GetMessage("ELEMENT_NOTFOUND")?></div>
<?elseif(!$arElement && $arParams['SET_STATUS_404'] === 'Y'):?>
	<?CAllcorp3Medc::goto404Page();?>
<?else:?>
	<?CAllcorp3Medc::CheckComponentTemplatePageBlocksParams($arParams, __DIR__);?>
	
	<?// share top?>
	<?if($arParams['USE_SHARE'] === 'Y' && $arElement):?>
		<?$this->SetViewTarget('cowl_buttons');?>
		<?Aspro\Allcorp3Medc\Functions\CAsproAllcorp3::showShareBlock(
			array(
				'CLASS' => 'top',
			)
		);?>
		<?$this->EndViewTarget();?>
	<?endif;?>

	<?// rss?>
	<?if($arParams['USE_RSS'] !== 'N'):?>
		<?$this->SetViewTarget('cowl_buttons');?>
		<?Aspro\Allcorp3Medc\Functions\CAsproAllcorp3::ShowRSSIcon(
			array(
				'URL' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['rss']
			)
		);?>
		<?$this->EndViewTarget();?>
	<?endif;?>
	
	<?CAllcorp3Medc::AddMeta(
		array(
			'og:description' => $arElement['PREVIEW_TEXT'],
			'og:image' => (($arElement['PREVIEW_PICTURE'] || $arElement['DETAIL_PICTURE']) ? CFile::GetPath(($arElement['PREVIEW_PICTURE'] ? $arElement['PREVIEW_PICTURE'] : $arElement['DETAIL_PICTURE'])) : false),
		)
	);?>
	<div class="detail detail-maxwidth js-popup-block template-<?=($templateName = $component->{'__template'}->{'__name'})?>" itemscope itemtype="http://schema.org/Service">
		<?$arParams["GRUPPER_PROPS"] = $arTheme["GRUPPER_PROPS"]["VALUE"];
		if($arTheme["GRUPPER_PROPS"]["VALUE"] != "NOT")
		{
			$arParams["PROPERTIES_DISPLAY_TYPE"] = "TABLE";

			if($arParams["GRUPPER_PROPS"] == "GRUPPER" && !\Bitrix\Main\Loader::includeModule("redsign.grupper"))
				$arParams["GRUPPER_PROPS"] = "NOT";
			if($arParams["GRUPPER_PROPS"] == "WEBDEBUG" && !\Bitrix\Main\Loader::includeModule("webdebug.utilities"))
				$arParams["GRUPPER_PROPS"] = "NOT";
			if($arParams["GRUPPER_PROPS"] == "YENISITE_GRUPPER" && !\Bitrix\Main\Loader::includeModule("yenisite.infoblockpropsplus"))
				$arParams["GRUPPER_PROPS"] = "NOT";
		}?>

		<?//element?>
		<?$sViewElementTemplate = $arParams["ELEMENT_TYPE_VIEW"];?>
		<?@include_once('page_blocks/'.$sViewElementTemplate.'.php');?>

	</div>
	<?
	if (is_array($arElement['IBLOCK_SECTION_ID']) && count($arElement['IBLOCK_SECTION_ID']) > 1) {
		CAllcorp3Medc::CheckAdditionalChainInMultiLevel($arResult, $arParams, $arElement);
	}
	?>
<?endif;?>
<?
if($arElement['IBLOCK_SECTION_ID']){
	$arSection = CAllcorp3MedcCache::CIBlockSection_GetList(array('CACHE' => array('TAG' => CAllcorp3MedcCache::GetIBlockCacheTag($arElement['IBLOCK_ID']), 'MULTI' => 'N')), array('ID' => $arElement['IBLOCK_SECTION_ID'], 'ACTIVE' => 'Y'), false, array('ID', 'NAME', 'SECTION_PAGE_URL'));
}
?>
<div class="bottom-links-block<?=($arElement ? ' detail-maxwidth' : '')?>">
    <?// back url?>
    <?Aspro\Allcorp3Medc\Functions\CAsproAllcorp3::showBackUrl(
        array(
            'URL' => ((isset($arSection) && $arSection) ? $arSection['SECTION_PAGE_URL'] : $arResult['FOLDER'].$arResult['URL_TEMPLATES']['news']),
            'TEXT' => ($arParams['T_PREV_LINK'] ? $arParams['T_PREV_LINK'] : GetMessage('BACK_LINK')),
        )
    );?>

    <?// share bottom?>
    <?if($arParams['USE_SHARE'] === 'Y' && $arElement):?>
        <?Aspro\Allcorp3Medc\Functions\CAsproAllcorp3::showShareBlock(
            array(
                'CLASS' => 'bottom',
            )
        );?>
    <?endif;?>
</div>