<?
use Bitrix\Main\Component\ParameterSigner;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var CBitrixComponentTemplate $this */
/** @var array $arParams */
/** @var array $arResult */
/** @global CDatabase $DB */

$this->setFrameMode(true);

$context = \Bitrix\Main\Context::getCurrent();
$request = $context->getRequest();
$bufferKey = $arParams['COMPONENT_TEMPLATE']."_".$arParams['COMPONENT_CALL_INDEX'];
$bAjax = TSolution::checkAjaxRequest() && $request['AJAX_REQUEST'] === "Y";

if (!$arResult['TABS'] || empty($arResult['TABS'])) return;

if ($bAjax) {
	$APPLICATION->RestartBuffer();
	$bAjax = TSolution::checkAjaxRequest() && $request['AJAX_REQUEST'] === "Y";
}

$arTab = array();
$arParams['SET_TITLE'] = 'N';
$arParams["FILTER_HIT_PROP"] = reset($arResult["TABS"])["CODE"];

$signer = new ParameterSigner();
?>
<div class="element-list <?=$blockClasses;?> <?=$templateName;?>-template" data-block_key="<?=$bufferKey;?>">
	<?$navHtml = '';?>
	<?if (count($arResult["TABS"]) > 1):?>
		<?ob_start();?>
			<div class="tab-nav-wrapper">
				<div class="tab-nav font_14" data-template="<?=$arParams['TYPE_TEMPLATE']?>">
					<?$iteratorTabs = 0;?>
					<?foreach ($arResult["TABS"] as $key => $arItem):?>
						<div class="tab-nav__item  bg-opacity-theme-hover bg-theme-active bg-theme-hover-active color-theme-hover-no-active<?=(!$iteratorTabs++ ? ' active clicked' : '');?>" 
								data-code="<?=$key;?>"
						><?=$arItem['TITLE'];?></div>
					<?endforeach;?>
				</div>
			</div>
		<?$navHtml = ob_get_clean();?>
	<?endif;?>
	
	<?=TSolution\Functions::showTitleBlock([
		'PATH' => 'elements-list',
		'PARAMS' => $arParams,
		'CENTER_BLOCK' => $navHtml
	]);?>

	<?if($arParams['NARROW']):?>
		<div class="maxwidth-theme">
	<?elseif($arParams['ITEMS_OFFSET']):?>
		<div class="maxwidth-theme maxwidth-theme--no-maxwidth">
	<?endif;?>
			<div class="line-block line-block--align-flex-stretch line-block--block">
				<div class="wrapper_tabs line-block__item flex-1">
					<span class='js-request-data request-data' data-value=''></span>
					<div class="js-tabs-ajax">
						<?$iteratorTabs = 0;?>
						<?foreach ($arResult["TABS"] as $key => $arItem):?>
							<?
							$signedFilter = $arItem['FILTER'] ? $signer->signParameters($this->__component->getName(), $arItem['FILTER']) : '';
							?>
							<div class="tab-content-block <?=(!$iteratorTabs ? 'active ' : 'loading-state');?>" data-code="<?=$key?>" data-filter="<?=$signedFilter;?>">
								<?
								if ($bAjax) $APPLICATION->RestartBuffer();

								if (!$iteratorTabs++) {
									if (!$bAjax && $arItem["FILTER"]) {
										$GLOBALS[$arParams["FILTER_NAME"]] = $arItem["FILTER"];
									}

									include __DIR__."/page_blocks/".$arParams['TYPE_TEMPLATE'].".php";
								}

								if ($bAjax) TSolution::checkRestartBuffer(true, $bufferKey);
								?>
							</div>
						<?endforeach;?>
					</div>
				</div>
				<?if(($arParams["ADVERTISING_ON_MAIN_PAGE"] === 'Y') && ($arParams['ELEMENT_IN_ROW'] == 4)):
					if ($arParams['TYPE_TEMPLATE'] === "catalog_block" ) {
						TSolution\Extensions::init('set_height_catalog_banner');
					}
					$arItemFilter = array('ACTIVE' => 'Y', 'IBLOCK_ID' => TSolution\Cache::$arIBlocks[SITE_ID]["aspro_".VENDOR_SOLUTION_NAME."_adv"]["aspro_".VENDOR_SOLUTION_NAME."_catalog_adv"][0],'PROPERTY_SHOW_INDEX_PAGE_VALUE' => 'YES');
					$arItems = TSolution\Cache::CIblockElement_GetList(array('CACHE' => array('TAG' => TSolution\Cache::GetIBlockCacheTag(TSolution\Cache::$arIBlocks[SITE_ID]["aspro_".VENDOR_SOLUTION_NAME."_adv"]["aspro_".VENDOR_SOLUTION_NAME."_catalog_adv"][0]), 'MULTI' => 'Y')), $arItemFilter, false, false, array('ID', 'PROPERTY_SHOW_INDEX_PAGE'));
					?>
					<?if ($arItems):?>
						<div class="catalog_banner__wrapper line-block__item flex-basis-25-min1368 flex-basis-25-max1368">
							<?$APPLICATION->IncludeComponent(
								"bitrix:main.include",
								"",
								Array(
									"AREA_FILE_SHOW" => "file",
									"PATH" => SITE_DIR."include/blocks/catalog/adv.php",
									"EDIT_TEMPLATE" => "include_area.php",
								)
							);?>
						</div>
					<?endif;?>
				<?endif?>
			</div>
	<?if ($arParams['NARROW'] || $arParams['ITEMS_OFFSET']):?>
	</div>
	<?endif;?>
</div>
<script>try {window.tabsInitOnReady('<?=$bufferKey;?>');} catch(e) {console.debug(e);}</script>