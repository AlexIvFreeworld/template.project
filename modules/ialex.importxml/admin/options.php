<?

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\Config\Option;

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_before.php');
require($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_admin_after.php');

global $APPLICATION;
IncludeModuleLangFile(__FILE__);

$moduleID = "ialex.importxml";
\Bitrix\Main\Loader::includeModule($moduleID);
$RIGHT = $APPLICATION->GetGroupRight($moduleID);
if ($RIGHT >= "R") {
    $arErr = array();
    $context = \Bitrix\Main\Application::getInstance()->getContext();
    $request = $context->getRequest();
    $arPost = $request->getPostList()->toArray();
    $arPost = $APPLICATION->ConvertCharsetArray($arPost, 'UTF-8', LANG_CHARSET);
    \Bitrix\Main\Diag\Debug::dumpToFile(array('date("d-m-Y H:i:s")' => date("d-m-Y H:i:s")), "", "/log.txt");
    \Bitrix\Main\Diag\Debug::dumpToFile(array('$arPost' => $arPost), "", "/log.txt");

    if (isset($arPost["type"]) && $arPost["type"] == "xml") {
        $arCategoryCorrespondence = array(
            intval($arPost["sec_in_1"]) => $arPost["sec_out_1"]
        );
        $arSectionIds = array($arPost["sec_in_1"]);
        $dom = new DOMDocument();
        $dom->encoding = 'utf-8';
        $dom->xmlVersion = '1.0';
        $dom->formatOutput = true;
        $xml_file_name = $_SERVER["DOCUMENT_ROOT"] . "/products.xml";

        $root = $dom->createElement('elec_market');
        date_default_timezone_set('Europe/Moscow');
        $attr_root_date = new DOMAttr('date', date("Y-m-d H:i:s"));
        $root->setAttributeNode($attr_root_date);
        // set currencies
        $currencies_node = $dom->createElement('currencies');
        $currency_node = $dom->createElement('currency');
        $attr_currency_node = new DOMAttr('id', $arPost["currency"]);
        $currency_node->setAttributeNode($attr_currency_node);
        $currencies_node->appendChild($currency_node);
        $root->appendChild($currencies_node);
        // /set currencies
        // to check exist id iblock
        $IBLOCK_ID = $arPost["block_id"];
        $arLists = CIBlockSection::GetList(
            array('SORT' => 'ASC'),
            array('IBLOCK_ID' => $IBLOCK_ID, 'ACTIVE' => "Y", "ID" => $arSectionIds),
            false,
            array('ID', 'NAME')
        );
        // set categories
        $categories_node = $dom->createElement('categories');
        while ($arList = $arLists->GetNext()) {
            // debug($arList);
            $rubricaId = (empty($arCategoryCorrespondence[$arList["ID"]])) ? "1523" : $arCategoryCorrespondence[$arList["ID"]];

            $category_node = $dom->createElement('category', $arList["NAME"]);
            $attr_category_node_1 = new DOMAttr('id', $arList["ID"]);
            $attr_category_node_2 = new DOMAttr('rubricaId', $rubricaId);
            $attr_category_node_3 = new DOMAttr('unit', "PCE");
            $attr_category_node_4 = new DOMAttr('currencyId', $arPost["currency"]);
            $category_node->setAttributeNode($attr_category_node_1);
            $category_node->setAttributeNode($attr_category_node_2);
            $category_node->setAttributeNode($attr_category_node_3);
            $category_node->setAttributeNode($attr_category_node_4);
            $categories_node->appendChild($category_node);
        }
        $root->appendChild($categories_node);
        // /set categories

        // set offers
        $offers_node = $dom->createElement('offers');

        $arSelect = array('ID', 'IBLOCK_ID', 'NAME', 'IBLOCK_SECTION_ID', 'DATE_ACTIVE_FROM', 'DETAIL_PAGE_URL', 'PREVIEW_TEXT', 'DETAIL_TEXT', 'DETAIL_PICTURE', 'PREVIEW_PICTURE', 'PROPERTY_*');
        $arFilter = array('IBLOCK_ID' => $IBLOCK_ID, 'ACTIVE' => 'Y', "IBLOCK_SECTION_ID" => $arSectionIds);
        $res = CIBlockElement::GetList(array(), $arFilter, false, array(), $arSelect);
        while ($ob = $res->GetNextElement()) {
            $arFields = $ob->GetFields();
            $arProps = $ob->GetProperties();
            // R52::debug($arFields);
            $arFile = CFile::GetFileArray(intval($arFields["PREVIEW_PICTURE"]));
            if (empty($arFields["PREVIEW_PICTURE"]) && !empty($arProps["PHOTOS"]["VALUE"])) {
                $firstKey = array_key_first($arProps["PHOTOS"]["VALUE"]);
                $arFile = CFile::GetFileArray(intval($arProps["PHOTOS"]["VALUE"][$firstKey]));
            }
            if (empty($arFile["SRC"])) {
                // R52::debug($arFields["ID"] . " empty picture");
            }
            $offer_node = $dom->createElement('offer');
            $attr_offer_node = new DOMAttr('id', $arFields["ID"]);
            $offer_node->setAttributeNode($attr_offer_node);

            $child_offer_node_categoryId = $dom->createElement('categoryId', $arFields["IBLOCK_SECTION_ID"]);
            $offer_node->appendChild($child_offer_node_categoryId);
            $child_offer_node_title = $dom->createElement('title', $arFields["NAME"]);
            $offer_node->appendChild($child_offer_node_title);
            $child_offer_node_url = $dom->createElement('url', "https://www.contravt.ru" . $arFields["DETAIL_PAGE_URL"]);
            $offer_node->appendChild($child_offer_node_url);
            $child_offer_node_unit = $dom->createElement('unit', "PCE");
            $offer_node->appendChild($child_offer_node_unit);
            if (!empty($arFile["SRC"])) {
                $child_offer_node_picture = $dom->createElement('picture', "https://www.contravt.ru" . $arFile["SRC"]);
                $offer_node->appendChild($child_offer_node_picture);
            }
            if (!empty($arFields["PREVIEW_TEXT"])) {
                $child_offer_node_tizer = $dom->createElement('tizer', str_replace(["&nbsp;"], [" "], $arFields["PREVIEW_TEXT"]));
                $offer_node->appendChild($child_offer_node_tizer);
            }
            // $child_offer_node_description = $dom->createElement('description', str_replace(["&nbsp;"], [" "], $arFields["~DETAIL_TEXT"]));
            // $attr_description_node = new DOMAttr('format', "html");
            // $child_offer_node_description->setAttributeNode($attr_description_node);
            // $offer_node->appendChild($child_offer_node_description);
            $offers_node->appendChild($offer_node);
        }

        $root->appendChild($offers_node);
        $dom->appendChild($root);
        $res = $dom->save($xml_file_name);
        \Bitrix\Main\Diag\Debug::dumpToFile(array('res : ' => $res), "", "/log.txt");
    }
    $response = json_encode($arErr);
    $APPLICATION->RestartBuffer();
    echo $response;
    die();
}
