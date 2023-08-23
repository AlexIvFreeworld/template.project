<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
$phone = $_POST['phone'];
$item = $_POST['id'];
$name = $_POST['name'];
$mail = $_POST['email'];
$type = $_POST['type'];
$text = $_POST['text'];
CModule::IncludeModule("iblock");
?>

<?
//&& empty($arErr)::dumpToFile(array('$_POST' => $_POST), "", "/log.txt");
$arErr = array();

define("SECRET_KEY", "6LcTHJUnAAAAAIzo-hfzalR3a7Uh3RzrYmgTXvUB");
$Response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . SECRET_KEY . "&response={$_POST["token"]}");
$Return = json_decode($Response, true);
//&& empty($arErr)::dumpToFile(array('$Return' => $Return), "", "/log.txt");

if ($Return["success"] === false) {
	$arErr[] = "Вы робот!";
}
//&& empty($arErr)::dumpToFile(array('$arErr' => $arErr), "", "/log.txt");

$el = new CIBlockElement;
if ($type == "opt" && empty($arErr)) {
	$arLoadProductArray = array(
		"IBLOCK_ID"      => 7,
		"PROPERTY_VALUES" => array(
			"PHONE" => $phone,
			"STATUS" => array("VALUE" => 20),
			"NAME" => $name,
			"MAIL" => $mail,
		),
		"NAME"           => $item,
		"ACTIVE"         => "Y",
	);

	$arEventFields = array(
		"PHONE" => $phone,
		"TOVAR" => $item,
		"NAME" => $name,
		"MAIL" => $mail,
	);
	$PRODUCT_ID = $el->Add($arLoadProductArray);
	$deb = CEvent::Send("OPTPRICE", 's1', $arEventFields);
} elseif ($type == "cont" && empty($arErr)) {
	$arLoadProductArray = array(
		"IBLOCK_ID"      => 13,
		"PROPERTY_VALUES" => array(
			"MAIL" => $mail,
			"STATUS" => array("VALUE" => 2050),
			"NAME" => $name,
		),
		"NAME"           => "Новый запрос",
		"ACTIVE"         => "Y",
		"DETAIL_TEXT" => $text,
	);

	$arEventFields = array(
		"MAIL" => $mail,
		"NAME" => $name,
	);
	$PRODUCT_ID = $el->Add($arLoadProductArray);
	$deb = CEvent::Send("CALLBACK", 's1', $arEventFields);
} elseif ($type == "vac" && empty($arErr)) {

	$file_id = CFile::SaveFile($_FILES['file'], "vac");

	$arLoadProductArray = array(
		"IBLOCK_ID"      => 14,
		"PROPERTY_VALUES" => array(
			"MAIL" => $mail,
			"PHONE" => $phone,
			"STATUS" => array("VALUE" => 2053),
			"NAME" => $name,
			"DOLZH" => $_POST['dolzh'],
			"FILE" => array(
				"n0" => array(
					"VALUE" => $file_id
				)
			)

		),
		"NAME"           => "Новый отклик",
		"ACTIVE"         => "Y",
		"DETAIL_TEXT" => $text,
	);

	$arEventFields = array(
		"MAIL" => $mail,
		"NAME" => $name,
	);

	$PRODUCT_ID = $el->Add($arLoadProductArray);
	$deb = CEvent::Send("CALLBACK", 's1', $arEventFields);
} elseif ($type == "call" && empty($arErr)) {
	$arLoadProductArray = array(
		"IBLOCK_ID"      => 8,
		"PROPERTY_VALUES" => array(
			"PHONE" => $phone,
			"STATUS" => array("VALUE" => 23),
			"NAME" => $name,
		),
		"NAME"           => "Новый запрос",
		"ACTIVE"         => "Y",
	);

	$arEventFields = array(
		"PHONE" => $phone,
		"NAME" => $name,
	);
	$PRODUCT_ID = $el->Add($arLoadProductArray);
	$deb = CEvent::Send("CALLBACK", 's1', $arEventFields);
} elseif ($item && empty($arErr)) {

	$arLoadProductArray = array(
		"IBLOCK_ID"      => 6,
		"PROPERTY_VALUES" => array(
			"PHONE" => $phone,
			"STATUS" => array("VALUE" => 17),
			"NAME" => $name,
		),
		"NAME"           => $item,
		"ACTIVE"         => "Y",
	);

	$arEventFields = array(
		"PHONE" => $phone,
		"TOVAR" => $item,
		"NAME" => $name,
	);
	$PRODUCT_ID = $el->Add($arLoadProductArray);
	$deb = CEvent::Send("ONECLICK", 's1', $arEventFields);
}

if (empty($arErr)) {
	//   echo "Спасибо. Менеджер свяжется с Вами в ближайшее время.";
	echo json_encode(["Y"]);
} else {
	//   echo "Error: ".$el->LAST_ERROR;
	$arErr[] = "Error: " . $el->LAST_ERROR;
	$res = json_encode($arErr);
	echo $res;
}