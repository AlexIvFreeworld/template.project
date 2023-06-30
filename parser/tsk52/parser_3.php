<?
require $_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php';
CModule::IncludeModule('iblock');
Cmodule::IncludeModule('catalog');
require_once $_SERVER['DOCUMENT_ROOT'] . '/phpQuery/phpQuery/phpQuery.php';

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

function addElement($params)
{
    CModule::IncludeModule('iblock');

    $PROP = array();
    $el = new CIBlockElement;
    $PROP["EXT_PAGE"] = $params["UF_EXT_PAGE"];
    // $PROP["EXT_CODE"] = $code;
    $PROP["EXT_PARENT_CODE"] = $params["UF_EXT_PARENT_CODE"];
    $arLoadProductArray = array(
        //"MODIFIED_BY"    => $USER->GetID(), // элемент изменен текущим пользователем
        "IBLOCK_SECTION_ID" => $params["IBLOCK_SECTION_ID"],
        "IBLOCK_ID"      => $params["IBLOCK_ID"],
        "PROPERTY_VALUES" => $PROP,
        "NAME"           => $params["NAME"],
        "CODE"           => $params["CODE"],
        "ACTIVE"         => "Y",
        'PREVIEW_TEXT' => "",
        "PREVIEW_TEXT_TYPE" => "html",
        "DETAIL_TEXT_TYPE" => "html",
        "PREVIEW_PICTURE" => $params["PICTURE"],
    );
    $PRODUCT_ID = $el->Add($arLoadProductArray);
    if ($PRODUCT_ID > 0) {
        // echo "New ID: " . $PRODUCT_ID;
    } else {
        print_r($el->LAST_ERROR);
    }
}
function addSection($params)
{
    CModule::IncludeModule('iblock');

    $bs = new CIBlockSection;
    $arFields = array(
        "ACTIVE" => "Y",
        "IBLOCK_SECTION_ID" => $params["IBLOCK_SECTION_ID"],
        "IBLOCK_ID" => $params["IBLOCK_ID"],
        "NAME" => $params["NAME"],
        "CODE" => $params["CODE"],
        'DESCRIPTION' => "",
        "DESCRIPTION_TYPE" => "html",
        "UF_EXT_PAGE" => $params["UF_EXT_PAGE"],
        // "UF_EXT_CODE" => $params["code"],
        "UF_EXT_PARENT_CODE" => $params["UF_EXT_PARENT_CODE"],
        "PICTURE" => $params["PICTURE"],
    );

    $ID = $bs->Add($arFields);
    $res = ($ID > 0);

    if (!$res) {
        echo $bs->LAST_ERROR;
    } else {
        // echo $ID;
    }
}
function getBlock($startUrl, $curSection)
{
    $catalog = file_get_contents($startUrl . $curSection);
    $pqCatalog = phpQuery::newDocument($catalog);
    return $pqCatalog->find("div.partner_block_inside");
}
function getBlockDetailPage($startUrl, $curSection)
{
    $catalog = file_get_contents($startUrl . $curSection);
    $pqCatalog = phpQuery::newDocument($catalog);
    return $pqCatalog->find("body");
}
function urlencode_str($matches)
{
    $str = $matches[0];
    $str = rawurlencode($str);
    return $str;
}
$startUrl = "https://doorhan.ru";
$startUrlHttp = "http://doorhan.ru";
$arStructure = array(
    "SECT_1" => 0,
    "EL_1" => 0,
    "SECT_2" => 0,
    "EL_2" => 0,
    "SECT_3" => 0,
    "EL_3" => 0,
    "SECT_4" => 0,
    "EL_4" => 0,
);

