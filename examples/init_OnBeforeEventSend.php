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
define("SHOW_MAIN_FORM", true);
define("SHOW_PART_PROPS_DETAIL", false);
define("SHOW_SYSTEM_AUTH_FORM", false);
define("SHOW_CONSTRUCTOR_BLOCK_MAIN", false);
define("SHOW_PURCHASE_QUICK_VIEW", false);

\Bitrix\Main\EventManager::getInstance()->addEventHandler(
    'main',
    'OnBeforeEventSend',
    'eventSendExt'
);
function eventSendExt(&$arFields, &$arTemplate)
{
    //\Bitrix\Main\Diag\Debug::dumpToFile(array('$arFields' => $arFields), "", "/log.txt");
    //\Bitrix\Main\Diag\Debug::dumpToFile(array('$arTemplate' => $arTemplate), "", "/log.txt");
    //\Bitrix\Main\Diag\Debug::dumpToFile(array('$_FILES' => $_FILES), "", "/log.txt");

    if ($_FILES['file-resume']['name']) {
        $file = array(
            "name" => $_FILES['file-resume']['name'],
            "size" => $_FILES['file-resume']['size'],
            "tmp_name" => $_FILES['file-resume']['tmp_name'],
            "type" => $_FILES['file-resume']['type'],
            "del" => "",
        );
        $fid = CFile::SaveFile($file, "resume");
        $arFile = CFile::GetFileArray(intval($fid));
        $filePath = (empty($_SERVER["HTTPS"])) ? "http://"  : "https://";
        $filePath .= $_SERVER["SERVER_NAME"] . $arFile["SRC"]; 
        //получим сообщение
        //\Bitrix\Main\Diag\Debug::dumpToFile(array('$filePath' => $filePath), "", "/log.txt");
        $arFields["LINK"] = $filePath;
        //\Bitrix\Main\Diag\Debug::dumpToFile(array('$arFields 2' => $arFields), "", "/log.txt");
    }
}
