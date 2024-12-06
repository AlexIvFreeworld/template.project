<? 
namespace Aspro\Allcorp3Medc\Functions;

use \CAllcorp3Medc as Solution;

class Extensions {
    public static function register(){
		$arConfig = [
			'before_after_image' => [
				'js' => SITE_TEMPLATE_PATH.'/vendor/js/drags_image.js',
				'css' => SITE_TEMPLATE_PATH.'/css/before_after_image.css',
				'rel' => [Solution::partnerName.'_before_after_image_init'],
			],
			'before_after_image_init' => [
				'js' => SITE_TEMPLATE_PATH.'/js/before_after_image_init.js',
			],
			'catalog_detail' => [
				'css' => SITE_TEMPLATE_PATH.'/css/catalog_detail.css',
			],
			'datetimepicker' => [
				'css' => SITE_TEMPLATE_PATH.'/css/bootstrap-datetimepicker.min.css',
				'js' => [
					SITE_TEMPLATE_PATH.'/js/bootstrap-datetimepicker.min.js',
					SITE_TEMPLATE_PATH.'/js/bootstrap-datetimepicker.ru.js',
				],
				'lang' => '/bitrix/modules/'.Solution::partnerName.'.'.Solution::themesSolutionName.'/lang/'.LANGUAGE_ID.'/lib/datetimepicker.php',
			],
			'datetimepicker_init' => [
				'css' => SITE_TEMPLATE_PATH.'/css/datetimepicker_init.min.css',
				'js' => SITE_TEMPLATE_PATH.'/js/datetimepicker_init.min.js',
				'rel' => [Solution::partnerName.'_datetimepicker'],
			],
			'filter_block' => [
				'css' => SITE_TEMPLATE_PATH.'/css/filter_block.css',
			],
			'form_custom_handlers' => [
				'js' => SITE_TEMPLATE_PATH.'/js/form_custom_handlers.js',
			],
			'header_opacity' => [
				'css' => SITE_TEMPLATE_PATH.'/css/header_opacity.css',
			],
			'index_tabs' => [
				'js' => SITE_TEMPLATE_PATH.'/js/index-tabs.js',
			],
			'images_detail' => [
				'css' => SITE_TEMPLATE_PATH.'/css/images_detail.css',
			],
			'item_action' => [
				'js' => SITE_TEMPLATE_PATH.'/js/item-action.js',
			],
			'link_scroll' => [
				'js' => SITE_TEMPLATE_PATH.'/js/sectionLinkScroll.js',
			],
			'logo_depend_banners' => [
				'js' =>  SITE_TEMPLATE_PATH.'/js/logo_depend_banners.js',
			],
			'logo' => [
				'js' => SITE_TEMPLATE_PATH.'/js/logo.js',
			],
			'notice' => [
				'js' => '/bitrix/js/'.Solution::partnerName.'.'.Solution::themesSolutionName.'/notice.js',
				'css' => '/bitrix/css/'.Solution::partnerName.'.'.Solution::themesSolutionName.'/notice.css',
				'lang' => '/bitrix/modules/'.Solution::partnerName.'.'.Solution::themesSolutionName.'/lang/'.LANGUAGE_ID.'/lib/notice.php',
			],
			'set_height_catalog_banner' => [
				'js' => SITE_TEMPLATE_PATH.'/js/setHeightCatalogBanner.js',
			],
			'swiper' => [
				'js' => SITE_TEMPLATE_PATH.'/vendor/js/carousel/swiper/swiper-bundle.min.js',
				'css' => [
					SITE_TEMPLATE_PATH.'/vendor/css/carousel/swiper/swiper-bundle.min.css',
					SITE_TEMPLATE_PATH.'/css/slider.swiper.min.css'
				],
				'rel' => [Solution::partnerName.'_swiper_init'],
			],
			'swiper_init' => [
				'js' => SITE_TEMPLATE_PATH.'/js/slider.swiper.min.js',
			],
			'tariffitem' => [
				'js' => '/bitrix/js/'.Solution::partnerName.'.'.Solution::themesSolutionName.'/property/tariffitem.js',
				'css' => '/bitrix/css/'.Solution::partnerName.'.'.Solution::themesSolutionName.'/property/tariffitem.css',
				'lang' => '/bitrix/modules/'.Solution::partnerName.'.'.Solution::themesSolutionName.'/lang/'.LANGUAGE_ID.'/property/tariffitem.php',
			],
		];

		foreach ($arConfig as $ext => $arExt) {
			\CJSCore::RegisterExt(Solution::partnerName.'_'.$ext, array_merge($arExt, ['skip_core' => true]));
		}
	}

	public static function init($arExtensions){
		$arExtensions = is_array($arExtensions) ? $arExtensions : (array)$arExtensions;

		if($arExtensions){
            
			$arExtensions = array_map(function($ext){
				return strpos($ext, Solution::partnerName) !== false ? $ext : Solution::partnerName.'_'.$ext;
			}, $arExtensions);

			\CJSCore::Init($arExtensions);
		}
	}

	public static function initInPopup($arExtensions){
		self::register();
		self::init($arExtensions);
	}
}
