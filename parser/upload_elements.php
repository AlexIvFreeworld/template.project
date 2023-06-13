<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
\Bitrix\Main\Loader::includeModule('iblock');
//\Bitrix\Main\Loader::includeModule('catalog');
//if (CModule::IncludeModule("catalog")):

exit;
if ($USER->IsAdmin()){
    $filename = 'items.csv';
    $items = file($filename);
    /* $data = file_get_contents($filename);
    //$bookshelf = json_decode($data);
    $items = unserialize($data); */


    $ACTIVE = 'Y';
    $IBLOCK_ID = '37';



		//print_r($items);exit; 
    //
    $old_id="";
    $ar_gallery = array();
    foreach ($items as $item){
        $item = str_replace("&nbsp;"," ",$item);
        $item = str_replace("&quote;","'",$item);
        $item = str_replace('""','"',$item);

        $item_arr=explode(';;',$item);

        $i=0;
        while ($i < count($item_arr)) {
            $item_arr[$i]= preg_replace('/(^"|"$)/', '', $item_arr[$i]);
            $i++;
        }
		//print_r($item_arr[2]);
		if($item_arr[2]==1){
			
			$arFilter = Array('IBLOCK_ID'=>$IBLOCK_ID, 'GLOBAL_ACTIVE'=>'Y', 'UF_OLD_ID'=>$item_arr[1]);
			$db_list = CIBlockSection::GetList(Array(), $arFilter, true);
			while($ar_result = $db_list->GetNext())
			{
				$pid = $ar_result['ID'];
				//echo $ar_result['ID'].' '.$ar_result['NAME'].': '.$ar_result['ELEMENT_CNT'].'<br>';
			}
			$PROP = array();
			$el = new CIBlockElement;
			//print_r($item_arr[17]);exit;
			$price = str_replace('"','',$item_arr[17]);
			$PROP['PRICE'] = $price.' p.';
			$PROP['FILTER_PRICE'] = $price;
			//print_r($PROP);exit;
			$PROP['STATUS'] = 'instock';
			/* print_r($pid.'//');
			print_r($item_arr[1].'//');
			print_r($PROP);exit;  */
			$arParams = array("replace_space"=>"-","replace_other"=>"-");
			$trans = Cutil::translit($item_arr[3],"ru",$arParams);
			$arLoadProductArray = Array(
				//"MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
				"IBLOCK_SECTION_ID" => $pid,
				"IBLOCK_ID"      => $IBLOCK_ID,
				"PROPERTY_VALUES"=> $PROP,
				"NAME"           => $item_arr[3],
				"CODE"		   => $trans,
				"ACTIVE"         => "Y",
				'PREVIEW_TEXT' => $item_arr[4], 
				"PREVIEW_TEXT_TYPE" => "html"

			);
			
			if($PRODUCT_ID = $el->Add($arLoadProductArray)){

				echo "New ID: ".$PRODUCT_ID;

				
			}else{
				print_r($el->LAST_ERROR);
			}
			//print_r(333);exit;
		}
		//print_r($arLoadProductArray);exit; 
        
    }
    exit;
}
function create_element($name,$section,$IBLOCK_ID,$article,$brand,$brand_code,$quantity_text,$weight,$price){
    $el = new CIBlockElement;
    $PROP = array();

    //создание бренда
    $arSelect = Array("ID");
    $arFilter = Array('IBLOCK_ID'=>9, 'NAME' =>$brand);
    $db_list_el = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>50), $arSelect);
    //print_r($db_list_el);exit;
    if($ar_element = $db_list_el->GetNext()){
        $id_brand = $ar_element['ID'];
        //print_r($ar_element);exit;
    }else{
        $arParams = array("replace_space"=>"-","replace_other"=>"-");
        $trans_brand = Cutil::translit($brand,"ru",$arParams);

        $arLoadBrandArray = Array(
            //"MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_SECTION_ID" => "",
            "IBLOCK_ID"      => 9,
            "NAME"           => $brand,
            "CODE"		   => $trans_brand,
            "ACTIVE"         => "Y"

        );

        //print_r($arLoadProductArray);exit;
        if($Brand_ID = $el->Add($arLoadBrandArray)){
            echo "Brand ID: ".$Brand_ID;
            $id_brand = $Brand_ID;
        }
        //print_r($id_brand);exit;
    }
    /* print_r('хз');
    print_r($id_brand);exit; */


    // наличие
    //if($quantity_text == "Более 10 шт" || $quantity_text == "Более 10 шт"){
    if(strpos($quantity_text,"Более 10 шт") !== false){
        $quantity = 100;
        $stock = "18";
    }elseif($quantity_text == "Заказ"){
        $quantity = 0;
        $stock = "";
    }else{
        $quantity = (int)$quantity_text;
        $stock = "18";
        //print_r($quantity_text);exit;
    }

    $property_enums = CIBlockPropertyEnum::GetList(Array("DEF"=>"DESC", "SORT"=>"ASC"), Array("IBLOCK_ID"=>$IBLOCK_ID, "CODE"=>"IN_STOCK"));
    while($enum_fields = $property_enums->GetNext())
    {
        if ( $enum_fields["VALUE"] == "Y"){
            $stock_1 = $enum_fields["ID"];
            //print_r($enum_fields); exit;
        };
    }//exit;

    $PROP['CML2_ARTICLE'] = $article;
    //$PROP['BRAND'] = $brand;
    $PROP['BRAND'] = array(
        'n0'=>array(
            'VALUE'=>$id_brand
        ));
    $PROP['DRAND_CODE'] = $brand_code;

    //$PROP['IN_STOCK'] = Array("VALUE" => $stock_1 );

    //print_r($PROP);exit;
    //print_r($brand_code);exit;

    //print_r($PROP);exit;
    // генерируем символьный код
    $arParams = array("replace_space"=>"-","replace_other"=>"-");
    $trans = Cutil::translit($name.$article,"ru",$arParams);



    //print_r($quantity);exit;
    //обработка цены
    $price = str_replace(" ","",$price);

    $arLoadProductArray = Array(
        //"MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
        "IBLOCK_SECTION_ID" => $section,
        "IBLOCK_ID"      => $IBLOCK_ID,
        "PROPERTY_VALUES"=> $PROP,
        "NAME"           => $name,
        "CODE"		   => $trans,
        "ACTIVE"         => "Y"

    );

    //print_r($arLoadProductArray);exit;
    if($PRODUCT_ID = $el->Add($arLoadProductArray)){

        echo "New ID: ".$PRODUCT_ID;

        $arFields = array(
            "ID" => $PRODUCT_ID,
            "QUANTITY"	   => $quantity,
            "WEIGHT"	       => str_replace(",",".",$weight),
            "VAT_ID" => 1, //выставляем тип ндс (задается в админке)
            "VAT_INCLUDED" => "N" //НДС входит в стоимость
        );

        if(CCatalogProduct::Add($arFields))
            echo "Добавили параметры товара к элементу каталога ".$PRODUCT_ID.'-'.$quantity.'<br>';
        else
            echo 'Ошибка добавления параметров<br>';

        $arFields = Array(
            "PRODUCT_ID" => $PRODUCT_ID,
            "CATALOG_GROUP_ID" => 1,
            "PRICE" => $price,
            "CURRENCY" => "RUB",
            "QUANTITY_FROM" => false,
            "QUANTITY_TO" => false
        );


        CPrice::Add($arFields);
    }else{
        echo "Error: ".$el->LAST_ERROR;
        echo $article;
    }


}




/** .......................................................
@return string
@desc Читает файл в строку
 **/
function file2str($fileName){
    $string='';
    if(is_file($fileName)){
        if($fd = @fopen($fileName, "r")){
            $string .= @fread($fd, filesize($fileName));
            fclose($fd);
        }
    }
    return $string;
}

?>