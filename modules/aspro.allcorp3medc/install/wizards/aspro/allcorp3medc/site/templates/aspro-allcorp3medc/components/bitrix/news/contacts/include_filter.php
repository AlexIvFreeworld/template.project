<?
use \Bitrix\Main\Localization\Loc;

if($arSection){
    $arAllItemsFilter = $arItemFilter;
    unset(
        $arAllItemsFilter['SECTION_ID'], 
        $arAllItemsFilter['SECTION_CODE'], 
        $arAllItemsFilter['SECTION_GLOBAL_ACTIVE'],
        $arAllItemsFilter['PROPERTY_SPECIALIZATION']
    );

    $arAllItems = CAllcorp3MedcCache::CIblockElement_GetList(
        array(
            'CACHE' => array(
                'TAG' => CAllcorp3MedcCache::GetIBlockCacheTag($arParams['IBLOCK_ID'])
            )
        ),
        $arAllItemsFilter,
        false,
        false,
        array('ID', 'IBLOCK_SECTION_ID', 'PROPERTY_SPECIALIZATION')
    );
}
else{
    $arAllItems = $arItems;
}


$bShowSpecializationFilter = false;
if($arTheme['CONTACTS_USE_SPECIALIZATION_FILTER']['VALUE'] !== 'N'){
    $arSectionsIDsBySpecialization = [];
    foreach($arAllItems as $arItem){
        if($arItem['PROPERTY_SPECIALIZATION_VALUE']){
            if (!is_array($arItem['PROPERTY_SPECIALIZATION_ENUM_ID'])) {
                $arItem['PROPERTY_SPECIALIZATION_VALUE'] = [$arItem['PROPERTY_SPECIALIZATION_VALUE']];
                $arItem['PROPERTY_SPECIALIZATION_ENUM_ID'] = [$arItem['PROPERTY_SPECIALIZATION_ENUM_ID']];
            }
            
            foreach($arItem['PROPERTY_SPECIALIZATION_ENUM_ID'] as $i => $enumId){
                if(!isset($arSectionsIDsBySpecialization[$enumId])){
                    $arSectionsIDsBySpecialization[$enumId] = [
                        'ENUM_ID' => $enumId,
                        'NAME' => $arItem['PROPERTY_SPECIALIZATION_VALUE'][$i],
                        'SECTIONS' => [],
                    ];
                }

                if($arItem['IBLOCK_SECTION_ID']){   
                    $arSectionsIDsBySpecialization[$enumId]['SECTIONS'][] = $arItem['IBLOCK_SECTION_ID'];
                }
            }
        }
    }

    uasort($arSectionsIDsBySpecialization, function($arA, $arB){
        return $arA['NAME'] <=> $arB['NAME'];
    });

    $bShowSpecializationFilter = boolval($arSectionsIDsBySpecialization);
    $selectSpecializationText = ($arParams['CHOOSE_SPECIALIZATION_TEXT'] ? $arParams['CHOOSE_SPECIALIZATION_TEXT'] : Loc::getMessage('CHOISE_ITEM', array('#ITEM#' => Loc::getMessage('CHOISE_SPECIALIZATION'))));
}

