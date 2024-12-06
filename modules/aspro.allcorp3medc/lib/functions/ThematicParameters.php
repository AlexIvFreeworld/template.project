<?

namespace Aspro\Allcorp3Medc\Functions;

IncludeModuleLangFile(__FILE__);

use \Bitrix\Main\Config\Option,
    \CAllcorp3Medc as Solution;

class ThematicParameters
{
    public static function transformParameters(&$arParams) {
        static::addRootChart($arParams);
        static::addOnMainPageWarningBanner($arParams);
        static::addRootExpressionsExpressionForOnlineRecord($arParams);
        static::addSkuTreePropertyCodeCatalogPage($arParams);
        static::addCatalogSkuPropertyCode($arParams);
        static::addSectionContactsUseSpecializationFilter($arParams);
        static::addSectionPriceListCount($arParams);
        static::addSectionStaffUseSpecializationFilter($arParams);
        static::addSectionStaffTypeMap($arParams);
        static::deleteOnMainPageGroups($arParams, ['TARIFFS', 'PROJECTS']);
        static::deleteRootGroups($arParams, ['TARIFFS_PAGE', 'PROJECT_PAGE']);
        static::changeNameButtonOrder($arParams);
    }



    public static function addRootChart(&$arParams) {
        $arParams['CHART'] = [
            'TITLE' => GetMessage('CHART_OPTIONS'),
            'THEME' => 'N',
            'OPTIONS' => [
                'SERVICES_IBLOCK_ID' => [
                    'TITLE' => GetMessage('SERVICES_IBLOCK_ID_TITLE'),
                    'TYPE' => 'selectbox',
                    'TYPE_SELECT' => 'IBLOCK',
                    'DEFAULT' => ['TYPE' => 'aspro_allcorp3medc_content', 'CODE' => 'aspro_allcorp3medc_services'],
                    'THEME' => 'N',
                ],
                'CONTACT_IBLOCK_ID' => [
                    'TITLE' => GetMessage('CONTACT_IBLOCK_ID_TITLE'),
                    'TYPE' => 'selectbox',
                    'TYPE_SELECT' => 'IBLOCK',
                    'DEFAULT' => ['TYPE' => 'aspro_allcorp3medc_content', 'CODE' => 'aspro_allcorp3medc_contact'],
                    'THEME' => 'N',
                ],
                'CHART_VIEW' => [
                    'TITLE' => GetMessage('CHART_VIEW_TITLE'),
                    'TYPE' => 'selectbox',
                    'DEFAULT' => 'MOUNTH',
                    'LIST' => [
                        'MOUNTH' => [
                            'TITLE' => GetMessage('CHART_VIEW_MOUNTH'),
                        ],
                        'REGULAR' => [
                            'TITLE' => GetMessage('CHART_VIEW_REGULAR'),
                        ],
                    ],
                    'THEME' => 'N',
                ],
            ],
        ];
    }

    public static function addOnMainPageWarningBanner(&$arParams) {
        $arParams['MAIN']['OPTIONS']['SHOW_WARNING_BANNER'] = [
            'TITLE' => GetMessage('SHOW_WARNING_BANNER_TITLE'),
            'TYPE' => 'checkbox',
            'DEFAULT' => 'Y',
            'ONE_ROW' => 'Y',
            'HINT' => GetMessage('WARNING_TEXT_VALUE_HINT'),
            'GROUP_BLOCK' => 'WARNING_BANNER_GROUP',
            'DEPENDENT_PARAMS' => [
                'WARNING_TEXT' => [
                    'TITLE' => GetMessage('WARNING_BANNER_TEXT_TITLE'),
                    'HIDE_TITLE' => 'Y',
                    'TYPE' => 'includefile',
                    'INCLUDEFILE' => '#SITE_DIR#include/warning_banner_text.php',
                    'CONDITIONAL_VALUE' => 'Y',
                    'PARAMS' => [
                        'WIDTH' => '100%'
                    ],
                    'DEFAULT' => GetMessage('WARNING_BANNER_TEXT_VALUE'),
                    'THEME' => 'N',
                ],
            ],
            'THEME' => 'Y',
            'NO_DELAY' => 'Y',
        ];
    }

    public static function addRootExpressionsExpressionForOnlineRecord(&$arParams) {
        $arParams['EXPRESSIONS']['OPTIONS']['EXPRESSION_FOR_ONLINE_RECORD'] = [
            'TITLE' => GetMessage('EXPRESSION_FOR_ONLINE_RECORD_TITLE'),
            'TYPE' => 'text',
            'DEFAULT' => GetMessage('EXPRESSION_FOR_ONLINE_RECORD_DEFAULT'),
            'THEME' => 'N',
            'GROUP_BLOCK_LINE' => 'Y',
            'GROUP_BLOCK' => 'EXPRESSION_ONLINE_RECORD_GROUP',
        ];
    }

