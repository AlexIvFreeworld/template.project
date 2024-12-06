<?
use \Bitrix\Main\Localization\Loc;

$arAllItemsFilter = $arItemFilter;
unset($arAllItemsFilter['PROPERTY_SPECIALIZATION']);

$arAllItems = TSolution\Cache::CIblockElement_GetList(
    array(
        'CACHE' => array(
            'TAG' => TSolution\Cache::GetIBlockCacheTag($arParams['IBLOCK_ID'])
        )
    ),
    $arAllItemsFilter,
    false,
    false,
    array('ID', 'IBLOCK_SECTION_ID', 'PROPERTY_SPECIALIZATION')
);

$bShowSpecializationFilter = false;
if($arTheme['STAFF_PAGE_USE_SPECIALIZATION_FILTER']['VALUE'] !== 'N'){
    $arSpecializations = [];
    foreach($arAllItems as $arItem){
        $arItem['PROPERTY_SPECIALIZATION_VALUE'] = is_array($arItem['PROPERTY_SPECIALIZATION_VALUE']) ? $arItem['PROPERTY_SPECIALIZATION_VALUE'] : array($arItem['PROPERTY_SPECIALIZATION_VALUE']);
        if($arItem['PROPERTY_SPECIALIZATION_VALUE']){
            foreach($arItem['PROPERTY_SPECIALIZATION_VALUE'] as $specialization){
                $specialization = $specialization;
                if(strlen($specialization)){
                    $arSpecializations[] = $specialization;
                }
            }
        }
    }

    $arSpecializations = array_unique($arSpecializations);
    sort($arSpecializations);
    $bShowSpecializationFilter = boolval($arSpecializations);
    $selectSpecializationText = ($arParams['CHOOSE_SPECIALIZATION_TEXT'] ? $arParams['CHOOSE_SPECIALIZATION_TEXT'] : Loc::getMessage('CHOISE_ITEM', array('#ITEM#' => Loc::getMessage('CHOISE_SPECIALIZATION'))));
}
?>
<?if($bShowSpecializationFilter):?>
    <?$bChecked = isset($arItemFilter['PROPERTY_SPECIALIZATION']) && in_array($arItemFilter['PROPERTY_SPECIALIZATION'], $arSpecializations);?>
    <div class="staff__filter line-block line-block--16">
        <div class="line-block__item">
            <div class="staff__filter-select">
                <div class="dropdown-select specialization bordered rounded-4" data-id="">
                    <div class="dropdown-select__title">
                        <span><?=($bChecked ? $arItemFilter['PROPERTY_SPECIALIZATION'] : $selectSpecializationText)?></span>
                        <?=TSolution::showIconSvg("down fill-dark-light", SITE_TEMPLATE_PATH.'/images/svg/Triangle_down.svg', '', '', true, false);?>
                    </div>
                    <div class="dropdown-select__list dropdown-menu-wrapper" role="menu">
                        <div class="dropdown-menu-inner rounded-4 scrollbar scroll-deferred">
                            <div class="dropdown-select__list-item">
                                <a href="<?=$APPLICATION->GetCurPageParam('', array('SPECIALIZATION'))?>" class="dropdown-select__list-link empty dark_link<?=($bChecked ? '' : ' dropdown-select__list-link--current')?>" rel="nofollow">
                                    <span><?=$selectSpecializationText?></span>
                                </a>
                            </div>
                            <?foreach($arSpecializations as $specialization):?>
                                <?$bChecked = $arItemFilter['PROPERTY_SPECIALIZATION'] === $specialization;?>
                                <div class="dropdown-select__list-item">
                                    <a href="<?=$APPLICATION->GetCurPageParam('SPECIALIZATION='.urlencode($specialization), array('SPECIALIZATION'))?>" class="dropdown-select__list-link dark_link<?=($bChecked ? ' dropdown-select__list-link--current' : '')?>" rel="nofollow">
                                        <span><?=$specialization?></span>
                                    </a>
                                </div>
                            <?endforeach;?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?endif;?>