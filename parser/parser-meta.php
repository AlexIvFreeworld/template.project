<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
\Bitrix\Main\Loader::includeModule('iblock');
require_once 'phpQuery/phpQuery/phpQuery.php';
die();
$IBLOCK_ID = 95;
// $ELEMENT_ID = 849;
$baseLink = "https://www.contravt.ru";
$arSelect = array('ID', 'IBLOCK_ID', 'NAME', 'DATE_ACTIVE_FROM', 'PROPERTY_*');
$arFilter = array('IBLOCK_ID' => $IBLOCK_ID, 'ACTIVE' => 'Y');
$count = 0;
$res = CIBlockElement::GetList(array(), $arFilter, false, array('nPageSize' => 1000000), $arSelect);
while ($ob = $res->GetNextElement()) {
    $arFields = $ob->GetFields();
    $arProps = $ob->GetProperties();
    $linkOldItem = $baseLink . $arProps["LINK_OLD_PAGE"]["VALUE"];
    // R52::debug($linkOldItem);
    if (empty($arProps["LINK_OLD_PAGE"]["VALUE"])) {
        \Bitrix\Main\Diag\Debug::dumpToFile(array('Empty link in $arFields["ID"]' => $arFields["ID"]), "", "/log.txt");
        continue;
    }
    $doc = file_get_contents($linkOldItem);
    $pqCatalog = phpQuery::newDocument($doc);
    $title = $pqCatalog->find("title")->text();
    // R52::debug($title);
    if (empty($title)) {
        \Bitrix\Main\Diag\Debug::dumpToFile(array('Empty title in $arFields["ID"]' => $arFields["ID"]), "", "/log.txt");
    }

    $description = $pqCatalog->find("meta[name='description']")->attr("content");
    // R52::debug($description);
    if (empty($description)) {
        \Bitrix\Main\Diag\Debug::dumpToFile(array('Empty $description in $arFields["ID"]' => $arFields["ID"]), "", "/log.txt");
    }

    // $ipropValues = new \Bitrix\Iblock\InheritedProperty\ElementValues($IBLOCK_ID, $arFields["ID"]);
    // $IPROPERTY  = $ipropValues->getValues();
    // R52::debug($IPROPERTY);
    // $ipropIblockValues = new \Bitrix\Iblock\InheritedProperty\IblockValues($IBLOCK_ID);
    // $ipropIblockValues->clearValues();

    $ipropElementTemplates = new \Bitrix\Iblock\InheritedProperty\ElementTemplates($IBLOCK_ID, $arFields["ID"]);
    $templates = $ipropElementTemplates->findTemplates();
    // R52::debug($templates);
    $newTemplates = array('ELEMENT_META_TITLE' => $title, "ELEMENT_META_DESCRIPTION" => $description);
    $ipropElementTemplates->set($newTemplates);
    $count++;
}
R52::debug("end");
R52::debug($count);
