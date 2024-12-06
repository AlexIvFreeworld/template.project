<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use \Bitrix\Main\Localization\Loc;
$this->setFrameMode(true);

global $arTheme;
$bUseSchema = !(isset($arParams["NO_USE_SHCEMA_ORG"]) && $arParams["NO_USE_SHCEMA_ORG"] == "Y");

$listClasses = 'grid-list--items-1';
if($arParams['ITEMS_OFFSET']){
	$listClasses .= ' grid-list--gap-32';
}
else {
	$listClasses .= ' grid-list--no-gap';
}

$itemClasses = 'shadow-hovered shadow-no-border-hovered';
if($arParams['ITEMS_OFFSET']){
	$itemClasses .= ' rounded-4';
}
?>
<?if($arResult['ITEMS']):?>
	<?if (!$arParams['IS_AJAX']):?>
		<div class="price-list <?=$templateName;?>-template">
		<?if($arParams['MAXWIDTH_WRAP']):?>
			<div class="maxwidth-theme">
		<?endif;?>
			<div class="price-container">
				<div class="flexbox flexbox--direction-row flexbox--column-t991">
					<div class="price-items flex-grow-1">
						<div class="accordion accordion-type-1 js_append ajax_load block grid-list color-theme-parent-all <?=$listClasses?>">
	<?endif;?>
							<?foreach($arResult['ITEMS'] as $arItem):?>
								<?
								// edit/add/delete buttons for edit mode
								$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_EDIT'));
								$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'ELEMENT_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

								$bHideOrderButton = $arParams['HIDE_RECORD_BUTTON'] === 'Y' || $arItem['PROPERTIES']['HIDE_ORDER_BUTTON']['VALUE'] === 'YES';

								$bDetailLink = $arParams['SHOW_DETAIL_LINK'] != 'N' && (!strlen($arItem['DETAIL_TEXT']) ? ($arParams['HIDE_LINK_WHEN_NO_DETAIL'] !== 'Y' && $arParams['HIDE_LINK_WHEN_NO_DETAIL'] != 1) : true);
								$bActiveAccordion = !$bDetailLink;
								?>
								<div class="grid-list__item item-accordion-wrapper <?=$itemClasses?>" id="<?=$this->GetEditAreaId($arItem['ID'])?>">
									<?if($bDetailLink):?>
										<div class="accordion-head <?=!strlen($arItem['PREVIEW_TEXT']) ? 'accordion-head--cursor_normal' : ''?>  accordion-close stroke-theme-hover" data-toggle="collapse" data-target="#accordion<?=$arItem['ID']?>">
									<?else:?>
										<a class="accordion-head <?=!strlen($arItem['PREVIEW_TEXT']) ? 'accordion-head--cursor_normal' : ''?> accordion-close stroke-theme-hover" data-toggle="collapse" data-parent="#accordion<?=$arItem['ID']?>" href="#accordion<?=$arItem['ID']?>">
									<?endif;?>
										<div class="price-head line-block line-block--40 line-block--8-vertical flexbox--justify-beetwen">
											<div class="price-title line-block__item">
												<?if($bDetailLink):?>
													<a href="<?=$arItem['DETAIL_PAGE_URL']?>" class="switcher-title dark_link color-theme-target font_<?=$arParams['NAME_SIZE']?>"><?=$arItem['NAME']?></a>
												<?else:?>
													<span class="switcher-title color_333 font_<?=$arParams['NAME_SIZE']?>"><?=$arItem['NAME']?></span>
												<?endif;?>
											</div>

											<div class="price-side line-block__item line-block line-block--40 line-block--8-vertical">
												<?// element price?>
												<div class="line-block__item">
													<?=TSolution\Functions::showPrice([
														'ITEM' => $arItem,
														'PARAMS' => $arParams,
														'SHOW_SCHEMA' => $bUseSchema,
														// 'TO_LINE' => true,
													]);?>
												</div>

												<?// element online button?>
												<?if(!$bHideOrderButton):?>
													<div class="buttons line-block__item">
														<div class="button">
															<span class="btn btn-default animate-load btn-md" data-event="jqm" data-param-id="<?=TSolution::getFormID('online');?>" data-hide-specialization data-hide-specialist data-autoload-product="<?=\TSolution::formatJsName($arItem['NAME'])?>"><?=(strlen($arTheme['EXPRESSION_FOR_ONLINE_RECORD']['VALUE']) ? $arTheme['EXPRESSION_FOR_ONLINE_RECORD']['VALUE'] : Loc::getMessage('RECORD_ONLINE'))?></span>
														</div>
													</div>
												<?endif;?>
											</div>
										</div>
										<?if(strlen($arItem['PREVIEW_TEXT'])):?>
											<?=TSolution::showIconSvg('right-arrow', SITE_TEMPLATE_PATH.'/images/svg/Plus_lg.svg');?>
										<?endif;?>
									<?if($bDetailLink):?>
										</div>
									<?else:?>
										</a>
									<?endif;?>
									<?if($arItem['PREVIEW_TEXT']):?>
										<div id="accordion<?=$arItem['ID']?>" class="panel-collapse collapse">
											<div class="accordion-body color_666">
												<?if($arItem['PREVIEW_TEXT']):?>
													<div class="accordion-preview">
														<?=$arItem['PREVIEW_TEXT']?>
													</div>
												<?endif;?>
												<div class="bg-theme accordion-line"></div>
											</div>
										</div>
									<?endif;?>
								</div>
							<?endforeach;?>
			<?if (!$arParams['IS_AJAX']):?>
						</div> <!--accordion-->
					</div>  <!--price-items-->
				</div> <!--flexbox-->
			</div> <!--price-container -->
			<?endif;?>
			<?// bottom pagination?>
			<?if($arParams['IS_AJAX']):?>
				<div class="wrap_nav bottom_nav_wrapper">
			<?endif;?>

			<div class="bottom_nav_wrapper nav-compact">
				<div class="bottom_nav <?=($bMobileScrolledItems ? 'hide-600' : '');?>" <?=($arParams['IS_AJAX'] ? "style='display: none; '" : "");?> data-parent=".price-list" data-append=".grid-list">
					<?if($arParams['DISPLAY_BOTTOM_PAGER']):?>
						<?=$arResult['NAV_STRING']?>
					<?endif;?>
				</div>
			</div>

			<?if($arParams['IS_AJAX']):?>
			</div>
			<?endif;?>

		<?if($arParams['MAXWIDTH_WRAP'] && !$arParams['IS_AJAX']):?>
			</div>
		<?endif;?>
	<?if(!$arParams['IS_AJAX']):?>
		 </div> <!--price-list  -->
	<?endif;?>
<?endif;?>