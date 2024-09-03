<?
namespace AI;

class R52
{
    public static function test()
    {
        echo "r52";
    }
    public static function debug($data)
    {
        global $USER;
        if ($USER->IsAdmin()) {
            echo "Вы администратор!";
            echo "<pre>" . print_r($data, 1) . "</pre>";
        }
    }
    public static function dump($data)
    {
        echo "Вы администратор!";
        echo "<pre>" . print_r($data, 1) . "</pre>";
    }
    public static function debugBackTrace()
    {
        global $USER;
        if ($USER->IsAdmin()) {
            echo 'Вы администратор!';
            echo '<pre>' . print_r(debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS), 1) . '</pre>';
        }
    }
    public static function getHbValueByXmlId($idBlock, $xmlIdElem, $propertyCode)
    {
        require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
        CModule::IncludeModule("highloadblock");

        $hlbl = $idBlock; // Указываем ID нашего highloadblock блока к которому будет делать запросы.
        $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getById($hlbl)->fetch();

        $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
            "filter" => array("UF_XML_ID" => $xmlIdElem)  // Задаем параметры фильтра выборки
        ));
        $arData = $rsData->Fetch();
        return $arData[$propertyCode];
    }
    public static function getHbValueById($idBlock, $idElem, $propertyCode)
    {
        require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
        CModule::IncludeModule("highloadblock");

        $hlbl = $idBlock; // Указываем ID нашего highloadblock блока к которому будет делать запросы.
        $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getById($hlbl)->fetch();

        $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
            "filter" => array("ID" => $idElem)  // Задаем параметры фильтра выборки
        ));
        $arData = $rsData->Fetch();
        return $arData[$propertyCode];
    }
    public static function getHbSrcByXmlId($idBlock, $xmlIdElem, $propertyCode)
    {
        require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
        CModule::IncludeModule("highloadblock");

        $hlbl = $idBlock; // Указываем ID нашего highloadblock блока к которому будет делать запросы.
        $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getById($hlbl)->fetch();

        $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array("ID" => "ASC"),
            "filter" => array("UF_XML_ID" => $xmlIdElem)  // Задаем параметры фильтра выборки
        ));
        $arData = $rsData->Fetch();
        $arFile = CFile::GetFileArray($arData[$propertyCode]);
        return $arFile["SRC"];
    }
    public static function getHbArray($idBlock)
    {
        require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
        CModule::IncludeModule("highloadblock");
        $arResult = array();

        $hlbl = $idBlock; // Указываем ID нашего highloadblock блока к которому будет делать запросы.
        $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getById($hlbl)->fetch();

        $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $rsData = $entity_data_class::getList(array(
            "select" => array("*"),
            "order" => array("UF_SORT" => "ASC"),
            "filter" => array("*")  // Задаем параметры фильтра выборки
        ));
        while ($arData = $rsData->Fetch()) {
            $arResult[] = $arData;
        }
        return $arResult;
    }
    public static function getPropValue($blockId, $elemId, $propCode)
    {
        CModule::IncludeModule("iblock");
        $val = "";
        $arSelect = array('ID', 'IBLOCK_ID', 'NAME', 'DATE_ACTIVE_FROM', 'PROPERTY_*');
        $arFilter = array('IBLOCK_ID' => $blockId, 'ID' => $elemId, 'ACTIVE' => 'Y');
        $res = CIBlockElement::GetList(array(), $arFilter, false, array('nPageSize' => 1), $arSelect);
        while ($ob = $res->GetNextElement()) {
            $arProps = $ob->GetProperties();
            $val = $arProps[$propCode]["VALUE"];
        }
        return $val;
    }
    public static function getArPropValue($blockId, $elemId, $propCode)
    {
        CModule::IncludeModule("iblock");
        $arRes = array();
        $arSelect = array('ID', 'IBLOCK_ID', 'NAME', 'DATE_ACTIVE_FROM', 'PROPERTY_*');
        $arFilter = array('IBLOCK_ID' => $blockId, 'ID' => $elemId, 'ACTIVE' => 'Y');
        $res = CIBlockElement::GetList(array(), $arFilter, false, array('nPageSize' => 1), $arSelect);
        while ($ob = $res->GetNextElement()) {
            $arProps = $ob->GetProperties();
            foreach ($arProps[$propCode]["VALUE"] as $item) {
                $arRes[] = $item;
            }
        }
        return $arRes;
    }
    public static function getArPropAll($blockId, $elemId)
    {
        CModule::IncludeModule("iblock");
        $arRes = array();
        $arSelect = array('ID', 'IBLOCK_ID', 'NAME', 'DATE_ACTIVE_FROM', 'PROPERTY_*');
        $arFilter = array('IBLOCK_ID' => $blockId, 'ID' => $elemId, 'ACTIVE' => 'Y');
        $res = CIBlockElement::GetList(array(), $arFilter, false, array('nPageSize' => 1), $arSelect);
        while ($ob = $res->GetNextElement()) {
            $arProps = $ob->GetProperties();
            foreach ($arProps as $item) {
                $arRes[$item["CODE"]] = $item;
            }
        }
        return $arRes;
    }
    public static function getHtmlValue($blockId, $elemId, $propCode)
    {
        CModule::IncludeModule("iblock");
        $val = "";
        $arSelect = array('ID', 'IBLOCK_ID', 'NAME', 'DATE_ACTIVE_FROM', 'PROPERTY_*');
        $arFilter = array('IBLOCK_ID' => $blockId, 'ID' => $elemId, 'ACTIVE' => 'Y');
        $res = CIBlockElement::GetList(array(), $arFilter, false, array('nPageSize' => 1), $arSelect);
        while ($ob = $res->GetNextElement()) {
            $arProps = $ob->GetProperties();
            $val = $arProps[$propCode]["~VALUE"]["TEXT"];
        }
        return $val;
    }
    public static function getImgpValueSrc($blockId, $elemId, $propCode, $width, $height)
    {
        CModule::IncludeModule("iblock");
        $val = "";
        $arSelect = array('ID', 'IBLOCK_ID', 'NAME', 'DATE_ACTIVE_FROM', 'PROPERTY_*');
        $arFilter = array('IBLOCK_ID' => $blockId, 'ID' => $elemId, 'ACTIVE' => 'Y');
        $res = CIBlockElement::GetList(array(), $arFilter, false, array('nPageSize' => 1), $arSelect);
        while ($ob = $res->GetNextElement()) {
            $arProps = $ob->GetProperties();
            $val = $arProps[$propCode]["VALUE"];
        }
        $resize_image = CFile::ResizeImageGet(
            $val,
            array(
                'width' => $width,
                'height' => $height,
            ),
            BX_RESIZE_IMAGE_PROPORTIONAL,
            true
        );
        return $resize_image["src"];
    }
    public static function validInput($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data, ENT_QUOTES);
        return $data;
    }
    public static function getClassPath($className)
    {
        $a = new \ReflectionClass($className);
        self::debug($a->getFileName());
    }
}
