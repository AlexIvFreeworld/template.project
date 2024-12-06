<?
namespace Aspro\Allcorp3Medc\Solution;

class ConditionClass {
    public static function OnAsproGetConditionClassHandler(&$class, $arSiteThemeOptions) {
        $class .= ' warning_banner_'.strtolower($arSiteThemeOptions['SHOW_WARNING_BANNER']);
    }
}