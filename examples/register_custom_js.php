<?php

use Bitrix\Main\Config\Option;
use Bitrix\Main\Page\Asset;

defined('B_PROLOG_INCLUDED') || die;

// Основной код.
CJSCore::RegisterExt(
    'custom_stuff',
    array(
        'js' => APP_JS_FOLDER . 'custom_stuff.js',
        'lang' => APP_LANG_FOLDER . LANGUAGE_ID . '/custom_stuff.js.php',
        'css' => APP_CSS_FOLDER . 'custom_stuff.css',
        'rel' => array('ajax')
    )
);

CJSCore::Init('custom_stuff');
CJSCore::Init(array("jquery"));


// Запускаем что надо.


$asset = Asset::getInstance();

/*
$profileTemplates = array(
    'profile' => ltrim(Option::get('intranet', 'path_user', '', SITE_ID), '/')
);
if (CComponentEngine::parseComponentPath('/', $profileTemplates, $arVars) == 'profile') {
    $asset->addString('<script>BX.ready(function () { BX.CustomStuff.createThankButton(); });</script>');
}

$asset->addString('<script>BX.ready(function () { BX.CustomStuff.enableDiskTemplates(); });</script>');
$asset->addString('<script>BX.ready(function () { BX.CustomStuff.overrideDiskShare(); });</script>');
$asset->addString('<script>BX.ready(function () { BX.CustomStuff.enableCreateGroupConfirmation(); });</script>');
*/  
//$asset->addString('<script>function checkBtn() {console.log("checkBtn");} </script>');
$asset->addString('<script>function checkBtn() { BX.CustomStuff.checkBtn(); } </script>');

if (CSite::InDir('/crm/deal/details')) {
    $asset->addString('<script>BX.ready(function () { BX.CustomStuff.inConsole(); });</script>');
}
