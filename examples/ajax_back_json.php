<?php require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php"); ?>
<?
$body = file_get_contents('php://input');
$body = json_decode($body);
\Bitrix\Main\Diag\Debug::dumpToFile(array('$body' => $body), "", "log.txt");

// response json
if($body->mode == "json"){
    $arRes = array(
        "result" => "0",
        "id" => '199',
        "sum" => "3999",
        "totalPos" => "23",
        "totalSum" => "399879" 
    );
    
    $res = json_encode($arRes);
    echo $res;
}
