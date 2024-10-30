<?
include_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/wsrubi.smtp/classes/general/wsrubismtp.php");

use Bitrix\Main;

$eventManager = Main\EventManager::getInstance();
$eventManager->addEventHandler('main', 'OnBeforeUserRegister', function (&$arFields) {
    $res = false;
    // \Bitrix\Main\Diag\Debug::dumpToFile(array('$arFields' => $arFields), "", "/log.txt");
    // \Bitrix\Main\Diag\Debug::dumpToFile(array('$_REQUEST' => $_REQUEST), "", "/log.txt");
    define("SECRET_KEY", "6LerStopAAAAAJWJrApUhH4ruGJ_vAjTM-2X5E3f");
    $Response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . SECRET_KEY . "&response={$_POST["g-recaptcha-response"]}");
    $Return = json_decode($Response, true);
    // \Bitrix\Main\Diag\Debug::dumpToFile(array('$Return' => $Return), "", "/log.txt");
    if ($Return["success"] === true) {
        $res = true;
    }
    return $res;
});
