<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
\Bitrix\Main\Loader::includeModule('iblock');
die();
function debug($data)
{
    echo "Вы администратор!";
    echo "<pre>" . print_r($data, 1) . "</pre>";
}
// set_time_limit(1000);
$filePath = $_SERVER['DOCUMENT_ROOT'] . '/export-in/block.xml';
// $xml = simplexml_load_file("http://newtss.r52.ru/export2/index.xml");
// $xml = simplexml_load_file( $filePath,"SimpleXMLElement", LIBXML_NOCDATA);
// debug($xml->Классификатор);

$arSection = array();

function start_element($parser, $element_name, $element_attrs)
{
    switch ($element_name) {
        case 'Свойство':
             echo '<h1>Свойство</h1><ul>';
            break;
        case 'Ид':
             echo '<li>';
            $arSection[] = $element_name;
            break;
    }
}

function end_element($parser, $element_name)
{
    switch ($element_name) {
        case 'Свойство':
             echo '</ul>';
            break;
        case 'Ид':
             echo '</li>';
            break;
    }
}


function character_data($parser, $data)
{
    echo htmlentities($data);
}

$parser = xml_parser_create();
xml_set_element_handler($parser, 'start_element', 'end_element');
xml_set_character_data_handler($parser, 'character_data');

$fp = fopen($filePath, 'r')
    or die("Cannot open keyword-data.xml!");


while ($data = fread($fp, 4096)) {
    xml_parse($parser, $data, feof($fp)) or
        die(sprintf(
            'XML ERROR: %s at line %d',
            xml_error_string(xml_get_error_code($parser)),
            xml_get_current_line_number($parser)
        ));
}


xml_parser_free($parser);
debug($arSection);