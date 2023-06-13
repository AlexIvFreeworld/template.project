<?php
/**
 * Created by PhpStorm.
 * User: luda
 * Date: 21.09.2018
 * Time: 12:45
 */

echo 'For developer only';
die();

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
\Bitrix\Main\Loader::includeModule('iblock');


// Копии картин зарубежных художников
$filename = 'galery30_pictures (1).csv';
$IBLOCK_ID = '11';


$items = file($filename);
$ACTIVE = 'Y';
$SORT = '500';


function translit($s) {
    $s = (string) $s; // преобразуем в строковое значение
    $s = strip_tags($s); // убираем HTML-теги
    $s = str_replace(array("\n", "\r"), " ", $s); // убираем перевод каретки
    $s = preg_replace("/\s+/", ' ', $s); // удаляем повторяющие пробелы
    $s = trim($s); // убираем пробелы в начале и конце строки
    $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s); // переводим строку в нижний регистр (иногда надо задать локаль)
    $s = strtr($s, array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''));
    $s = preg_replace("/[^0-9a-z-_ ]/i", "", $s); // очищаем строку от недопустимых символов
    $s = str_replace(" ", "-", $s); // заменяем пробелы знаком минус
    return $s; // возвращаем результат
}



echo '<pre>';

// Копии картин зарубежных художников

