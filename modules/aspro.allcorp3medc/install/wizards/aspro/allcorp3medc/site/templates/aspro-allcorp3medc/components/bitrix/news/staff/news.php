<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use \Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

global $arTheme, $APPLICATION;

$arItemFilter = TSolution::GetIBlockAllElementsFilter($arParams);
$arItemFilter["SECTION_GLOBAL_ACTIVE"] = "Y";

if(strlen($arParams['FILTER_NAME'])){
	$GLOBALS[$arParams['FILTER_NAME']] = array_merge((array)$GLOBALS[$arParams['FILTER_NAME']], $arItemFilter);
}
else{
	$arParams['FILTER_NAME'] = 'arrFilter';
	$GLOBALS[$arParams['FILTER_NAME']] = $arItemFilter;
}

// filter by specialization
if($arTheme['STAFF_PAGE_USE_SPECIALIZATION_FILTER']['VALUE'] !== 'N'){
	if(
		isset($_REQUEST['SPECIALIZATION']) &&
		strlen(trim($_REQUEST['SPECIALIZATION']))
	){
		if(!strlen($arParams['FILTER_NAME'])){
			$arParams['FILTER_NAME'] = 'arrFilter';
			$GLOBALS[$arParams['FILTER_NAME']] = [];
		}

		$GLOBALS[$arParams['FILTER_NAME']]['PROPERTY_SPECIALIZATION'] = $arItemFilter['PROPERTY_SPECIALIZATION'] = trim(urldecode($_REQUEST['SPECIALIZATION']));
	}
}

$itemsCnt = TSolution\Cache::CIblockElement_GetList(array("CACHE" => array("TAG" => TSolution\Cache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), $arItemFilter, array());

TSolution::CheckComponentTemplatePageBlocksParams($arParams, __DIR__);
?>
<?// rss?>
<?if($arParams['USE_RSS'] !== 'N'):?>
	<?$this->SetViewTarget('cowl_buttons');?>
	<?TSolution\Functions::ShowRSSIcon(
		array(
			'URL' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['rss']
		)
	);?>
	<?$this->EndViewTarget();?>
<?endif;?>

<?
// section elements
$sViewElementsTemplate = ($arParams["SECTION_ELEMENTS_TYPE_VIEW"] == "FROM_MODULE" ? $arTheme["STAFF_PAGE"]["VALUE"] : $arParams["SECTION_ELEMENTS_TYPE_VIEW"]);
@include_once('page_blocks/'.$sViewElementsTemplate.'.php');
?>