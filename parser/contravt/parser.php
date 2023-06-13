<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
\Bitrix\Main\Loader::includeModule('iblock');
require_once 'phpQuery/phpQuery/phpQuery.php';


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
class Parser
{

    public static function getPage($params = [])
    {

        if ($params) {

            if (!empty($params["url"])) {

                $url = $params["url"];

                // Остальной код пишем тут
                $useragent      = !empty($params["useragent"]) ? $params["useragent"] : "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.182 Safari/537.36";
                $timeout        = !empty($params["timeout"]) ? $params["timeout"] : 5;
                $connecttimeout = !empty($params["connecttimeout"]) ? $params["connecttimeout"] : 5;
                $head           = !empty($params["head"]) ? $params["head"] : false;

                $cookie_file    = !empty($params["cookie"]["file"]) ? $params["cookie"]["file"] : false;
                $cookie_session = !empty($params["cookie"]["session"]) ? $params["cookie"]["session"] : false;

                $proxy_ip   = !empty($params["proxy"]["ip"]) ? $params["proxy"]["ip"] : false;
                $proxy_port = !empty($params["proxy"]["port"]) ? $params["proxy"]["port"] : false;
                $proxy_type = !empty($params["proxy"]["type"]) ? $params["proxy"]["type"] : false;

                $headers = !empty($params["headers"]) ? $params["headers"] : false;

                $post = !empty($params["post"]) ? $params["post"] : false;
                if ($cookie_file) {

                    file_put_contents(__DIR__ . "/" . $cookie_file, "");
                }

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, $url);

                // Далее продолжаем кодить тут
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_USERAGENT, $useragent);

                curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $connecttimeout);

                if ($head) {

                    curl_setopt($ch, CURLOPT_HEADER, true);
                    curl_setopt($ch, CURLOPT_NOBODY, true);
                }
                if (strpos($url, "https") !== false) {
                    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
                }
                if ($cookie_file) {

                    curl_setopt($ch, CURLOPT_COOKIEJAR, __DIR__ . "/" . $cookie_file);
                    curl_setopt($ch, CURLOPT_COOKIEFILE, __DIR__ . "/" . $cookie_file);

                    if ($cookie_session) {

                        curl_setopt($ch, CURLOPT_COOKIESESSION, true);
                    }
                }
                if ($proxy_ip && $proxy_port && $proxy_type) {

                    curl_setopt($ch, CURLOPT_PROXY, $proxy_ip . ":" . $proxy_port);
                    curl_setopt($ch, CURLOPT_PROXYTYPE, $proxy_type);
                }

                if ($headers) {

                    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
                }

                if ($post) {

                    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
                }
                curl_setopt($ch, CURLINFO_HEADER_OUT, true);

                $content = curl_exec($ch);
                $info = curl_getinfo($ch);

                $error = false;

                if ($content === false) {

                    $data = false;

                    $error["message"] = curl_error($ch);
                    $error["code"] = self::$error_codes[curl_errno($ch)];
                } else {

                    $data["content"] = $content;
                    $data["info"] = $info;
                }

                curl_close($ch);

                return [
                    "data"  => $data,
                    "error" => $error
                ];
            }
        }
        return false;
    }
    private static $error_codes = [
        "CURLE_UNSUPPORTED_PROTOCOL",
        "CURLE_FAILED_INIT",

        // Тут более 60 элементов, в архиве вы найдете весь список

        "CURLE_FTP_BAD_FILE_LIST",
        "CURLE_CHUNK_FAILED"
    ];
}
/*
$html = Parser::getPage([
    "url" => "https://combatmarkt.com/catalog",
    //"url" => "https://www.svyaznoy.ru/catalog",
]);
echo "<pre>";
print_r($html["data"]);
echo "</pre>";
*/


// catalog - <div class="nav-wrap"> <a> <li> <div class="subnav"> <li> <a> (html, href)
/*
$arLevel = array("name" => "", "url" => "", "arLevel" => array());
debug($arLevel);
$arStructure = array("level1" => $arLevel);
debug($arStructure);
*/

if (false) {
    $arStructure = array();
    $structure = ["id", "level1", "level2"];
    $dictionary = array();

    $arCsvCategory = array();
    $arCsvCategory[] = "folder_id; folder_name; level; hidden; noindex";
    $strCsvCategory = "";
    $counterCategory = 100;
    $arArticleOrigin = array();

    $catalog = file_get_contents("https://combatmarkt.com/catalog");
    $pqCatalog = phpQuery::newDocument($catalog);
    $htmlSpan = $pqCatalog->find("li.first_level a");
    $id = 0;
    $i = 0;
    foreach ($htmlSpan as $key => $a) {
        $a = pq($a);
        //debug($a->attr("href"));
        //$str = strval($a->attr("href"));
        //debug(strripos($a->attr("href"), "catalog"));

        if ($a->text() == "Новинки" || $a->text() == "Скидки%" || $a->text() == "Все товары категории") {
            continue;
            //debug("extra");
        }

        if (!strripos($a->attr("href"), "catalog")) {
            $span = $a->find("span");
            $span = pq($span);
            //debug($span->text());
            $folderName = $span->text();
            //debug($a->attr("href"));
            $countDev = substr_count($a->attr("href"), '/');
            //debug($countDev);
            $folderLevel = 1;
            $catalogName = (trim($a->attr("href"), "/") == "clinch") ? "clinch_3" : trim($a->attr("href"), "/");
            $dictionary += [$catalogName => $span->text()];
        } else {
            $arStrTemp = explode("/", $a->attr("href"));
            //debug($arStrTemp);
            //debug($a->attr("href"));
            $countDev = substr_count($a->attr("href"), '/');
            //debug($countDev);
            $folderLevel = $countDev - 2;
            //debug($a->text());
            $folderName = $a->text();
            //$strTemp = str_replace($arStrTemp[2], $dictionary[$arStrTemp[2]], $a->attr("href"));
            $catalogName = ($dictionary[$arStrTemp[2]] == "") ? "Прочее" : $dictionary[$arStrTemp[2]];
            $structure = $catalogName . "," . $a->text();
            $arStructure[] = ["category" => $structure, "href" => "https://combatmarkt.com" . $a->attr("href")];
            $id++;
        }
        $counterCategory++;
        $folderId = "r52" . strval($counterCategory);
        $strCsvCategory = $folderId . ";" . $folderName . ";" . $folderLevel . ";" . "0;" . "0";
        //debug($strCsvCategory);
        $arCsvCategory[] = $strCsvCategory;
        $i++;
        if ($i >= 2) {
            //break;
        }
    }
    //debug($dictionary);
    //debug($arStructure);
    //debug($arCsvCategory);
    phpQuery::unloadDocuments();


    /*
    $fp = fopen('ex_category_all.csv', 'w');
    foreach ($arCsvCategory as $str) {
        fwrite($fp, $str . PHP_EOL);
    }
    fclose($fp);
    debug("end");
    */

    set_time_limit(0);

    $arItemsGoods = array();
    for ($k = 0; $k < 26; $k++) {
        $hrefGoods = "https://combatmarkt.com/goods/?PAGEN_1=" . strval($k + 1);
        //$arItems = array();
        $good = file_get_contents($hrefGoods);
        $pqGood = phpQuery::newDocument($good);
        $aTitleGood = $pqGood->find("section.content ul.catalog-type2 a.title");
        //debug(count($aTitle));
        foreach ($aTitleGood as $titleGood) {
            $titleGood = pq($titleGood);
            //debug($title->attr("href"));
            $urlItemGood = "https://combatmarkt.com" . $titleGood->attr("href");
            $arItemsGoods[] = $urlItemGood;
        }
        phpQuery::unloadDocuments();
    }

    //debug($arItemsGoods);

    $arItemsOrig = array();
    foreach ($arStructure as $key => $structure) {
        $j = 0;
        $arItems = array();
        $item = file_get_contents($structure["href"]);
        $pqItem = phpQuery::newDocument($item);
        $aTitle = $pqItem->find("section.content a.title");
        //debug(count($aTitle));
        foreach ($aTitle as $title) {
            $title = pq($title);
            //debug($title->attr("href"));
            $urlItem = "https://combatmarkt.com" . $title->attr("href");
            if (in_array($urlItem, $arItemsOrig)) {
                //debug("is not orig");
                continue;
            }
            $arItemsOrig[] = $urlItem;
            $arItems[] = $urlItem;
            $j++;

            if ($j >= 15) {
                //break;
            }
            if (($keyGood = array_search($urlItem, $arItemsGoods)) !== FALSE) {
                unset($arItemsGoods[$keyGood]);
            }
        }
        $arStructure[$key] += ["arHrefItems" => $arItems];
        phpQuery::unloadDocuments();
    }

    $arStructure[] = ["category" => "Прочее", "href" => "", "arHrefItems" => $arItemsGoods];

    //debug($arItemsOrig);
    //debug($arItemsGoods);
    //debug($arStructure);


    $arGoods = array();
    $strScvTitle = "name : Название; vendor : Производитель; image : Иллюстрация; folder : Категория;  body : Описание; cf_size_table : Таблица размеров; is_kind : Модификация; cf_size_item : Размер общий; price : Цена; kind_id : ID; article : Артикул";
    $arGoods[] = $strScvTitle;
    $kindIdItem = 1400000000;
    foreach ($arStructure as $key => $structure) {
        $folderItem = $structure["category"];
        $vendorItem = "ADIDAS";
        foreach ($structure["arHrefItems"] as $href) {
            $strScv = "";
            $item = file_get_contents($href);
            $pq = phpQuery::newDocument($item);
            // get name
            $htmlH1 = $pq->find("div.page-title__col-left h1");
            $nameItem = trim($htmlH1->text());
            //debug($nameItem);
            // get images
            $imgSrc = $pq->find("img.image-cloudzoom");
            //debug(count($imgSrc));
            $imageItem = "";
            foreach ($imgSrc as $src) {
                $src = pq($src);
                $pathFile = $src->attr("src");

                $file = "https://combatmarkt.com" . $pathFile;
                $arEx = explode("/", $src->attr("src"));
                $newfile = "img/" . end($arEx);
                /*
                if (!copy($file, $newfile)) {
                    echo "не удалось скопировать $file...\n";
                }
               */
                $imageItem .= end($arEx) . ", ";
            }
            //debug($imageItem);
            // get article
            $spanArticle = $pq->find("span.article");
            $articleItem = $spanArticle->text();
            //debug($articleItem);

            // check
            if (in_array($articleItem, $arArticleOrigin)) {
                $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
                $addPartArticle = substr(str_shuffle($permitted_chars), 0, 5);
                $articleItem .= $addPartArticle;
            }
            $arArticleOrigin[] = $articleItem;
            // get description
            $htmlP1 = $pq->find("#tab-1 p:eq(1)");
            $bodyItemTrim = trim($htmlP1->text());
            // clear
            $bodyItem = str_replace([PHP_EOL, "\t"], " ", $bodyItemTrim);

            //debug($bodyItem);
            // get size_table

            $table = $pq->find("#tab-2 div.table-container");
            $sizeTable = $table->html();
            $strTableTrim = trim($sizeTable);
            $sizeTableItemStyle = preg_replace("/style=\"(.*?)\"/", '', $strTableTrim);
            $sizeTableItem = str_replace([PHP_EOL, "\t", "&nbsp;"], "", $sizeTableItemStyle);

            //debug($sizeTableItem);
            $strScv = $nameItem . ";" . $vendorItem . ";" . $imageItem . ";"  . $folderItem . ";" . $bodyItem . ";" . $sizeTableItem;
            // get is_kind
            $tdLink = $pq->find("div.table td.link");
            //debug(count($tdLink));
            $count = count($tdLink);
            for ($i = 0; $i < $count; $i++) {
                $strScvFinal = "";
                $tdLink = $pq->find("div.table td.link:eq(" . $i . ")");
                $sizeItem = trim($tdLink->text());
                //debug($sizeItem);

                $tdUncorr = $pq->find("div.table td.uncorr:eq(" . $i . ")");
                $priceItemStartUncorr = $tdUncorr->text();
                $priceItemExUncorr = explode(" ", $priceItemStartUncorr);
                $priceItemUncorr = $priceItemExUncorr[0];
                //debug($priceItemUncorr);

                $tdCorr = $pq->find("div.table td.corr:eq(" . $i . ") span");
                $priceItemStart = $tdCorr->text();
                $priceItemEx = explode(" ", $priceItemStart);
                $priceItem = $priceItemEx[0];
                //debug($priceItem);

                $priceItem = (!empty($priceItemUncorr)) ? $priceItemUncorr : $priceItem;

                $isKindItem = ($i == 0) ? "" : "*";
                $kindIdItem++;

                $articleItemKind = $articleItem . strval($i);

                $strScvFinal = $strScv . ";" . $isKindItem . ";" . $sizeItem . ";" . $priceItem . ";" . $kindIdItem . ";" . $articleItemKind;
                $arGoods[] = $strScvFinal;
            }
            // get price
            phpQuery::unloadDocuments();
        }
    }
    //debug($arGoods);
    $fp = fopen('goods_all_6.csv', 'w');
    foreach ($arGoods as $str) {
        fwrite($fp, $str . PHP_EOL);
    }
    fclose($fp);
    debug("end");

    /*
    $id = 0;
    $htmlA = $pqCatalog->find("li.first_level div.subnav a");
    foreach ($htmlA as $key => $a) {
    
        $a = pq($a);
        //debug($a->text());
        $structure = $id . $a->attr("href") . $a->text();
        $arStructure[] = $structure;
        $id++;
    }
    debug($arStructure);
    */
    // goods
    // name - <div class="page-title__col-left">  <h1>
    // description - <div class="tab-content" id="tab-1" style="display: block;"> <p> <ul> 
    // table - <div class="tab-content" id="tab-2" style="display: block;"> <tbody>

    $arGoods = array();
    //$arGoods = ["id", "level1", "level2", "name"];
    //$item = "";

    /*
    $fp = fopen('file.csv', 'w');
    fputcsv($fp, $arGoods);
    fclose($fp);
    */

    /*
    $item = file_get_contents("https://combatmarkt.com/goods/perchatki_bokserskie_clinch_kids_sine_serebristye/");
    //echo ($html);
    $pq = phpQuery::newDocument($item);
    $table = $pq->find("#tab-2 div.table-container");
    $descriptionItemTable = $table->html();
    $strTableTrim = trim($descriptionItemTable);
    $strTable = str_replace([PHP_EOL, 'style="border-collapse: collapse;"', 'style="text-align: center;"', 'style="font-family: Arial;"', "\t"], "", $strTableTrim);
    //$strTable = str_replace(";", "", $strTableEol);
    //$strTable = $strTableTrim; 
    //$strTable = preg_replace($patterns, $replacements, $strTableEol);
    debug($strTable);
    $str = "111111;" . $strTable . ";" . "2222222;";
    $fp = fopen('ex_clear_html.csv', 'w');
    fwrite($fp, $str . PHP_EOL);
    fclose($fp);
    */
    /*
    $tdLink = $pq->find("div.table td.link");
    //debug(count($tdLink));
    $count = count($tdLink);
    for ($i = 0; $i < $count; $i++) {
        $strScvFinal = "";
        $tdLink = $pq->find("div.table td.link:eq(" . $i . ")");
        $sizeItem = trim($tdLink->text());
        //debug($sizeItem);
    
        $tdUncorr = $pq->find("div.table td.uncorr:eq(" . $i . ")");
        $priceItemStartUncorr = $tdUncorr->text();
        $priceItemExUncorr = explode(" ", $priceItemStartUncorr);
        $priceItemUncorr = $priceItemExUncorr[0];
        //debug($priceItemUncorr);
    
        $tdCorr = $pq->find("div.table td.corr:eq(" . $i . ") span");
        $priceItemStart = $tdCorr->text();
        $priceItemEx = explode(" ", $priceItemStart);
        $priceItem = $priceItemEx[0];
        //debug($priceItem);
    
        $priceItem = (!empty($priceItemUncorr)) ? $priceItemUncorr : $priceItem;
        debug($priceItem);
    
        $isKindItem = ($i == 0) ? "" : "*";
        $strScvFinal = $strScv . ";" . $isKindItem . ";" . $sizeItem . ";" . $priceItem . ";";
        $arGoods[] = $strScvFinal;
    }
    */

    /*
    $tdLink = $pq->find("td.link");
    debug(count($tdLink));
    foreach ($tdLink as $link) {
        $link = pq($link);
        debug(trim($link->text()));
    }
    */
    /*
    $tdLink = $pq->find("div.table td.link");
    debug(count($tdLink));
    $count = count($tdLink);
    for ($i = 0; $i < $count; $i++) {
        $tdLink = $pq->find("div.table td.link:eq(" . $i . ")");
        $sizeItem = trim($tdLink->text());
        debug($sizeItem);
        $tdUncorr = $pq->find("div.table td.uncorr:eq(" . $i . ")");
        $priceItemStart = $tdUncorr->text();
        debug($priceItem);
        $priceItemEx = explode(" ", $priceItemStart);
        $priceItem = $priceItemEx[0];
        debug($priceItem);
    }
    */

    /*
    $priceItem = $tdUncorr->text();
    $arPriceItem = explode(" ", $priceItem);
    debug($arPriceItem[0]);
    
    
    $tdUncorr = $pq->find("td.uncorr:eq(1)");
    $priceItem = $tdUncorr->text();
    $arPriceItem = explode(" ", $priceItem);
    debug($arPriceItem[0]);
    
    
    $spanArticle = $pq->find("span.article");
    $article = $spanArticle->text();  
    debug($article);
    
    
    $htmlH1 = $pq->find("div.page-title__col-left h1");
    $htmlP1 = $pq->find("#tab-1 p:eq(1)");
    $htmlP2 = $pq->find("#tab-1 p:eq(2)");
    
    $htmlLi = $pq->find("#tab-1 li:eq(1)");
    $htmlUl = $pq->find("#tab-1 ul:eq(2)");
    $htmlUlLast = $pq->find("#tab-1 ul:eq(1)");
    
    $nameItem = trim($htmlH1->text());
    debug($nameItem);
    $descriptionItemP1 = trim($htmlP1->text());
    debug($descriptionItemP1);
    $descriptionItemP2 = trim($htmlP2->text());
    debug($descriptionItemP2);
    $descriptionItemUl = trim($htmlUl->text());
    debug($descriptionItemUl);
    //$descriptionItemLi = $htmlLi->html();
    //debug($descriptionItemLi);
    */


    /*
    $lis =  $pq->find("#tab-1 li");
    $tmp = [];
    
    foreach ($lis as $key => $li) {
    
        $li = pq($li);
    
        $tmp[$key] = [
            "text" => trim($li->text()),
            "url"  => trim($li->attr("href"))
        ];
    }
    
    //debug($tmp);
    $descriptionItemUlLast = $htmlUlLast->text();
    debug($descriptionItemUlLast);
    */

    /*
    $imgSrc = $pq->find("img.image-cloudzoom");
    debug(count($imgSrc));
    $strSrc = "";
    foreach($imgSrc as $src){
        $src = pq($src);
        $pathFile = $src->attr("src");
        $file = "https://combatmarkt.com" . $pathFile;
        $arEx = explode("/", $src->attr("src"));
        $newfile = "img/" . end($arEx);
        
        if (!copy($file, $newfile)) {
            echo "не удалось скопировать $file...\n";
        }
      
        $strSrc .= end($arEx) . ", ";
    }
    debug($strSrc);
    */

    /*
    debug($imgSrc->attr("src"));
    $pathFile = $imgSrc->attr("src");
    $file = "https://combatmarkt.com" . $pathFile;
    $numberFile = 1;
    $newfile = "img/src_" . $numberFile . ".jpg";
    
    if (!copy($file, $newfile)) {
        echo "не удалось скопировать $file...\n";
    }
    */

    /*
    foreach ($htmlLi as $li) {
        debug($li);
    }
    */
    /*
    foreach ($links as $link) {
        echo "<pre>";
        print_r($link);
        echo "</pre>"; 
    }
    */
    /*
    $text = array();
    $href = array();
    foreach ($links as $link) {
    
        $pqLink = pq($link); //pq делает объект phpQuery
    
        $text[] = $pqLink->html();
        $href[] = $pqLink->attr('href');
    }
    */
    /*
    echo "<pre>";
    print_r($text);
    echo "</pre>";
    echo "<pre>";
    print_r($href);
    echo "</pre>"; 
    */
}

