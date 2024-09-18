<?

namespace IAlex;

if (!defined('IALEX_MENU_INCLUDED')) {
    define('IALEX_MENU_INCLUDED', 'ialex.importxml');
}

use \Bitrix\Main\Application,
    \Bitrix\Main\Type\Collection,
    \Bitrix\Main\Loader,
    \Bitrix\Main\IO\File,
    \Bitrix\Main\Localization\Loc,
    \Bitrix\Main\Config\Option;

Loc::loadMessages(__FILE__);

// initialize module parametrs list and default values
// include_once __DIR__.'/../../parametrs.php';
// include_once __DIR__.'/../../presets.php';

class IhelpXML
{
    public static function seyText($text)
    {
        echo $text;
    }
}
