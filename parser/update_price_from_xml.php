<?
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
CModule::IncludeModule('iblock');
Cmodule::IncludeModule('catalog');
require_once $_SERVER['DOCUMENT_ROOT'] . '/phpQuery/phpQuery/phpQuery.php';

function translit_sef($value)
{
    $converter = array(
        'а' => 'a',    'б' => 'b',    'в' => 'v',    'г' => 'g',    'д' => 'd',
        'е' => 'e',    'ё' => 'e',    'ж' => 'zh',   'з' => 'z',    'и' => 'i',
        'й' => 'y',    'к' => 'k',    'л' => 'l',    'м' => 'm',    'н' => 'n',
        'о' => 'o',    'п' => 'p',    'р' => 'r',    'с' => 's',    'т' => 't',
        'у' => 'u',    'ф' => 'f',    'х' => 'h',    'ц' => 'c',    'ч' => 'ch',
        'ш' => 'sh',   'щ' => 'sch',  'ь' => '',     'ы' => 'y',    'ъ' => '',
        'э' => 'e',    'ю' => 'yu',   'я' => 'ya',
    );

    $value = mb_strtolower($value);
    $value = strtr($value, $converter);
    $value = mb_ereg_replace('[^-0-9a-z]', '-', $value);
    $value = mb_ereg_replace('[-]+', '-', $value);
    $value = trim($value, '-');

    return $value;
}
$filePath = $_SERVER['DOCUMENT_ROOT'] . '/upload/tss.ru_bitrix_catalog_export_yandex_811573.xml';
$IBLOCK_ID = 94;
$count = 0;
$EL_ID = 105275;
$arIdPrice = array();
$arIdExtId = array();
$arArtPrice = array();
$SECTION_ID = 18682;

if (false) {
    // $xml = simplexml_load_file("http://newtss.r52.ru/export2/index.xml");
    $xml = simplexml_load_file($filePath, "SimpleXMLElement", LIBXML_NOCDATA);
    // R52::debug($xml->shop->offers);
    // die();
    foreach ($xml->shop->offers->offer as $key => $offer) {
        if ($count > 10000) {
            break;
        }
        // echo $offer['id'] . " - " . $offer->price . "<br>";
        // R52::debug($offer->param[0]. "");
        // $arIdPrice[$offer['id'] . ""] = $offer->price . "";
        $arArtPrice[$offer->param[0] . ""] = $offer->price . "";
        // $count++;
    }
    // R52::debug(count($arIdPrice));
    // $arSelect = array('ID', 'IBLOCK_ID', 'NAME', 'DATE_ACTIVE_FROM', 'PROPERTY_*');
    // $arFilter = array('IBLOCK_ID' => $IBLOCK_ID, 'ACTIVE' => 'Y');
    // $res = CIBlockElement::GetList(array(), $arFilter, false, array('nPageSize' => 10000), $arSelect);
    // while ($ob = $res->GetNextElement()) {
    //     $arFields = $ob->GetFields();
    //     $arProps = $ob->GetProperties();
    //     // R52::debug($arProps);
    //     $arIdExtId[$arFields["ID"]] = $arProps["IMPORT_XML_ID"]["VALUE"];
    // }

    // R52::debug(count($arIdExtId));
    // die();
    $arSelect = array('ID', 'IBLOCK_ID', 'NAME', 'DATE_ACTIVE_FROM', 'PROPERTY_*');
    $arFilter = array('IBLOCK_ID' => $IBLOCK_ID, 'ACTIVE' => 'Y');
    $res = CIBlockElement::GetList(array(), $arFilter, false, array('nPageSize' => 10000), $arSelect);
    while ($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();
        // R52::debug($arFields["ID"]);
        $arProps = $ob->GetProperties();
        // R52::debug($arProps["IMPORT_XML_ID"]["VALUE"]);
        // $importXmlId = trim($arProps["IMPORT_XML_ID"]["VALUE"]);
        $article = trim($arProps["CML2_ARTICLE"]["VALUE"]);
        if (array_key_exists($article, $arArtPrice)) {

            $PRODUCT_ID = intval($arFields["ID"]);
            $quantity = 10;
            echo "ID: " . $PRODUCT_ID;

            $arFields_2 = array(
                "ID" => $PRODUCT_ID,
                "QUANTITY"       => $quantity,
                // "WEIGHT"           => str_replace(",", ".", $weight),
                "VAT_ID" => 1, //выставляем тип ндс (задается в админке)
                "VAT_INCLUDED" => "N" //НДС входит в стоимость
            );

            if (CCatalogProduct::Add($arFields_2)) {
                // echo "Добавили параметры товара к элементу каталога " . $PRODUCT_ID . '-' . $quantity . '<br>';
            } else
                echo 'Ошибка добавления параметров<br>';

            $arFields_3 = array(
                "PRODUCT_ID" => $PRODUCT_ID,
                "CATALOG_GROUP_ID" => 1,
                "PRICE" => $arArtPrice[$article],
                "CURRENCY" => "RUB",
                "QUANTITY_FROM" => false,
                "QUANTITY_TO" => false
            );


            CPrice::Add($arFields_3);
            $count++;
        } else {
            R52::debug("Error: " . $arFields["ID"]);
        }
    }
}
if (true) {
    $arSelect = array('ID', 'IBLOCK_ID', 'NAME', 'DATE_ACTIVE_FROM', 'PROPERTY_*');
    $arFilter = array('IBLOCK_ID' => $IBLOCK_ID, 'IBLOCK_SECTION_ID' => $SECTION_ID, 'ACTIVE' => 'Y');
    $res = CIBlockElement::GetList(array(), $arFilter, false, array('nPageSize' => 10000), $arSelect);
    while ($ob = $res->GetNextElement()) {
        // $arFields = $ob->GetFields();
        // $arProps = $ob->GetProperties();
        $count++;
    }
}
echo "end" . " total: " . $count;