if($bShowSectionFilter){
    $arAllSections = $arAllItems ? CAllcorp3Medc::GetSections($arAllItems, array_merge($arParams, array('SEF_URL_TEMPLATES' => array()))) : array();
    
    $bHasSections = (isset($arAllSections['ALL_SECTIONS']) && $arAllSections['ALL_SECTIONS']);
    $bHasChildSections = (isset($arAllSections['CHILD_SECTIONS']) && $arAllSections['CHILD_SECTIONS']);
    if($bHasSections){
        $arAllSections['CURRENT_SECTIONS'] = array(
            'PARENT' => array('ID' => false),
            'CHILD' => array('ID' => false),
        );
    
        if($arSection){
            foreach($arAllSections['ALL_SECTIONS'] as $_arSection){
                $bChecked = $_arSection['SECTION']['ID'] == $arSection['ID'];
                if($bChecked){
                    $arAllSections['CURRENT_SECTIONS']['PARENT'] = $_arSection['SECTION'];
                    break;
                }
                elseif($bHasChildSections){
                    if(in_array($arSection['ID'], $_arSection['CHILD_IDS'])){
                        $arAllSections['CURRENT_SECTIONS']['PARENT'] = $_arSection['SECTION'];
                        $arAllSections['CURRENT_SECTIONS']['CHILD'] = $arAllSections['CHILD_SECTIONS'][$arSection['ID']];
                        break;
                    }
                }
            }
        }
    
        $selectRegionText = ($arParams['CHOOSE_REGION_TEXT'] ? $arParams['CHOOSE_REGION_TEXT'] : Loc::getMessage('CHOISE_ITEM', array('#ITEM#' => ($bHasChildSections ? Loc::getMessage('CHOISE_REGION') : Loc::getMessage('CHOISE_CITY')))));
    
        $selectCityText = Loc::getMessage('CHOISE_ITEM', array('#ITEM#' => Loc::getMessage('CHOISE_CITY')));
    }
}

