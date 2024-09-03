<?php
CModule::AddAutoloadClasses(
    '',
    array(
        'AI\R52' => '/local/php_interface/include/classes/r52.php',
        'Shuchkin\SimpleXLS' => '/local/php_interface/include/classes/SimpleXLS.php',
    )
);
$arJsConfig = array( 
    'custom_main' => array( 
        'js' => '/local/php_interface/include/js/mainCustom.js', 
        // 'css' => '/bitrix/js/custom/main.css', 
        // 'rel' => array(), 
    ) 
); 

foreach ($arJsConfig as $ext => $arExt) { 
    \CJSCore::RegisterExt($ext, $arExt); 
}
require_once("include/functions.php");
require_once("include/constants.php");