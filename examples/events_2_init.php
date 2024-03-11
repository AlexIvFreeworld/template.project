<?

/* if(isset($_GET["nid"]) && isset($_GET["a"])){
	if(CModule::IncludeModule("iblock")){ 
	
	$arFilter = Array(
	 "IBLOCK_ID"=>12, 
	 "ACTIVE"=>"Y", 
	 "PROPERTY_OLD_ID"=>$_GET["nid"]
	 );
	$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), $arFilter, Array("NAMY","CODE"));
	while($ar_fields = $res->GetNext())
	{
	 echo $ar_fields["CODE"]."<br>";
	}
	}
} */

//include_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/wsrubi.smtp/classes/general/wsrubismtp.php");

function recaptcha()
{
	echo '<div class="g-recaptcha" data-sitekey="6Lcoq8QUAAAAAOxY3I3mwj8BdgmZcLRn2zulWgzA"></div>';

	echo '<div class="text-danger" id="recaptchaError"></div>';
}

if (Bitrix\Main\Loader::includeModule('artamonov.api')) (new Artamonov\Api\Init)->start();
if (CSite::InDir('/bitrix/')) {
	require_once $_SERVER['DOCUMENT_ROOT'] . '/local/templates/kit/config.php';
};
define("LOGO",  COption::GetOptionString('r52.nastroyki', "LOGO"));
define("PHONE",  COption::GetOptionString('r52.nastroyki', "PHONE"));
define("MAIL",  COption::GetOptionString('r52.nastroyki', "MAIL"));
define("MAIL",  COption::GetOptionString('r52.nastroyki', "MAIL"));
define("ADRESS",  COption::GetOptionString('r52.nastroyki', "ADRESS"));
define("SITEDESC",  COption::GetOptionString('r52.nastroyki', "SITEDESC"));
// Параметры темы
define("COLORPRIMARY",  COption::GetOptionString('r52.nastroyki', "COLORPRIMARY"));
define("COLORALT",  COption::GetOptionString('r52.nastroyki', "COLORALT"));
define("COLORDARK",  COption::GetOptionString('r52.nastroyki', "COLORDARK"));
define("COLORBODY",  COption::GetOptionString('r52.nastroyki', "COLORBODY"));
define("COLORHEADING",  COption::GetOptionString('r52.nastroyki', "COLORHEADING"));
define("BUTTONCOLOR",  COption::GetOptionString('r52.nastroyki', "BUTTONCOLOR"));
define("BUTTONOUTLINE",  COption::GetOptionString('r52.nastroyki', "BUTTONOUTLINE"));
define("BUTTONROUND",  COption::GetOptionString('r52.nastroyki', "BUTTONROUND"));
define("BUTTONGRADIENT",  COption::GetOptionString('r52.nastroyki', "BUTTONGRADIENT"));

if (!function_exists('custom_mail') && COption::GetOptionString("webprostor.smtp", "USE_MODULE") == "Y") {
	function custom_mail($to, $subject, $message, $additional_headers = '', $additional_parameters = '')
	{
		if (CModule::IncludeModule("webprostor.smtp")) {
			$smtp = new CWebprostorSmtp("s1");
			$result = $smtp->SendMail($to, $subject, $message, $additional_headers, $additional_parameters);

			if ($result)
				return true;
			else
				return false;
		}
	}
}

AddEventHandler("main", "OnBeforeEventAdd", array("EventsR52", "OnBeforeEventAddHandler"));
class EventsR52
{
	public static function OnBeforeEventAddHandler(&$event, &$lid, &$arFields)
	{
		//\Bitrix\Main\Diag\Debug::dumpToFile(array('$event' => $event), "", "/log.txt");
		//\Bitrix\Main\Diag\Debug::dumpToFile(array('$arFields' => $arFields), "", "/log.txt");
		//\Bitrix\Main\Diag\Debug::dumpToFile(array('$_FILES' => $_FILES), "", "/log.txt");
		$name = date("Y-m-d H:i:s") . " : ";
		$text = "";
		$file_path = "";
		if ($event == "SIGN_UP") {
			$name .= "Записаться на приём";
			$text .= $arFields["MESSAGE"];

		}
		if ($event == "FEEDBACK_FORM") {
			$name .= "Обратная связь - " . $arFields["AUTHOR"];
			$text .= $arFields["HTML_FIELDS"];

		}
		if ($event == "PROFILE") {
			$name .= "Анкета - " . $arFields["PROFILE_FIO"];
			$text .= "Ваше имя: " . $arFields["PROFILE_FIO"] . "</br>";
			$text .= "Страна, город: " . $arFields["PROFILE_CITY"] . "</br>";
			$text .= "Год рождения: " . $arFields["PROFILE_YEAR"] . "</br>";
			$text .= "Рост: " . $arFields["PROFILE_HEIGHT"] . "</br>";
			$text .= "Вес: " . $arFields["PROFILE_WEIGHT"] . "</br>";
			$text .= "Образование: " . $arFields["PROFILE_EDUCATION"] . "</br>";
			$text .= "Сфера деятельности: " . $arFields["PROFILE_ACTIVITY"] . "</br>";
			$text .= "Владелец или должность: " . $arFields["PROFILE_POSITION"] . "</br>";
			$text .= "Наличие детей: " . $arFields["PROFILE_CHILDREN"] . "</br>";
			$text .= "Телефон: " . $arFields["PROFILE_PHONE"] . "</br>";
			$text .= "Email *: " . $arFields["PROFILE_EMAIL"] . "</br>";
		}
		if ($_FILES['profile_photo']['name']) {
			$file = array(
				"name" => $_FILES['profile_photo']['name'],
				"size" => $_FILES['profile_photo']['size'],
				"tmp_name" => $_FILES['profile_photo']['tmp_name'],
				"type" => $_FILES['profile_photo']['type'],
				"del" => "",
			);
			$fid = CFile::SaveFile($file, "fprofiles");
			$arFile = CFile::GetFileArray(intval($fid));
			//\Bitrix\Main\Diag\Debug::dumpToFile(array('$arFile' => $arFile), "", "/log.txt");
			$file_path = $arFile["SRC"];
		}
		if (CModule::IncludeModule("iblock")) {
			$el = new CIBlockElement;
			$arLoadProductArray = array(
				"IBLOCK_ID"      => 17,
				// "PROPERTY_VALUES" => $PROP,
				"NAME"           => $name,
				// "CODE"           => translit_sef($offer->model),
				"ACTIVE"         => "Y",
				'PREVIEW_TEXT' => $text,
				"PREVIEW_TEXT_TYPE" => "html",
				"PREVIEW_PICTURE" => (!empty($file_path)) ? CFile::MakeFileArray($file_path):false,
			);
			$PRODUCT_ID = $el->Add($arLoadProductArray);
			if ($PRODUCT_ID > 0) {
				//\Bitrix\Main\Diag\Debug::dumpToFile(array('New ID: ' => $PRODUCT_ID), "", "/log.txt");
			} else {
				//\Bitrix\Main\Diag\Debug::dumpToFile(array('$el->LAST_ERROR: ' => $el->LAST_ERROR), "", "/log.txt");
			}
		}
	}
}
