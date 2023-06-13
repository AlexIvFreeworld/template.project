<?php
require_once 'phpQuery/phpQuery/phpQuery.php';

function debug($data)
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
$startUrl = "https://tpkpsd.ru";
$arStructure = array();

$catalog = file_get_contents($startUrl . "/produkty/");
$pqCatalog = phpQuery::newDocument($catalog);
$htmlDiv = $pqCatalog->find("#column-left div.left_menu");
foreach ($htmlDiv as $key => $div) {
    $structure = array(
        "LEVEL_1_NAME" => "",
        "LEVEL_1_CODE" => "",
        "LEVEL_2_NAME" => "",
        "LEVEL_2_CODE" => "",
        "URL_SECTION" => "",
        "AR_URL_ITEMS" => array(),
    );
    $div = pq($div);
    $span = $div->find("span:eq(0)");
    $span = pq($span);
    //debug($span->text());
    $arA = $div->find("ul li a");
    foreach ($arA as $key => $a) {
        $structure["LEVEL_1_NAME"] = $span->text();
        $structure["LEVEL_1_CODE"] = translit_sef($span->text());
        $a = pq($a);
        //debug($a->text());
        $structure["LEVEL_2_NAME"] = $a->text();
        $structure["LEVEL_2_CODE"] = translit_sef($a->text());
        //debug($a->attr("href"));
        $structure["URL_SECTION"] = $startUrl . $a->attr("href");
        $arStructure[] = $structure;
    }
}
phpQuery::unloadDocuments();
//debug($arStructure);

foreach ($arStructure as $key => $structure) {
    $pages = 1;
    $section = file_get_contents($structure["URL_SECTION"]);
    $pqSection = phpQuery::newDocument($section);
    $arA = $pqSection->find("div.pagination:eq(0) div.links a");
    //debug(count($arA));
    if (count($arA) != 0) {
        $pages = intval(count($arA) - 1);
    }
    //debug($pages);
    $input = $pqSection->find("#filterpro input[name='category_id']");
    //debug($input->attr("value"));
    $categoryId = $input->attr("value");
    $arUrlItem = array();
    if ($pages > 1) {
        for ($i = 1; $i <= $pages; $i++) {
            if ($i == 1) {
                $arAitemPage = $pqSection->find("#content .name a");
                foreach ($arAitemPage as $keyAp => $aP) {
                    $aP = pq($aP);
                    //debug($a->attr("href"));
                    $arUrl = explode("/", $aP->attr("href"));
                    $arUrlItem[] = $startUrl . "/" . end($arUrl);
                    //debug($a->text());
                }
                unset($sectionPage);
                phpQuery::unloadDocuments();
            } else {
                $curl = curl_init();
                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://tpkpsd.ru/index.php?route=module/filterpro/getproducts',
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'POST',
                    CURLOPT_POSTFIELDS => array('category_id' => $categoryId, 'page' => strval($i)),
                    CURLOPT_HTTPHEADER => array(
                        'Cookie: PHPSESSID=9e59a1f7c85b3a172bed7abc79b4c997; currency=RUB; language=ru'
                    ),
                ));
                $response = curl_exec($curl);
                curl_close($curl);

                $pqSectionPage = phpQuery::newDocument($response);
                //debug($pqSection);
                $arAitemPage = $pqSectionPage->find("a");
                foreach ($arAitemPage as $keyAp => $aP) {
                    $aP = pq($aP);
                    //debug($a->attr("href"));
                    if (!empty($aP->attr("href"))) {
                        $url = str_replace(["\\", "\""], "", $aP->attr("href"));
                        $url = str_replace("http", "https", $url);
                        if (!in_array($url, $arUrlItem) && !stristr($url, 'index')) {
                            $arUrlItem[] = $url;
                        }
                    }
                    //debug($a->text());
                }
                phpQuery::unloadDocuments();
                //debug($sectionPage);
                //debug($arUrlItem);
            }
        }
    } else {
        $arAitem = $pqSection->find("#content .name a");
        foreach ($arAitem as $keyA => $a) {
            $a = pq($a);
            //debug($a->attr("href"));
            $arUrl = explode("/", $a->attr("href"));
            $arUrlItem[] = $startUrl . "/" . end($arUrl);
        }
        phpQuery::unloadDocuments();
    }
    $arStructure[$key]["AR_URL_ITEMS"] = $arUrlItem;
}
//debug($arStructure);

