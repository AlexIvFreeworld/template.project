<?

namespace IAlex;

if (!defined('IALEX_MENU_INCLUDED')) {
    define('IALEX_MENU_INCLUDED', 'ialex.importxml');
}

use \Bitrix\Main\Application,
    \Bitrix\Main\Type\Collection,
    \Bitrix\Main\Loader,
    \Bitrix\Main\IO\File,
    \Bitrix\Main\Localization\Loc,
    \Bitrix\Main\Config\Option;

Loc::loadMessages(__FILE__);

// initialize module parametrs list and default values
// include_once __DIR__.'/../../parametrs.php';
// include_once __DIR__.'/../../presets.php';

class IhelpXML
{

    public $arPost = array();
    protected $dom = null;
    protected $root = null;
    protected $xml_file_name = "";
    protected $arErr = array();
    protected $IBLOCK_ID = "";
    protected $arSectionIds = array();
    protected $arCategoryCorrespondence = array();

    public function __construct($xml_file_name)
    {
        global $APPLICATION;
        $context = \Bitrix\Main\Application::getInstance()->getContext();
        $request = $context->getRequest();
        $this->arPost = $request->getPostList()->toArray();
        $this->arPost = $APPLICATION->ConvertCharsetArray($this->arPost, 'UTF-8', LANG_CHARSET);
        $this->dom = new \DOMDocument();
        $this->dom->encoding = 'utf-8';
        $this->dom->xmlVersion = '1.0';
        $this->dom->formatOutput = true;
        $this->xml_file_name = $xml_file_name;
        $this->IBLOCK_ID = $this->arPost["block_id"];
        $arSecIn = array();
        $arSecOut = array();
        foreach ($this->arPost as $key => $val) {
            if (strpos($key, "sec_in") !== false) {
                $inKey = str_replace("sec_in_", "", $key);
                $arSecIn[$inKey] = $val;
            }
        }
        $this->arCategoryCorrespondence = array(
            intval($this->arPost["sec_in_1"]) => $this->arPost["sec_out_1"]
        );

        $this->arSectionIds = array($this->arPost["sec_in_1"]);
    }
    public function setRoot()
    {
        $this->root = $this->dom->createElement('elec_market');
        date_default_timezone_set('Europe/Moscow');
        $attr_root_date = new \DOMAttr('date', date("Y-m-d H:i:s"));
        $this->root->setAttributeNode($attr_root_date);
    }

    public function setCurrencies()
    {
        $currencies_node = $this->dom->createElement('currencies');
        $currency_node = $this->dom->createElement('currency');
        $attr_currency_node = new \DOMAttr('id', $this->arPost["currency"]);
        $currency_node->setAttributeNode($attr_currency_node);
        $currencies_node->appendChild($currency_node);
        $this->root->appendChild($currencies_node);
    }
    public function setCategories()
    {
        $arLists = \CIBlockSection::GetList(
            array('SORT' => 'ASC'),
            array('IBLOCK_ID' => $this->IBLOCK_ID, 'ACTIVE' => "Y", "ID" => $this->arSectionIds),
            false,
            array('ID', 'NAME')
        );
        $categories_node = $this->dom->createElement('categories');
        while ($arList = $arLists->GetNext()) {
            // debug($arList);
            $rubricaId = (empty($this->arCategoryCorrespondence[$arList["ID"]])) ? "1523" : $this->arCategoryCorrespondence[$arList["ID"]];
            $category_node = $this->dom->createElement('category', $arList["NAME"]);
            $attr_category_node_1 = new \DOMAttr('id', $arList["ID"]);
            $attr_category_node_2 = new \DOMAttr('rubricaId', $rubricaId);
            $attr_category_node_3 = new \DOMAttr('unit', "PCE");
            $attr_category_node_4 = new \DOMAttr('currencyId', $this->arPost["currency"]);
            $category_node->setAttributeNode($attr_category_node_1);
            $category_node->setAttributeNode($attr_category_node_2);
            $category_node->setAttributeNode($attr_category_node_3);
            $category_node->setAttributeNode($attr_category_node_4);
            $categories_node->appendChild($category_node);
        }
        $this->root->appendChild($categories_node);
    }
    public function setOffers()
    {
        $offers_node = $this->dom->createElement('offers');

        $arSelect = array('ID', 'IBLOCK_ID', 'NAME', 'IBLOCK_SECTION_ID', 'DATE_ACTIVE_FROM', 'DETAIL_PAGE_URL', 'PREVIEW_TEXT', 'DETAIL_TEXT', 'DETAIL_PICTURE', 'PREVIEW_PICTURE', 'PROPERTY_*');
        $arFilter = array('IBLOCK_ID' => $this->IBLOCK_ID, 'ACTIVE' => 'Y', "IBLOCK_SECTION_ID" => $this->arSectionIds);
        $res = \CIBlockElement::GetList(array(), $arFilter, false, array(), $arSelect);
        while ($ob = $res->GetNextElement()) {
            $arFields = $ob->GetFields();
            $arProps = $ob->GetProperties();
            // R52::debug($arFields);
            $arFile = \CFile::GetFileArray(intval($arFields["PREVIEW_PICTURE"]));
            if (empty($arFields["PREVIEW_PICTURE"]) && !empty($arProps["PHOTOS"]["VALUE"])) {
                $firstKey = array_key_first($arProps["PHOTOS"]["VALUE"]);
                $arFile = \CFile::GetFileArray(intval($arProps["PHOTOS"]["VALUE"][$firstKey]));
            }
            if (empty($arFile["SRC"])) {
                // R52::debug($arFields["ID"] . " empty picture");
            }
            $offer_node = $this->dom->createElement('offer');
            $attr_offer_node = new \DOMAttr('id', $arFields["ID"]);
            $offer_node->setAttributeNode($attr_offer_node);

            $child_offer_node_categoryId = $this->dom->createElement('categoryId', $arFields["IBLOCK_SECTION_ID"]);
            $offer_node->appendChild($child_offer_node_categoryId);
            $child_offer_node_title = $this->dom->createElement('title', $arFields["NAME"]);
            $offer_node->appendChild($child_offer_node_title);
            $child_offer_node_url = $this->dom->createElement('url', "https://www.contravt.ru" . $arFields["DETAIL_PAGE_URL"]);
            $offer_node->appendChild($child_offer_node_url);
            $child_offer_node_unit = $this->dom->createElement('unit', "PCE");
            $offer_node->appendChild($child_offer_node_unit);
            if (!empty($arFile["SRC"])) {
                $child_offer_node_picture = $this->dom->createElement('picture', "https://www.contravt.ru" . $arFile["SRC"]);
                $offer_node->appendChild($child_offer_node_picture);
            }
            if (!empty($arFields["PREVIEW_TEXT"])) {
                $child_offer_node_tizer = $this->dom->createElement('tizer', str_replace(["&nbsp;"], [" "], $arFields["PREVIEW_TEXT"]));
                $offer_node->appendChild($child_offer_node_tizer);
            }
            $offers_node->appendChild($offer_node);
        }

        $this->root->appendChild($offers_node);
        $this->dom->appendChild($this->root);
    }
    public function saveXmlFile()
    {
        $res = $this->dom->save($this->xml_file_name);
        if (empty($res)) {
            $this->arErr["Error save file"];
        }
        $response = json_encode($this->arErr);
        return $response;
    }
}
