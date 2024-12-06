<?
namespace Aspro\Allcorp3Medc\Solution;

use \Bitrix\Main\Localization\Loc;

class General {
    public static function showWarningBanner(){
        global $arTheme, $APPLICATION;

        if ($arTheme['SHOW_WARNING_BANNER']['VALUE'] === 'Y') {
            $APPLICATION->SetAdditionalCSS(SITE_TEMPLATE_PATH.'/css/warning-banner.css');
            ?>
            <div class="warning-banner">
                <div class="maxwidth-theme">
                    <div class="warning-banner__text">
                        <?$APPLICATION->IncludeFile(SITE_DIR."include/warning_banner_text.php", 
                            array(), 
                            array(
                                "MODE" => "html",
                                "NAME" => Loc::getMessage('EDIT_WARNING_BANNER'),
                                "TEMPLATE" => "include_area.php",
                            )
                        );?>
                    </div>
                </div>
            </div>
            <?
        }
    }
}