if (false) {
    $html = '';
    $doc = file_get_contents("https://www.contravt.ru/production/schyotchiki-rele-vremeni-taymery/vosmirazryadniy-odnokanalniy-schyotchik-impulsov-erkon-1315");
    $pqCatalog = phpQuery::newDocument($doc);
    $arDiv = $pqCatalog->find("[data-class='tabs_content']");
    debug(count($arDiv));
    $arDiv2 = $pqCatalog->find("div.block_is");
    debug(count($arDiv2));
    foreach ($arDiv2 as $d) {
        // debug(pq($d)->html());
    }
    $arP = $pqCatalog->find("p.ca");
    debug(count($arP));
    foreach ($arP as $p) {
        if (pq($p)->text() == "Общие сведения") {
            $divParent = pq($p)->parent();
            $divParent->find("a")->remove();
            $arImg = $divParent->find("img");
            foreach ($arImg as $img) {
                // debug(pq($img)->attr("src"));
                $oldSrc = pq($img)->attr("src");
                $file = "https:" . $oldSrc;

                $newSrcEx = explode("/", $oldSrc);
                $countSrc = count($newSrcEx);
                $newSrc = "/upload/img_outside/" . $newSrcEx[$countSrc - 1];
                $newfile = "upload/img_outside/" . $newSrcEx[$countSrc - 1];

                // debug($newSrc);
                pq($img)->attr("src", $newSrc);

                // debug($file);
                // debug($newfile);
                if (!copy($file, $newfile)) {
                    echo "не удалось скопировать $file...\n";
                }
            }
            // debug(pq($divParent)->html());       
            $html = pq($divParent)->html();
        }
    }
    // $data = pq($arDiv)->html();
    // debug($html);

}
if (false) {
    $html = "";
    $doc = file_get_contents("https://www.contravt.ru/production/normirujushhie-izmeritelnye-preobrazovateli/pst-a-pro");
    $pqCatalog = phpQuery::newDocument($doc);
    $arP = $pqCatalog->find("p.ca");
    debug(count($arP));
    foreach ($arP as $p) {
        $pos = strpos(pq($p)->text(), "Технические характеристики");
        if ($pos !== false) {
            $divParent = pq($p)->parent();
            $html = pq($divParent)->html();
        }
    }
    debug($html);
}
if (false) {
    $html = "";
    $doc = file_get_contents("https://www.contravt.ru/production/normirujushhie-izmeritelnye-preobrazovateli/npsi-tp");
    $pqCatalog = phpQuery::newDocument($doc);
    $arP = $pqCatalog->find("div.fotorama-prod a");
    debug(count($arP));
    foreach ($arP as $key => $p) {
        $oldSrc = pq($p)->attr("href");
        $oldSrcEx = explode("?", $oldSrc);
        $file = "https://www.contravt.ru" . $oldSrcEx[0];

        $newSrcEx = explode("/", $oldSrc);
        $countSrc = count($newSrcEx);
        $newSrcEx = explode("?",  $newSrcEx[$countSrc - 1]);

        $newfile = "upload/icons_outside/" . $newSrcEx[1] . $newSrcEx[0];

        // debug($file);
        // debug($newfile);
        if (true) {
            if (!copy($file, $newfile)) {
                echo "не удалось скопировать $file...\n";
            }
        }
        if ($key == 0) {
            $file_path = $_SERVER["DOCUMENT_ROOT"] . "/" . $newfile;
            $el = new CIBlockElement;
            $arFieldsUp = array("DETAIL_PICTURE" => CFile::MakeFileArray($file_path), "PREVIEW_PICTURE" => CFile::MakeFileArray($file_path));
            $resUp = $el->Update(847, $arFieldsUp);
            debug($resUp);
        }
    }
}
if (false) {
    $html = "";
    $doc = file_get_contents("https://www.contravt.ru/production/barery-iskrozashchity/ka5003ex");
    $pqCatalog = phpQuery::newDocument($doc);
    $arP = $pqCatalog->find("img");
    debug(count($arP));
    $pos1Count = 0;
    $pos2Count = 0;
    foreach ($arP as $key => $p) {
        $pos1 = strpos(pq($p)->attr("alt"), "Функциональная схема");
        $pos2 = strpos(pq($p)->attr("alt"), "Схема подключения");
        if ($pos1 !== false && $pos1Count == 0) {
            debug("Функциональная схема");
            $oldSrc = pq($p)->attr("src");
            $file = "https://www.contravt.ru" . $oldSrc;

            $newSrcEx = explode("/", $oldSrc);
            $countSrc = count($newSrcEx);

            $newfile = "upload/schema_outside/" . $newSrcEx[$countSrc - 1];

            debug($file);
            debug($newfile);
            if (true) {
                if (!copy($file, $newfile)) {
                    echo "не удалось скопировать $file...\n";
                }
            }
            $blockId = 99;
            $PROP = array();
            $el = new CIBlockElement;
            $file_path = $_SERVER["DOCUMENT_ROOT"] . "/" . $newfile;
            // debug($arItem["pname"]);
            // debug($arSectionIdName[$arItem["pname"]]);
            $arLoadProductArray = array(
                //"MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
                "IBLOCK_SECTION_ID" => "",
                "IBLOCK_ID"      => $blockId,
                "PROPERTY_VALUES" => $PROP,
                "NAME"           => "847",
                "CODE"           => "847",
                "ACTIVE"         => "Y",
                'PREVIEW_TEXT' => "",
                "PREVIEW_TEXT_TYPE" => "html",
                "PREVIEW_PICTURE" => CFile::MakeFileArray($file_path),
                // "DETAIL_PICTURE" => CFile::MakeFileArray($file_path),
            );

            if (true) {
                if ($PRODUCT_ID = $el->Add($arLoadProductArray)) {
                    $PROPERTY_VALUE = array(
                        0 => array("VALUE" => $PRODUCT_ID),
                    );
                    $resSet = CIBlockElement::SetPropertyValuesEx(847, $IBLOCK_ID, array('FANCTIONAL_DIAGRAMS' => $PROPERTY_VALUE));
                    debug($resSet);
                    echo "New ID: " . $PRODUCT_ID;
                } else {
                    print_r($el->LAST_ERROR);
                }
            }


            $pos1Count++;
        }
        if ($pos2 !== false && $pos2Count == 0) {
            debug("Схема подключения");
            $oldSrc = pq($p)->attr("src");
            $file = "https://www.contravt.ru" . $oldSrc;

            $newSrcEx = explode("/", $oldSrc);
            $countSrc = count($newSrcEx);

            $newfile = "upload/schema_outside/" . $newSrcEx[$countSrc - 1];

            debug($file);
            debug($newfile);
            if (true) {
                if (!copy($file, $newfile)) {
                    echo "не удалось скопировать $file...\n";
                }
            }
            $blockId = 98;
            $PROP = array();
            $el = new CIBlockElement;
            $file_path = $_SERVER["DOCUMENT_ROOT"] . "/" . $newfile;
            // debug($arItem["pname"]);
            // debug($arSectionIdName[$arItem["pname"]]);
            $arLoadProductArray = array(
                //"MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
                "IBLOCK_SECTION_ID" => "",
                "IBLOCK_ID"      => $blockId,
                "PROPERTY_VALUES" => $PROP,
                "NAME"           => "847",
                "CODE"           => "847",
                "ACTIVE"         => "Y",
                'PREVIEW_TEXT' => "",
                "PREVIEW_TEXT_TYPE" => "html",
                "PREVIEW_PICTURE" => CFile::MakeFileArray($file_path),
                // "DETAIL_PICTURE" => CFile::MakeFileArray($file_path),
            );

            if (true) {
                if ($PRODUCT_ID = $el->Add($arLoadProductArray)) {
                    $PROPERTY_VALUE = array(
                        0 => array("VALUE" => $PRODUCT_ID),
                    );
                    $resSet = CIBlockElement::SetPropertyValuesEx(847, $IBLOCK_ID, array('DIAGRAMS' => $PROPERTY_VALUE));
                    debug($resSet);
                    echo "New ID: " . $PRODUCT_ID;
                } else {
                    print_r($el->LAST_ERROR);
                }
            }


            $pos2Count++;
        }
    }
}
if (false) {
    $html = "";
    $doc = file_get_contents("https://www.contravt.ru/production/normirujushhie-izmeritelnye-preobrazovateli/npsi-tp");
    $pqCatalog = phpQuery::newDocument($doc);
    $arP = $pqCatalog->find("a.link-passportpdf");
    debug(count($arP));
    foreach ($arP as $key => $p) {
        $oldSrc = pq($p)->attr("href");
        $file = "https://www.contravt.ru" . $oldSrc;

        $newSrcEx = explode("=", $oldSrc);
        $countSrc = count($newSrcEx);

        $newfile = "upload/docs_outside/" . $newSrcEx[$countSrc - 1] . ".pdf";

        debug($file);
        debug($newfile);
        if (true) {
            if (!copy($file, $newfile)) {
                echo "не удалось скопировать $file...\n";
            } else {
                $blockId = 66;
                $PROP = array();
                $el = new CIBlockElement;
                $file_path = $_SERVER["DOCUMENT_ROOT"] . "/" . $newfile;
                // debug($arItem["pname"]);
                // debug($arSectionIdName[$arItem["pname"]]);
                $arLoadProductArray = array(
                    //"MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
                    "IBLOCK_SECTION_ID" => "347",
                    "IBLOCK_ID"      => $blockId,
                    "PROPERTY_VALUES" => $PROP,
                    "NAME"           => "847",
                    "CODE"           => "847",
                    "ACTIVE"         => "Y",
                    'PREVIEW_TEXT' => "",
                    "PREVIEW_TEXT_TYPE" => "html",
                    "PREVIEW_PICTURE" => CFile::MakeFileArray($file_path),
                    // "DETAIL_PICTURE" => CFile::MakeFileArray($file_path),
                );

                if (true) {
                    if ($PRODUCT_ID = $el->Add($arLoadProductArray)) {
                        $PROPERTY_VALUE = array(
                            0 => array("VALUE" => $PRODUCT_ID),
                        );
                        $resSet = CIBlockElement::SetPropertyValuesEx(847, $IBLOCK_ID, array('FANCTIONAL_DIAGRAMS' => $PROPERTY_VALUE));
                        debug($resSet);
                        echo "New ID: " . $PRODUCT_ID;
                    } else {
                        print_r($el->LAST_ERROR);
                    }
                }
            }
        }
    }
}
if (false) {
    $html = "";
    $doc = file_get_contents("https://www.contravt.ru/production/barery-iskrozashchity/ka5003ex");
    $pqCatalog = phpQuery::newDocument($doc);
    $arP = $pqCatalog->find(".structlevels_files_wrap>div");
    debug(count($arP));
    $isBooklet = false;
    foreach ($arP as $key => $p) {
        $class = pq($p)->attr("class");
        debug($class);
        $obP = pq($p)->find("a div:q(1)");
        $class2 = pq($obP)->text();
        debug($class2);
        $pos = strpos($class2, "Эксплуатацион");
        if ($pos !== false && $class == "structlevel_1") {
            $isBooklet = true;
            debug("true");
        }
        if ($pos === false && $class == "structlevel_1") {
            $isBooklet = false;
            debug("false");
        }
        if ($isBooklet && $class == "structlevel_2") {
            debug("action");

            $oldSrc = pq($p)->find("a")->attr("href");
            $file = "https://www.contravt.ru" . $oldSrc;

            $newSrcEx = explode("=", $oldSrc);
            $countSrc = count($newSrcEx);

            $newfile = "upload/docs_outside/" . $newSrcEx[$countSrc - 1] . ".pdf";

            debug($file);
            debug($newfile);
            if (true) {
                if (!copy($file, $newfile)) {
                    echo "не удалось скопировать $file...\n";
                } else {
                    $blockId = 66;
                    $PROP = array();
                    $el = new CIBlockElement;
                    $file_path = $_SERVER["DOCUMENT_ROOT"] . "/" . $newfile;
                    $PROP["DOCUMENT"] = CFile::MakeFileArray($file_path);
                    // debug($arItem["pname"]);
                    // debug($arSectionIdName[$arItem["pname"]]);
                    $arLoadProductArray = array(
                        //"MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
                        "IBLOCK_SECTION_ID" => "347",
                        "IBLOCK_ID"      => $blockId,
                        "PROPERTY_VALUES" => $PROP,
                        "NAME"           => $class2 . "847",
                        "CODE"           => translit_sef($class2 . "847"),
                        "ACTIVE"         => "Y",
                        'PREVIEW_TEXT' => "",
                        "PREVIEW_TEXT_TYPE" => "html",
                        // "PREVIEW_PICTURE" => CFile::MakeFileArray($file_path),
                        // "DETAIL_PICTURE" => CFile::MakeFileArray($file_path),
                    );

                    if (true) {
                        $IBLOCK_ID = 95;
                        $countArProp = 0;
                        $arItemsLink = [];
                        $arSelect = array('ID', 'IBLOCK_ID', 'NAME', 'DATE_ACTIVE_FROM', 'PROPERTY_*');
                        $arFilter = array('IBLOCK_ID' => $IBLOCK_ID, "ID" => 847, 'ACTIVE' => 'Y');
                        $res = CIBlockElement::GetList(array(), $arFilter, false, array('nPageSize' => 1), $arSelect);
                        while ($ob = $res->GetNextElement()) {
                            $arFields = $ob->GetFields();
                            $arProps = $ob->GetProperties();
                            debug($arProps['OPER_DOCS_LINKS']["VALUE"]);
                            $arItemsLink = $arProps['OPER_DOCS_LINKS']["VALUE"];
                            // $countArProp = count($arProps['OPER_DOCS_LINKS']["VALUE"]);
                        }
                        // debug($countArProp);
                        if ($PRODUCT_ID = $el->Add($arLoadProductArray)) {
                            $PROPERTY_VALUE = array();
                            $countEl = 0;
                            foreach ($arItemsLink as $key => $arEl) {
                                $PROPERTY_VALUE += array($key => array("VALUE" => $arEl, "DESCRIPTION" => ""));
                                $countEl++;
                            }
                            $PROPERTY_VALUE += array(
                                $countEl => array("VALUE" => $PRODUCT_ID),
                            );
                            debug($PROPERTY_VALUE);

                            $resSet = CIBlockElement::SetPropertyValuesEx(847, $IBLOCK_ID, array('OPER_DOCS_LINKS' => $PROPERTY_VALUE));
                            debug($resSet);
                            echo "New ID: " . $PRODUCT_ID;
                        } else {
                            print_r($el->LAST_ERROR);
                        }
                    }
                }
            }
        }
    }
}

