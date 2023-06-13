<?php
require_once 'phpQuery/phpQuery/phpQuery.php';
function debug($data)
{
    echo "<pre>" . print_r($data, 1) . "</pre>";
}

/*
//$ch = curl_init("https://tpkpsd.ru/produkty/detali-truboprovoda/opory/#category_id=80&page=2&path=72_59_80&route=product%2Fcategory&min_price=0&max_price=0");
$ch = curl_init("https://tpkpsd.ru/produkty/detali-truboprovoda/opory/");

curl_setopt($ch, CURLOPT_HEADER, 0);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec($ch);
curl_close($ch);
debug($output);
*/

//Request URL: https://tpkpsd.ru/index.php?route=module/filterpro/getproducts
// content-type: text/html; charset=utf-8

/*
$headers = stream_context_create(array(
    'http' => array(
        'method' => 'POST',
        'header' => 'Content-Type: text/html; charset=utf-8' . PHP_EOL,
        'content' => 'category_id=80&page=2&path=72_59_80&route=product/category&min_price=0&max_price=0&getPriceLimits=true',
    ),
));

$html = file_get_contents('https://tpkpsd.ru/index.php?route=module/filterpro/getproducts', false, $headers);
*/

/*
$headers = array(
    'Content-Type: application/x-www-form-urlencoded',
);
$array = array(
    "category_id" => 80,
    "page" => 2,
    "path" => "72_59_80",
    "route" => "product/category",
    "min_price" => 0,
    "max_price" => 0,
);

$ch = curl_init('https://tpkpsd.ru/index.php?route=module/filterpro/getproducts');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $array);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
// Или предать массив строкой: 
// curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($array, '', '&'));

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_HEADER, true);
$html = curl_exec($ch);
curl_close($ch);

debug($html);
*/

/*
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
    CURLOPT_POSTFIELDS => array('category_id' => '80', 'page' => '1'),
    CURLOPT_HTTPHEADER => array(
        'Cookie: PHPSESSID=9e59a1f7c85b3a172bed7abc79b4c997; currency=RUB; language=ru'
    ),
));

$response = curl_exec($curl);

curl_close($curl);
//echo $response;
*/
$arUrlItem = array();

/*
$urlPage = "https://tpkpsd.ru/produkty/detali-truboprovoda/opory/#category_id=80&page=2&path=72_59_80&route=product%2Fcategory&min_price=0&max_price=0";

//debug($urlPage);
$sectionPage = file_get_contents($urlPage);
*/

/*
//$pqSectionPage = phpQuery::newDocument($sectionPage);
$pqSectionPage = phpQuery::newDocument($response);
//debug($pqSection);
$arAitemPage = $pqSectionPage->find("a");
foreach ($arAitemPage as $keyAp => $aP) {
    $aP = pq($aP);
    //debug($a->attr("href"));
    if (!empty($aP->attr("href"))) {
        $url = str_replace("\\", "", $aP->attr("href"));
        if (!in_array($url, $arUrlItem) && !stristr($url, 'index')) {
            $arUrlItem[] = $url;
        }
    }
    //debug($a->text());
}
phpQuery::unloadDocuments();
//debug($sectionPage);
debug($arUrlItem);
*/

$str = '<div id="elem">Текст</div><div>Еще тег</div>';
$pq = phpQuery::newDocument($str);

$elem = $pq->remove(pq('#elem'));
$text = $elem->html();
debug($text);
