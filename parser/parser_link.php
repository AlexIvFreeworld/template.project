<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

if(false){
    $filePath = 'link.txt';
    $items = file($filePath);
    debug(count($items));
    
    $origItems = array();
    foreach($items as $item){
        // debug($item);
        if(!in_array($item,$origItems)){
            $origItems[] = $item;
        }
    }
    
    debug(count($origItems));
    
    $fp = fopen('new_link.txt', 'a');
    foreach ($origItems as $str) {
        // debug($str);
        fwrite($fp, $str . PHP_EOL);
    }
    fclose($fp);   
}
debug("end");