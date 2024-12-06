<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true ) die();
$this->setFrameMode(true);
use \Bitrix\Main\Localization\Loc;

$arItems = $arResult['SECTIONS'];
?>
<?if($arItems):?>
	<?
	$bNarrow = $arParams['NARROW'];

	$blockClasses = ($arParams['ITEMS_OFFSET'] ? 'price-sections-list--items-offset' : 'price-sections-list--items-close');

	$gridClass = 'grid-list grid-list--items-'.$arParams['ELEMENTS_ROW'];
	if($arParams['MOBILE_SCROLLED']){
		$gridClass .= ' mobile-scrolled mobile-scrolled--items-2 mobile-offset';
	}
	if(!$arParams['ITEMS_OFFSET']){
		$gridClass .= ' grid-list--no-gap';
	}

	$itemWrapperClasses = ' grid-list__item stroke-theme-parent-all colored_theme_hover_bg-block animate-arrow-hover';
	if(!$arParams['ITEMS_OFFSET'] && $arParams['BORDER']){
		$itemWrapperClasses .= ' grid-list-border-outer';
	}
	
	$itemWrapperClasses .= ' color-theme-parent-all';
	
	$itemClasses = 'height-100 flexbox';
	if($arParams['ROW_VIEW']){
		$itemClasses .= ' flexbox--direction-row';
	}
	if($arParams['COLUMN_REVERSE']){
		$itemClasses .= ' flexbox--direction-column-reverse';
	}
	if($arParams['BORDER']){
		$itemClasses .= ' bordered';
	}
	if($arParams['ROUNDED'] && $arParams['ITEMS_OFFSET']){
		$itemClasses .= ' rounded-4';
	}
	if($arParams['ITEM_HOVER_SHADOW']){
		$itemClasses .= ' shadow-hovered shadow-no-border-hovered';
	}
	if($arParams['DARK_HOVER']){
		$itemClasses .= ' dark-block-hover';
	}
		
	$itemClasses .= ' price-sections-list__item--section price-sections-list__item--big-padding';

	if($arParams['ELEMENTS_ROW'] == 1){
		$itemClasses .= ' price-sections-list__item--wide';
	}

	if (!$arParams['MOBILE_SCROLLED']) {
		$itemClasses .= ' price-sections-list__item--no-scrolled';
	}

	$imageWrapperClasses = 'price-sections-list__item-image-wrapper--'.($arParams['IMAGES'] === 'TRANSPARENT_PICTURES' ? 'PICTURE' : $arParams['IMAGES']).' price-sections-list__item-image-wrapper--'.$arParams['IMAGE_POSITION'];
	$imageClasses = $arParams['IMAGES'] === 'ROUND_PICTURES' ? 'rounded' : (($arParams['IMAGES'] === 'PICTURES' || $arParams['IMAGES'] === 'BIG_PICTURES') ? '' : '');
	if($arParams['IMAGE_POSITION'] === 'TOP'){
		$imageWrapperClasses .= ' no-shrinked';
	}
	?>
	<?if(!$arParams['IS_AJAX']):?>
		<div class="price-sections-list <?=$blockClasses?> <?=$templateName?>-template">

		<?if($arParams['MAXWIDTH_WRAP']):?>
			<?if($bNarrow):?>
				<div class="maxwidth-theme">
			<?elseif($arParams['ITEMS_OFFSET']):?>
				<div class="maxwidth-theme maxwidth-theme--no-maxwidth">
			<?endif;?>
		<?endif;?>

		<div class="<?=$gridClass?>">
	<?endif;?>
			<?
			$counter = 1;
			foreach($arItems as $i => $arItem):?>
				<?
				// edit/add/delete buttons for edit mode
				$arSectionButtons = CIBlock::GetPanelButtons($arItem['IBLOCK_ID'], 0, $arItem['ID'], array('SESSID' => false, 'CATALOG' => true));
				$this->AddEditAction($arItem['ID'], $arSectionButtons['edit']['edit_section']['ACTION_URL'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'SECTION_EDIT'));
				$this->AddDeleteAction($arItem['ID'], $arSectionButtons['edit']['delete_section']['ACTION_URL'], CIBlock::GetArrayByID($arItem['IBLOCK_ID'], 'SECTION_DELETE'), array('CONFIRM' => GetMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')));

				// use detail link?
				$bDetailLink = $arParams['SHOW_DETAIL_LINK'] != 'N' && (!strlen($arItem['DETAIL_TEXT']) ? ($arParams['HIDE_LINK_WHEN_NO_DETAIL'] !== 'Y' && $arParams['HIDE_LINK_WHEN_NO_DETAIL'] != 1) : true);

				// detail url
				$detailUrl = $arItem['SECTION_PAGE_URL'];

				// preview text
				$previewText = $arItem['~UF_TOP_SEO'];
				$htmlPreviewText = '';
				?>
				<div class="price-sections-list__wrapper <?=$itemWrapperClasses?>">
					<div class="price-sections-list__item <?=$itemClasses?> <?=($bDetailLink ? '' : 'price-sections-list__item--cursor-initial')?>" id="<?=$this->GetEditAreaId($arItem['ID'])?>">
						<div class="price-sections-list__item-text-wrapper flexbox">
							<div class="price-sections-list__item-text-top-part">
								<div class="price-sections-list__item-title switcher-title font_<?=$arParams['NAME_SIZE']?>">
									<?if($bDetailLink):?>
										<a class="dark_link color-theme-target" href="<?=$detailUrl?>"><?=$arItem['NAME']?></a>
										<a class="arrow-all arrow-all--wide stroke-theme-target" href="<?=$detailUrl?>">
											<?=CAllcorp3Medc::showIconSvg(' arrow-all__item-arrow', SITE_TEMPLATE_PATH.'/images/svg/Arrow_lg.svg');?>
											<div class="arrow-all__item-line colored_theme_hover_bg-el"></div>
										</a>
									<?else:?>
										<span class="color_333"><?=$arItem['NAME']?></span>
									<?endif;?>

									<?if($arParams['IMAGE_POSITION'] === 'TOP_LEFT'):?>
										<?if($bDetailLink):?>
											<?if($arParams['ELEMENTS_ROW'] == 1):?>
												<a class="arrow-all arrow-all--wide stroke-theme-target" href="<?=$detailUrl?>">
													<?=CAllcorp3Medc::showIconSvg(' arrow-all__item-arrow', SITE_TEMPLATE_PATH.'/images/svg/Arrow_lg.svg');?>
													<div class="arrow-all__item-line colored_theme_hover_bg-el"></div>
												</a>
											<?else:?>
												<a class="arrow-all stroke-theme-target" href="<?=$detailUrl?>">
													<?=CAllcorp3Medc::showIconSvg(' arrow-all__item-arrow', SITE_TEMPLATE_PATH.'/images/svg/Arrow_map.svg');?>
													<div class="arrow-all__item-line colored_theme_hover_bg-el"></div>
												</a>
											<?endif;?>
										<?endif;?>
									<?endif;?>
								</div>

								<?if(
									in_array('PREVIEW_TEXT', $arParams['FIELD_CODE']) &&
									$arParams['SHOW_PREVIEW'] &&
									strlen($previewText)
								):?>
									<?ob_start()?>
										<div class="price-sections-list__item-preview-wrapper">
											<div class="price-sections-list__item-preview font_15 color_666">
												<?=$previewText?>
											</div>
										</div>
									<?$htmlPreviewText = ob_get_clean()?>
								<?endif;?>

								<?if($arItem['CHILD']):?>
									<div class="price-sections-list__item-childs font_15">
										<ul class="list-unstyled">
											<?$i = 0;?>
											<?foreach($arItem['CHILD'] as $arChild):?>
												<li class="price-sections-list__item-childs-item-wraper">
													<?$bShowChildDetail = $arChild['SECTION_PAGE_URL'] || ($arParams['SHOW_ELEMENTS_DETAIL_LINK'] != 'N' && (!strlen($arChild['DETAIL_TEXT']) ? ($arParams['HIDE_ELEMENTS_LINK_WHEN_NO_DETAIL'] !== 'Y' && $arParams['HIDE_ELEMENTS_LINK_WHEN_NO_DETAIL'] != 1) : true));?>
													<?if($bShowChildDetail):?>
														<a class="price-sections-list__item-childs-item" href="<?=($arChild['DETAIL_PAGE_URL'] ? $arChild['DETAIL_PAGE_URL'] : $arChild['SECTION_PAGE_URL'])?>">
													<?else:?>
														<span class="price-sections-list__item-childs-item">
													<?endif;?>

														<span class="price-sections-list__item-childs-item-name"><?=$arChild['NAME']?></span>
														<?if(++$i < (count($arItem['CHILD']))):?>
															<span class="price-sections-list__item-childs-item-separator">&mdash;</span>
														<?endif;?>

													<?if($bShowChildDetail):?>
														</a>
													<?else:?>
														</span>
													<?endif;?>
												</li>
											<?endforeach;?>
										</ul>
									</div>
								<?endif;?>

								<?=$htmlPreviewText?>
							</div>
						</div>
					</div>
				</div>
			<?
			$counter++;
			endforeach;?>

			<?if($arParams['IS_AJAX']):?>
				<div class="wrap_nav bottom_nav_wrapper">
					<script>InitScrollBar();</script>
			<?endif;?>
				<?$bHasNav = (strpos($arResult["NAV_STRING"], 'more_text_ajax') !== false);?>
				<div class="bottom_nav mobile_slider <?=($bHasNav ? '' : ' hidden-nav');?>" data-parent=".price-sections-list" data-append=".grid-list" <?=($arParams["IS_AJAX"] ? "style='display: none; '" : "");?>>
					<?if($bHasNav):?>
						<?=$arResult["NAV_STRING"]?>
					<?endif;?>
				</div>

			<?if($arParams['IS_AJAX']):?>
				</div>
			<?endif;?>

	<?if(!$arParams['IS_AJAX']):?>
		</div>
	<?endif;?>

		<?// bottom pagination?>
		<?if($arParams['IS_AJAX']):?>
			<div class="wrap_nav bottom_nav_wrapper">
		<?endif;?>

		<div class="bottom_nav_wrapper nav-compact">
			<div class="bottom_nav hide-600" <?=($arParams['IS_AJAX'] ? "style='display: none; '" : "");?> data-parent=".price-sections-list" data-append=".grid-list">
				<?if($arParams['DISPLAY_BOTTOM_PAGER']):?>
					<?=$arResult['NAV_STRING']?>
				<?endif;?>
			</div>
		</div>

		<?if($arParams['IS_AJAX']):?>
			</div>
		<?endif;?>

	<?if(!$arParams['IS_AJAX']):?>
		<?if($arParams['MAXWIDTH_WRAP']):?>
			<?if($bNarrow):?>
				</div>
			<?elseif($arParams['ITEMS_OFFSET']):?>
				</div>
			<?endif;?>
		<?endif;?>

		</div> <?// .price-sections-list?>
	<?endif;?>
<?endif;?>