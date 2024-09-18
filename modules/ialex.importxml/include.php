<?
$arJsConfig = array(
    'ialex_main' => array(
        'js' => '/bitrix/js/ialex.importxml/main.js',
        'css' => '/bitrix/css/ialex.importxml/main.css',
        // 'rel' => array(), 
    ),
    'ialex_ajax' => array(
        'js' => '/bitrix/js/ialex.importxml/ajax.js',
        // 'css' => '/bitrix/css/ialex.importxml/main.css',
        // 'rel' => array(), 
    )
);
foreach ($arJsConfig as $ext => $arExt) {
    \CJSCore::RegisterExt($ext, $arExt);
}

CModule::AddAutoloadClasses(
    'ialex.importxml',
    array(
        'IAlex\IhelpXML' => 'classes/general/IAlex.php',
        'AI\R52' => 'classes/general/r52.php',
    )
);
