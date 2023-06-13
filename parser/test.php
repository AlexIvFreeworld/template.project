<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
\Bitrix\Main\Loader::includeModule('iblock');
$APPLICATION->SetTitle("Стройакустика");

$data = array();

$Element_ID = 191;
$IBlock_ID = 12;
$PROPERTY_CODE = "PRODUCTS";
$PROPERTY_VALUE = array(
    0 => array("VALUE"=> 108),
    1 => array("VALUE"=> 112),
    2 => array("VALUE"=> 113), 
  );

$res = CIBlockElement::SetPropertyValuesEx($Element_ID, $IBlock_ID, array($PROPERTY_CODE => $PROPERTY_VALUE)); // Обновляем массив свойств типа файл
debug($res);
?>



<?php require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>