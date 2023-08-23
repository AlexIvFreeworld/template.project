<?
require_once($_SERVER['DOCUMENT_ROOT'] . "/bitrix/modules/main/include/prolog_before.php");
\Bitrix\Main\Loader::includeModule('iblock');
function debug($array) {
		print '<pre>';
		print_r($array);
		print '</pre>';
}
die();
function wrapPhone($content)
{
	$arContent = explode(" ", $content);
	// debug($arContent);
	$regexp = '/^[0-9+.()-]{11,}+$/i';
	foreach ($arContent as &$item) {
        $isP = "";
        if(preg_match("/[0-9]{10,}/", $item) && preg_match("/[\w|\W]/", $item)){
            echo $item . "<br>";
            if(preg_match("/\/p/", $item)){
                $isP = "</p>";
                // echo "exist teg p <br>";
            }
            // echo "exist number <br>";
            $item = preg_replace('/[^0-9+]/',"",$item);
            $item = '<a href="tel:' . $item . '">' . $item . '</a>' . $isP;
            debug ($item);
        }
	}
	$newContent = implode(" ", $arContent);

	return $newContent;
}
$count = 0;
$IBLOCK_ID = 9;
$sectionCode = "";
$TEST_EL_ID = 31;
$arSelect = array('ID', 'IBLOCK_ID', 'NAME', 'DETAIL_TEXT', 'DATE_ACTIVE_FROM', 'PROPERTY_*');
$arFilter = array('IBLOCK_ID' => $IBLOCK_ID, /*"ID" => $TEST_EL_ID,*/ 'ACTIVE' => 'Y');
$res = CIBlockElement::GetList(array(), $arFilter, false, array('nPageSize' => 1000), $arSelect);
while ($ob = $res->GetNextElement()) {
	$arFields = $ob->GetFields();
	$arProps = $ob->GetProperties();
    debug("EL_ID " . $arFields["ID"]);
    // $newText = htmlspecialchars($arFields["DETAIL_TEXT"], ENT_QUOTES);
    $newText = $arFields["DETAIL_TEXT"];
	$newDetailText = wrapPhone($newText);
    // debug($newDetailText);
    $count++;
    continue;
	$el = new CIBlockElement;
	$arFieldsUp = array("DETAIL_TEXT" => $newDetailText);
	$resUp = $el->Update(intval($arFields['ID']), $arFieldsUp);
	//debug($resUp);
}

debug("total: " . $count . "end");