$arSectionIdName = [
    "Нормирующие преобразователи измерительные" => 319,
    "Барьеры искрозащиты (барьеры искробезопасности)" => 322,
    "Контроллеры, модули ввода-вывода" => 323,
    "Измерители-регуляторы технологические" => 324,
    "Регистраторы видеографические" => 325,
    "Счётчики, реле времени, таймеры" => 326,
    "Блоки питания и коммутационные устройства" => 327,
    "Программное обеспечение" => 328,
];
$blockId = 95;

if (false) {
    $arLists = CIBlockSection::GetList(
        array('SORT' => 'ASC'),
        array('IBLOCK_ID' => $blockId),
        false,
        array('NAME', 'CODE', 'ID')
    );
    while ($arList = $arLists->GetNext()) {
        // debug($arList);
        $arSectionIdName[$arList["NAME"]] = $arList["ID"];
    }
}

if (false) {
    $html = "";
    $doc = file_get_contents("offers.html");
    $pqCatalog = phpQuery::newDocument($doc);
    $arP = $pqCatalog->find("legend");
    debug(count($arP));
    foreach ($arP as $p) {
        $prName = pq($p)->text();
        $prName = trim($prName);
        $prName = str_replace([PHP_EOL], " ", $prName);
        $prName = str_replace(["\t", " ...", "+", "--"], "", $prName);
        debug($prName);
    }
}
if (false) {
    $html = "";
    $doc = file_get_contents("offers.html");
    $pqCatalog = phpQuery::newDocument($doc);
    $arP = $pqCatalog->find("form div.header");
    debug(count($arP));
    foreach ($arP as $p) {
        $prName = pq($p)->text();
        $prName = str_replace([PHP_EOL], " ", $prName);
        $prName = str_replace(["\t", " ...", "+", "--"], "", $prName);
        $prName = trim($prName);
        debug($prName);
    }
}
if (false) {
    $html = "";
    $doc = file_get_contents("offers.html");
    $pqCatalog = phpQuery::newDocument($doc);
    $arP = $pqCatalog->find("form fieldset");
    debug(count($arP));
    $arElemIds = [];
    foreach ($arP as  $key => $p) {
        if ($key == 0) {
            continue;
        }
        $prName = pq($p)->find("legend")->text();
        $prName = trim($prName);
        $prName = str_replace([PHP_EOL], " ", $prName);
        $prName = str_replace(["\t", " ...", "+", "--"], "", $prName);
        // debug($prName);
        $prNameEx = explode(" ", $prName);
        $partName = $prNameEx[1] . " " . $prNameEx[2] . "%";
        // debug($partName);
        $IBLOCK_ID = 95;
        $ELEM_ID = '';
        $arItemsLink = [];
        $arSelect4 = array('ID', 'IBLOCK_ID', 'NAME', 'DATE_ACTIVE_FROM', 'PROPERTY_*');
        $arFilter4 = array('IBLOCK_ID' => $IBLOCK_ID, "NAME" => $partName, 'ACTIVE' => 'Y');
        $res4 = CIBlockElement::GetList(array(), $arFilter4, false, array('nPageSize' => 1), $arSelect4);
        while ($ob4 = $res4->GetNextElement()) {
            $arFields4 = $ob4->GetFields();
            $ELEM_ID = $arFields4["ID"];
        }
        if (empty($ELEM_ID)) {
            debug("not found" . $prName);
            continue;
        }
        if (in_array($ELEM_ID, $arElemIds)) {
            debug("dubl id" . $ELEM_ID);
            continue;
        }
        $arElemIds[] = $ELEM_ID;

        pq($p)->find("tr.theader")->remove();
        $arTr = pq($p)->find("tr");
        foreach ($arTr as $tr) {

            $tdName = pq($tr)->find("td:q(1) span")->text();
            $tdName = trim($tdName);
            $tdName = str_replace([PHP_EOL], " ", $tdName);
            $tdName = str_replace(["\t", " ...", "+", "--"], "", $tdName);
            // debug($tdName);

            $tdPrice = pq($tr)->find("td.catbas_line_prod_price div")->text();
            $tdPrice = trim($tdPrice);
            $tdPrice = str_replace([PHP_EOL], " ", $tdPrice);
            $tdPrice = str_replace(["\t", " ...", "+", "--"], "", $tdPrice);
            // debug($tdPrice);
            if (false) {
                $arSelect3 = array('ID', 'IBLOCK_ID', 'NAME', 'DATE_ACTIVE_FROM', 'PROPERTY_*');
                $arFilter3 = array('IBLOCK_ID' => 67, "ID" => 2292, 'ACTIVE' => 'Y');
                $res3 = CIBlockElement::GetList(array(), $arFilter3, false, array('nPageSize' => 1), $arSelect3);
                while ($ob3 = $res3->GetNextElement()) {
                    $arFields3 = $ob3->GetFields();
                    $arProps3 = $ob3->GetProperties();
                    // debug($arProps3['MODIFICATION']);
                    // debug($arProps3['PRICE_CURRENCY']);
                }
            }

            $ibpenum = new CIBlockPropertyEnum;
            $PROPERTY_ID = 1598;
            if ($PropID = $ibpenum->Add(array('PROPERTY_ID' => $PROPERTY_ID, 'VALUE' => $tdName))) {
                debug('New ID:' . $PropID);
            }

            $blockId = 67;
            $PROP = array();
            $el = new CIBlockElement;
            // $file_path = $_SERVER["DOCUMENT_ROOT"] . "/" . $newfile;
            // $PROP["DOCUMENT"] = CFile::MakeFileArray($file_path);
            $PROP["PRICE"] = $tdPrice;
            $PROP["MODIFICATION"]["VALUE_ENUM_ID"] = $PropID;
            $PROP["PRICE_CURRENCY"]["VALUE_ENUM_ID"] = "377";
            // debug($arItem["pname"]);
            // debug($arSectionIdName[$arItem["pname"]]);
            $arLoadProductArray = array(
                //"MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
                "IBLOCK_SECTION_ID" => "",
                "IBLOCK_ID"      => $blockId,
                "PROPERTY_VALUES" => $PROP,
                "NAME"           => $tdName,
                "CODE"           => translit_sef($tdName),
                "ACTIVE"         => "Y",
                'PREVIEW_TEXT' => "",
                "PREVIEW_TEXT_TYPE" => "html",
                // "PREVIEW_PICTURE" => CFile::MakeFileArray($file_path),
                // "DETAIL_PICTURE" => CFile::MakeFileArray($file_path),
            );

            if (true) {
                // $countArProp = 0;
                $arItemsLink = [];
                $arSelect2 = array('ID', 'IBLOCK_ID', 'NAME', 'DATE_ACTIVE_FROM', 'PROPERTY_*');
                $arFilter2 = array('IBLOCK_ID' => $IBLOCK_ID, "ID" => $ELEM_ID, 'ACTIVE' => 'Y');
                $res2 = CIBlockElement::GetList(array(), $arFilter2, false, array('nPageSize' => 1), $arSelect2);
                while ($ob2 = $res2->GetNextElement()) {
                    $arFields2 = $ob2->GetFields();
                    $arProps2 = $ob2->GetProperties();
                    // debug($arProps['OPER_DOCS_LINKS']["VALUE"]);
                    $arItemsLink = $arProps2['LINK_SKU']["VALUE"];
                    // $countArProp = count($arProps['OPER_DOCS_LINKS']["VALUE"]);
                }
                // debug($countArProp);
                if ($PRODUCT_ID = $el->Add($arLoadProductArray)) {
                    $PROPERTY_VALUE = array();
                    $countEl = 0;
                    foreach ($arItemsLink as $key => $arEl) {
                        $PROPERTY_VALUE += array($key => array("VALUE" => $arEl, "DESCRIPTION" => ""));
                        $countEl++;
                    }
                    $PROPERTY_VALUE += array(
                        $countEl => array("VALUE" => $PRODUCT_ID),
                    );
                    // debug($PROPERTY_VALUE);

                    $resSet = CIBlockElement::SetPropertyValuesEx($ELEM_ID, $IBLOCK_ID, array('LINK_SKU' => $PROPERTY_VALUE));
                    debug($resSet);
                    echo "New ID: " . $PRODUCT_ID;
                } else {
                    print_r($el->LAST_ERROR);
                }
            }
        }
    }
}


