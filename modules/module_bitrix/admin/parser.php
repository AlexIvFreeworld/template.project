<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

function dduf($data)
{
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

$file = "1c2.xml";
if (false) {
    $depth = array();

    function startElement($parser, $name, $attrs)
    {
        global $depth;

        if (!isset($depth[$parser])) {
            $depth[$parser] = 0;
        }

        for ($i = 0; $i < $depth[$parser]; $i++) {
            echo "  ";
        }
        echo "$name\n";
        $depth[$parser]++;
    }

    function endElement($parser, $name)
    {
        global $depth;
        $depth[$parser]--;
    }

    $xml_parser = xml_parser_create();
    xml_set_element_handler($xml_parser, "startElement", "endElement");
    if (!($fp = fopen($file, "r"))) {
        die("Невозможно произвести чтение XML");
    }

    while ($data = fread($fp, 4096)) {
        if (!xml_parse($xml_parser, $data, feof($fp))) {
            die(sprintf(
                "Ошибка XML: %s на строке %d",
                xml_error_string(xml_get_error_code($xml_parser)),
                xml_get_current_line_number($xml_parser)
            ));
        }
    }
    xml_parser_free($xml_parser);
    print_r($xml_parser);
}

if (false) {
    if (file_exists('1c.xml')) {
        echo "exists";
        $xml = simplexml_load_file('1c.xml');
        $strXml = file_get_contents("1c.xml");
        // print_r($xml);
        // print_r($strXml);
    } else {
        exit('Не удалось открыть файл test.xml.');
    }

    $simple = "<para><note>простое примечание</note></para>";
    $p = xml_parser_create();
    xml_parse_into_struct($p, $strXml, $vals, $index);
    xml_parser_free($p);
    echo "Массив index\n";
    echo "<pre>";
    print_r($index);
    echo "</pre>";

    echo "\nМассив vals\n";
    echo "<pre>";
    print_r($vals);
    echo "</pre>";
}

if (false) {
    $xml = simplexml_load_file("block.xml");
    // print_r($xml->CatalogObject);
    // print_r($xml->CatalogObject);
    // echo "<pre>";
    // print_r($xml->Классификатор->Группы);
    // echo "</pre>";

    foreach ($xml->Классификатор->Группы->Группа as $arItem) {
        dduf($arItem->Ид);
    }

    // echo "<pre>";
    // print_r($xml->Каталог->Товары);
    // echo "</pre>";

}

$xml = simplexml_load_file($_SERVER["DOCUMENT_ROOT"] ."/upload/1c.xml");
$IBLOCK_ID = 13;
// debug($IBLOCK_ID);
// debug($xml);
if (false) {
    $count = 1;
    foreach ($xml->Data->Номенклатура as $key => $arItem) {

        // debug($arItem->IsFolder);
        if ($arItem->IsFolder == "false" || $arItem->Parent == "00000000-0000-0000-0000-000000000000") {
            continue;
        }
        // debug($arItem->Description);
        // debug(translit_sef($arItem->Description)."-".$count);

        $bs = new CIBlockSection;
        $arFields = array(
            "ACTIVE" => "Y",
            "IBLOCK_SECTION_ID" => "",
            "IBLOCK_ID" => $IBLOCK_ID,
            "NAME" => $arItem->Description,
            "CODE" => translit_sef($arItem->Description) . "-" . $count,
            'DESCRIPTION' => "",
            "DESCRIPTION_TYPE" => "html",
            "UF_EXT_CODE" => $arItem->Ref,
            "UF_EXT_PARENT_CODE" => $arItem->Parent,
            // "PICTURE" => CFile::MakeFileArray($file_path),
        );

        if (true) {
            $ID = $bs->Add($arFields);
            $res = ($ID > 0);

            if (!$res) {
                echo $bs->LAST_ERROR;
            } else {
                echo $ID;
            }
        }
        $count++;
    }
}

if (false) {
    $arLists = CIBlockSection::GetList(
        array('SORT' => 'ASC'),
        array('IBLOCK_ID' => $IBLOCK_ID),
        false,
        array('ID', 'NAME', 'CODE', 'IBLOCK_SECTION_ID', 'UF_EXT_CODE', "UF_EXT_PARENT_CODE")
    );
    $arCodeId = array();
    while ($arList = $arLists->GetNext()) {
        if (empty($arList['UF_EXT_CODE'])) {
            continue;
        }
        // debug($arList);
        $arCodeId[$arList['UF_EXT_CODE']] = $arList['ID'];
    }
    // debug($arCodeId);
    // die();
    $arLists = CIBlockSection::GetList(
        array('SORT' => 'ASC'),
        array('IBLOCK_ID' => $IBLOCK_ID),
        false,
        array('ID', 'NAME', 'CODE', 'IBLOCK_SECTION_ID', 'UF_EXT_CODE', "UF_EXT_PARENT_CODE")
    );
    while ($arList = $arLists->GetNext()) {
        if (empty($arList['UF_EXT_CODE'])) {
            continue;
        }
        // debug($arList);

        if (true) {
            $bs = new CIBlockSection;
            $arFields = array(
                "IBLOCK_SECTION_ID" => $arCodeId[$arList["UF_EXT_PARENT_CODE"]],
            );

            $res = $bs->Update($arList["ID"], $arFields);

            if (!$res) {
                echo $bs->LAST_ERROR;
            } else {
                echo $res;
            }
        }
    }
}

if (false) {
    $countEl = 1;
    foreach ($xml->Data->Номенклатура as $key => $arItem) {

        // debug($arItem->IsFolder);
        if ($arItem->IsFolder == "true") {
            continue;
        }
        // debug($arItem->Description);
        //debug(translit_sef($arItem->Description) . "-" . $countEl);
        $PROP = array();
        $el = new CIBlockElement;
        $PROP["PROP_1"] = "";
        $PROP["PROP_EXT_CODE"] = $arItem->Ref;
        $PROP["PROP_EXT_PARENT_CODE"] = $arItem->Parent;
        $arLoadProductArray = array(
            //"MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_SECTION_ID" => "637",
            "IBLOCK_ID"      => $IBLOCK_ID,
            "PROPERTY_VALUES" => $PROP,
            "NAME"           => $arItem->Description,
            "CODE"           => translit_sef($arItem->Description) . "-" . $countEl,
            "ACTIVE"         => "Y",
            'PREVIEW_TEXT' => "",
            "PREVIEW_TEXT_TYPE" => "html",
            // "PREVIEW_PICTURE" => CFile::MakeFileArray($file_path),
            // "DETAIL_PICTURE" => CFile::MakeFileArray($file_path),
        );
        $PRODUCT_ID = $el->Add($arLoadProductArray);
        if ($PRODUCT_ID > 0) {
            echo "New ID: " . $PRODUCT_ID;
        } else {
            print_r($el->LAST_ERROR);
        }
        $countEl++;
    }
}

if (false) {
    $arLists = CIBlockSection::GetList(
        array('SORT' => 'ASC'),
        array('IBLOCK_ID' => $IBLOCK_ID),
        false,
        array('ID', 'NAME', 'CODE', 'IBLOCK_SECTION_ID', 'UF_EXT_CODE', "UF_EXT_PARENT_CODE")
    );
    $arCodeId = array();
    while ($arList = $arLists->GetNext()) {
        if (empty($arList['UF_EXT_CODE'])) {
            continue;
        }
        // debug($arList);
        $arCodeId[$arList['UF_EXT_CODE']] = $arList['ID'];
    }
    // debug($arCodeId);
    $arCodeUnit = array();
    foreach ($xml->Data->ЕдиницыИзмерения as $key => $arItem) {
        // debug($arItem->Owner . " - " . $arItem->Description);
        $arCodeUnit[$arItem->Owner . ""] = $arItem->Description . "";
    }
    // debug($arCodeUnit);
    // die();
    $arSelect = array('ID', 'IBLOCK_ID', 'NAME', 'DATE_ACTIVE_FROM', 'PROPERTY_*');
    $arFilter = array('IBLOCK_ID' => $IBLOCK_ID, 'SECTION_ID' => "637", 'ACTIVE' => 'Y');
    $res = CIBlockElement::GetList(array(), $arFilter, false, array('nPageSize' => 1000), $arSelect);
    while ($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();
        $arProps = $ob->GetProperties();
        // debug($arFields);
        // debug($arProps["PROP_EXT_PARENT_CODE"]["VALUE"]);
        // debug($arCodeId[$arProps["PROP_EXT_PARENT_CODE"]["VALUE"]]);
        if (true) {
            $el = new CIBlockElement;
            $arFieldsUp = array("IBLOCK_SECTION_ID" => $arCodeId[$arProps["PROP_EXT_PARENT_CODE"]["VALUE"]]);
            $resUp = $el->Update(intval($arFields['ID']), $arFieldsUp);
            // debug($resUp);
            CIBlockElement::SetPropertyValuesEx($arFields["ID"], $IBLOCK_ID, array('PROP_1' => $arCodeUnit[$arProps["PROP_EXT_CODE"]["VALUE"]]));

            if ($resUp != 1) {
                debug("err update" . $arFields['ID']);
            }
        }
    }
}

if(true){
    echo("end");
}

