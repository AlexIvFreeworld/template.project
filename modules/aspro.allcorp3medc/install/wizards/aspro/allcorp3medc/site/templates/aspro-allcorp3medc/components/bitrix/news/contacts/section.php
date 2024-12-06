<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use \Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

global $arTheme, $APPLICATION;

$bUseMap = CAllcorp3Medc::GetFrontParametrValue('CONTACTS_USE_MAP', SITE_ID) != 'N';
$typeMap = CAllcorp3Medc::GetFrontParametrValue('CONTACTS_TYPE_MAP', SITE_ID);
$bUseFeedback = CAllcorp3Medc::GetFrontParametrValue('CONTACTS_USE_FEEDBACK', SITE_ID) != 'N';
$bUseTabs = $bUseMap && CAllcorp3Medc::GetFrontParametrValue('CONTACTS_USE_TABS', SITE_ID) != 'N';

$arSectionFilter = CAllcorp3Medc::GetCurrentSectionFilter($arResult["VARIABLES"], $arParams);
$arSection = CAllcorp3MedcCache::CIblockSection_GetList(array("CACHE" => array("TAG" => CAllcorp3MedcCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]), "MULTI" => "N")), $arSectionFilter, false, array('ID', 'NAME', 'DESCRIPTION', 'PICTURE', 'DETAIL_PICTURE', 'IBLOCK_ID'));

$arItemFilter = CAllcorp3Medc::GetCurrentSectionElementFilter($arResult["VARIABLES"], $arParams, $arSection['ID']);
$arItemSelect = array('ID', 'NAME', 'IBLOCK_ID', 'IBLOCK_SECTION_ID', 'DETAIL_PAGE_URL', 'PROPERTY_ADDRESS', 'PROPERTY_MAP', 'PROPERTY_SCHEDULE', 'PROPERTY_EMAIL', 'PROPERTY_METRO', 'PROPERTY_PHONE');

// filter by specialization
if($arTheme['CONTACTS_USE_SPECIALIZATION_FILTER']['VALUE'] !== 'N'){
	$arItemSelect[] = 'PROPERTY_SPECIALIZATION';

	if(
		isset($_REQUEST['SPECIALIZATION']) &&
		intval($_REQUEST['SPECIALIZATION']) > 0
	){
		if(!strlen($arParams['FILTER_NAME'])){
			$arParams['FILTER_NAME'] = 'arrFilter';
		}

		$GLOBALS[$arParams['FILTER_NAME']]['PROPERTY_SPECIALIZATION'] = $arItemFilter['PROPERTY_SPECIALIZATION'] = intval($_REQUEST['SPECIALIZATION']);
	}
}

// get section items count
$itemsCnt = CAllcorp3MedcCache::CIblockElement_GetList(array("CACHE" => array("TAG" => CAllcorp3MedcCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), $arItemFilter, array());

$arItems = CAllcorp3MedcCache::CIBlockElement_GetList(
	array(
		"CACHE" => array("
			TAG" => CAllcorp3MedcCache::GetIBlockCacheTag($arParams['IBLOCK_ID'])
		)
	),
	$arItemFilter,
	false,
	false,
	$arItemSelect
);

$GLOBALS[$arParams['FILTER_NAME']]['SECTION_ID'] = $arSection['ID'];
$GLOBALS[$arParams['FILTER_NAME']]['INCLUDE_SUBSECTIONS'] = 'Y';
?>
<?if(!$arSection && ($arParams['SET_STATUS_404'] !== 'Y' || $_SERVER['REQUEST_METHOD'] === 'POST')):?>
	<div class="alert alert-warning"><?=GetMessage('SECTION_NOTFOUND')?></div>
<?elseif(!$arSection && $arParams['SET_STATUS_404'] === 'Y'):?>
	<?CAllcorp3Medc::goto404Page();?>
<?else:?>
	<?
	CAllcorp3Medc::AddMeta(
		array(
			'og:description' => $arSection['DESCRIPTION'],
			'og:image' => (($arSection['PICTURE'] || $arSection['DETAIL_PICTURE']) ? CFile::GetPath(($arSection['PICTURE'] ? $arSection['PICTURE'] : $arSection['DETAIL_PICTURE'])) : false),
		)
	);

	CAllcorp3Medc::CheckComponentTemplatePageBlocksParams($arParams, __DIR__);

	$sViewElementsTemplate = ($arParams["SECTIONS_TYPE_VIEW"] == "FROM_MODULE" ? 'sections_'.$arTheme["PAGE_CONTACTS"]["VALUE"] : $arParams["SECTIONS_TYPE_VIEW"]);
	@include_once('page_blocks/'.$sViewElementsTemplate.'.php');
	
	if (CAllcorp3Medc::checkAjaxRequest()){
		die();
	}
	?>
<?endif;?>