// debug($arSectionIdName);


if (false) {
    set_time_limit(0);
    $structure = [];
    $host = "https://www.contravt.ru";
    $catalog = file_get_contents("https://www.contravt.ru/production");
    $pqCatalog = phpQuery::newDocument($catalog);
    // debug($pqCatalog);
    $blockSection = $pqCatalog->find(".list_table_std");
    $arLinkLevel_1 = $blockSection->find(".cell_standart_icon_text a.menuchilds");
    // debug($arLinkLevel_1);
    $id = 0;
    $count1 = 0;
    foreach ($arLinkLevel_1 as $key => $a) {
        if ($count1 > 20) {
            break;
        }
        $code = "lev1_" . $id;
        $a = pq($a);
        // debug($a->attr("href"));
        // debug($a->text());
        $arItem = ["code" => $code, "name" => $a->text(), "link" => $a->attr("href")];
        $structure[] = $arItem;
        $id++;
        $count1++;
    }
    phpQuery::unloadDocuments();
    // debug($structure);
    $arrItems = [];
    foreach ($structure as $i => $arItem) {
        // debug($arItem["link"]);
        $catalog = file_get_contents($host . $arItem["link"]);
        $pqCatalog = phpQuery::newDocument($catalog);
        $blockSection = $pqCatalog->find(".list_table_std");
        $arLinkLevel_2 = $blockSection->find(".cell_standart_icon_text a.menuchilds");
        $count2 = 0;
        foreach ($arLinkLevel_2 as $key => $a) {
            if ($count2 > 1000) {
                break;
            }
            $code = "lev2_" . $id;
            $a = pq($a);
            // debug($a->attr("href"));
            // debug($a->text());
            if (!empty($a->text())) {
                $arItem2 = ["pname" => trim($arItem["name"]), "cname" => translit_sef($a->text()), "code" => $code, "name" => $a->text(), "link" => $a->attr("href")];
                $structure[$i]["child"][] = $arItem2;
                $arrItems[] = $arItem2;
            }
            $id++;
            $count2++;
        }
        phpQuery::unloadDocuments();
    }


    $count3 = 0;
    foreach ($arrItems as $i => $arItem) {
        if ($count3 > 1000) {
            break;
        }
        $PROP = array();
        $el = new CIBlockElement;
        $PROP['LINK_OLD_PAGE'] = $arItem["link"];
        // debug($arItem["pname"]);
        // debug($arSectionIdName[$arItem["pname"]]);
        if (empty($arSectionIdName[$arItem["pname"]])) {
            debug("err" . $arItem["pname"]);
        }
        $arLoadProductArray = array(
            //"MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
            "IBLOCK_SECTION_ID" => $arSectionIdName[$arItem["pname"]],
            "IBLOCK_ID"      => $blockId,
            "PROPERTY_VALUES" => $PROP,
            "NAME"           => $arItem["name"],
            "CODE"           => $arItem["cname"],
            "ACTIVE"         => "Y",
            'PREVIEW_TEXT' => "",
            "PREVIEW_TEXT_TYPE" => "html"
        );
        if (true) {
            if ($PRODUCT_ID = $el->Add($arLoadProductArray)) {

                echo "New ID: " . $PRODUCT_ID;
            } else {
                print_r($el->LAST_ERROR);
            }
        }

        $count3++;
    }
    // debug($arrItems);

}
if (false) {
    $host = "https://www.contravt.ru";
    $IBLOCK_ID = 95;
    $arSelect = array('ID', 'IBLOCK_ID', 'NAME', 'DATE_ACTIVE_FROM', 'PROPERTY_*');
    $arFilter = array('IBLOCK_ID' => $IBLOCK_ID, 'ACTIVE' => 'Y');
    $res = CIBlockElement::GetList(array(), $arFilter, false, array('nPageSize' => 1000), $arSelect);
    while ($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();
        // debug($arFields);
        $arProps = $ob->GetProperties();
        // debug($arProps);
        $html = '';
        $link = $host . $arProps["LINK_OLD_PAGE"]["VALUE"];
        // debug($link);
        $doc = file_get_contents($host . $arProps["LINK_OLD_PAGE"]["VALUE"]);
        $pqCatalog = phpQuery::newDocument($doc);
        $arP = $pqCatalog->find("p.ca");
        // debug(count($arP));
        foreach ($arP as $p) {
            if (pq($p)->text() == "Общие сведения") {
                $divParent = pq($p)->parent();
                $divParent->find("a")->remove();
                $arImg = $divParent->find("img");
                foreach ($arImg as $img) {
                    // debug(pq($img)->attr("src"));
                    $oldSrc = pq($img)->attr("src");
                    $file = "https:" . $oldSrc;

                    $newSrcEx = explode("/", $oldSrc);
                    $countSrc = count($newSrcEx);
                    $newSrc = "/upload/img_outside/" . $newSrcEx[$countSrc - 1];
                    $newfile = "upload/img_outside/" . $newSrcEx[$countSrc - 1];

                    // debug($newSrc);
                    pq($img)->attr("src", $newSrc);

                    // debug($file);
                    // debug($newfile);
                    if (false) {
                        if (!copy($file, $newfile)) {
                            echo "не удалось скопировать $file...\n";
                        }
                    }
                }
                // debug(pq($divParent)->html());    
                // /production/normirujushhie-izmeritelnye-preobrazovateli/npsi-tp
                $html = pq($divParent)->html();
                if (true) {
                    $el = new CIBlockElement;
                    $arFieldsUp = array("DETAIL_TEXT_TYPE" => "html", "~DETAIL_TEXT_TYPE" => "html", "DETAIL_TEXT" => $html);
                    $resUp = $el->Update(intval($arFields['ID']), $arFieldsUp);
                    // debug($resUp);
                    if ($resUp != 1) {
                        debug("err update" . $arFields['ID']);
                    }
                    if (false) {
                        $resSet = CIBlockElement::SetPropertyValuesEx($arFields["ID"], $IBLOCK_ID, array('DETAIL_TEXT' => $html));
                        debug($resSet);
                    }
                }
            }
        }
        // $data = pq($arDiv)->html();
        // debug($html);
    }
}
if (false) {
    $host = "https://www.contravt.ru";
    $IBLOCK_ID = 95;
    $arSelect = array('ID', 'IBLOCK_ID', 'NAME', 'DATE_ACTIVE_FROM', 'PROPERTY_*');
    $arFilter = array('IBLOCK_ID' => $IBLOCK_ID, 'ACTIVE' => 'Y');
    $res = CIBlockElement::GetList(array(), $arFilter, false, array('nPageSize' => 1000), $arSelect);
    while ($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();
        // debug($arFields);
        $arProps = $ob->GetProperties();
        // debug($arProps);
        $html = '';
        $link = $host . $arProps["LINK_OLD_PAGE"]["VALUE"];
        // debug($link);
        $doc = file_get_contents($host . $arProps["LINK_OLD_PAGE"]["VALUE"]);
        $pqCatalog = phpQuery::newDocument($doc);
        $arP = $pqCatalog->find("p.ca");
        // debug(count($arP));
        foreach ($arP as $p) {
            $pos = strpos(pq($p)->text(), "Технические характеристики");
            if ($pos !== false) {
                $divParent = pq($p)->parent();
                $html = pq($divParent)->html();
                if (true) {
                    $resSet = CIBlockElement::SetPropertyValuesEx($arFields["ID"], $IBLOCK_ID, array('CHARACTERISTICS_ELEM' => $html));
                    debug($resSet);
                }
            }
        }
        // $data = pq($arDiv)->html();
        // debug($html);
    }
}
if (false) {
    $host = "https://www.contravt.ru";
    $IBLOCK_ID = 95;
    $arSelect = array('ID', 'IBLOCK_ID', 'NAME', 'DATE_ACTIVE_FROM', 'PROPERTY_*');
    $arFilter = array('IBLOCK_ID' => $IBLOCK_ID, 'ACTIVE' => 'Y');
    $res = CIBlockElement::GetList(array(), $arFilter, false, array('nPageSize' => 1000), $arSelect);
    while ($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();
        // debug($arFields);
        $arProps = $ob->GetProperties();
        // debug($arProps);
        $html = '';
        $link = $host . $arProps["LINK_OLD_PAGE"]["VALUE"];
        // debug($link);
        $doc = file_get_contents($host . $arProps["LINK_OLD_PAGE"]["VALUE"]);
        $pqCatalog = phpQuery::newDocument($doc);
        $arP = $pqCatalog->find("div.fotorama-prod a");
        // debug(count($arP));
        foreach ($arP as $key => $p) {
            $oldSrc = pq($p)->attr("href");
            $oldSrcEx = explode("?", $oldSrc);
            $file = "https://www.contravt.ru" . $oldSrcEx[0];

            $newSrcEx = explode("/", $oldSrc);
            $countSrc = count($newSrcEx);
            $newSrcEx = explode("?",  $newSrcEx[$countSrc - 1]);

            $newfile = "upload/icons_outside/" . $newSrcEx[1] . $newSrcEx[0];

            // debug($file);
            // debug($newfile);
            if (true) {
                if (!copy($file, $newfile)) {
                    echo "не удалось скопировать $file...\n";
                }
            }
            if ($key == 0) {
                $file_path = $_SERVER["DOCUMENT_ROOT"] . "/" . $newfile;
                $el = new CIBlockElement;
                $arFieldsUp = array("DETAIL_PICTURE" => CFile::MakeFileArray($file_path), "PREVIEW_PICTURE" => CFile::MakeFileArray($file_path));
                $resUp = $el->Update($arFields["ID"], $arFieldsUp);
                debug($resUp);
            }
        }
    }
}
if (false) {
    $host = "https://www.contravt.ru";
    $IBLOCK_ID = 95;
    $arSelect = array('ID', 'IBLOCK_ID', 'NAME', 'DATE_ACTIVE_FROM', 'PROPERTY_*');
    $arFilter = array('IBLOCK_ID' => $IBLOCK_ID, 'ACTIVE' => 'Y');
    $res = CIBlockElement::GetList(array(), $arFilter, false, array('nPageSize' => 1000), $arSelect);
    while ($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();
        // debug($arFields);
        $arProps = $ob->GetProperties();
        // debug($arProps);
        $html = '';
        $link = $host . $arProps["LINK_OLD_PAGE"]["VALUE"];
        // debug($link);
        $doc = file_get_contents($host . $arProps["LINK_OLD_PAGE"]["VALUE"]);
        $pqCatalog = phpQuery::newDocument($doc);

        $arP = $pqCatalog->find("img");
        // debug(count($arP));
        $pos1Count = 0;
        $pos2Count = 0;
        foreach ($arP as $key => $p) {
            $pos1 = strpos(pq($p)->attr("alt"), "Функциональная схема");
            $pos2 = strpos(pq($p)->attr("alt"), "Схема подключения");
            if ($pos1 !== false && $pos1Count == 0) {
                // debug("Функциональная схема");
                $oldSrc = pq($p)->attr("src");
                $file = "https://www.contravt.ru" . $oldSrc;

                $newSrcEx = explode("/", $oldSrc);
                $countSrc = count($newSrcEx);

                $newfile = "upload/schema_outside/" . $newSrcEx[$countSrc - 1];

                // debug($file);
                // debug($newfile);
                if (true) {
                    if (!copy($file, $newfile)) {
                        echo "не удалось скопировать $file...\n";
                    }
                }
                $blockId = 99;
                $PROP = array();
                $el = new CIBlockElement;
                $file_path = $_SERVER["DOCUMENT_ROOT"] . "/" . $newfile;
                // debug($arItem["pname"]);
                // debug($arSectionIdName[$arItem["pname"]]);
                $arLoadProductArray = array(
                    //"MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
                    "IBLOCK_SECTION_ID" => "",
                    "IBLOCK_ID"      => $blockId,
                    "PROPERTY_VALUES" => $PROP,
                    "NAME"           => $arFields["NAME"],
                    "CODE"           => $arFields["CODE"],
                    "ACTIVE"         => "Y",
                    'PREVIEW_TEXT' => "",
                    "PREVIEW_TEXT_TYPE" => "html",
                    "PREVIEW_PICTURE" => CFile::MakeFileArray($file_path),
                    // "DETAIL_PICTURE" => CFile::MakeFileArray($file_path),
                );

                if (true) {
                    if ($PRODUCT_ID = $el->Add($arLoadProductArray)) {
                        $PROPERTY_VALUE = array(
                            0 => array("VALUE" => $PRODUCT_ID),
                        );
                        $resSet = CIBlockElement::SetPropertyValuesEx($arFields["ID"], $IBLOCK_ID, array('FANCTIONAL_DIAGRAMS' => $PROPERTY_VALUE));
                        debug($resSet);
                        echo "New ID: " . $PRODUCT_ID;
                    } else {
                        print_r($el->LAST_ERROR);
                    }
                }


                $pos1Count++;
            }
            if ($pos2 !== false && $pos2Count == 0) {
                // debug("Схема подключения");
                $oldSrc = pq($p)->attr("src");
                $file = "https://www.contravt.ru" . $oldSrc;

                $newSrcEx = explode("/", $oldSrc);
                $countSrc = count($newSrcEx);

                $newfile = "upload/schema_outside/" . $newSrcEx[$countSrc - 1];

                // debug($file);
                // debug($newfile);
                if (true) {
                    if (!copy($file, $newfile)) {
                        echo "не удалось скопировать $file...\n";
                    }
                }
                $blockId = 98;
                $PROP = array();
                $el = new CIBlockElement;
                $file_path = $_SERVER["DOCUMENT_ROOT"] . "/" . $newfile;
                // debug($arItem["pname"]);
                // debug($arSectionIdName[$arItem["pname"]]);
                $arLoadProductArray = array(
                    //"MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
                    "IBLOCK_SECTION_ID" => "",
                    "IBLOCK_ID"      => $blockId,
                    "PROPERTY_VALUES" => $PROP,
                    "NAME"           => $arFields["NAME"],
                    "CODE"           => $arFields["CODE"],
                    "ACTIVE"         => "Y",
                    'PREVIEW_TEXT' => "",
                    "PREVIEW_TEXT_TYPE" => "html",
                    "PREVIEW_PICTURE" => CFile::MakeFileArray($file_path),
                    // "DETAIL_PICTURE" => CFile::MakeFileArray($file_path),
                );

                if (true) {
                    if ($PRODUCT_ID = $el->Add($arLoadProductArray)) {
                        $PROPERTY_VALUE = array(
                            0 => array("VALUE" => $PRODUCT_ID),
                        );
                        $resSet = CIBlockElement::SetPropertyValuesEx($arFields["ID"], $IBLOCK_ID, array('DIAGRAMS' => $PROPERTY_VALUE));
                        debug($resSet);
                        echo "New ID: " . $PRODUCT_ID;
                    } else {
                        print_r($el->LAST_ERROR);
                    }
                }


                $pos2Count++;
            }
        }
    }
}
if (false) {
    $host = "https://www.contravt.ru";
    $IBLOCK_ID = 95;
    $arSelect = array('ID', 'IBLOCK_ID', 'NAME', 'DATE_ACTIVE_FROM', 'PROPERTY_*');
    $arFilter = array('IBLOCK_ID' => $IBLOCK_ID, 'ACTIVE' => 'Y');
    $res = CIBlockElement::GetList(array(), $arFilter, false, array('nPageSize' => 1000), $arSelect);
    while ($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();
        // debug($arFields);
        $arProps = $ob->GetProperties();
        // debug($arProps);
        $html = '';
        $link = $host . $arProps["LINK_OLD_PAGE"]["VALUE"];
        // debug($link);
        $doc = file_get_contents($host . $arProps["LINK_OLD_PAGE"]["VALUE"]);
        $pqCatalog = phpQuery::newDocument($doc);

        $arP = $pqCatalog->find(".structlevels_files_wrap>div");
        // debug(count($arP));
        $isBooklet = false;
        foreach ($arP as $key => $p) {
            $class = pq($p)->attr("class");
            // debug($class);
            $obP = pq($p)->find("a div:q(1)");
            $class2 = pq($obP)->text();
            // debug($class2);
            $pos = strpos($class2, "Статьи");
            if ($pos !== false && $class == "structlevel_1") {
                $isBooklet = true;
                // debug("true");
            }
            if ($pos === false && $class == "structlevel_1") {
                $isBooklet = false;
                // debug("false");
            }
            if ($isBooklet && $class == "structlevel_2") {
                // debug("action");

                $oldSrc = pq($p)->find("a")->attr("href");
                $file = "https://www.contravt.ru" . $oldSrc;

                $newSrcEx = explode("=", $oldSrc);
                $countSrc = count($newSrcEx);

                $newfile = "upload/docs_outside/" . $newSrcEx[$countSrc - 1] . ".pdf";

                // debug($file);
                // debug($newfile);
                if (true) {
                    if (!copy($file, $newfile)) {
                        debug("не удалось скопировать " . $file . " for " . $arFields["ID"]);
                    } else {
                        $blockId = 66;
                        $PROP = array();
                        $el = new CIBlockElement;
                        $file_path = $_SERVER["DOCUMENT_ROOT"] . "/" . $newfile;
                        $PROP["DOCUMENT"] = CFile::MakeFileArray($file_path);
                        // debug($arItem["pname"]);
                        // debug($arSectionIdName[$arItem["pname"]]);
                        $arLoadProductArray = array(
                            //"MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
                            "IBLOCK_SECTION_ID" => "349",
                            "IBLOCK_ID"      => $blockId,
                            "PROPERTY_VALUES" => $PROP,
                            "NAME"           => $class2,
                            "CODE"           => translit_sef($class2 . explode(" ", $arFields["NAME"])[0] . " " . $arFields["ID"]),
                            "ACTIVE"         => "Y",
                            'PREVIEW_TEXT' => "",
                            "PREVIEW_TEXT_TYPE" => "html",
                            // "PREVIEW_PICTURE" => CFile::MakeFileArray($file_path),
                            // "DETAIL_PICTURE" => CFile::MakeFileArray($file_path),
                        );

                        if (true) {
                            // $countArProp = 0;
                            $arItemsLink = [];
                            $arSelect2 = array('ID', 'IBLOCK_ID', 'NAME', 'DATE_ACTIVE_FROM', 'PROPERTY_*');
                            $arFilter2 = array('IBLOCK_ID' => $IBLOCK_ID, "ID" => $arFields["ID"], 'ACTIVE' => 'Y');
                            $res2 = CIBlockElement::GetList(array(), $arFilter2, false, array('nPageSize' => 1), $arSelect2);
                            while ($ob2 = $res2->GetNextElement()) {
                                $arFields2 = $ob2->GetFields();
                                $arProps2 = $ob2->GetProperties();
                                // debug($arProps['OPER_DOCS_LINKS']["VALUE"]);
                                $arItemsLink = $arProps2['ARTICLE_LINKS']["VALUE"];
                                // $countArProp = count($arProps['OPER_DOCS_LINKS']["VALUE"]);
                            }
                            // debug($countArProp);
                            if ($PRODUCT_ID = $el->Add($arLoadProductArray)) {
                                $PROPERTY_VALUE = array();
                                $countEl = 0;
                                foreach ($arItemsLink as $key => $arEl) {
                                    $PROPERTY_VALUE += array($key => array("VALUE" => $arEl, "DESCRIPTION" => ""));
                                    $countEl++;
                                }
                                $PROPERTY_VALUE += array(
                                    $countEl => array("VALUE" => $PRODUCT_ID),
                                );
                                // debug($PROPERTY_VALUE);

                                $resSet = CIBlockElement::SetPropertyValuesEx($arFields["ID"], $IBLOCK_ID, array('ARTICLE_LINKS' => $PROPERTY_VALUE));
                                debug($resSet);
                                echo "New ID: " . $PRODUCT_ID;
                            } else {
                                print_r($el->LAST_ERROR);
                            }
                        }
                    }
                }
            }
        }
    }
}
if (false) {
    $host = "https://www.contravt.ru";
    $IBLOCK_ID = 95;
    $arSelect = array('ID', 'IBLOCK_ID', 'NAME', 'DATE_ACTIVE_FROM', 'PROPERTY_*');
    $arFilter = array('IBLOCK_ID' => $IBLOCK_ID, 'ACTIVE' => 'Y');
    $res = CIBlockElement::GetList(array(), $arFilter, false, array('nPageSize' => 1000), $arSelect);
    while ($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();
        // debug($arFields);
        $arProps = $ob->GetProperties();
        // debug($arProps);
        $html = '';
        $link = $host . $arProps["LINK_OLD_PAGE"]["VALUE"];
        // debug($link);
        $doc = file_get_contents($host . $arProps["LINK_OLD_PAGE"]["VALUE"]);
        $pqCatalog = phpQuery::newDocument($doc);

        $arP = $pqCatalog->find(".structlevels_files_wrap>div");
        // debug(count($arP));
        $isBooklet = false;
        foreach ($arP as $key => $p) {
            $class = pq($p)->attr("class");
            // debug($class);
            $obP = pq($p)->find("a div:q(1)");
            $class2 = pq($obP)->text();
            // debug($class2);
            $pos = strpos($class2, "Сертификаты");
            if ($pos !== false && $class == "structlevel_1") {
                $isBooklet = true;
                // debug("true");
            }
            if ($pos === false && $class == "structlevel_1") {
                $isBooklet = false;
                // debug("false");
            }
            if ($isBooklet && $class == "structlevel_2") {
                // debug("action");

                $oldSrc = pq($p)->find("a")->attr("href");
                $file = "https://www.contravt.ru" . $oldSrc;

                $newSrcEx = explode("=", $oldSrc);
                $countSrc = count($newSrcEx);

                $newfile = "upload/sert_outside/" . $newSrcEx[$countSrc - 1] . ".jpg";

                // debug($file);
                // debug($newfile);
                if (true) {
                    if (!copy($file, $newfile)) {
                        debug("не удалось скопировать " . $file . " for " . $arFields["ID"]);
                    } else {
                        $blockId = 74;
                        $PROP = array();
                        $el = new CIBlockElement;
                        $file_path = $_SERVER["DOCUMENT_ROOT"] . "/" . $newfile;
                        // $PROP["DOCUMENT"] = CFile::MakeFileArray($file_path);
                        // debug($arItem["pname"]);
                        // debug($arSectionIdName[$arItem["pname"]]);
                        $arLoadProductArray = array(
                            //"MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
                            "IBLOCK_SECTION_ID" => "354",
                            "IBLOCK_ID"      => $blockId,
                            "PROPERTY_VALUES" => $PROP,
                            "NAME"           => $class2 . explode(" ", $arFields["NAME"])[0],
                            "CODE"           => translit_sef($class2 . explode(" ", $arFields["NAME"])[0] . " " . $arFields["ID"]),
                            "ACTIVE"         => "Y",
                            'PREVIEW_TEXT' => "",
                            "PREVIEW_TEXT_TYPE" => "html",
                            "PREVIEW_PICTURE" => CFile::MakeFileArray($file_path),
                            "DETAIL_PICTURE" => CFile::MakeFileArray($file_path),
                        );

                        if (true) {
                            // $countArProp = 0;
                            $arItemsLink = [];
                            $arSelect2 = array('ID', 'IBLOCK_ID', 'NAME', 'DATE_ACTIVE_FROM', 'PROPERTY_*');
                            $arFilter2 = array('IBLOCK_ID' => $IBLOCK_ID, "ID" => $arFields["ID"], 'ACTIVE' => 'Y');
                            $res2 = CIBlockElement::GetList(array(), $arFilter2, false, array('nPageSize' => 1), $arSelect2);
                            while ($ob2 = $res2->GetNextElement()) {
                                $arFields2 = $ob2->GetFields();
                                $arProps2 = $ob2->GetProperties();
                                // debug($arProps['OPER_DOCS_LINKS']["VALUE"]);
                                $arItemsLink = $arProps2['CERTIFICATES_LINKS']["VALUE"];
                                // $countArProp = count($arProps['OPER_DOCS_LINKS']["VALUE"]);
                            }
                            // debug($countArProp);
                            if ($PRODUCT_ID = $el->Add($arLoadProductArray)) {
                                $PROPERTY_VALUE = array();
                                $countEl = 0;
                                foreach ($arItemsLink as $key => $arEl) {
                                    $PROPERTY_VALUE += array($key => array("VALUE" => $arEl, "DESCRIPTION" => ""));
                                    $countEl++;
                                }
                                $PROPERTY_VALUE += array(
                                    $countEl => array("VALUE" => $PRODUCT_ID),
                                );
                                // debug($PROPERTY_VALUE);

                                $resSet = CIBlockElement::SetPropertyValuesEx($arFields["ID"], $IBLOCK_ID, array('CERTIFICATES_LINKS' => $PROPERTY_VALUE));
                                debug($resSet);
                                echo "New ID: " . $PRODUCT_ID;
                            } else {
                                print_r($el->LAST_ERROR);
                            }
                        }
                    }
                }
            }
        }
    }
}
if (false) {
    $host = "https://www.contravt.ru";
    $IBLOCK_ID = 95;
    $arSelect = array('ID', 'IBLOCK_ID', 'NAME', 'DATE_ACTIVE_FROM', 'PROPERTY_*');
    $arFilter = array('IBLOCK_ID' => $IBLOCK_ID, 'ACTIVE' => 'Y');
    $res = CIBlockElement::GetList(array(), $arFilter, false, array('nPageSize' => 1000), $arSelect);
    while ($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();
        // debug($arFields);
        $arProps = $ob->GetProperties();
        // debug($arProps);
        $html = '';
        $link = $host . $arProps["LINK_OLD_PAGE"]["VALUE"];
        // debug($link);
        $doc = file_get_contents($host . $arProps["LINK_OLD_PAGE"]["VALUE"]);
        $pqCatalog = phpQuery::newDocument($doc);

        $arP = $pqCatalog->find(".structlevels_files_wrap>div");
        // debug(count($arP));
        $isBooklet = false;
        $arVideo = [];
        foreach ($arP as $key => $p) {
            $class = pq($p)->attr("class");
            // debug($class);
            $obP = pq($p)->find("a div:q(1)");
            $class2 = pq($obP)->text();
            // debug($class2);
            $pos = strpos($class2, "Видеоматериалы");
            if ($pos !== false && $class == "structlevel_1") {
                $isBooklet = true;
                // debug("true");
            }
            if ($pos === false && $class == "structlevel_1") {
                $isBooklet = false;
                // debug("false");
            }
            if ($isBooklet && $class == "structlevel_2") {
                // debug("action");

                $vLink = pq($p)->find("a")->attr("href");
                $arV = [
                    "link" => $vLink,
                    "title" => $class2,
                ];
                $arVideo[] = $arV;
            }
        }

        phpQuery::unloadDocuments();
        // debug($arVideo);

        foreach ($arVideo as $srV) {
            $doc = file_get_contents($host . $srV["link"]);
            $pqCatalog = phpQuery::newDocument($doc);

            $arP = $pqCatalog->find("table h1")->parent();
            $iframe = $pqCatalog->find("iframe")->parent();
            $iframeHtml = $iframe->html();
            // debug($iframeHtml); 
            // debug(count($arP));
            // debug($arP->html());

            $arP->find("iframe")->parent()->remove();
            $html = $arP->html();
            // debug($html);
            $blockId = 100;
            $PROP = array();
            $el = new CIBlockElement;
            $file_path = $_SERVER["DOCUMENT_ROOT"] . "/" . $newfile;
            $PROP["VIDEO_IFRAME"][0] = $iframeHtml;
            // debug($arItem["pname"]);
            // debug($arSectionIdName[$arItem["pname"]]);
            $arLoadProductArray = array(
                //"MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
                "IBLOCK_SECTION_ID" => "",
                "IBLOCK_ID"      => $blockId,
                "PROPERTY_VALUES" => $PROP,
                "NAME"           => $srV["title"],
                "CODE"           => translit_sef($srV["title"] . " " . $arFields["ID"]),
                "ACTIVE"         => "Y",
                'PREVIEW_TEXT' => "",
                "PREVIEW_TEXT_TYPE" => "html",
                'DETAIL_TEXT' => $html,
                "DETAIL_TEXT_TYPE" => "html",
                // "PREVIEW_PICTURE" => CFile::MakeFileArray($file_path),
                // "DETAIL_PICTURE" => CFile::MakeFileArray($file_path),
            );

            if (true) {
                // $countArProp = 0;
                $arItemsLink = [];
                $arSelect2 = array('ID', 'IBLOCK_ID', 'NAME', 'DATE_ACTIVE_FROM', 'PROPERTY_*');
                $arFilter2 = array('IBLOCK_ID' => $IBLOCK_ID, "ID" => $arFields["ID"], 'ACTIVE' => 'Y');
                $res2 = CIBlockElement::GetList(array(), $arFilter2, false, array('nPageSize' => 1), $arSelect2);
                while ($ob2 = $res2->GetNextElement()) {
                    $arFields2 = $ob2->GetFields();
                    $arProps2 = $ob2->GetProperties();
                    // debug($arProps['OPER_DOCS_LINKS']["VALUE"]);
                    $arItemsLink = $arProps2['VIDEO_LINKS']["VALUE"];
                    // $countArProp = count($arProps['OPER_DOCS_LINKS']["VALUE"]);
                }
                // debug($countArProp);
                if ($PRODUCT_ID = $el->Add($arLoadProductArray)) {
                    $PROPERTY_VALUE = array();
                    $countEl = 0;
                    foreach ($arItemsLink as $key => $arEl) {
                        $PROPERTY_VALUE += array($key => array("VALUE" => $arEl, "DESCRIPTION" => ""));
                        $countEl++;
                    }
                    $PROPERTY_VALUE += array(
                        $countEl => array("VALUE" => $PRODUCT_ID),
                    );
                    // debug($PROPERTY_VALUE);

                    $resSet = CIBlockElement::SetPropertyValuesEx($arFields["ID"], $IBLOCK_ID, array('VIDEO_LINKS' => $PROPERTY_VALUE));
                    debug($resSet);
                    echo "New ID: " . $PRODUCT_ID;
                } else {
                    print_r($el->LAST_ERROR);
                }
            }
            phpQuery::unloadDocuments();
        }
    }
}
if (false) {
    $host = "https://www.contravt.ru";
    $IBLOCK_ID = 95;
    $arSelect = array('ID', 'IBLOCK_ID', 'NAME', 'DATE_ACTIVE_FROM', 'PROPERTY_*');
    $arFilter = array('IBLOCK_ID' => $IBLOCK_ID, 'ACTIVE' => 'Y');
    $res = CIBlockElement::GetList(array(), $arFilter, false, array('nPageSize' => 1000), $arSelect);
    while ($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();
        // debug($arFields);
        $arProps = $ob->GetProperties();
        // debug($arProps);
        $html = '';
        $link = $host . $arProps["LINK_OLD_PAGE"]["VALUE"];
        // debug($link);
        $doc = file_get_contents($host . $arProps["LINK_OLD_PAGE"]["VALUE"]);
        $pqCatalog = phpQuery::newDocument($doc);

        $arP = $pqCatalog->find(".structlevels_files_wrap>div");
        // debug(count($arP));
        $isBooklet = false;
        $arVideo = [];
        foreach ($arP as $key => $p) {
            $class = pq($p)->attr("class");
            // debug($class);
            $obP = pq($p)->find("a div:q(1)");
            $class2 = pq($obP)->text();
            // debug($class2);
            $pos = strpos($class2, "Чертежи");
            if ($pos !== false && $class == "structlevel_1") {
                $isBooklet = true;
                // debug("true");
            }
            if ($pos === false && $class == "structlevel_1") {
                $isBooklet = false;
                // debug("false");
            }
            if ($isBooklet && $class == "structlevel_2") {
                // debug("action");

                $vLink = pq($p)->find("a")->attr("href");
                $arV = [
                    "link" => $vLink,
                    "title" => $class2,
                ];
                $arVideo[] = $arV;
            }
        }

        phpQuery::unloadDocuments();
        // debug($arVideo);

        $doc = file_get_contents($host . $arV["link"]);
        $pqCatalog = phpQuery::newDocument($doc);

        $arP = $pqCatalog->find(".structlevels_files_wrap>div");
        // debug(count($arP));
        $isBooklet = false;
        $arVideo = [];
        foreach ($arP as $key => $p) {
            $class = pq($p)->attr("class");
            // debug($class);
            $obP = pq($p)->find("a div:q(1)");
            $class2 = pq($obP)->text();
            // debug($class2);
            $pos = strpos($class2, "модели");
            if ($pos !== false && $class == "structlevel_1") {
                $isBooklet = true;
                // debug("true");
            }
            if ($pos === false && $class == "structlevel_1") {
                $isBooklet = false;
                // debug("false");
            }
            if ($isBooklet && $class == "structlevel_2") {
                // debug("action");
                $oldSrc = pq($p)->find("a")->attr("href");
                $file = $oldSrc;

                $newSrcEx = explode("/", $oldSrc);
                $countSrc = count($newSrcEx);

                $newfile = "upload/draw_outside/" . $newSrcEx[$countSrc - 1];

                // debug($file);
                // debug($newfile);
                if (true) {
                    if (!copy($file, $newfile)) {
                        debug("не удалось скопировать " . $file . " for " . $arFields["ID"]);
                    } else {
                        $blockId = 66;
                        $PROP = array();
                        $el = new CIBlockElement;
                        $file_path = $_SERVER["DOCUMENT_ROOT"] . "/" . $newfile;
                        $PROP["DOCUMENT"] = CFile::MakeFileArray($file_path);
                        // debug($arItem["pname"]);
                        // debug($arSectionIdName[$arItem["pname"]]);
                        $arLoadProductArray = array(
                            //"MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
                            "IBLOCK_SECTION_ID" => "353",
                            "IBLOCK_ID"      => $blockId,
                            "PROPERTY_VALUES" => $PROP,
                            "NAME"           => $class2 . explode(" ", $arFields["NAME"])[0],
                            "CODE"           => translit_sef($class2 . explode(" ", $arFields["NAME"])[0] . " " . $arFields["ID"]),
                            "ACTIVE"         => "Y",
                            'PREVIEW_TEXT' => "",
                            "PREVIEW_TEXT_TYPE" => "html",
                            // "PREVIEW_PICTURE" => CFile::MakeFileArray($file_path),
                            // "DETAIL_PICTURE" => CFile::MakeFileArray($file_path),
                        );

                        if (true) {
                            // $countArProp = 0;
                            $arItemsLink = [];
                            $arSelect2 = array('ID', 'IBLOCK_ID', 'NAME', 'DATE_ACTIVE_FROM', 'PROPERTY_*');
                            $arFilter2 = array('IBLOCK_ID' => $IBLOCK_ID, "ID" => $arFields["ID"], 'ACTIVE' => 'Y');
                            $res2 = CIBlockElement::GetList(array(), $arFilter2, false, array('nPageSize' => 1), $arSelect2);
                            while ($ob2 = $res2->GetNextElement()) {
                                $arFields2 = $ob2->GetFields();
                                $arProps2 = $ob2->GetProperties();
                                // debug($arProps['OPER_DOCS_LINKS']["VALUE"]);
                                $arItemsLink = $arProps2['MODELS_3D']["VALUE"];
                                // $countArProp = count($arProps['OPER_DOCS_LINKS']["VALUE"]);
                            }
                            // debug($countArProp);
                            if ($PRODUCT_ID = $el->Add($arLoadProductArray)) {
                                $PROPERTY_VALUE = array();
                                $countEl = 0;
                                foreach ($arItemsLink as $key => $arEl) {
                                    $PROPERTY_VALUE += array($key => array("VALUE" => $arEl, "DESCRIPTION" => ""));
                                    $countEl++;
                                }
                                $PROPERTY_VALUE += array(
                                    $countEl => array("VALUE" => $PRODUCT_ID),
                                );
                                // debug($PROPERTY_VALUE);

                                $resSet = CIBlockElement::SetPropertyValuesEx($arFields["ID"], $IBLOCK_ID, array('MODELS_3D' => $PROPERTY_VALUE));
                                debug($resSet);
                                echo "New ID: " . $PRODUCT_ID;
                            } else {
                                print_r($el->LAST_ERROR);
                            }
                        }
                    }
                }
            }
        }
    }
}
if (false) {
    $host = "https://www.contravt.ru";
    $IBLOCK_ID = 95;
    $arSelect = array('ID', 'IBLOCK_ID', 'NAME', 'DETAIL_TEXT', 'PROPERTY_*');
    $arFilter = array('IBLOCK_ID' => $IBLOCK_ID, 'ACTIVE' => 'Y');
    $res = CIBlockElement::GetList(array(), $arFilter, false, array('nPageSize' => 1000), $arSelect);
    while ($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();
        // debug($arFields);
        if (!empty($arFields["DETAIL_TEXT"]) && false) {
            continue;
        }
        // debug($arFields["NAME"]);
        $arProps = $ob->GetProperties();
        // debug($arProps);
        $html = '';
        $link = $host . $arProps["LINK_OLD_PAGE"]["VALUE"];
        // debug($link);
        $doc = file_get_contents($host . $arProps["LINK_OLD_PAGE"]["VALUE"]);
        $pqCatalog = phpQuery::newDocument($doc);

        $arP = $pqCatalog->find("p.ca");
        // debug(count($arP));
        if (true) {
            foreach ($arP as $p) {
                // debug(pq($p)->text());
                $pos1 = strpos(pq($p)->text(), "Общие");
                $pos2 = strpos(pq($p)->text(), "Применение нормирующих преобразователей");
                $pos3 = strpos(pq($p)->text(), "Особенности");
                if ($pos1 !== false || $pos2 !== false || $pos3 !== false) {
                    // debug("is text");
                    // continue;
                    $divParent = pq($p)->parent();
                    $divParent->find("a")->remove();
                    $arImg = $divParent->find("img");
                    foreach ($arImg as $img) {
                        // debug(pq($img)->attr("src"));
                        $oldSrc = pq($img)->attr("src");
                        $file = $host . $oldSrc;

                        $newSrcEx = explode("/", $oldSrc);
                        $countSrc = count($newSrcEx);
                        $newSrc = "/upload/img_outside/" . $newSrcEx[$countSrc - 1];
                        $newfile = "upload/img_outside/" . $newSrcEx[$countSrc - 1];

                        // debug($newSrc);
                        pq($img)->attr("src", $newSrc);

                        // debug($file);
                        // debug($newfile);
                        if (!copy($file, $newfile)) {
                            echo "не удалось скопировать $file...\n";
                        }
                    }
                    // debug(pq($divParent)->html());       
                    $html = pq($divParent)->html();
                    if (false) {
                        $el = new CIBlockElement;
                        $arFieldsUp = array("DETAIL_TEXT_TYPE" => "html", "~DETAIL_TEXT_TYPE" => "html", "DETAIL_TEXT" => $html);
                        $resUp = $el->Update(intval($arFields['ID']), $arFieldsUp);
                        // debug($resUp);
                        if ($resUp != 1) {
                            debug("err update" . $arFields['ID']);
                        }
                    }
                }
            }
        }


        phpQuery::unloadDocuments();
    }
}
if (false) {
    $host = "https://www.contravt.ru";
    $IBLOCK_ID = 67;
    // CIBlockElement::SetPropertyValuesEx(2305, $IBLOCK_ID, array('FORM_ORDER' => ["VALUE_ENUM_ID"=>"371"]));
    $arSelect = array('ID', 'IBLOCK_ID', 'NAME', 'DETAIL_TEXT', 'PROPERTY_*');
    $arFilter = array('IBLOCK_ID' => $IBLOCK_ID, 'ACTIVE' => 'Y');
    $res = CIBlockElement::GetList(array(), $arFilter, false, array('nPageSize' => 1000), $arSelect);
    while ($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();
        // debug($arFields["NAME"]);
        $arProps = $ob->GetProperties();
        // debug($arProps["FORM_ORDER"]);
        CIBlockElement::SetPropertyValuesEx($arFields["ID"], $IBLOCK_ID, array('FORM_ORDER' => ["VALUE_ENUM_ID" => "371"]));
    }
}

