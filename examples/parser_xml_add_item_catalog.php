<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
\Bitrix\Main\Loader::includeModule('iblock');

function debug($data)
{
    echo "Вы администратор!";
    echo "<pre>" . print_r($data, 1) . "</pre>";
}
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
$blockId = 31;
$IBLOCK_SECTION_ID = 1405;
$arIdPrice = array();
// set_time_limit(1000);
if (false) {
    $filePath = $_SERVER['DOCUMENT_ROOT'] . '/upload/price.php.xml';
    // $xml = simplexml_load_file("http://newtss.r52.ru/export2/index.xml");
    $xml = simplexml_load_file($filePath, "SimpleXMLElement", LIBXML_NOCDATA);
    // debug($xml->shop->offers);
    // die();
    foreach ($xml->shop->offers->offer as $key => $offer) {
        // echo $offer->model . "<br>";
        $PROP = array();
        $el = new CIBlockElement;
        // $file_path = $_SERVER["DOCUMENT_ROOT"] . "/" . $newfile;
        // $PROP["LINK_OLD_PAGE"] = $host . $link;
        // debug($arItem["pname"]);
        // debug($arSectionIdName[$arItem["pname"]]);
        $arLoadProductArray = array(
            //"MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_SECTION_ID" => $IBLOCK_SECTION_ID,
            "IBLOCK_ID"      => $blockId,
            "PROPERTY_VALUES" => $PROP,
            "NAME"           => $offer->model,
            "CODE"           => translit_sef($offer->model),
            "ACTIVE"         => "Y",
            'PREVIEW_TEXT' => $offer->description,
            "PREVIEW_TEXT_TYPE" => "html",
            "PREVIEW_PICTURE" => CFile::MakeFileArray($offer->picture),
            "DETAIL_PICTURE" => CFile::MakeFileArray($offer->picture),
        );
        $PRODUCT_ID = $el->Add($arLoadProductArray);
        if ($PRODUCT_ID > 0) {
            echo "New ID: " . $PRODUCT_ID;
        } else {
            print_r($el->LAST_ERROR);
        }
    }
}
if (false) {
    $filePath = $_SERVER['DOCUMENT_ROOT'] . '/upload/price.php.xml';
    // $xml = simplexml_load_file("http://newtss.r52.ru/export2/index.xml");
    $xml = simplexml_load_file($filePath, "SimpleXMLElement", LIBXML_NOCDATA);
    // debug($xml->shop->offers);
    // die();
    foreach ($xml->shop->offers->offer as $key => $offer) {
        // echo $offer->model . "<br>";
        $arIdPrice[$offer->model . ""] = intval($offer->price);   
    }
}
// debug($arIdPrice);
die();
$arSelect = array('ID', 'IBLOCK_ID', 'NAME', 'DATE_ACTIVE_FROM', 'PROPERTY_*');
$arFilter = array('IBLOCK_ID' => $blockId, 'SECTION_ID' => $IBLOCK_SECTION_ID, 'ACTIVE' => 'Y');
$res = CIBlockElement::GetList(array(), $arFilter, false, array('nPageSize' => 100), $arSelect);
while ($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    debug($arFields["NAME"]);
    $arProps = $ob->GetProperties();
    if ($arFields["ID"]) {

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

        if (CCatalogProduct::Add($arFields_2))
            echo "Добавили параметры товара к элементу каталога " . $PRODUCT_ID . '-' . $quantity . '<br>';
        else
            echo 'Ошибка добавления параметров<br>';

        $arFields_3 = array(
            "PRODUCT_ID" => $PRODUCT_ID,
            "CATALOG_GROUP_ID" => 1,
            "PRICE" => $arIdPrice[$arFields["NAME"]],
            "CURRENCY" => "RUB",
            "QUANTITY_FROM" => false,
            "QUANTITY_TO" => false
        );


        CPrice::Add($arFields_3);
    } else {
        echo "Error: " . $el->LAST_ERROR;
        echo $article;
    }
}
// IMPORT_XML_ID