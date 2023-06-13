<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
\Bitrix\Main\Loader::includeModule('iblock');

if ($USER->IsAdmin()){
	// файл и скрипт кладем в корень сайта
    $filename = 'davidov_shop.csv';
    $items = file($filename);

    $ACTIVE = 'Y';
    $IBLOCK_ID = '4';

    $old_id="";
    $ar_gallery = array();
    foreach ($items as $item){
        $item = str_replace("&nbsp;"," ",$item);
        $item = str_replace("&quote;","'",$item);
        $item = str_replace('""','"',$item);

        $item_arr=explode(';',$item);

        $i=0;
        while ($i < count($item_arr)) {
            $item_arr[$i]= preg_replace('/(^"|"$)/', '', $item_arr[$i]);
            $i++;
        }
		
		if($item_arr[2]==1){
			$arSelect = Array("ID", "NAME");
			$arFilter = Array('IBLOCK_ID'=>$IBLOCK_ID, 'PROPERTY_OLD_ID'=>$item_arr[0]);
			
			$db_list = CIBlockElement::GetList(Array(), $arFilter, false, Array(), $arSelect);
			while($ar_result = $db_list->GetNext())
			{
				$PRODUCT_ID = $ar_result['ID'];
				
			}
			$brand = str_replace('"','',$item_arr[11]);
			
			CIBlockElement::SetPropertyValuesEx($PRODUCT_ID, $IBLOCK_ID, array('BRAND' => (int)$brand));
			
		}
		
        
    }
    exit;
}

?>