if (false) {
    $host = "https://www.contravt.ru";
    $host2 = "https:";
    $IBLOCK_ID = 95;
    $arSelect = array('ID', 'IBLOCK_ID', 'NAME', 'DETAIL_TEXT', 'PROPERTY_*');
    $arFilter = array('IBLOCK_ID' => $IBLOCK_ID, 'ID' => 926, 'ACTIVE' => 'Y');
    $res = CIBlockElement::GetList(array(), $arFilter, false, array('nPageSize' => 1), $arSelect);
    while ($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();
        // debug($arFields);
        if (!empty($arFields["DETAIL_TEXT"]) && false) {
            continue;
        }
        debug($arFields["NAME"]);
        $arProps = $ob->GetProperties();
        // debug($arProps);
        $html = '';
        $link = $host . $arProps["LINK_OLD_PAGE"]["VALUE"];
        // debug($link);
        $doc = file_get_contents($host . $arProps["LINK_OLD_PAGE"]["VALUE"]);
        $pqCatalog = phpQuery::newDocument($doc);

        $arP = $pqCatalog->find("p.ca");
        // debug(count($arP));
        if (true) {
            foreach ($arP as $p) {
                // debug(pq($p)->text());
                $pos1 = strpos(pq($p)->text(), "Общие");
                $pos2 = strpos(pq($p)->text(), "Измерители-регуляторы МЕТАКОН");
                $pos3 = strpos(pq($p)->text(), "Внимание!");
                if ($pos1 !== false || $pos2 !== false || $pos3 !== false) {
                    // debug("is text");
                    // continue;
                    $divParent = pq($p)->parent();
                    $divParent->find("a")->remove();
                    $arImg = $divParent->find("img");
                    foreach ($arImg as $img) {
                        // debug(pq($img)->attr("src"));
                        $oldSrc = pq($img)->attr("src");
                        $file = $host . $oldSrc;

                        $newSrcEx = explode("/", $oldSrc);
                        $countSrc = count($newSrcEx);
                        $newSrc = "/upload/img_outside/" . $newSrcEx[$countSrc - 1];
                        $newfile = "upload/img_outside/" . $newSrcEx[$countSrc - 1];

                        // debug($newSrc);
                        pq($img)->attr("src", $newSrc);

                        debug($file);
                        debug($newfile);
                        if (!copy($file, $newfile)) {
                            debug("не удалось скопировать" . $file);
                        }
                    }
                    // debug(pq($divParent)->html());       
                    $html = pq($divParent)->html();
                    if (true) {
                        $el = new CIBlockElement;
                        $arFieldsUp = array("DETAIL_TEXT_TYPE" => "html", "~DETAIL_TEXT_TYPE" => "html", "DETAIL_TEXT" => $html);
                        $resUp = $el->Update(intval($arFields['ID']), $arFieldsUp);
                        // debug($resUp);
                        if ($resUp != 1) {
                            debug("err update" . $arFields['ID']);
                        }
                    }
                }
            }
        }


        phpQuery::unloadDocuments();
    }
}