    public static function addSkuTreePropertyCodeCatalogPage(&$arParams){
        $arParams['CATALOG_PAGE']['OPTIONS']['CATALOG_SKU_TREE_PROPERTY_CODE'] =  array(
            'TITLE' => GetMessage('CATALOG_SKU_TREE_PROPERTY_CODE_TITLE'),
            'TYPE' => 'multiselectbox',
            'TYPE_SELECT' => 'IBLOCK_PROPS',
            'PROPS_SETTING' => [
                'IBLOCK_ID_OPTION' => 'CATALOG_SKU_IBLOCK_ID',
                'IBLOCK_CODE' => 'aspro_'.Solution::themesSolutionName.'_sku',
                'IS_TREE' => true,
            ],
            'DEFAULT' => 'COLOR,COLOR_REF,SIZE',
            'GROUP_BLOCK' => 'MAIN_ALL_GROUP',
            'THEME' => 'N',
        );
    }

    public static function addCatalogSkuPropertyCode(&$arParams){
        $arParams['CATALOG_PAGE']['OPTIONS']['CATALOG_SKU_PROPERTY_CODE'] =  array(
            'TITLE' => GetMessage('CATALOG_SKU_PROPERTY_CODE_TITLE'),
            'TYPE' => 'multiselectbox',
            'TYPE_SELECT' => 'IBLOCK_PROPS',
            'PROPS_SETTING' => [
                'IBLOCK_ID_OPTION' => 'CATALOG_SKU_IBLOCK_ID',
                'IBLOCK_CODE' => 'aspro_'.Solution::themesSolutionName.'_sku',
            ],
            'DEFAULT' => 'FORM_ORDER,STATUS,ARTICLE,PRICE_CURRENCY,PRICE,PRICEOLD,ECONOMY,COLOR,COLOR_REF,GOST,DLINA,VYLET_STRELY,MARK_STEEL,WIDTH',
            'GROUP_BLOCK' => 'MAIN_ALL_GROUP',
            'THEME' => 'N',
        );
    }

    public static function addSectionContactsUseSpecializationFilter(&$arParams) {
        $arParams['SECTION']['OPTIONS']['CONTACTS_USE_SPECIALIZATION_FILTER'] = array(
            'TITLE' => GetMessage('CONTACTS_OPTIONS_USE_SPECIALIZATION_FILTER_TITLE'),
            'TYPE' => 'checkbox',
            'DEFAULT' => 'Y',
            'THEME' => 'Y',
            'GROUP_BLOCK' => 'SECTION_CONTACTS_GROUP',
            'ONE_ROW' => 'Y',
            'NO_DELAY' => 'Y',
        );
    }

    public static function addSectionPriceListCount(&$arParams) {
        $arParams['SECTION']['OPTIONS']['PRICE_LINK_COUNT'] = array(
            'TITLE' => GetMessage('PRICE_LINK_COUNT_TITLE'),
            'GROUP_BLOCK' => 'PRICE_PAGE_GROUP',
            'TYPE' => 'text',
            'IS_ROW' => 'Y',
            'DEFAULT' => 20,
            'THEME' => 'N',
        );
    }
    public static function addSectionStaffUseSpecializationFilter(&$arParams) {
        $arParams['SECTION']['OPTIONS']['STAFF_PAGE_USE_SPECIALIZATION_FILTER'] = array(
            'TITLE' => GetMessage('STAFF_PAGE_USE_SPECIALIZATION_FILTER_TITLE'),
            'TYPE' => 'checkbox',
            'DEFAULT' => 'Y',
            'THEME' => 'Y',
            'GROUP_BLOCK' => 'STAFF_PAGE_GROUP',
            'ONE_ROW' => 'Y',
            'NO_DELAY' => 'Y',
        );
    }
    public static function addSectionStaffTypeMap(&$arParams) {
        $arParams['SECTION']['OPTIONS']['STAFF_TYPE_MAP'] = array(
            'TITLE' => GetMessage('STAFF_TYPE_MAP'),
            'TYPE' => 'selectbox',
            'GROUP_BLOCK' => 'STAFF_PAGE_GROUP',
            'LIST' => array(
                'YANDEX' => GetMessage('CONTACTS_OPTIONS_TYPE_MAP_YANDEX'),
                'GOOGLE' => GetMessage('CONTACTS_OPTIONS_TYPE_MAP_GOOGLE'),
            ),
            'DEFAULT' => 'YANDEX',
            'THEME' => 'N',
        );
    }

    public static function deleteOnMainPageGroups(&$arParams, $keyGroups = array()){
        $currentIndexType = Option::get(Solution::moduleID, 'INDEX_TYPE', 'index1');
		foreach ($keyGroups as $keyGroup) {
			unset($arParams['INDEX_PAGE']['OPTIONS']['INDEX_TYPE']['SUB_PARAMS'][$currentIndexType][$keyGroup]);
		}
	}

    public static function deleteRootGroups(&$arParams, $keyGroups = array()){
        foreach ($keyGroups as $keyGroup) {
			unset($arParams[$keyGroup]);
		}
	}

    public static function changeNameButtonOrder(&$arParams){
        $arParams['EXPRESSIONS']['OPTIONS']['EXPRESSION_ORDER_BUTTON']['DEFAULT'] = GetMessage('EXPRESSION_ORDER_BUTTON_DEFAULT');
	}
    
}
