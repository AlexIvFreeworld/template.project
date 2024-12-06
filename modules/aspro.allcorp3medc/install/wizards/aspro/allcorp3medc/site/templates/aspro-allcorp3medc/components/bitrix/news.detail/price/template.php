<?
if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

use \Bitrix\Main\Localization\Loc;

global $arTheme;

$bOrderViewBasket = $arParams['ORDER_VIEW'];
$dataItem = $bOrderViewBasket ? TSolution::getDataItem($arResult) : false;
$bOnlineButton = $arParams['HIDE_RECORD_BUTTON'] !== 'Y' && $arResult['PROPERTIES']['HIDE_ORDER_BUTTON']['VALUE'] != 'YES';
$bAskButton = $arResult['PROPERTIES']['FORM_QUESTION']['VALUE_XML_ID'] == 'YES';

/*set array props for component_epilog*/
$templateData = array(
	'ORDER' => $bOrderViewBasket,
	'ORDER_BTN' => ($bOnlineButton || $bAskButton),
);
?>

<?if($arResult['CATEGORY_ITEM']):?>
	<meta itemprop="category" content="<?=$arResult['CATEGORY_ITEM'];?>" />
<?endif;?>
<?if($arResult['DETAIL_PICTURE']):?>
	<meta itemprop="image" content="<?=$arResult['DETAIL_PICTURE']['SRC'];?>" />
<?endif;?>
<meta itemprop="name" content="<?=$arResult['NAME'];?>" />
<link itemprop="url" href="<?=$arResult['DETAIL_PAGE_URL'];?>" />

<?ob_start();?>
	<div class="btn btn-default btn-wide btn-lg <?=($bOnlineButton && $bAskButton) ? 'btn-transparent-border' : '';?> animate-load" data-event="jqm" data-param-id="<?=TSolution::getFormID("aspro_allcorp3medc_question");?>" data-autoload-need_product="<?=TSolution::formatJsName($arResult['NAME'])?>" data-name="question">
		<span><?=(strlen($arParams['S_ASK_QUESTION']) ? $arParams['S_ASK_QUESTION'] : Loc::getMessage('S_ASK_QUESTION'))?></span>
	</div>
<?$askButtonHtml = ob_get_clean()?>

<?// form question?>
<?if($bAskButton):?>
	<?$this->SetViewTarget('under_sidebar_content');?>
		<div class="ask-block bordered rounded-4">
			<div class="ask-block__container">
				<div class="ask-block__icon">
					<?=TSolution::showIconSvg('ask colored', SITE_TEMPLATE_PATH.'/images/svg/Question_lg.svg');?>
				</div>
				<div class="ask-block__text text-block color_666 font_14">
					<?$APPLICATION->IncludeComponent(
						'bitrix:main.include',
						'',
						array(
							"AREA_FILE_SHOW" => "page",
							"AREA_FILE_SUFFIX" => "ask",
							"EDIT_TEMPLATE" => ""
						)
					);?>
				</div>
				<div class="ask-block__button">
					<?=str_replace('btn-wide btn-lg', ' btn-transparent-border ', $askButtonHtml);?>
				</div>
			</div>
		</div>
	<?$this->EndViewTarget();?>
<?endif;?>