set_time_limit(0);
$arItems = array();
$counterItems = 1000;
$counterTmp1 = 0;
$counterTmp2 = 0;
$arOrigName = array();
foreach ($arStructure as $key => $structure) {
    if ($counterTmp1 >= 1) {
        //break;
    }
    foreach ($structure["AR_URL_ITEMS"] as $key => $urlItem) {
        if ($counterTmp2 >= 2) {
            //break;
        }
        $arItem = array(
            "IE_XML_ID" => "",
            "IE_NAME" => "",
            "IE_ID" => "",
            "IE_ACTIVE" => "Y",
            "IE_PREVIEW_PICTURE" => "",
            "IE_PREVIEW_TEXT" => "",
            "IE_PREVIEW_TEXT_TYPE" => "text",
            "IE_DETAIL_PICTURE" => "",
            "IE_DETAIL_TEXT" => "",
            "IE_DETAIL_TEXT_TYPE" => "html",
            "IE_CODE" => "",
            "IE_SORT" => "500",
            "IP_PROP54" => "",
            "IP_PROP63" => "",
            "IC_GROUP0" => "",
            "IC_GROUP1" => "",
            "IC_GROUP2" => "",
            "IC_GROUP0_CODE" => "",
            "IC_GROUP1_CODE" => "",
        );
        $arItem["IE_XML_ID"] = "ST" . strval($counterItems);
        $counterItems++;
        $arItem["IC_GROUP0"] = $structure["LEVEL_1_NAME"];
        $arItem["IC_GROUP1"] = $structure["LEVEL_2_NAME"];
        $arItem["IC_GROUP0_CODE"] = $structure["LEVEL_1_CODE"];
        $arItem["IC_GROUP1_CODE"] = $structure["LEVEL_2_CODE"];
        $html = file_get_contents($urlItem);
        $pqHtml = phpQuery::newDocument($html);
        $h1 = $pqHtml->find("div.header-title-product h1");
        //debug($h1->text());
        $arItem["IE_NAME"] = str_replace("  ", " ", $h1->text());
        if (in_array($arItem["IE_NAME"], $arOrigName)) {
            debug("is not orig : " . $arItem["IE_NAME"]);
            $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
            $addPartName = substr(str_shuffle($permitted_chars), 0, 3);
            $arItem["IE_NAME"] .= "-" . $addPartName;
        }
        $arOrigName[] = $arItem["IE_NAME"];
        $arItem["IE_CODE"] = translit_sef($arItem["IE_NAME"]);
        $aImage = $pqHtml->find("div.product-info .image a");
        $arImage = explode("/", $aImage->attr("href"));
        $arItem["IE_PREVIEW_PICTURE"] = end($arImage);
        $arItem["IE_DETAIL_PICTURE"] = end($arImage);
        $pDescription = $pqHtml->find("#tab-description > p");
        // $arItem["IE_DETAIL_TEXT"] = str_replace([PHP_EOL, "&nbsp;"], "", $divDescription->html());
        //$strTemp = "";
        foreach ($pDescription as $p) {
            $arItem["IE_DETAIL_TEXT"] .= "<p>";
            $p = pq($p);
            //debug($p->text());
            //debug($p->html());
            $str = str_replace([PHP_EOL, "\t", "&nbsp;"], "", htmlentities($p->text()));
            $str = str_replace(";", ",", $str);
            $arItem["IE_DETAIL_TEXT"] .= $str;
            $arItem["IE_DETAIL_TEXT"] .= "</p>";
            //$arItem["IE_DETAIL_TEXT"] .= $p->html();
        }
        $arItem["IE_DETAIL_TEXT"] = preg_replace("/&(.*?);/", '', $arItem["IE_DETAIL_TEXT"]);
        //$arItem["IE_DETAIL_TEXT"] = htmlentities($arItem["IE_DETAIL_TEXT"]);

        //debug($strTemp);
        $tables = $pqHtml->find("#tab-description table");
        foreach ($tables as $table) {
            $table = pq($table);
            $arItem["IP_PROP63"] .= "<table>";
            //$arItem["IP_PROP63"] .= ($table->html());
            //$arItem["IP_PROP63"] .= "<table>" . str_replace([PHP_EOL, "\t", "&nbsp;"], "", htmlentities($table->html())) . "</table>";
            $str = str_replace([PHP_EOL, "\t", "&nbsp;"], "", $table->html());
            $str = str_replace(";", ",", $str);
            $arItem["IP_PROP63"] .= $str;
            $arItem["IP_PROP63"] .= "</table>";
        }
        $arItem["IP_PROP63"] = preg_replace("/&(.*?);/", '', $arItem["IP_PROP63"]);
        $arItem["IP_PROP63"] = preg_replace("/style=\"(.*?)\"/", '', $arItem["IP_PROP63"]);
        //$arItem["IP_PROP63"] = htmlentities($arItem["IP_PROP63"]);
        //debug($arItem);
        $arItems[] = $arItem;
        $counterTmp2++;
    }
    $counterTmp1++;
}
//debug($arItems);


$fp = fopen('import_5.csv', 'w');

foreach ($arItems as $item) {
    $strCsv = "";
    foreach ($item as $key => $val) {
        $strCsv .= $key . ";";
    }
    // remove last symbol
    $strCsv = substr($strCsv, 0, -1);
    fwrite($fp, $strCsv . PHP_EOL);
    break;
}
foreach ($arItems as $item) {
    $strCsv = "";
    foreach ($item as $key => $val) {
        $strCsv .= $val . ";";
    }
    // remove last symbol
    $strCsv = substr($strCsv, 0, -1);
    fwrite($fp, $strCsv . PHP_EOL);
}
fclose($fp);
debug("end csv");


/*
foreach ($arItems as $item) {
    $file = "https://tpkpsd.ru/image/cache/" . $item["IE_DETAIL_PICTURE"];
    $newfile = "img/" . $item["IE_DETAIL_PICTURE"];
    if (!copy($file, $newfile)) {
        echo "не удалось скопировать $file...\n";
    }
}
debug("end image");
*/

// --------------

/*
$dictionary = array();

$arCsvCategory = array();
$arCsvCategory[] = "folder_id; folder_name; level; hidden; noindex";
$strCsvCategory = "";
$counterCategory = 100;
$arArticleOrigin = array();

$catalog = file_get_contents("https://tpkpsd.ru/produkty/");
$pqCatalog = phpQuery::newDocument($catalog);
$htmlSpan = $pqCatalog->find("#column-left");
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



$fp = fopen('ex_category_all.csv', 'w');
foreach ($arCsvCategory as $str) {
    fwrite($fp, $str . PHP_EOL);
}
fclose($fp);
debug("end");


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
            
            if (!copy($file, $newfile)) {
                echo "не удалось скопировать $file...\n";
            }
           
            $imageItem .= end($arEx) . ", ";
        }
        //debug($imageItem);
        // get article
        $spanArticle = $pq->find("span.article");
        $articleItem = $spanArticle->text();
        //debug($articleItem);

        // check
        if(in_array($articleItem, $arArticleOrigin)){
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
*/

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

//$arGoods = array();
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
