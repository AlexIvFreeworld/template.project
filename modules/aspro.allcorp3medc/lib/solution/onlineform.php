<?
namespace Aspro\Allcorp3Medc\Solution;

use \CAllcorp3Medc as Solution;

class OnlineForm {
    public static function onGetItemMapHtmlHandler($arOptions, &$html, $btnClass) {
        if ($arOptions['SHOW_RECORD_ONLINE_BTN'] === 'Y') {
            global $arTheme;
            $btn_title = strlen($arTheme['EXPRESSION_FOR_ONLINE_RECORD']['VALUE']) ? $arTheme['EXPRESSION_FOR_ONLINE_RECORD']['VALUE'] : GetMessage('EXPRESSION_FOR_ONLINE_RECORD_DEFAULT');
            
            $html .= '<div class="map-detail-items__item-buttons">';
                $html .= '<span class="btn btn-default btn-transparent-border animate-load '.$btnClass.'" data-event="jqm" data-param-id="'.Solution::getFormID().'" '.$arOptions['SHOW_RECORD_ONLINE_BTN_ATTRS'].' data-name="record_online">'.$btn_title.'</span>';
            $html .= '</div>';
        }
    }
}