$counter = 0;
$codes = [];
foreach ($items as $item) {
    $item = str_replace("&nbsp;"," ",$item);
    $item = str_replace("&quote;","'",$item);
    $item = str_replace('""','"',$item);
    $item = str_replace('"','',$item);
//    $item = str_replace("''","'",$item);
//    $item = str_replace("'","",$item);


    $item_arr=explode('|',$item);
    print_r ($item_arr);

    if ($item_arr[1] != 0){

        $counter += 1;

        $arFilter = array('IBLOCK_ID' => $IBLOCK_ID, 'UF_OLD_ID'=>$item_arr[1]);
        $rsSections = CIBlockSection::GetList(array(), $arFilter);
        if ($arSction = $rsSections->Fetch())
        {
            echo $arSction['ID'];
            echo '<br>';
        }

        $code = translit($item_arr[3]);
        if (in_array($code, $codes)) {
            $code = $code.$counter;
        }else{
            array_push($codes, $code);
        }

        $el = new CIBlockElement;

//        if (!$file_path){
            if (file_exists($_SERVER["DOCUMENT_ROOT"]."/parsers/images/".$item_arr[0].".jpg")) {
                $file_path = $_SERVER["DOCUMENT_ROOT"] . "/parsers/images/" . $item_arr[0] . ".jpg";
            }else{
                $file_path = $_SERVER["DOCUMENT_ROOT"] . "/parsers/images/" . $item_arr[0] . "_middle.jpg";
            }
//        }


        $PROP = array();
        $PROP[23] = $item_arr[0];
//        $PROP[14] = $item_arr[12];
        $arLoadProductArray  = Array(
            "ACTIVE" => $ACTIVE,
            "IBLOCK_SECTION_ID" => $arSction['ID'],
            "IBLOCK_ID" => $IBLOCK_ID,
            "NAME" => $item_arr[3],
            "CODE" => $code,
//            "PREVIEW_TEXT" => $item_arr[5],
//            "DETAIL_TEXT" => $detail_text,
//            "DETAIL_TEXT_TYPE"=>'html',
//            'DATE_ACTIVE_FROM' => $date,
            "PROPERTY_VALUES"=> $PROP,
            "DETAIL_PICTURE" => CFile::MakeFileArray($file_path),
            "PREVIEW_PICTURE" => CFile::MakeFileArray($file_path)
        );

//
        if($PRODUCT_ID = $el->Add($arLoadProductArray))
            echo "New ID: ".$PRODUCT_ID;
        else
            echo "Error: ".$el->LAST_ERROR;


    }


//    $bs = new CIBlockSection;
//    $arFields = Array(
//        "ACTIVE" => $ACTIVE,
//        "IBLOCK_SECTION_ID" => '',
//        "IBLOCK_ID" => $IBLOCK_ID,
//        "NAME" => $item_arr[3],
//        "SORT" => $SORT,
//        'CODE' => translit($item_arr[3]),
//        "UF_OLD_ID" => $item_arr[0]
////        "PICTURE" => $_FILES["PICTURE"],
////        "DESCRIPTION" => $DESCRIPTION,
////        "DESCRIPTION_TYPE" => $DESCRIPTION_TYPE
//    );

//
//    $ID = $bs->Add($arFields);
//    $res = ($ID>0);


//    if(!$res)
//        echo $bs->LAST_ERROR;

//echo stripos($item_arr[7] , "news/");
//    if(stripos($item_arr[7] , "newsw/") !== false){
//        print_r($item_arr);

//
////        $arFilter = array('IBLOCK_ID' => $IBLOCK_ID, 'UF_OLD_ID'=>$item_arr[1]);
////        $rsSections = CIBlockSection::GetList(array(), $arFilter);
////        if ($arSction = $rsSections->Fetch())
////        {
////            echo $arSction['ID'];
////            echo '<br>';
////        }
//
//        print_r($item_arr);
//        $code = translit($item_arr[4]);
//        if (in_array($code, $codes)) {
//            $code = $code.$counter;
//        }else{
//            array_push($codes, $code);
//        }
//
//        print_r($code);
//        echo '<br>';
//
//        $el = new CIBlockElement;
//        $PROP = array();
//        $PROP[26] = $item_arr[5];
//        $PROP[14] = $item_arr[12];
//        $PROP[15] = $item_arr[8];
//        $PROP[13] = 5;

//        $file_path = '';


//
//
//        $file = $_SERVER["DOCUMENT_ROOT"]."/parsers/newsw/".$item_arr[0].".html";
//        echo $file;
//        echo '<br>';
//        if (file_exists($file)) {
//            $detail_info = iconv("windows-1251", "utf-8", file_get_contents($file));
//
//            print_r($detail_info);
//            preg_match('@src="([^"]+)"@', $detail_info, $matches);
//
//            print_r($matches[1]);
//            $file_path = str_replace ('preview.', '', $matches[1]);
//            $detail_text =  str_replace ('http://www.gallery30.ru/data/pages', '/parsers', $detail_info);
//
/*            preg_match('/<img.*?>/si', $detail_text, $matches);*/
//
//            print_r($matches[0]);
//            $detail_text =  str_replace ($matches[0], '', $detail_text);
//
//        }
//
//        if (!$file_path){
//            if (file_exists($_SERVER["DOCUMENT_ROOT"]."/parsers/newsw/images/".$item_arr[0].".jpg")) {
//                $file_path = $_SERVER["DOCUMENT_ROOT"] . "/parsers/newsw/images/" . $item_arr[0] . ".jpg";
//            }else{
//                $file_path = $_SERVER["DOCUMENT_ROOT"] . "/parsers/newsw/images/" . $item_arr[0] . "_middle.jpg";
//            }
//        }
//
//
//        echo $file_path;
//        echo '<br>';
//
//        $date = date('d.m.Y',$item_arr[1]);
//        echo $date.'<br>';
//        $arLoadProductArray  = Array(
//            "ACTIVE" => $ACTIVE,
//            "IBLOCK_SECTION_ID" => '',//$arSction['ID'],
//            "IBLOCK_ID" => $IBLOCK_ID,
//            "NAME" => $item_arr[4],
//            "CODE" => $code,
//            "PREVIEW_TEXT" => $item_arr[5],
//            "DETAIL_TEXT" => $detail_text,
//            "DETAIL_TEXT_TYPE"=>'html',
//            'DATE_ACTIVE_FROM' => $date,
//            "PROPERTY_VALUES"=> $PROP,
//            "DETAIL_PICTURE" => CFile::MakeFileArray($file_path),
//            "PREVIEW_PICTURE" => CFile::MakeFileArray($file_path)
//        );
//
////
//        if($PRODUCT_ID = $el->Add($arLoadProductArray))
//            echo "New ID: ".$PRODUCT_ID;
//        else
//            echo "Error: ".$el->LAST_ERROR;
////    }
//    $counter += 1;
//    }
}
echo '</pre>';