$cntFilters = ($bShowSpecializationFilter ? 1 : 0) + (($bShowSectionFilter && $bHasSections) ? 1 + ($bHasChildSections ? 1 : 0) : 0);
?>
<?if($cntFilters):?>
    <div class="contacts__filter line-block line-block--16<?=($cntFilters > 2 ? ' contacts__filter--wider' : '')?>">
        <?if($bShowSpecializationFilter):?>
            <div class="line-block__item">
                <div class="contacts__filter-select">
                    <div class="dropdown-select specialization bordered rounded-4" data-id="">
                        <div class="dropdown-select__title">
                            <span><?=$selectSpecializationText?></span>
                            <?=CAllcorp3Medc::showIconSvg("down fill-dark-light", SITE_TEMPLATE_PATH.'/images/svg/Triangle_down.svg', '', '', true, false);?>
                        </div>
                        <div class="dropdown-select__list dropdown-menu-wrapper" role="menu">
                            <div class="dropdown-menu-inner rounded-4 scrollbar">
                                <div class="dropdown-select__list-item">
                                    <a href="javascript:;" class="dropdown-select__list-link empty dark_link dropdown-select__list-link--current" rel="nofollow">
                                        <span><?=$selectSpecializationText?></span>
                                    </a>
                                </div>
                                <?foreach($arSectionsIDsBySpecialization as $enumId => $arSpecialization):?>
                                    <div class="dropdown-select__list-item">
                                        <a href="javascript:;" class="dropdown-select__list-link dark_link" data-id="<?=$arSpecialization['ENUM_ID']?>" rel="nofollow">
                                            <span><?=$arSpecialization['NAME']?></span>
                                        </a>
                                    </div>
                                <?endforeach;?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?endif;?>
        <?if(
            $bShowSectionFilter && 
            $bHasSections
        ):?>
            <div class="line-block__item">
                <div class="contacts__filter-select">
                    <div class="dropdown-select <?=($bHasChildSections ? 'region' : 'city')?> bordered rounded-4" data-id="<?=$arAllSections['CURRENT_SECTIONS']['PARENT']['ID']?>">
                        <div class="dropdown-select__title">
                            <span><?=($arAllSections['CURRENT_SECTIONS']['PARENT']['ID'] ? $arAllSections['CURRENT_SECTIONS']['PARENT']['NAME'] : $selectRegionText)?></span>
                            <?=CAllcorp3Medc::showIconSvg("down fill-dark-light", SITE_TEMPLATE_PATH.'/images/svg/Triangle_down.svg', '', '', true, false);?>
                        </div>
                        <div class="dropdown-select__list dropdown-menu-wrapper" role="menu">
                            <div class="dropdown-menu-inner rounded-4 scrollbar">
                                <div class="dropdown-select__list-item">
                                    <?$bChecked = !$arAllSections['CURRENT_SECTIONS']['PARENT']['ID'];?>
                                    <a href="<?=$arResult["FOLDER"].$arResult["URL_TEMPLATES"]["news"]?>" class="dropdown-select__list-link empty dark_link <?=($bChecked ? 'dropdown-select__list-link--current' : '')?>" data-id="0" rel="nofollow">
                                        <span><?=$selectRegionText?></span>
                                    </a>
                                </div>
                                <?foreach($arAllSections['ALL_SECTIONS'] as $_arSection):?>
                                    <?$bChecked = $_arSection['SECTION']['ID'] == $arAllSections['CURRENT_SECTIONS']['PARENT']['ID'];?>
                                    <div class="dropdown-select__list-item">
                                        <a href="<?=$_arSection['SECTION']['SECTION_PAGE_URL']?>" class="dropdown-select__list-link dark_link <?=($bChecked ? 'dropdown-select__list-link--current' : '')?>" data-id="<?=$_arSection['SECTION']['ID']?>" rel="nofollow">
                                            <span><?=$_arSection['SECTION']['NAME']?></span>
                                        </a>
                                    </div>
                                <?endforeach;?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?if($bHasChildSections):?>
                <div class="line-block__item">
                    <div class="contacts__filter-select">
                        <div class="dropdown-select city bordered rounded-4" data-id="<?=($arAllSections['CURRENT_SECTIONS']['CHILD']['ID'] ?: $arAllSections['CURRENT_SECTIONS']['PARENT']['ID'])?>">
                            <div class="dropdown-select__title">
                                <span><?=($arAllSections['CURRENT_SECTIONS']['CHILD']['ID'] ? $arAllSections['CURRENT_SECTIONS']['CHILD']['NAME'] : $selectCityText)?></span>
                                <?=CAllcorp3Medc::showIconSvg("down fill-dark-light", SITE_TEMPLATE_PATH.'/images/svg/Triangle_down.svg', '', '', true, false);?>
                            </div>
                            <div class="dropdown-select__list dropdown-menu-wrapper" role="menu">
                                <div class="dropdown-menu-inner rounded-4 scrollbar">
                                    <div class="dropdown-select__list-item">
                                        <?$bChecked = !$arAllSections['CURRENT_SECTIONS']['CHILD']['ID'];?>
                                        <a href="javascript:;" class="dropdown-select__list-link empty dark_link <?=($bChecked ? 'dropdown-select__list-link--current' : '')?>" data-id="0" rel="nofollow">
                                            <span><?=$selectCityText?></span>
                                        </a>
                                    </div>
                                    <?foreach($arAllSections['CHILD_SECTIONS'] as $_arSubSection):?>
                                        <?
                                        $bChecked = $_arSubSection['ID'] == $arAllSections['CURRENT_SECTIONS']['CHILD']['ID'];
                                        $bVisible = $_arSubSection['IBLOCK_SECTION_ID'] == $arAllSections['CURRENT_SECTIONS']['PARENT']['ID'];
                                        ?>
                                        <div class="dropdown-select__list-item">
                                            <a href="<?=$_arSubSection['SECTION_PAGE_URL']?>" class="dropdown-select__list-link dark_link <?=($bChecked ? 'dropdown-select__list-link--current' : '')?>" data-id="<?=$_arSubSection['ID']?>" data-parent_id="<?=$_arSubSection['IBLOCK_SECTION_ID']?>" rel="nofollow" <?=($bVisible ? '' : 'style="display:none"')?>>
                                                <span><?=$_arSubSection['NAME']?></span>
                                            </a>
                                        </div>
                                    <?endforeach;?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?endif;?>
        <?endif;?>
    </div>
<?endif;?>