<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");
$APPLICATION->SetPageProperty("description", "Комплексное оснащение учебных учреждений, обеспечение детских садов и школ Нижнего Новгорода и Нижегородской области.");
$APPLICATION->SetPageProperty("title", "Оснащение образовательных учреждений");
$APPLICATION->SetTitle("Интернет-магазин \"Аргумент\"");

use Bitrix\Sale;
// global $arrFilterMainHits;
// $arrFilterMainHits = array('!=PROPERTY_SHOW_IN_CATALOG_SEARCH' => "52");
// phpinfo();

?><?$APPLICATION->IncludeComponent(
	"bitrix:search.page", 
	".default", 
	array(
		"AJAX_MODE" => "N",
		"AJAX_OPTION_ADDITIONAL" => "",
		"AJAX_OPTION_HISTORY" => "N",
		"AJAX_OPTION_JUMP" => "N",
		"AJAX_OPTION_STYLE" => "Y",
		"CACHE_TIME" => "3600",
		"CACHE_TYPE" => "A",
		"CHECK_DATES" => "N",
		"DEFAULT_SORT" => "rank",
		"DISPLAY_BOTTOM_PAGER" => "Y",
		"DISPLAY_TOP_PAGER" => "Y",
		"FILTER_NAME" => "",
		"NO_WORD_LOGIC" => "Y",
		"PAGER_SHOW_ALWAYS" => "Y",
		"PAGER_TEMPLATE" => "",
		"PAGER_TITLE" => "Результаты поиска",
		"PAGE_RESULT_COUNT" => "50",
		"PATH_TO_USER_PROFILE" => "",
		"RATING_TYPE" => "",
		"RESTART" => "Y",
		"SHOW_RATING" => "",
		"SHOW_WHEN" => "N",
		"SHOW_WHERE" => "N",
		"USE_LANGUAGE_GUESS" => "N",
		"USE_SUGGEST" => "N",
		"USE_TITLE_RANK" => "Y",
		"arrFILTER" => array(
			0 => "iblock_content", // select by type infoblock
		),
		"arrFILTER_iblock_content" => array(
			0 => "4", // select by id infoblock
			1 => "all",
		),
		"arrWHERE" => "",
		"COMPONENT_TEMPLATE" => ".default"
	),
	false
);?><? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>