//    $i=0;
//    while ($i < count($item_arr)) {
//        $item_arr[$i]= preg_replace('/(^"|"$)/', '', $item_arr[$i]);
//        $i++;
//    }
//
//    $arr_name=explode(' ',$item_arr[0]);
//    $arr_size=explode('х',$arr_name[2]);
//
//    // print_r($arr_size);exit;
//
//
//
//    /*$arSelect = Array("ID");
//    $arFilter = Array('IBLOCK_ID'=>$IBLOCK_ID, 'PROPERTY_OLD_ID' =>$item_arr['0']);
//    $db_list_el = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
//    $ar_element = $db_list_el->GetNext();
//    //print_r($ar_element['ID']);exit;
//    $section_id=array();
//    $i=0;
//    if(!empty($ar_element['ID'])){
//        //print_r($db_list_el); exit;
//        $parent_arr = explode(',',$item_arr[1]);
//        //$old_parent = max($parent_arr);
//        foreach($parent_arr as $old_parent){
//            $arFilter = Array('IBLOCK_ID'=>$IBLOCK_ID, 'GLOBAL_ACTIVE'=>'Y', 'UF_OLD_ID' => $old_parent);
//            $db_list = CIBlockSection::GetList(Array(), $arFilter, true);
//            //print_r($db_list);exit;
//            while($ar_result = $db_list->GetNext())
//            {
//                $section_id[$i] = $ar_result['ID'];
//            }
//            $i++;
//        }*/
//    //print_r($section_id);exit;
//    $el = new CIBlockElement;
//
//
//
//
//
//    //exit;
//
//
//
//    /* $parent_arr = explode (",", $item_arr['1']);
//    $parent_id = array_pop($parent_arr); */
//    $PROP = array();
//    if(count($arr_size) == 2){
//        $PROP['diameter'] = $arr_size['0'];
//        $PROP['thickness'] = $arr_size['1'];
//    }else{
//        $PROP['thickness'] = $arr_size['2'];
//        $PROP['width'] = $arr_size['0'];
//        $PROP['height'] = $arr_size['1'];
//    }
//    $PROP['GOST'] = $item_arr['1'];
//    $PROP['VPM'] = $item_arr['3'];
//    $PROP['metrag'] = $item_arr['2'];
//    $PROP['STARTSHOP_QUANTITY'] = 1;
//    $PROP['STARTSHOP_PRICE_1'] = str_replace(',','.',$item_arr['4']);
//
//
//    // print_r($PROP);exit;
//
//    $alias = translit($item_arr['0']);
//
//    $arLoadProductArray = Array(
//        //"MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
//        "IBLOCK_SECTION" => false,
//        "IBLOCK_ID"      => $IBLOCK_ID,
//        "NAME"           => $item_arr['0'],
//        "CODE" => $alias.$iii,
//        "ACTIVE"         => "Y",
//        "PROPERTY_VALUES"=> $PROP,
//    );
//
//    //print_r($arLoadProductArray);exit;
//    if($PRODUCT_ID = $el->Add($arLoadProductArray)){
//        echo "Update ID: ".$PRODUCT_ID;
//
//        /* $arFields = array(
//             "ID" => $PRODUCT_ID,
//             "VAT_ID" => 1, //выставляем тип ндс (задается в админке)
//             "VAT_INCLUDED" => "N" //НДС входит в стоимость
//         );
//
//         if(CCatalogProduct::Add($arFields))
//             echo "Добавили параметры товара к элементу каталога ".$item_arr['0'].'<br>';
//         else
//             echo 'Ошибка добавления параметров<br>';
//
//       /* 	$arFields = Array(
//                 "PRODUCT_ID" => $PRODUCT_ID,
//                 "CATALOG_GROUP_ID" => 1,
//                 "PRICE" => $item_arr['11'],
//                 "CURRENCY" => "RUB",
//                 "QUANTITY_FROM" => false,
//                 "QUANTITY_TO" => false
//             );
//
//
//             CPrice::Add($arFields); */
//    }else{
//        echo "Error: ".$el->LAST_ERROR;
//        echo $item_arr['0'];
//    }
//
//    /* }else{
//         echo 'Элемент уже загружен'.$ar_element['ID'];
//     }*/
//    $iii++;
//}
//
///** .......................................................
//@return string
//@desc Читает файл в строку
// **/
//function file2str($fileName){
//    $string='';
//    if(is_file($fileName)){
//        if($fd = @fopen($fileName, "r")){
//            $string .= @fread($fd, filesize($fileName));
//            fclose($fd);
//        }
//    }
//    return $string;
//}

?>