<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Стройбаза НН - стройматериалы с доставкой на дом в Нижнем Новгороде | Интернет магазин стройматериалов в нижнем новгороде");
$APPLICATION->SetPageProperty("title", "Интернет-магазин стройматериалов | ТД Благов");
$APPLICATION->SetPageProperty("viewed_show", "Y");
$APPLICATION->SetTitle("Сайт по умолчанию");
// die();
if (false) {
    $IBLOCK_ID = 18;
    $EL_ID = 5998;
    $arSelect = array('ID', 'IBLOCK_ID', 'NAME', 'TIMESTAMP_X', 'PROPERTY_ARTIKUL');
    $arFilter = array('IBLOCK_ID' => $IBLOCK_ID, /*"ID" => $EL_ID,*/ 'ACTIVE' => 'Y');
    $count = 0;
    $connection = \Bitrix\Main\Application::getConnection();
    $res = CIBlockElement::GetList(array(), $arFilter, false, array(), $arSelect);
    while ($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();
        $arProps = $ob->GetProperties();
        // debug($arFields);
        foreach ($arProps as $key => $arProp) {
            if (!empty($arProp["VALUE"])) {
                if (is_array($arProp["VALUE"])) {
                    // debug($key);
                    foreach ($arProp["VALUE"] as $key2 => $val) {
                        // debug($key2 . " " . $val);
                    }
                } else {
                    // debug($key . " " . $arProp["VALUE"]);
                }
            }
        }
        // CIBlockElement::SetPropertyValuesEx($arFields["ID"], $IBLOCK_ID, array('ARTIKUL' => $arProps["ARTIKUL"]["VALUE"]));
        // CIBlockElement::UpdateSearch($arFields["ID"], true);
        // $el = new CIBlockElement;
        // $arFieldsUp = array("TIMESTAMP_X" => "07.10.2024 11:21:56");
        // $resUp = $el->Update($arFields["ID"], $arFieldsUp);
        // $resUp = $connection->query('SELECT ID,	TIMESTAMP_X FROM b_iblock_element WHERE ID=\''.$arFields["ID"].'\'');
        // if ($row = $resUp->Fetch()) {
        //     debug($row);
        // }
        $connection->query('UPDATE b_iblock_element SET TIMESTAMP_X = "2024-11-07 16:17:23" WHERE ID=\''.$arFields["ID"].'\'');
        // $resUp = $connection->query('SELECT ID,	TIMESTAMP_X FROM b_iblock_element WHERE ID=\''.$arFields["ID"].'\'');
        // if ($row = $resUp->Fetch()) {
        //     debug($row);
        // }

        $count++;
    }
}
// INSERT INTO table_name (column1, column2, column3, ...,columnN) VALUES (value1, value2, value3,...valueN);
debug("\$count: " . $count);
?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>