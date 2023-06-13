<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
\Bitrix\Main\Loader::includeModule('iblock');
?>
<?php
/*
$IBLOCK_ID = 19;

$arSelect = array("*");
$arFilter = array('IBLOCK_ID' => $IBLOCK_ID);
*/
// get list items
/*
$arArticlesId = array();
$iterator = CIBlockElement::GetPropertyValues($IBLOCK_ID, array('ACTIVE' => 'Y'), true, array('ID' => array(113)));
$i = 1;
while ($row = $iterator->Fetch()) {
    if ($i > 2000) {
        break;
    }
    //debug($row);
    $arArticlesId[$row["IBLOCK_ELEMENT_ID"]] = $row["113"];

    $i++;
}
//debug($arArticlesId);
//return;
// get id item $arItem["ARTICLE"] = array(); 
// $key = array_search('green', $array); // $key = 2;
*/
//$filename2 = 'import_4.csv';
/*
//$items = file($filename);
$items2 = file_get_contents($filename2, $use_include_path = false, $context = null, $offset = 0, $maxlen = 10000000);
//debug($items2);
$items2 = explode("\n", $items2);
//debug($items2);
$arLevelString = array();
$arOrigCode = array();
$j = 0;
foreach ($items2 as $item) {
    if ($j == 0) {
        $j++;
        continue;
    }
    $arElem = array();
    if ($j >= 2000) {
        break;
    }
    //debug($item);
    $arItem = str_getcsv($item, $separator = ";", $enclosure = "\n");

    $arFile = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"] . "/upload/iblock/ocalift/" . $arItem[3]);
    //debug($arFile);
    $id = array_search($arItem[5], $arArticlesId);
    if ($arFile) {
        $arFile["del"] = false;
        $arFile["MODULE_ID"] = "iblock";
        $arFile["description"] = "описание файла";
        //debug($arItem[5]);
        //debug($arItem[3]);
        //debug($arFile);
        //debug($id);
        $arElem = array("VALUE" => $arFile, "DESCRIPTION" => "");
        $arLevelString[$id][] = $arElem;
    }

    $j++;
}
//debug($arLevelString);
//return;
*/
/*
$IBlock_ID = 19;
$PROPERTY_CODE = "MORE_PHOTO";


foreach ($arLevelString as $key => $arItem) {
    $Element_ID = $key;
    $PROPERTY_VALUE = array();
    foreach ($arItem as $keyEl => $arEl) {
        $PROPERTY_VALUE += array($keyEl => array("VALUE" => $arEl["VALUE"], "DESCRIPTION" => ""));
    }
    //debug($PROPERTY_VALUE);
    $res = CIBlockElement::SetPropertyValuesEx($Element_ID, $IBlock_ID, array($PROPERTY_CODE => $PROPERTY_VALUE)); // Обновляем массив свойств типа файл

}

debug("all");
return;
*/

/*
$db_list = CIBlockElement::GetList(array(), $arFilter, false, array(), $arSelect);
$i = 1;
while ($ar_result = $db_list->GetNext()) {
    if ($i > 1) {
        break;
    }
    //debug($ar_result);
    //$PRODUCT_ID = $ar_result['ID'];
    //debug($ar_result['NAME']);
    //debug($ar_result['ID']);
    //debug($ar_result["DETAIL_TEXT_TYPE"]);
    //debug($ar_result["~DETAIL_TEXT_TYPE"]);

    // -----
    
    $el = new CIBlockElement;
    $arFields = array("DETAIL_TEXT_TYPE" => "html", "~DETAIL_TEXT_TYPE" => "html");
    $res = $el->Update(intval($ar_result['ID']), $arFields);

    if ($res) {
        //debug("ok - " .  $ar_result['ID']);
    } else {
        debug("not - " .  $ar_result['ID']);
    }
    
    // -----

    //$i++;
}
//debug($i);
*/

/*
//найдем самые большие файлы ядра
$res = CFile::GetList(array("FILE_SIZE" => "desc"), array("MODULE_ID" => "iblock"));
while ($res_arr = $res->GetNext()) {
    if ($i > 1) {
        break;
    }
    //echo $res_arr["SUBDIR"]."/".$res_arr["FILE_NAME"]." = ".$res_arr["FILE_SIZE"]."<br>";
    //debug($res_arr["ORIGINAL_NAME"]);
    //debug($res_arr["SUBDIR"] . "/" . $res_arr["ORIGINAL_NAME"]);

    $i++;
}
*/
// get array files from folder
/*
debug("start");
$dir = $_SERVER["DOCUMENT_ROOT"] . "/upload/info_test";
debug($dir);

if (!scandir($dir)) {
    debug("error");
} else {
    $arfileName = scandir($dir);
    debug($arfileName);
}
// -----
*/
/*
//$arFile = CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"] . "/upload/iblock/ocalift/0101dhs_bez_karetok.jpg");
$arFile = CFile::MakeFileArray($dir . "/" . $arfileName[4]);

//debug($arFile);

$arFile["del"] = false;
$arFile["MODULE_ID"] = "iblock";
$arFile["description"] = "описание файла";
debug($arFile);

$idFile = CFile::SaveFile(
    $arFile,
    $savePath,
    false,
    false,
    $dirAdd = '',
    true
);
debug($idFile);


$rsFile = CFile::GetByID($idFile);
$arFile = $rsFile->Fetch();
debug($arFile);
*/