<?if ($bOnlineButton || $bAskButton):?>
	<?$this->SetViewTarget('PRICE_ONLINE_INFO');?>
		<div class="order-info-block" itemprop="offers" itemscope itemtype="http://schema.org/Offer" data-id="<?=$arResult['ID']?>"<?=($bOrderViewBasket ? ' data-item="'.$dataItem.'"' : '')?>>
			<div class="detail-info__image-wrapper rounded hidden">
				<div class="detail-info__image" data-src="<?=$imageSrc?>">
					<span class="tariffs-list__item-image" style="background-image: url(<?=$imageSrc?>);"></span>
				</div>
			</div>

			<div class="line-block line-block--align-normal line-block--40">
				<div class="line-block__item icon-svg-block">
					<?=TSolution::showIconSvg('review stroke-theme', SITE_TEMPLATE_PATH . '/images/svg/order_large.svg'); ?>
				</div>
				<div class="line-block__item flex-1">
					<div class="text font_18 color_333 font_large">
						<?$APPLICATION->IncludeComponent(
							'bitrix:main.include',
							'',
							array(
								'AREA_FILE_SHOW' => 'page',
								'AREA_FILE_SUFFIX' => 'online_price',
								'EDIT_TEMPLATE' => ''
							)
						);?>
					</div>
				</div>
				<?=TSolution\Functions::showPrice([
					'ITEM' => $arResult,
					'PARAMS' => $arParams,
					'SHOW_SCHEMA' => true,
					'BASKET' => false,
					'WRAPPER_CLASS' => "line-block__item no-shrinked",
				]);?>
				<div class="line-block__item order-info-btns">
					<div class="line-block line-block--align-normal line-block--12">
						<?if ($bOnlineButton):?>
							<div class="line-block__item">
								<span class="buy_block btn-actions__inner">
									<span class="buttons">
										<span class="btn btn-default animate-load btn-lg btn-wide" data-event="jqm" data-param-id="<?=TSolution::getFormID('online');?>" data-hide-specialization data-hide-specialist data-autoload-product="<?=TSolution::formatJsName($arResult['NAME'])?>"><?=(strlen($arTheme['EXPRESSION_FOR_ONLINE_RECORD']['VALUE']) ? $arTheme['EXPRESSION_FOR_ONLINE_RECORD']['VALUE'] : Loc::getMessage('RECORD_ONLINE'))?></span>
									</span>
								</span>
							</div>
							<?if ($bAskButton):?>
								<div class="line-block__item">
									<a
									href="javascript:void(0)"
									rel="nofollow"
									class="btn btn-default btn-lg btn-transparent-border animate-load btn-wide"
									data-param-id="<?=TSolution::getFormID("aspro_allcorp3medc_question");?>" 
									data-name="question" 
									data-event="jqm" 
									title="<?=Loc::getMessage('QUESTION_FORM_TITLE')?>"
									data-autoload-need_product="<?=TSolution::formatJsName($arResult['NAME'])?>"
									>
										<span>?</span>
									</a>
								</div>
							<?endif;?>
						<?elseif ($bAskButton):?>
							<div class="line-block__item">
								<?=$askButtonHtml;?>
							</div>
						<?endif;?>
					</div>
				</div>
			</div>
		</div>
	<?$this->EndViewTarget();?>
<?endif;?>

<?$templateData['PREVIEW_TEXT'] = boolval(strlen($arResult['FIELDS']['PREVIEW_TEXT']) && !$templateData['SECTION_BNR_CONTENT']);?>

<?if (boolval(strlen($arResult['FIELDS']['PREVIEW_TEXT'])) && boolval(strlen($arResult['PROPERTIES']['ANONS']['VALUE'])) && $templateData['SECTION_BNR_CONTENT']) {
	$templateData['PREVIEW_TEXT'] = true;
}?>

<?// detail description?>
<?$templateData['DETAIL_TEXT'] = boolval(strlen($arResult['DETAIL_TEXT']));?>
<?if($templateData['DETAIL_TEXT'] || $templateData['PREVIEW_TEXT']):?>
	<?$this->SetViewTarget('PRODUCT_DETAIL_TEXT_INFO');?>
		<div class="content" itemprop="description">
			<?if ($templateData['PREVIEW_TEXT']):?>
				<div class="introtext">
					<?if($arResult['PREVIEW_TEXT_TYPE'] == 'text'):?>
						<p><?=$arResult['FIELDS']['PREVIEW_TEXT'];?></p>
					<?else:?>
						<?=$arResult['FIELDS']['PREVIEW_TEXT'];?>
					<?endif;?>
				</div>
			<?endif?>
			<?if ($templateData['DETAIL_TEXT']):?>
				<?=$arResult['DETAIL_TEXT'];?>
			<?endif;?>
		</div>
	<?$this->EndViewTarget();?>
<?endif;?>

