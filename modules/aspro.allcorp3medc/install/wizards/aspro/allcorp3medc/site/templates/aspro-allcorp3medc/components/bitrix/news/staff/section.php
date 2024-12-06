<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use \Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

global $arTheme, $APPLICATION;

// geting section items count and section
$arSectionFilter = TSolution::GetCurrentSectionFilter($arResult["VARIABLES"], $arParams);
$arSection = TSolution\Cache::CIblockSection_GetList(array("CACHE" => array("TAG" => TSolution\Cache::GetIBlockCacheTag($arParams["IBLOCK_ID"]), "MULTI" => "N")), $arSectionFilter, false, array('ID', 'NAME', 'DESCRIPTION', 'PICTURE', 'DETAIL_PICTURE'), true);
$arItemFilter = TSolution::GetCurrentSectionElementFilter($arResult["VARIABLES"], $arParams, $arSection['ID']);

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
?>
<?if(!$arSection && $arParams['SET_STATUS_404'] !== 'Y'):?>
	<div class="alert alert-warning"><?=GetMessage("SECTION_NOTFOUND")?></div>
<?elseif(!$arSection && $arParams['SET_STATUS_404'] === 'Y'):?>
	<?TSolution::goto404Page();?>
<?else:?>
	<?
	TSolution::AddMeta(
		array(
			'og:description' => $arSection['DESCRIPTION'],
			'og:image' => (($arSection['PICTURE'] || $arSection['DETAIL_PICTURE']) ? CFile::GetPath(($arSection['PICTURE'] ? $arSection['PICTURE'] : $arSection['DETAIL_PICTURE'])) : false),
		)
	);

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
<?endif;?>