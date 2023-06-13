<?php
function debug($data)
{
    echo "<pre>" . print_r($data, 1) . "</pre>";
}

//$filename = 'folders.csv';
$filename = 'products.csv';

//$items = file($filename);
$items = file_get_contents($filename, $use_include_path = false, $context = null, $offset = 0, $maxlen = 4000000);


//$items = str_replace([PHP_EOL, "\n"], "|", $items);
//$items = htmlentities($items);
$items = preg_replace("/style=\"(.*?)\"/", '', $items);
$items = str_replace([PHP_EOL, "\n", "\t", "&nbsp;"], "", $items);
//$items = str_replace("\n", '|', $items);
//$items = str_replace('<', '', $items);
//$items = str_replace('>', '', $items);
$items = explode("|||", $items);

//debug($items);


$i = 0;
foreach ($items as $item) {
    //$item = trim($item);
    //$item = str_replace('""', '"', $item);
    //$item = preg_replace("/style=\"(.*?)\"/", '', $item);
    //debug($item);
    //$item = str_replace([PHP_EOL, "\t", "&nbsp;"], "", $item);
    //$item = preg_replace("/style=\"(.*?)\"/", '', $item);

    $arItem = str_getcsv($item, $separator = ";", $enclosure = "\"", $escape = '\\');
    if (count($arItem) != 242) {
        debug("error" . $i);
        debug($item);
        debug($arItem);
        return;
    }
    //debug($item); 

    if ($i >= 50) {
       // return;
    }
    $i++;
}

debug("end");
/*
//get the csv
$csvFile = file_get_contents('products.csv');
//separate each line
$csv = explode("\n", $csvFile);

$i = 0;
foreach ($csv as $csvLine) {

    //separet each fields
    $linkToInsert = explode(";", $csvLine);
    //echo what you need
    //$linkToInsert[0] would be the first field, $linkToInsert[1] second field, etc
    //echo '• ' . $linkToInsert[1] . '<br>';
    debug($linkToInsert);
    if ($i >= 50) {
        return;
    }
    $i++;
}
*/