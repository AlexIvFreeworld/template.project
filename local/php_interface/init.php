<?php
CModule::AddAutoloadClasses(
    '',
    array(
        'R52' => '/local/classes/r52.php',
    )
);
$arJsConfig = array( 
    'custom_main' => array( 
        'js' => '/local/js/mainCustom.js', 
        // 'css' => '/bitrix/js/custom/main.css', 
        // 'rel' => array(), 
    ) 
); 

foreach ($arJsConfig as $ext => $arExt) { 
    \CJSCore::RegisterExt($ext, $arExt); 
}