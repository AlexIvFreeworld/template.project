<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Стройбаза НН - стройматериалы с доставкой на дом в Нижнем Новгороде | Интернет магазин стройматериалов в нижнем новгороде");
$APPLICATION->SetPageProperty("title", "Интернет-магазин стройматериалов | ТД Благов");
$APPLICATION->SetPageProperty("viewed_show", "Y");
$APPLICATION->SetTitle("Сайт по умолчанию");
?>
<?
$IBLOCK_ID = 18;
$SECTION_ID = 230;
$EL_ID = 56796;
if (false) {
    $arSelect = array('ID', 'IBLOCK_ID', 'NAME', 'DATE_ACTIVE_FROM', 'PROPERTY_*');
    $arFilter = array('IBLOCK_ID' => $IBLOCK_ID, /*'SECTION_ID' => $SECTION_ID,*/ 'ACTIVE' => 'Y');
    $res = CIBlockElement::GetList(array(), $arFilter, false, array(), $arSelect);
    $update = 0;
    $emptyUpdate = 0;
    $allElements = 0;
    while ($ob = $res->GetNextElement()) {
        $arFields = $ob->GetFields();
        $arProps = $ob->GetProperties();
        //debug($arFields["NAME"]);
        // debug($arProps);

        $arSKU = CCatalogSKU::getOffersList(
            $arFields["ID"],
            0,
            array('ACTIVE' => 'Y'),
            array('ID', 'NAME', 'CODE', 'PRICE'),
            array()
        );
        // debug($arSKU[$arFields["ID"]]);
        if (!empty($arSKU[$arFields["ID"]])) {
            $minPrice = 0;
            $firstItem = true;
            foreach ($arSKU[$arFields["ID"]] as $id => $arItem) {
                $price = GetCatalogProductPrice($id, 1);
                //debug($price["PRICE"]);
                if ($firstItem) {
                    $minPrice = intval($price["PRICE"]);
                    $firstItem = false;
                } else {
                    $minPrice = ($minPrice < intval($price["PRICE"])) ? $minPrice : intval($price["PRICE"]);
                }
            }
            //debug("minprice " . $minPrice);
            if ($minPrice > 0) {
                CIBlockElement::SetPropertyValuesEx($arFields["ID"], $IBLOCK_ID, array('PRICE_SORT' => $minPrice));
                $update++;
            } else {
                CIBlockElement::SetPropertyValuesEx($arFields["ID"], $IBLOCK_ID, array('PRICE_SORT' => 999999999));
                $emptyUpdate++;
            }
        } else {
            if (!empty($arProps["MINIMUM_PRICE"]["VALUE"]) && intval($arProps["MINIMUM_PRICE"]["VALUE"]) > 0) {
                CIBlockElement::SetPropertyValuesEx($arFields["ID"], $IBLOCK_ID, array('PRICE_SORT' => intval($arProps["MINIMUM_PRICE"]["VALUE"])));
                $update++;
            } else {
                CIBlockElement::SetPropertyValuesEx($arFields["ID"], $IBLOCK_ID, array('PRICE_SORT' => 999999999));
                $emptyUpdate++;
            }
        }

        $allElements++;
    }
}
debug("\$update : " . $update);
debug("\$emptyUpdate : " . $emptyUpdate);
debug("\$allElements : " . $allElements);
?>
<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>