if (false) {
    $host = "https://www.contravt.ru";
    $IBLOCK_ID = 95;
    // CIBlockElement::SetPropertyValuesEx(2305, $IBLOCK_ID, array('FORM_ORDER' => ["VALUE_ENUM_ID"=>"371"]));
    $arSelect = array('ID', 'IBLOCK_ID', 'NAME', 'DETAIL_TEXT', 'PROPERTY_*');
    $arFilter = array('IBLOCK_ID' => $IBLOCK_ID, 'ACTIVE' => 'Y');
    $res = CIBlockElement::GetList(array(), $arFilter, false, array('nPageSize' => 1000), $arSelect);
    while ($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();
        // debug($arFields["NAME"]);
        $arProps = $ob->GetProperties();
        // debug($arProps["ITEM_NAME"]);
        CIBlockElement::SetPropertyValuesEx($arFields["ID"], $IBLOCK_ID, array('ITEM_NAME' => $arFields["NAME"]));
    }
}

if (false) {
    $html = '';
    $host = "https://www.contravt.ru";
    $uri = "/postavka-kipia";
    $doc = file_get_contents($host . $uri);
    $pqCatalog = phpQuery::newDocument($doc);
    $container = $pqCatalog->find("h1")->parent();
    debug(count($container));
    $arElements = $container->find("table.cell_standart_icon");
    debug(count($arElements));
    foreach ($arElements as $e) {
        // debug(pq($e)->find("img")->attr("src"));
        $src = pq($e)->find("img")->attr("src");
        // debug(pq($e)->find("img")->attr("alt"));
        $title = pq($e)->find("img")->attr("alt");
        // debug(pq($e)->find("span.short")->text());
        $desc = pq($e)->find("span.short")->text();

        $file = $host . $src;

        $newSrcEx = explode("/", $src);
        $countSrc = count($newSrcEx);
        // $newSrc = "/upload/section_outside/" . $newSrcEx[$countSrc - 1];
        $newSrcEx2 = explode("?", $newSrcEx[$countSrc - 1]);
        $newfile = "upload/section_outside/" . $newSrcEx2[0];

        // debug($newSrc);
        pq($img)->attr("src", $newSrc);

        // debug($file);
        // debug($newfile);
        if (!copy($file, $newfile)) {
            debug("не удалось скопировать" . $file);
        } else {
            $file_path = $_SERVER["DOCUMENT_ROOT"] . "/" . $newfile;
            $IBLOCK_ID = 101;
            $bs = new CIBlockSection;
            $arFields = array(
                "ACTIVE" => "Y",
                "IBLOCK_SECTION_ID" => "",
                "IBLOCK_ID" => $IBLOCK_ID,
                "NAME" => $title,
                "CODE" => translit_sef($title),
                'DESCRIPTION' => $desc,
                "DESCRIPTION_TYPE" => "html",
                "PICTURE" => CFile::MakeFileArray($file_path),
            );

            //print_r($arFields);exit;
            $ID = $bs->Add($arFields);
            $res = ($ID > 0);

            if (!$res) {
                echo $bs->LAST_ERROR;
            } else {
                echo $ID;
            }
        }
    }
}
if (false) {
    $html = '';
    $host = "https://www.contravt.ru";
    $uri = "/?id=14831";
    $doc = file_get_contents($host . $uri);
    $pqCatalog = phpQuery::newDocument($doc);
    $container = $pqCatalog->find("table.list_table_std");
    debug(count($container));
    $arElements = $container->find("tr.list_table_std");
    debug(count($arElements));
    // return;
    foreach ($arElements as $e) {
        // debug(pq($e)->find("img")->attr("src"));
        $src = pq($e)->find("img")->attr("src");
        // debug(pq($e)->find("img")->attr("alt"));
        $title = pq($e)->find("td.cell_standart_icon_text>a")->text();
        // debug($title);
        $desc = pq($e)->find("span.short")->text();
        // debug($desc);

        $file = $host . $src;

        $newSrcEx = explode("/", $src);
        $countSrc = count($newSrcEx);
        // $newSrc = "/upload/section_outside/" . $newSrcEx[$countSrc - 1];
        $newSrcEx2 = explode("?", $newSrcEx[$countSrc - 1]);
        $newfile = "upload/section_outside/" . $newSrcEx2[1] . $newSrcEx2[0];

        // debug($newSrc);
        pq($img)->attr("src", $newSrc);

        // debug($file);
        // debug($newfile);
        if (!copy($file, $newfile)) {
            debug("не удалось скопировать" . $file);
        } else {
            $file_path = $_SERVER["DOCUMENT_ROOT"] . "/" . $newfile;
            $IBLOCK_ID = 101;
            $bs = new CIBlockSection;
            $arFields = array(
                "ACTIVE" => "Y",
                "IBLOCK_SECTION_ID" => "368",
                "IBLOCK_ID" => $IBLOCK_ID,
                "NAME" => $title,
                "CODE" => translit_sef($title),
                'DESCRIPTION' => $desc,
                "DESCRIPTION_TYPE" => "html",
                "PICTURE" => CFile::MakeFileArray($file_path),
            );

            //print_r($arFields);exit;
            $ID = $bs->Add($arFields);
            $res = ($ID > 0);

            if (!$res) {
                echo $bs->LAST_ERROR;
            } else {
                echo $ID;
            }
        }
    }
}
if (false) {
    $html = '';
    $host = "https://www.contravt.ru";
    $uri = "/?id=14879";
    $doc = file_get_contents($host . $uri);
    $pqCatalog = phpQuery::newDocument($doc);
    $container = $pqCatalog->find("table.list_table_std");
    debug(count($container));
    $arElements = $container->find("td.list_table_std");
    debug(count($arElements));
    // return;
    foreach ($arElements as $e) {
        // debug(pq($e)->find("img")->attr("src"));
        $src = pq($e)->find("img")->attr("src");
        // debug(pq($e)->find("img")->attr("alt"));
        $title = pq($e)->find("td.cell_standart_icon_text>a")->text();
        // debug($title);
        $desc = pq($e)->find("span.short")->text();
        // debug($desc);

        $file = $host . $src;

        $newSrcEx = explode("/", $src);
        $countSrc = count($newSrcEx);
        // $newSrc = "/upload/section_outside/" . $newSrcEx[$countSrc - 1];
        $newSrcEx2 = explode("?", $newSrcEx[$countSrc - 1]);
        $newfile = "upload/section_outside/" . $newSrcEx2[1] . $newSrcEx2[0];

        // debug($newSrc);
        pq($img)->attr("src", $newSrc);

        // debug($file);
        // debug($newfile);
        if (!copy($file, $newfile)) {
            debug("не удалось скопировать" . $file);
        } else {
            $file_path = $_SERVER["DOCUMENT_ROOT"] . "/" . $newfile;
            $IBLOCK_ID = 101;
            $bs = new CIBlockSection;
            $arFields = array(
                "ACTIVE" => "Y",
                "IBLOCK_SECTION_ID" => "376",
                "IBLOCK_ID" => $IBLOCK_ID,
                "NAME" => $title,
                "CODE" => translit_sef($title),
                'DESCRIPTION' => $desc,
                "DESCRIPTION_TYPE" => "html",
                "PICTURE" => CFile::MakeFileArray($file_path),
            );

            //print_r($arFields);exit;
            $ID = $bs->Add($arFields);
            $res = ($ID > 0);

            if (!$res) {
                echo $bs->LAST_ERROR;
            } else {
                echo $ID;
            }
        }
    }
}