/*
$Element_ID = 1604;
$IBlock_ID = 26;
$PROPERTY_CODE = "FILE";
$PROPERTY_VALUE = array(
    0 => array("VALUE"=> $arFile,"DESCRIPTION"=>"") 
  );

$res = CIBlockElement::SetPropertyValuesEx($Element_ID, $IBlock_ID, array($PROPERTY_CODE => $PROPERTY_VALUE)); // Обновляем массив свойств типа файл
debug($res);
*/

/*
// get prop 
$IBlock_ID = 26;
$arElementFilter = array("ID" => array(1603, 1604));
$propertyFilter = array(57);
//$iterator = CIBlockElement::GetPropertyValues($IBlock_ID, array('ACTIVE' => 'Y'), true, array('ID' => array(57))); // work
$iterator = CIBlockElement::GetPropertyValues($IBlock_ID, $arElementFilter, true, $propertyFilter);
while ($row = $iterator->Fetch()) {
    debug($row);
}
$arSelect = array("*");
$arFilter = array('IBLOCK_ID' => $IBlock_ID);
$db_list = CIBlockElement::GetList(array(), $arFilter, false, array(), $arSelect);
$i = 1;
while ($ar_result = $db_list->GetNext()) {
    if ($i > 1) {
        break;
    }
    debug($ar_result);
    $i++;
}
//
*/
/*
$el = new CIBlockElement;

$IBlock_ID = 26;
$sectionId = 29;
$PROP = array();
$PROP[57] = $idFile;

$arLoadProductArray = array(
    "IBLOCK_ID" => $IBlock_ID,
    "IBLOCK_CODE" => "docs",
    "IBLOCK_SECTION_ID" => $sectionId,
    "PROPERTY_VALUES" => $PROP,
    "NAME" => $arfileName[4],
    "ACTIVE" => "Y",
    //'PREVIEW_TEXT' => $order_detail, // distribute
    //"DETAIL_TEXT" => $data['order'], // set order
);
$PRODUCT_ID = $el->Add($arLoadProductArray);
*/


// upload files
debug("start");

$IBlock_ID = 26;
$sectionId = 29;

//debug("start");
$dir = $_SERVER["DOCUMENT_ROOT"] . "/upload/files_part_2";
//debug($dir);

if (!scandir($dir)) {
    debug("error");
} else {
    $arfileName = scandir($dir);
    //debug($arfileName);
}
echo("amount files in folder : " . (count($arfileName)-2));
$arAddedItems = array();
foreach ($arfileName as $key => $fileName) {
    if ($key < 2) {
        continue;
    }
    $arFile = CFile::MakeFileArray($dir . "/" . $fileName);
    //debug($arFile);
    $arFile["del"] = false;
    $arFile["MODULE_ID"] = "iblock";
    $arFile["description"] = "описание файла";
    //debug($arFile);

    $idFile = CFile::SaveFile(
        $arFile,
        $savePath,
        false,
        false,
        $dirAdd = '',
        true
    );
    //debug($idFile);

    $el = new CIBlockElement;

    $PROP = array();
    $PROP[57] = $idFile;
    $mameEx = explode(".",$fileName);
    array_pop($mameEx);
    $name = implode(".",$mameEx);
    //debug($name);
    $arLoadProductArray = array(
        "IBLOCK_ID" => $IBlock_ID,
        "IBLOCK_CODE" => "docs",
        "IBLOCK_SECTION_ID" => $sectionId,
        "PROPERTY_VALUES" => $PROP,
        "NAME" => $name,
        "ACTIVE" => "Y",
        //'PREVIEW_TEXT' => $order_detail, // distribute
        //"DETAIL_TEXT" => $data['order'], // set order
    );
    $PRODUCT_ID = $el->Add($arLoadProductArray);
    //debug($PRODUCT_ID);
    $arAddedItems[] = $PRODUCT_ID;
}
debug("end");
debug("amount added items : " . count($arAddedItems));
// ---------------

?>
<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>