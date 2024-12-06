<?php
function debug($data)
{
    global $USER;
    if ($USER->IsAdmin()) {
        echo "Вы администратор!";
        echo "<pre>" . print_r($data, 1) . "</pre>";
    }
}
function getHighloadElementProperty2($idBlock, $xmlIdElem, $propertyCode)
{
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
    CModule::IncludeModule("highloadblock");

    $hlbl = $idBlock; // Указываем ID нашего highloadblock блока к которому будет делать запросы.
    $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getById($hlbl)->fetch();

    $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
    $entity_data_class = $entity->getDataClass();

    $rsData = $entity_data_class::getList(array(
        "select" => array("*"),
        "order" => array("ID" => "ASC"),
        "filter" => array("UF_XML_ID" => $xmlIdElem)  // Задаем параметры фильтра выборки
    ));
    $arData = $rsData->Fetch();
    return $arData[$propertyCode];
}
function getHighloadElementXMLID($idBlock, $nameElem, $propertyCode)
{
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
    CModule::IncludeModule("highloadblock");

    $hlbl = $idBlock; // Указываем ID нашего highloadblock блока к которому будет делать запросы.
    $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getById($hlbl)->fetch();

    $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
    $entity_data_class = $entity->getDataClass();

    $rsData = $entity_data_class::getList(array(
        "select" => array("*"),
        "order" => array("ID" => "ASC"),
        "filter" => array("UF_NAME" => $nameElem)  // Задаем параметры фильтра выборки
    ));
    $arData = $rsData->Fetch();
    return $arData[$propertyCode];
}
function getHighloadFileSrc2($idBlock, $xmlIdElem, $propertyCode)
{
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
    CModule::IncludeModule("highloadblock");

    $hlbl = $idBlock; // Указываем ID нашего highloadblock блока к которому будет делать запросы.
    $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getById($hlbl)->fetch();

    $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
    $entity_data_class = $entity->getDataClass();

    $rsData = $entity_data_class::getList(array(
        "select" => array("*"),
        "order" => array("ID" => "ASC"),
        "filter" => array("UF_XML_ID" => $xmlIdElem)  // Задаем параметры фильтра выборки
    ));
    $arData = $rsData->Fetch();
    $arFile = CFile::GetFileArray($arData[$propertyCode]);
    //debug($arFile["SRC"]);
    return $arFile["SRC"];
}
function getHighloadArray($idBlock)
{
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
    CModule::IncludeModule("highloadblock");
    $arResult = array();

    $hlbl = $idBlock; // Указываем ID нашего highloadblock блока к которому будет делать запросы.
    $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getById($hlbl)->fetch();

    $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
    $entity_data_class = $entity->getDataClass();

    $rsData = $entity_data_class::getList(array(
        "select" => array("*"),
        "order" => array("UF_SORT" => "ASC"),
        "filter" => array("*")  // Задаем параметры фильтра выборки
    ));
    while ($arData = $rsData->Fetch()) {
        $arResult[] = $arData;
    }
    return $arResult;
}
function debugBackTrace()
{
    global $USER;
    if ($USER->IsAdmin()) {
        echo 'Вы администратор!';
        echo '<pre>' . print_r(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), 1) . '</pre>';
    }
}
if (!function_exists('custom_mail') && COption::GetOptionString("webprostor.smtp", "USE_MODULE") == "Y")
{
   function custom_mail($to, $subject, $message, $additional_headers='', $additional_parameters='')
   {
      if(CModule::IncludeModule("webprostor.smtp"))
      {
         $smtp = new CWebprostorSmtp("s1");
         $result = $smtp->SendMail($to, $subject, $message, $additional_headers, $additional_parameters);

         if($result)
            return true;
         else
            return false;
      }
   }
}
define("SHOW_FILTER",false);
require($_SERVER["DOCUMENT_ROOT"] . "/yookassa/lib/autoload.php");
use Bitrix\Main;

$eventManager = Main\EventManager::getInstance();
$eventManager->addEventHandler('main', 'OnBeforeEventAdd', function (&$event, &$lid, &$arFields) {
    if($event == "SALE_NEW_ORDER"){
       if(Bitrix\Main\Loader::includeModule("sale")){
        $order = \Bitrix\Sale\Order::load($arFields["ORDER_ID"]); 
        $propertyCollection = $order->getPropertyCollection();
        $propsData = [];
        foreach ($propertyCollection as $propertyItem) {
            if (!empty($propertyItem->getField("CODE"))) {
                $propsData[$propertyItem->getField("CODE")] = trim($propertyItem->getValue());
            }
        }
        $arFields["PHONE"] = $propsData["PHONE"];
        $arFields["ADDRESS_DELIVERY"] = $propsData["ADDRESS"];
       }
    }
});