if (false) {
    $html = '';
    $host = "https://www.contravt.ru";
    $uri = "/?id=14486";
    $doc = file_get_contents($host . $uri);
    $pqCatalog = phpQuery::newDocument($doc);
    $container = $pqCatalog->find("table.list_table_std");
    debug(count($container));
    $arElements = $container->find("td.list_table_std");
    debug(count($arElements));
    // return;
    foreach ($arElements as $e) {
        $title = pq($e)->find("td.cell_standart_icon_text>a")->text();
        if ($title == "") {
            continue;
        }
        // debug($title);
        $link = pq($e)->find("td.cell_standart_icon_text>a")->attr("href");
        // debug($host. $link);
        $src = pq($e)->find("img")->attr("src");
        $desc = pq($e)->find("span.short")->text();
        // debug($desc);

        $file = $host . $src;

        $newSrcEx = explode("/", $src);
        $countSrc = count($newSrcEx);
        // $newSrc = "/upload/section_outside/" . $newSrcEx[$countSrc - 1];
        $newSrcEx2 = explode("?", $newSrcEx[$countSrc - 1]);
        $newfile = "upload/section_outside/" . $newSrcEx2[1] . $newSrcEx2[0];

        // debug($file);
        // debug($newfile);

        if (!copy($file, $newfile)) {
            debug("не удалось скопировать" . $file);
        } else {
            $blockId = 101;
            $PROP = array();
            $el = new CIBlockElement;
            $file_path = $_SERVER["DOCUMENT_ROOT"] . "/" . $newfile;
            $PROP["LINK_OLD_PAGE"] = $host . $link;
            // debug($arItem["pname"]);
            // debug($arSectionIdName[$arItem["pname"]]);
            $arLoadProductArray = array(
                //"MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
                "IBLOCK_SECTION_ID" => "375",
                "IBLOCK_ID"      => $blockId,
                "PROPERTY_VALUES" => $PROP,
                "NAME"           => $title,
                "CODE"           => translit_sef($title),
                "ACTIVE"         => "Y",
                'PREVIEW_TEXT' => $desc,
                "PREVIEW_TEXT_TYPE" => "html",
                "PREVIEW_PICTURE" => CFile::MakeFileArray($file_path),
                // "DETAIL_PICTURE" => CFile::MakeFileArray($file_path),
            );
            $PRODUCT_ID = $el->Add($arLoadProductArray);
            if ($PRODUCT_ID > 0) {
                echo "New ID: " . $PRODUCT_ID;
            } else {
                print_r($el->LAST_ERROR);
            }
        }
    }
}

