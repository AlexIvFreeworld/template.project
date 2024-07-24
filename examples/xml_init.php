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

if (!function_exists('custom_mail') && COption::GetOptionString("webprostor.smtp", "USE_MODULE") == "Y") {

    function custom_mail($to, $subject, $message, $additional_headers = '', $additional_parameters = '')

    {

        if (CModule::IncludeModule("webprostor.smtp")) {

            $smtp = new CWebprostorSmtp("s1");

            $result = $smtp->SendMail($to, $subject, $message, $additional_headers, $additional_parameters);



            if ($result)

                return true;

            else

                return false;
        }
    }
}

CModule::AddAutoloadClasses(

    '',

    array(

        'R52' => '/local/classes/r52.php',

        'CAsproAllcorp3Ext' => '/local/classes/CAsproAllcorp3Ext.php',

        'CAllcorp3R52' => '/local/classes/CAllcorp3R52.php',
        'CAllcorp3Ext' => '/local/classes/CAllcorp3Ext.php',

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

define("HIDE_SECTION", true);

define("HIDE_PRICE", true);
define("SHOW_PFICE_TOP_BASKET", false);
define("SHOW_VALIDITY_LICENSE", true);
define("SHOW_BURGER_MENU_DARK", false);
define("SHOW_FORM_TOP_VACANCY", false);
define("HIDE_SECTION_NEWS", false);
define("SHOW_COLUMN_DIPLOMA", false);
define("DONT_SHOW_BLOCK", false);
define("SHOW_ORDER_CALL_CONTACTS", false);
define("SHOW_COLUMN_PLACE", false);
define("SHOW_COLUMN_PHOTO", true);

AddEventHandler("main", "OnBeforeEventAdd", array("Ex2", "Ex2_51"));
AddEventHandler("iblock", "OnAfterIBlockElementUpdate", array("Ex2", "OnAfterIBlockElementUpdateHandler"));

class Ex2
{
    public static  function Ex2_51(&$event, &$lid, &$arFields)
    {
        if ($event == "FORM_FILLING_aspro_allcorp3_subscribe_s1") {
            //\Bitrix\Main\Diag\Debug::dumpToFile(array('$arFields' => $arFields), "", "/log.txt");

            $apiKey = "6bhocs4hjey1j67pte4cs8r9jb5xpzu5753t9nue";
            $testMail = $arFields["EMAIL"];
            $list_ids = "3,4";
            $url = 'https://api.unisender.com/en/api/isContactInLists?api_key=' . $apiKey . '&email=' . $testMail . '&list_ids=' . $list_ids . '&condition=or';
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_VERBOSE, 1);
            curl_setopt($curl, CURLOPT_POST, false); // 
            curl_setopt($curl, CURLOPT_URL, $url);
            $result = curl_exec($curl);
            curl_close($curl);
            $result = json_decode($result);
            // debug($result->result);
            //\Bitrix\Main\Diag\Debug::dumpToFile(array('$result->result' => $result->result), "", "/log.txt");            
            if ($result->result != 1) {
                $list_ids = "3";
                $url = 'https://api.unisender.com/ru/api/importContacts?format=json&api_key=' . $apiKey . '&field_names[0]=email&field_names[1]=email_list_ids&data[0][0]=' . $testMail . '&data[0][1]=' . $list_ids;
                $curl = curl_init();
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_VERBOSE, 1);
                curl_setopt($curl, CURLOPT_POST, false); // 
                curl_setopt($curl, CURLOPT_URL, $url);
                $result = curl_exec($curl);
                curl_close($curl);
                $result = json_decode($result);
                //\Bitrix\Main\Diag\Debug::dumpToFile(array('$result' => $result), "", "/log.txt");
            }
        }
    }
    public static function OnAfterIBlockElementUpdateHandler(&$arFields)
    {
        if($arFields["IBLOCK_ID"] == 95){
            // \Bitrix\Main\Diag\Debug::dumpToFile(array('$arFields[IBLOCK_ID]' => $arFields["IBLOCK_ID"]), "", "/log.txt");
            // \Bitrix\Main\Diag\Debug::dumpToFile(array('$arFields[NAME]' => $arFields["NAME"]), "", "/log.txt");
    
            $arCategoryCorrespondence = array(
                319 => "1523",
                322 => "2435",
                325 => "4243",
                324 => "1709",
                323 => "4246",
                327 => "4223",
            );
            
            $dom = new DOMDocument();
            $dom->encoding = 'utf-8';
            $dom->xmlVersion = '1.0';
            $dom->formatOutput = true;
            $xml_file_name = $_SERVER["DOCUMENT_ROOT"] . "/products.xml";
            
            $root = $dom->createElement('elec_market');
            date_default_timezone_set('Europe/Moscow');
            $attr_root_date = new DOMAttr('date', date("Y-m-d H:i:s"));
            $root->setAttributeNode($attr_root_date);
            // set currencies
            $currencies_node = $dom->createElement('currencies');
            $currency_node = $dom->createElement('currency');
            $attr_currency_node = new DOMAttr('id', "RUR");
            $currency_node->setAttributeNode($attr_currency_node);
            $currencies_node->appendChild($currency_node);
            $root->appendChild($currencies_node);
            // /set currencies
            $IBLOCK_ID = 95;
            $arLists = CIBlockSection::GetList(
                array('SORT' => 'ASC'),
                array('IBLOCK_ID' => $IBLOCK_ID, 'ACTIVE' => "Y", "!=ID" => 328),
                false,
                array('ID', 'NAME')
            );
            // set categories
            $categories_node = $dom->createElement('categories');
            while ($arList = $arLists->GetNext()) {
                // debug($arList);
                $rubricaId = (empty($arCategoryCorrespondence[$arList["ID"]])) ? "1523" : $arCategoryCorrespondence[$arList["ID"]];
            
                $category_node = $dom->createElement('category', $arList["NAME"]);
                $attr_category_node_1 = new DOMAttr('id', $arList["ID"]);
                $attr_category_node_2 = new DOMAttr('rubricaId', $rubricaId);
                $attr_category_node_3 = new DOMAttr('unit', "PCE");
                $attr_category_node_4 = new DOMAttr('currencyId', "RUR");
                $category_node->setAttributeNode($attr_category_node_1);
                $category_node->setAttributeNode($attr_category_node_2);
                $category_node->setAttributeNode($attr_category_node_3);
                $category_node->setAttributeNode($attr_category_node_4);
                $categories_node->appendChild($category_node);
            }
            $root->appendChild($categories_node);
            // /set categories
            
            // set offers
            $offers_node = $dom->createElement('offers');
            
            $arSelect = array('ID', 'IBLOCK_ID', 'NAME', 'IBLOCK_SECTION_ID', 'DATE_ACTIVE_FROM', 'DETAIL_PAGE_URL', 'PREVIEW_TEXT', 'DETAIL_TEXT', 'DETAIL_PICTURE', 'PREVIEW_PICTURE', 'PROPERTY_*');
            $arFilter = array('IBLOCK_ID' => $IBLOCK_ID, 'ACTIVE' => 'Y', "!=IBLOCK_SECTION_ID" => 328);
            $res = CIBlockElement::GetList(array(), $arFilter, false, array(), $arSelect);
            while ($ob = $res->GetNextElement()) {
                $arFields = $ob->GetFields();
                $arProps = $ob->GetProperties();
                // R52::debug($arFields);
                $arFile = CFile::GetFileArray(intval($arFields["PREVIEW_PICTURE"]));
                if(empty($arFields["PREVIEW_PICTURE"]) && !empty($arProps["PHOTOS"]["VALUE"])){
                    $firstKey = array_key_first($arProps["PHOTOS"]["VALUE"]);
                    $arFile = CFile::GetFileArray(intval($arProps["PHOTOS"]["VALUE"][$firstKey]));
                }
                if(empty($arFile["SRC"])){
                    // R52::debug($arFields["ID"] . " empty picture");
                }
                $offer_node = $dom->createElement('offer');
                $attr_offer_node = new DOMAttr('id', $arFields["ID"]);
                $offer_node->setAttributeNode($attr_offer_node);
            
                $child_offer_node_categoryId = $dom->createElement('categoryId', $arFields["IBLOCK_SECTION_ID"]);
                $offer_node->appendChild($child_offer_node_categoryId);
                $child_offer_node_title = $dom->createElement('title', $arFields["NAME"]);
                $offer_node->appendChild($child_offer_node_title);
                $child_offer_node_url = $dom->createElement('url', "https://www.contravt.ru" . $arFields["DETAIL_PAGE_URL"]);
                $offer_node->appendChild($child_offer_node_url);
                $child_offer_node_unit = $dom->createElement('unit', "PCE");
                $offer_node->appendChild($child_offer_node_unit);
                if (!empty($arFile["SRC"])) {
                    $child_offer_node_picture = $dom->createElement('picture', "https://www.contravt.ru" . $arFile["SRC"]);
                    $offer_node->appendChild($child_offer_node_picture);
                }
                if (!empty($arFields["PREVIEW_TEXT"])) {
                    $child_offer_node_tizer = $dom->createElement('tizer', str_replace(["&nbsp;"], [" "], $arFields["PREVIEW_TEXT"]));
                    $offer_node->appendChild($child_offer_node_tizer);
                }
                // $child_offer_node_description = $dom->createElement('description', str_replace(["&nbsp;"], [" "], $arFields["~DETAIL_TEXT"]));
                // $attr_description_node = new DOMAttr('format', "html");
                // $child_offer_node_description->setAttributeNode($attr_description_node);
                // $offer_node->appendChild($child_offer_node_description);
                $offers_node->appendChild($offer_node);
            }
            
            $root->appendChild($offers_node);
            $dom->appendChild($root);
            $res = $dom->save($xml_file_name);
            // \Bitrix\Main\Diag\Debug::dumpToFile(array('res : ' => $res), "", "/log.txt");
        }

    }
}