$IBLOCK_ID = 206;
$count = 0;
$SECTION_ID_FOR_SECTION = 19515;
// $SECTION_ID_FOR_ELEM = 19682;
$curSection = "/products/";
set_time_limit(0);
if (false) {
    $htmlDiv = getBlock($startUrl, $curSection);
    foreach ($htmlDiv as $div) {
        $div = pq($div);
        $params = array(
            "IBLOCK_SECTION_ID" => $SECTION_ID_FOR_SECTION,
            "IBLOCK_ID" => $IBLOCK_ID,
            "NAME" => $div->find("div.partner_bottom_title_text")->text(),
            "CODE" => translit_sef($div->find("div.partner_bottom_title_text")->text()),
            'DESCRIPTION' => "",
            "DESCRIPTION_TYPE" => "html",
            "UF_EXT_PAGE" => $div->find("a")->attr("href"),
            "UF_EXT_PARENT_CODE" => $curSection,
            "PICTURE" => CFile::MakeFileArray($startUrl . $div->find("img.img-responsive")->attr("src")),
        );
        // debug($params);
        if (strpos($params["UF_EXT_PAGE"], "?") !== false) {
            // debug("it is elem :" . $params["UF_EXT_PAGE"]);
            // addElement($params);
            $arStructure["EL_1"]++;
        } else {
            // debug("it is section :" . $params["UF_EXT_PAGE"]);
            // addSection($params);
            $arStructure["SECT_1"]++;
            $htmlDiv2 = getBlock($startUrl, $params["UF_EXT_PAGE"]);
            if (true) {
                foreach ($htmlDiv2 as $div2) {
                    $div2 = pq($div2);
                    $params2 = array(
                        "IBLOCK_SECTION_ID" => $SECTION_ID_FOR_SECTION,
                        "IBLOCK_ID" => $IBLOCK_ID,
                        "NAME" => $div2->find("div.partner_bottom_title_text")->text(),
                        "CODE" => translit_sef($div2->find("div.partner_bottom_title_text")->text()),
                        'DESCRIPTION' => "",
                        "DESCRIPTION_TYPE" => "html",
                        "UF_EXT_PAGE" => $div2->find("a")->attr("href"),
                        "UF_EXT_PARENT_CODE" => $params["UF_EXT_PAGE"],
                        "PICTURE" => CFile::MakeFileArray($startUrl . $div2->find("img.img-responsive")->attr("src")),
                    );
                    if (strpos($params2["UF_EXT_PAGE"], "?") !== false) {
                        // debug("it is elem :" . $params2["UF_EXT_PAGE"]);
                        addElement($params2);
                        $arStructure["EL_2"]++;
                    } else {
                        // debug("it is section :" . $params2["UF_EXT_PAGE"]);
                        addSection($params2);
                        $arStructure["SECT_2"]++;
                        $htmlDiv3 = getBlock($startUrl, $params2["UF_EXT_PAGE"]);
                        if (true) {
                            foreach ($htmlDiv3 as $div3) {
                                $div3 = pq($div3);
                                $params3 = array(
                                    "IBLOCK_SECTION_ID" => $SECTION_ID_FOR_SECTION,
                                    "IBLOCK_ID" => $IBLOCK_ID,
                                    "NAME" => $div3->find("div.partner_bottom_title_text")->text(),
                                    "CODE" => translit_sef($div3->find("div.partner_bottom_title_text")->text()),
                                    'DESCRIPTION' => "",
                                    "DESCRIPTION_TYPE" => "html",
                                    "UF_EXT_PAGE" => $div3->find("a")->attr("href"),
                                    "UF_EXT_PARENT_CODE" => $params2["UF_EXT_PAGE"],
                                    "PICTURE" => CFile::MakeFileArray($startUrl . $div3->find("img.img-responsive")->attr("src")),
                                );
                                if (strpos($params3["UF_EXT_PAGE"], "?") !== false) {
                                    // debug("it is elem :" . $params3["UF_EXT_PAGE"]);
                                    addElement($params3);
                                    $arStructure["EL_3"]++;
                                } else {
                                    // debug("it is section :" . $params3["UF_EXT_PAGE"]);
                                    addSection($params3);
                                    $arStructure["SECT_3"]++;
                                    $htmlDiv4 = getBlock($startUrl, $params3["UF_EXT_PAGE"]);
                                    if (true) {
                                        foreach ($htmlDiv4 as $div4) {
                                            $div4 = pq($div4);
                                            $params4 = array(
                                                "IBLOCK_SECTION_ID" => $SECTION_ID_FOR_SECTION,
                                                "IBLOCK_ID" => $IBLOCK_ID,
                                                "NAME" => $div4->find("div.partner_bottom_title_text")->text(),
                                                "CODE" => translit_sef($div4->find("div.partner_bottom_title_text")->text()),
                                                'DESCRIPTION' => "",
                                                "DESCRIPTION_TYPE" => "html",
                                                "UF_EXT_PAGE" => $div4->find("a")->attr("href"),
                                                "UF_EXT_PARENT_CODE" => $params3["UF_EXT_PAGE"],
                                                "PICTURE" => CFile::MakeFileArray($startUrl . $div4->find("img.img-responsive")->attr("src")),
                                            );
                                            if (strpos($params4["UF_EXT_PAGE"], "?") !== false) {
                                                // debug("it is elem :" . $params4["UF_EXT_PAGE"]);
                                                addElement($params4);
                                                $arStructure["EL_4"]++;
                                            } else {
                                                // debug("it is section :" . $params4["UF_EXT_PAGE"]);
                                                addSection($params4);
                                                $arStructure["SECT_4"]++;
                                            }
                                            $count++;
                                        }
                                    }
                                }
                                $count++;
                            }
                        }
                    }
                    $count++;
                }
            }
        }
        $count++;
    }
}
if (false) {
    $arSectionExPageId = array();
    $trySectId = 19816;
    $arLists = CIBlockSection::GetList(
        array('SORT' => 'ASC'),
        array('IBLOCK_ID' => $IBLOCK_ID, 'SECTION_ID' => $SECTION_ID_FOR_SECTION),
        false,
        array('ID', 'NAME', 'CODE', 'SECTION_PAGE_URL', "UF_EXT_PAGE")
    );
    while ($arList = $arLists->GetNext()) {
        // debug($arList);
        $arSectionExPageId[$arList["UF_EXT_PAGE"]] = $arList["ID"];
    }
    debug($arSectionExPageId);
    $arLists = CIBlockSection::GetList(
        array('SORT' => 'ASC'),
        array('IBLOCK_ID' => $IBLOCK_ID, 'SECTION_ID' => $SECTION_ID_FOR_SECTION),
        false,
        array('ID', 'NAME', 'CODE', 'SECTION_PAGE_URL', "UF_EXT_PAGE", "UF_EXT_PARENT_CODE")
    );
    while ($arList = $arLists->GetNext()) {
        // debug($arList);
        if ($arList["UF_EXT_PARENT_CODE"] != "/products/") {
            $bs = new CIBlockSection;
            $arFields = array(
                'IBLOCK_SECTION_ID' => $arSectionExPageId[$arList["UF_EXT_PARENT_CODE"]],
            );
            $bs->Update($arList["ID"], $arFields);
        }
        $count++;
    }
}
if (false) {
    $arSectionExPageId = array();
    $tryElemId = 113013;
    $arLists = CIBlockSection::GetList(
        array('SORT' => 'ASC'),
        array('IBLOCK_ID' => $IBLOCK_ID, 'SECTION_ID' => $SECTION_ID_FOR_SECTION),
        false,
        array('ID', 'NAME', 'CODE', 'SECTION_PAGE_URL', "UF_EXT_PAGE")
    );
    while ($arList = $arLists->GetNext()) {
        // debug($arList);
        $arSectionExPageId[$arList["UF_EXT_PAGE"]] = $arList["ID"];
    }
    // debug($arSectionExPageId);
    $arSelect = array('ID', 'IBLOCK_ID', 'NAME', 'DATE_ACTIVE_FROM', 'PROPERTY_*');
    $arFilter = array('IBLOCK_ID' => $IBLOCK_ID, 'SECTION_ID' => $SECTION_ID_FOR_SECTION, 'ACTIVE' => 'Y');
    $res = CIBlockElement::GetList(array(), $arFilter, false, array('nPageSize' => 1000), $arSelect);
    while ($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();
        $arProps = $ob->GetProperties();
        $el = new CIBlockElement;
        $arFieldsUp = array("IBLOCK_SECTION_ID" => $arSectionExPageId[$arProps["EXT_PARENT_CODE"]["VALUE"]]);
        $resUp = $el->Update(intval($arFields['ID']), $arFieldsUp);
        $count++;
    }
}
if (false) {
    $tryElemId = 113015;
    $elemEnd = 114000;
    $arResIds = array();
    $arSelect = array('ID', 'IBLOCK_ID', 'NAME', 'DATE_ACTIVE_FROM', 'PROPERTY_*');
    $arFilter = array('IBLOCK_ID' => $IBLOCK_ID, ">ID" => $tryElemId, "<ID" => $elemEnd, 'ACTIVE' => 'Y');
    $res = CIBlockElement::GetList(array(), $arFilter, false, array('nPageSize' => 2000), $arSelect);
    while ($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();
        $arProps = $ob->GetProperties();
        //debug($arProps["EXT_PAGE"]["VALUE"]);
        if (strpos($arProps["EXT_PAGE"]["VALUE"], "spravka") === false) {
            // debug($arFields["ID"]);
            // $count++;
            continue;
        }

        $html = "<div>";
        if (!empty($arProps["EXT_PAGE"]["VALUE"])) {
            $htmlDiv = getBlockDetailPage($startUrl, $arProps["EXT_PAGE"]["VALUE"]);
            // debug(count($htmlDiv));
            // $desc = $htmlDiv->find(".main-header")->html();
            // debug($desc);
            // $html .= $desc;
            $arAprint = $htmlDiv->find("a.printBlock");
            foreach ($arAprint as $a) {
                $a = pq($a);
                $a->remove();
            }
            $arImg = $htmlDiv->find("img");
            foreach ($arImg as $img) {
                $img = pq($img);
                $src = $img->attr("src");
                if (strpos($src, "upload") === false) {
                    continue;
                }
                // debug($src);
                $file = $startUrl . $src;
                $arEx = explode("/", $src);
                $newfile = "upload/doorhan/" . end($arEx);
                $from = $file;
                $from = preg_replace_callback('/[а-яА-Яёй\s]+/ui', 'urlencode_str', $from);
                $to = $newfile;
                copy($from, $to);
                $img->attr("src", "/" . $newfile);
            }
            $blocPrint = $htmlDiv->find(".block-print");
            foreach ($blocPrint as $block) {
                $block = pq($block);
                $isAdventages = $block->find(".type17_block")->attr("class");
                if ($isAdventages == "type17_block") {
                    continue;
                }
                $isQuestion = $block->find(".question")->attr("class");
                if ($isQuestion == "question") {
                    continue;
                }
                $bigPicture = $block->find(".text-block-desc-big")->attr("class");
                //debug($bigPicture);
                if (strpos($bigPicture, "text-block-desc-big")  !== false) {
                    $src = $block->find("img")->attr("src");
                    $src = urldecode($src);
                    // debug("src " . $src);
                    $file = $startUrl . $src;
                    $arEx = explode("/", $src);
                    $newfile = "upload/doorhan/" . end($arEx);
                    $from = $file;
                    $from = preg_replace_callback('/[а-яА-Яёй\s]+/ui', 'urlencode_str', $from);
                    $to = $newfile;
                    copy($from, $to);

                    // $newfile = "importFiles/" . end($arEx);
                    // $newfile = "upload/doorhan/" . "test2.png";
                    // $ch = curl_init($file);
                    // $fp = fopen($newfile, 'wb');
                    // curl_setopt($ch, CURLOPT_FILE, $fp);
                    // curl_setopt($ch, CURLOPT_HEADER, 0);
                    // curl_exec($ch);
                    // curl_close($ch);
                    // fclose($fp);
                    // $opts = array(
                    //     'https' => array(
                    //         'method' => "GET",
                    //         'header' => "Accept-language: ru\r\n" .
                    //             "Cookie: foo=bar\r\n"
                    //     )
                    // );

                    // $context = stream_context_create($opts);
                    // $resCopy = file_put_contents($newfile, file_get_contents($file, false, $context));
                    // if (!$resCopy) {
                    //     echo "не удалось скопировать $file...\n";
                    //     debug(error_get_last());
                    // }
                    // debug("newfile " . $newfile);
                    // if (!copy($file, $newfile)) {
                    //     echo "не удалось скопировать $file...\n";
                    //     debug(error_get_last());
                    // }
                    $file_path = $_SERVER["DOCUMENT_ROOT"] . "/" . $newfile;
                    //debug($file_path);
                    $el = new CIBlockElement;
                    $arFieldsUp = array("DETAIL_PICTURE" => CFile::MakeFileArray($file_path));
                    //debug($arFieldsUp);
                    $resUp = $el->Update(intval($arFields['ID']), $arFieldsUp);
                    //debug("resUp " . $resUp);
                    $block->find(".text-block-desc-big")->remove();
                }
                $html .= "<div class='block-print'>";
                $html .= $block->html();
                $html .= "</div>";
            }
        }
        $html .= "</div>";
        //debug($html);
        $el = new CIBlockElement;
        $arFieldsUp = array("DETAIL_TEXT_TYPE" => "html", "DETAIL_TEXT" => $html);
        $resUp = $el->Update(intval($arFields['ID']), $arFieldsUp);
        //debug($resUp);
        $arResIds[] = $arFields["ID"];
        $count++;
    }
}

echo "end" . " total: " . $count;
debug($arResIds);