if (false) {
    $host = "https://www.contravt.ru";
    $host2 = "https:";
    $IBLOCK_ID = 101;
    $arSelect = array('ID', 'IBLOCK_ID', 'NAME', 'DETAIL_TEXT', 'PROPERTY_*');
    $arFilter = array('IBLOCK_ID' => $IBLOCK_ID, 'ACTIVE' => 'Y');
    $res = CIBlockElement::GetList(array(), $arFilter, false, array('nPageSize' => 1000), $arSelect);
    while ($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();
        // debug($arFields);
        if (!empty($arFields["DETAIL_TEXT"]) && false) {
            continue;
        }
        // debug($arFields["NAME"]);

        $arProps = $ob->GetProperties();
        // debug($arProps);
        $html = '';
        $link = $host . $arProps["LINK_OLD_PAGE"]["VALUE"];
        // debug($link);
        $doc = file_get_contents($arProps["LINK_OLD_PAGE"]["VALUE"]);
        $pqCatalog = phpQuery::newDocument($doc);

        $container = $pqCatalog->find("h1")->parent();
        // debug(count($container));

        $arImg = $container->find("img");
        $file_path = "";

        foreach ($arImg as $key => $img) {
            // debug(pq($img)->attr("src"));
            $oldSrc = pq($img)->attr("src");
            $file = $host . $oldSrc;

            $newSrcEx = explode("/", $oldSrc);
            $countSrc = count($newSrcEx);
            $newSrc = "/upload/img_outside/" . $newSrcEx[$countSrc - 1];
            $newfile = "upload/img_outside/" . $newSrcEx[$countSrc - 1];

            pq($img)->attr("src", $newSrc);

            // debug($file);
            // debug($newfile);
            if (!copy($file, $newfile)) {
                debug("не удалось скопировать" . $file);
            }
            $parentDiv = pq($img)->parent();
            if($parentDiv->attr("class") == "insert_pos_icon"){
                $file_path = $_SERVER["DOCUMENT_ROOT"] . "/" . $newfile;
                $parentDiv->find("img")->remove();
            }
        }
        $container->find("a")->remove();
        $container->find("h1")->remove();
        $html = $container->html();
        if (true) {
            $el = new CIBlockElement;
            $arFieldsUp = array("DETAIL_TEXT_TYPE" => "html", "~DETAIL_TEXT_TYPE" => "html", "DETAIL_TEXT" => $html, "DETAIL_PICTURE" => CFile::MakeFileArray($file_path));
            $resUp = $el->Update(intval($arFields['ID']), $arFieldsUp);
            // debug($resUp);
            CIBlockElement::SetPropertyValuesEx($arFields["ID"], $IBLOCK_ID, array('ITEM_NAME' => $arFields["NAME"]));

            if ($resUp != 1) {
                debug("err update" . $arFields['ID']);
            }
        }

        phpQuery::unloadDocuments();
    }
}

debug("end");
