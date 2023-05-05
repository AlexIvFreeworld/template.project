<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
\Bitrix\Main\Loader::includeModule('iblock');

function debug($data)
{
    echo "Вы администратор!";
    echo "<pre>" . print_r($data, 1) . "</pre>";
}
if (false) {
    $filePath = 'data.json';
    $json = file_get_contents($filePath);
    // debug($json);
    $arSection = json_decode($json, true);

    $arSectionName = array();
    foreach ($arSection as $arItem) {
        $arSectionName[$arItem["NAME"]] = $arItem;
    }

    // debug($arSectionName);

    $arSectionsIds = array(283);
    $blockId = 26;
    $arLists = CIBlockSection::GetList(
        array('SORT' => 'ASC'),
        array('IBLOCK_ID' => $blockId, 'ID' => $arSectionsIds),
        false,
        array('NAME', 'CODE', 'SECTION_PAGE_URL')
    );
    while ($arList = $arLists->GetNext()) {
        debug($arList);
        $arResult['DIRECTIONS'][] = $arList;
    }
}
die();
$filePath = 'price.json';
$json = file_get_contents($filePath);
// debug($json);
$arElem = json_decode($json, true);

$arElemXmlId = array();
$count = 0;
foreach ($arElem as $key => $arItem) {
    if ($count > 10000) {
        break;
    }
    $arElemXmlId[$key] = $arItem;
    $count++;
}

// debug($arElemXmlId);
$EL_ID = array();
$blockId = 31;
$arSelect = array('ID', 'IBLOCK_ID', 'NAME', 'DATE_ACTIVE_FROM', 'PROPERTY_*');
$arFilter = array('IBLOCK_ID' => $blockId, "ID" => $EL_ID);
$res = CIBlockElement::GetList(array(), $arFilter, false, array('nPageSize' => 10000), $arSelect);
while ($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    // debug($arFields["NAME"]);
    $arProps = $ob->GetProperties();

    if ($arProps["IMPORT_XML_ID"]["VALUE"]) {

        $PRODUCT_ID = intval($arFields["ID"]);
        $quantity = intval($arElemXmlId[$arProps["IMPORT_XML_ID"]["VALUE"]]["PRODUCT_QUANTITY"]);
        $price = floatval($arElemXmlId[$arProps["IMPORT_XML_ID"]["VALUE"]]["PRICE"]);
        // debug("price " . $price);
        // echo "ID: " . $PRODUCT_ID;

        $arFields_2 = array(
            "ID" => $PRODUCT_ID,
            "QUANTITY"       => $quantity,
            // "WEIGHT"           => str_replace(",", ".", $weight),
            "VAT_ID" => 1, //выставляем тип ндс (задается в админке)
            "VAT_INCLUDED" => "N" //НДС входит в стоимость
        );

        if (CCatalogProduct::Add($arFields_2)){
            // echo "Добавили параметры товара к элементу каталога " . $PRODUCT_ID . '-' . $quantity . '<br>';
        }
        else{
            echo 'Ошибка добавления параметров<br>';
        }

        $arFields_3 = array(
            "PRODUCT_ID" => $PRODUCT_ID,
            "CATALOG_GROUP_ID" => 1,
            "PRICE" => $price,
            "CURRENCY" => "RUB",
            "QUANTITY_FROM" => false,
            "QUANTITY_TO" => false
        );
        $resPrice = CPrice::GetList(
            array(),
            array(
                "PRODUCT_ID" => $PRODUCT_ID,
                "CATALOG_GROUP_ID" => 1
            )
        );
        if ($arr = $resPrice->Fetch()) {
            \CPrice::Update($arr['ID'], $arFields_3);
        } else {
            \CPrice::Add($arFields_3);
        }
    } else {
        echo "Error: " . $el->LAST_ERROR;
        echo $article;
    }
}
debug("end");
