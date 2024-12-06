<?
use \Bitrix\Main\Localization\Loc;

$bTab = isset($tabCode) && $tabCode === 'prices';
?>
<?//show prices block?>
<?if($templateData['PRICES']['VALUE'] && $templateData['PRICES']['IBLOCK_ID']):?>
    <?if(!isset($html_prices)):?>
        <?$GLOBALS['arrPricesFilter'] = array('ID' => $templateData['PRICES']['VALUE']);?>
        <?ob_start();?>
        <?$bCheckAjaxBlockPrice = CAllcorp3Medc::checkRequestBlock("price-list-inner");?>
        <?$isAjaxPrice = (CAllcorp3Medc::checkAjaxRequest() && $bCheckAjaxBlockPrice ) ? 'Y' : 'N'; ?>
        <?$APPLICATION->IncludeComponent(
            "bitrix:news.list",
            "price-list",
            array(
                "IBLOCK_TYPE" => "aspro_allcorp3medc_catalog",
                "IBLOCK_ID" => $templateData['PRICES']['IBLOCK_ID'],
                "NEWS_COUNT" => CAllcorp3Medc::GetFrontParametrValue('PRICE_LINK_COUNT') ?? "20",
                "SORT_BY1" => "SORT",
                "SORT_ORDER1" => "ASC",
                "SORT_BY2" => "ID",
                "SORT_ORDER2" => "DESC",
                "FILTER_NAME" => "arrPricesFilter",
                "FIELD_CODE" => array(
                    0 => "NAME",
                    1 => "PREVIEW_TEXT",
                    2 => "",
                ),
                "PROPERTY_CODE" => array(
                    0 => "PRICE",
                    1 => "PRICE_CURRENCY",
                    2 => "PRICEOLD",
                    3 => "FILTER_PRICE",
                    4 => "",
                ),
                "CHECK_DATES" => "Y",
                "DETAIL_URL" => "",
                "AJAX_MODE" => "N",
                "AJAX_OPTION_JUMP" => "N",
                "AJAX_OPTION_STYLE" => "Y",
                "AJAX_OPTION_HISTORY" => "N",
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "36000000",
                "CACHE_FILTER" => "Y",
                "CACHE_GROUPS" => "N",
                "PREVIEW_TRUNCATE_LEN" => "",
                "ACTIVE_DATE_FORMAT" => "d.m.Y",
                "SET_TITLE" => "N",
                "SET_STATUS_404" => "N",
                "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                "ADD_SECTIONS_CHAIN" => "N",
                "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                "PARENT_SECTION" => "",
                "PARENT_SECTION_CODE" => "",
                "INCLUDE_SUBSECTIONS" => "Y",
                "PAGER_TEMPLATE" => "ajax",
                "DISPLAY_TOP_PAGER" => "N",
                "DISPLAY_BOTTOM_PAGER" => "Y",
                "PAGER_TITLE" => "",
                "PAGER_SHOW_ALWAYS" => "N",
                "PAGER_DESC_NUMBERING" => "N",
                "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                "PAGER_SHOW_ALL" => "N",
                "COMPONENT_TEMPLATE" => "price-list",
                "SET_LAST_MODIFIED" => "N",
                "STRICT_SECTION_CHECK" => "N",
                "SHOW_DETAIL_LINK" => "Y",
                "PAGER_BASE_LINK_ENABLE" => "N",
                "SHOW_404" => "N",
                "MESSAGE_404" => "",
                "SHOW_DATE" => "Y",
                "COUNT_IN_LINE" => "4",
                "SHOW_CHILD_SECTIONS" => "Y",
                "SHOW_CHILD_ELEMENTS" => "Y",
                "SHOW_ELEMENTS_IN_LAST_SECTION" => "N",
                "S_RECORD_ONLINE" => "",
		        "HIDE_RECORD_BUTTON" => "N",

                "ROW_VIEW" => true,
                "BORDER" => true,
                "ITEM_HOVER_SHADOW" => true,
                "DARK_HOVER" => false,
                "ROUNDED" => true,
                "ELEMENTS_ROW" => 1,
                "MAXWIDTH_WRAP" => false,
                "MOBILE_SCROLLED" => false,
                "NARROW" => false,
                "ITEMS_OFFSET" => false,
                "SHOW_PREVIEW" => false,
                "SHOW_TITLE" => false,
                "TITLE_POSITION" => "",
                "TITLE" => "",
                "RIGHT_TITLE" => "",
                "RIGHT_LINK" => "",
                "CHECK_REQUEST_BLOCK" => $bCheckAjaxBlockPrice,
                "IS_AJAX" => CAllcorp3Medc::checkAjaxRequest() && $bCheckAjaxBlockPrice,
                "NAME_SIZE" => "18",
                "SUBTITLE" => "",
                "SHOW_PREVIEW_TEXT" => "Y",
            ),
            false, array("HIDE_ICONS" => "Y")
        );?>
        <?$html_prices = trim(ob_get_clean());?>
    <?endif;?>

    <?if($html_prices && strpos($html_prices, 'error') === false):?>
        <?if($bTab):?>
            <?if(!isset($bShow_prices)):?>
                <?$bShow_prices = true;?>
            <?else:?>
                <div class="tab-pane <?=(!($iTab++) ? 'active' : '')?>" id="prices">
                    <div class="ordered-block__title switcher-title font_22"><?=$arParams["T_PRICES"]?></div>
                    <div class="ajax-pagination-wrapper" data-class="price-list-inner">
                        <?if ($isAjaxPrice === 'Y'):?>
                            <?$APPLICATION->RestartBuffer();?>
                        <?endif;?>
                        <?=$html_prices?>
                        <?if ($isAjaxPrice === 'Y'):?>
                            <?die();?>
                        <?endif;?>
                    </div>
                </div>
            <?endif;?>
        <?else:?>
            <div class="detail-block ordered-block prices">
                <div class="ordered-block__title switcher-title font_22"><?=$arParams["T_PRICES"]?></div>
                <div class="ajax-pagination-wrapper" data-class="price-list-inner">
                    <?if ($isAjaxPrice === 'Y'):?>
                        <?$APPLICATION->RestartBuffer();?>
                    <?endif;?>
                    <?=$html_prices?>
                    <?if ($isAjaxPrice === 'Y'):?>
                        <?die();?>
                    <?endif;?>
                </div>
            </div>
        <?endif;?>
    <?endif;?>
<?endif;?>