<?
namespace {
    if (!defined('VENDOR_PARTNER_NAME')) {
        /** @const Aspro partner name */
        define('VENDOR_PARTNER_NAME', 'aspro');
    }
    
    if (!defined('VENDOR_SOLUTION_NAME')) {
        /** @const Aspro solution name */
        define('VENDOR_SOLUTION_NAME', 'allcorp3medc');
    }
    
    if (!defined('VENDOR_MODULE_ID')) {
        /** @const Aspro module id */
        define('VENDOR_MODULE_ID', 'aspro.allcorp3medc');
    }
    
    if (!defined('VENDOR_MODULE_CODE')) {
        /** @const Aspro module code */
        define('VENDOR_MODULE_CODE', 'medc');
    }
    
    foreach ([
        'CAllcorp3Medc' => 'TSolution',
        'CAllcorp3MedcEvents' => 'TSolution\Events',
        'CAllcorp3MedcCache' => 'TSolution\Cache',
        'CAllcorp3MedcRegionality' => 'TSolution\Regionality',
        'CAllcorp3MedcCondition' => 'TSolution\Condition',
        'CInstargramAllcorp3Medc' => 'TSolution\Instagram',
        'CAllcorp3MedcTools' => 'TSolution\Tools',
        'CVKAllcorp3Medc' => 'TSolution\VK',
        'Aspro\Allcorp3Medc\Functions\CAsproAllcorp3' => 'TSolution\Functions',
        'Aspro\Functions\CAsproAllcorp3MedcCustom' => 'TSolution\FunctionsCustom',
        'Aspro\Allcorp3Medc\Functions\CAsproAllcorp3ReCaptcha' => 'TSolution\ReCaptcha',
        'Aspro\Allcorp3Medc\Functions\CAsproAllcorp3Switcher' => 'TSolution\Switcher',
        'Aspro\Allcorp3Medc\Functions\Extensions' => 'TSolution\Extensions',
        'Aspro\Allcorp3Medc\Functions\CSKU' => 'TSolution\SKU',
        'Aspro\Allcorp3Medc\Functions\CSKUTemplate' => 'TSolution\CSKUTemplate',
        'Aspro\Allcorp3Medc\Functions\ExtComponentParameter' => 'TSolution\ExtComponentParameter',
        'Aspro\Allcorp3Medc\Property\CustomFilter' => 'TSolution\Property\CustomFilter',
        'Aspro\Allcorp3Medc\Property\TariffItem' => 'TSolution\Property\TariffItem',
        'Aspro\Allcorp3Medc\Notice' => 'TSolution\Notice',
        'Aspro\Allcorp3Medc\Eyed' => 'TSolution\Eyed',
        'Aspro\Allcorp3Medc\Banner\Transparency' => 'TSolution\Banner\Transparency',
        'Aspro\Allcorp3Medc\Video\Iframe' => 'TSolution\Video\Iframe',
        'Aspro\Allcorp3Medc\MarketingPopup' => 'TSolution\MarketingPopup',
        'Aspro\Allcorp3Medc\Product\Common' => 'TSolution\Product\Common',
        'Aspro\Allcorp3Medc\Product\DetailGallery' => 'TSolution\Product\DetailGallery',
        'Aspro\Allcorp3Medc\Product\Image' => 'TSolution\Product\Image',
        'Aspro\Allcorp3Medc\Form\Field\Factory' => 'TSolution\Form\Field\Factory',
    ] as $original => $alias) {
        if (!class_exists($alias)) {
            class_alias($original, $alias);
        }    
    }

    // these alias declarations for IDE only
    if (false) {
        class TSolution extends CAllcorp3Medc {}
    }
}

// these alias declarations for IDE only
namespace TSolution {
    if (false) {
        class Events extends \CAllcorp3MedcEvents {}

        class Cache extends \CAllcorp3MedcCache {}

        class Regionality extends \CAllcorp3MedcRegionality {}

        class Condition extends \CAllcorp3MedcCondition {}

        class Instagram extends \CInstargramAllcorp3Medc {}

        class Tools extends \CAllcorp3MedcTools {}

        class Functions extends \Aspro\Allcorp3Medc\Functions\CAsproAllcorp3 {}

        class FunctionsCustom extends \Aspro\Functions\CAsproAllcorp3MedcCustom {}

        class Extensions extends \Aspro\Allcorp3Medc\Functions\Extensions {}

        class SKU extends \Aspro\Allcorp3Medc\Functions\CSKU {}

        class Notice extends \Aspro\Allcorp3Medc\Notice {}

        class Eyed extends \Aspro\Allcorp3Medc\Eyed {}

        class ExtComponentParameter extends \Aspro\Allcorp3Medc\Functions\ExtComponentParameter {}

        class ReCaptcha extends \Aspro\Allcorp3Medc\Functions\CAsproAllcorp3ReCaptcha {}

        class Switcher extends \Aspro\Allcorp3Medc\Functions\CAsproAllcorp3Switcher {}

        class CSKUTemplate extends \Aspro\Allcorp3Medc\Functions\CSKUTemplate {}

        class VK extends \CVKAllcorp3Medc {}

        class MarketingPopup extends \Aspro\Allcorp3Medc\MarketingPopup {}
    }
}

namespace TSolution\Banner {
    if (false) {
        class Transparency extends \Aspro\Allcorp3Medc\Banner\Transparency {}
    }
}

namespace TSolution\Form\Field {
    if (false) {
        class Factory extends \Aspro\Allcorp3Medc\Form\Field\Factory {}
    }
}

namespace TSolution\Product {
    if (false) {
        class Common extends \Aspro\Allcorp3Medc\Product\Common {}

        class Image extends \Aspro\Allcorp3Medc\Product\Image {}
        class DetailGallery extends \Aspro\Allcorp3Medc\Product\DetailGallery {}
    }
}

namespace TSolution\Video {
    if (false) {
        class Iframe extends \Aspro\Allcorp3Medc\Video\Iframe {}
    }
}

namespace TSolution\Property {
    if (false) {
        class CustomFilter extends \Aspro\Allcorp3Medc\Property\CustomFilter {}

        class TariffItem extends \Aspro\Allcorp3Medc\Property\TariffItem {}
    }
}