<?// props content?>
<?$templateData['CHARACTERISTICS'] = boolval($arResult['CHARACTERISTICS']);?>
<?if($arResult['CHARACTERISTICS']):?>
	<?$this->SetViewTarget('PRODUCT_PROPS_INFO');?>
		<?$strGrupperType = $arParams["GRUPPER_PROPS"];?>
		<?if($strGrupperType == "GRUPPER"):?>
			<div class="props_block bordered rounded-4">
				<div class="props_block__wrapper">
					<?$APPLICATION->IncludeComponent(
						"redsign:grupper.list",
						"",
						Array(
							"CACHE_TIME" => "3600000",
							"CACHE_TYPE" => "A",
							"COMPOSITE_FRAME_MODE" => "A",
							"COMPOSITE_FRAME_TYPE" => "AUTO",
							"DISPLAY_PROPERTIES" => $arResult["CHARACTERISTICS"]
						),
						$component, array('HIDE_ICONS'=>'Y')
					);?>
				</div>
			</div>
		<?elseif($strGrupperType == "WEBDEBUG"):?>
			<div class="props_block bordered rounded-4">
				<div class="props_block__wrapper">
					<?$APPLICATION->IncludeComponent(
						"webdebug:propsorter",
						"linear",
						array(
							"IBLOCK_TYPE" => $arResult['IBLOCK_TYPE'],
							"IBLOCK_ID" => $arResult['IBLOCK_ID'],
							"PROPERTIES" => $arResult['CHARACTERISTICS'],
							"EXCLUDE_PROPERTIES" => array(),
							"WARNING_IF_EMPTY" => "N",
							"WARNING_IF_EMPTY_TEXT" => "",
							"NOGROUP_SHOW" => "Y",
							"NOGROUP_NAME" => "",
							"MULTIPLE_SEPARATOR" => ", "
						),
						$component, array('HIDE_ICONS'=>'Y')
					);?>
				</div>
			</div>
		<?elseif($strGrupperType == "YENISITE_GRUPPER"):?>
			<div class="props_block bordered rounded-4">
				<div class="props_block__wrapper">
					<?$APPLICATION->IncludeComponent(
						'yenisite:ipep.props_groups',
						'',
						array(
							'DISPLAY_PROPERTIES' => $arResult['CHARACTERISTICS'],
							'IBLOCK_ID' => $arParams['IBLOCK_ID']
						),
						$component, array('HIDE_ICONS'=>'Y')
					)?>
				</div>
			</div>
		<?else:?>
			<?/*if($arParams["PROPERTIES_DISPLAY_TYPE"] != "TABLE"):?>
				<div class="props_block">
					<div class="props_block__wrapper flexbox row">
						<?foreach($arResult["CHARACTERISTICS"] as $propCode => $arProp):?>
							<div class="char col-lg-3 col-md-4 col-xs-6 bordered" itemprop="additionalProperty" itemscope itemtype="http://schema.org/PropertyValue">
								<div class="char_name font_15 color_666">
									<div class="props_item <?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"){?>whint<?}?>">
										<span itemprop="name"><?=$arProp["NAME"]?></span>
									</div>
									<?if($arProp["HINT"] && $arParams["SHOW_HINTS"]=="Y"):?><div class="hint hint--down"><span class="hint__icon rounded bg-theme-hover border-theme-hover bordered"><i>?</i></span><div class="tooltip"><?=$arProp["HINT"]?></div></div><?endif;?>
								</div>
								<div class="char_value font_15 color_333" itemprop="value">
									<?if(count($arProp["DISPLAY_VALUE"]) > 1):?>
										<?=implode(', ', $arProp["DISPLAY_VALUE"]);?>
									<?else:?>
										<?=$arProp["DISPLAY_VALUE"];?>
									<?endif;?>
								</div>
							</div>
						<?endforeach;?>
					</div>
				</div>
			<?else:*/?>
				<div class="props_block props_block--table props_block--nbg bordered rounded-4">
					<table class="props_block__wrapper">
						<?foreach($arResult["CHARACTERISTICS"] as $arProp):?>
							<tr class="char" >
								<td class="char_name font_15 color_666">
									<div class="props_item <?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"){?>whint<?}?>">
										<span><?=$arProp["NAME"]?></span>
										<?if($arProp["HINT"] && $arParams["SHOW_HINTS"]=="Y"):?><div class="hint hint--down"><span class="hint__icon rounded bg-theme-hover border-theme-hover bordered"><i>?</i></span><div class="tooltip"><?=$arProp["HINT"]?></div></div><?endif;?>
									</div>
								</td>
								<td class="char_value font_15 color_333">
									<span>
										<?if (is_array($arProp["DISPLAY_VALUE"]) && count($arProp["DISPLAY_VALUE"]) > 1):?>
											<?=implode(', ', $arProp["DISPLAY_VALUE"]);?>
										<?else:?>
											<?=$arProp["DISPLAY_VALUE"];?>
										<?endif;?>
									</span>
								</td>
							</tr>
						<?endforeach;?>
					</table>
				</div>
			<?/*endif;*/?>
		<?endif;?>
	<?$this->EndViewTarget();?>
<?endif;?>