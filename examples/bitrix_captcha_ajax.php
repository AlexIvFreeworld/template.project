<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
// \Bitrix\Main\Diag\Debug::dumpToFile(array('apply $_POST' => $_POST), "", "log.txt");
// \Bitrix\Main\Diag\Debug::dumpToFile(array('apply $_FILES' => $_FILES), "", "log.txt");

           
   
/* if (!$APPLICATION->CaptchaCheckCode($_POST["captcha_word"], $_POST["captcha_code"])) {
	// Неправильное значение
	// \Bitrix\Main\Diag\Debug::dumpToFile(array('res captcha' => "not"), "", "/log.txt");
	echo "error";
	die();
} else {
	// Правильное значение
	// \Bitrix\Main\Diag\Debug::dumpToFile(array('res captcha' => "yes"), "", "/log.txt");
	
} */

if (isset($_POST) and $_SERVER["REQUEST_METHOD"] == "POST" && ($_POST["radio-ask"] == "4")) {
	if ($APPLICATION->CaptchaCheckCode($_POST["captcha_word"], $_POST["captcha_code"])) {
		// ------------------------------------------------------------------------------ //
		$service = strip_tags($_POST['service']);
		$service = mb_convert_encoding($service, 'WINDOWS-1251', 'UTF-8');
		$subService = "";
		$labs = "";
		foreach ($_POST['lab'] as $lab) {
			$labIn = strip_tags($lab);
			$labIn = mb_convert_encoding($labIn, 'WINDOWS-1251', 'UTF-8');
			$labs .= $labIn . "\n";
		}
		$repairinstr = "";
		foreach ($_POST['repairinstr'] as $str) {
			$str = strip_tags($str);
			$str = mb_convert_encoding($str, 'WINDOWS-1251', 'UTF-8');
			$repairinstr .= $str . "\n";
		}
		$hidraulic = "";
		foreach ($_POST['hidraulic'] as $str) {
			$str = strip_tags($str);
			$str = mb_convert_encoding($str, 'WINDOWS-1251', 'UTF-8');
			$hidraulic .= $str . "\n";
		}
		$rent = "";
		foreach ($_POST['rent'] as $str) {
			$str = strip_tags($str);
			$str = mb_convert_encoding($str, 'WINDOWS-1251', 'UTF-8');
			$rent .= $str . "\n";
		}
		$approvals = "";
		foreach ($_POST['approvals'] as $str) {
			$str = strip_tags($str);
			$str = mb_convert_encoding($str, 'WINDOWS-1251', 'UTF-8');
			$approvals .= $str . "\n";
		}
		$nondestrtest = "";
		foreach ($_POST['nondestrtest'] as $str) {
			$str = strip_tags($str);
			$str = mb_convert_encoding($str, 'WINDOWS-1251', 'UTF-8');
			$nondestrtest .= $str . "\n";
		}
		$eleclab = "";
		foreach ($_POST['eleclab'] as $str) {
			$str = strip_tags($str);
			$str = mb_convert_encoding($str, 'WINDOWS-1251', 'UTF-8');
			$eleclab .= $str . "\n";
		}
		$engcomm = "";
		foreach ($_POST['engcomm'] as $str) {
			$str = strip_tags($str);
			$str = mb_convert_encoding($str, 'WINDOWS-1251', 'UTF-8');
			$engcomm .= $str . "\n";
		}
		$heatnet = "";
		foreach ($_POST['heatnet'] as $str) {
			$str = strip_tags($str);
			$str = mb_convert_encoding($str, 'WINDOWS-1251', 'UTF-8');
			$heatnet .= $str . "\n";
		}
		$heatpoint = "";
		foreach ($_POST['heatpoint'] as $str) {
			$str = strip_tags($str);
			$str = mb_convert_encoding($str, 'WINDOWS-1251', 'UTF-8');
			$heatpoint .= $str . "\n";
		}
		$boiler = "";
		foreach ($_POST['boiler'] as $str) {
			$str = strip_tags($str);
			$str = mb_convert_encoding($str, 'WINDOWS-1251', 'UTF-8');
			$boiler .= $str . "\n";
		}
		$thermmal = "";
		foreach ($_POST['thermmal'] as $str) {
			$str = strip_tags($str);
			$str = mb_convert_encoding($str, 'WINDOWS-1251', 'UTF-8');
			$thermmal .= $str . "\n";
		}
		$relland = "";
		foreach ($_POST['relland'] as $str) {
			$str = strip_tags($str);
			$str = mb_convert_encoding($str, 'WINDOWS-1251', 'UTF-8');
			$relland .= $str . "\n";
		}
		$person = strip_tags($_POST['person']);
		$person = mb_convert_encoding($person, 'WINDOWS-1251', 'UTF-8');
		$fioask = strip_tags($_POST['fio-ask']);
		$fioask = mb_convert_encoding($fioask, 'WINDOWS-1251', 'UTF-8');
		$fioask2 = strip_tags($_POST['fio-ask2']);
		$fioask2 = mb_convert_encoding($fioask2, 'WINDOWS-1251', 'UTF-8');
		$phone = strip_tags($_POST['phone-ask']);
		$phone = mb_convert_encoding($phone, 'WINDOWS-1251', 'UTF-8');
		$email = strip_tags($_POST['email-ask']);
		$email = mb_convert_encoding($email, 'WINDOWS-1251', 'UTF-8');
		$describe = strip_tags($_POST['message-ask']);
		$describe = mb_convert_encoding($describe, 'WINDOWS-1251', 'UTF-8');
		$fileName = strip_tags($_FILES['file-warning']['name']);
		$fileName = mb_convert_encoding($fileName, 'WINDOWS-1251', 'UTF-8');

		$subServices = array($labs, $repairinstr, $hidraulic, $rent, $approvals, $nondestrtest, $eleclab, $engcomm, $heatnet, $heatpoint, $boiler, $thermmal, $relland);
		foreach ($subServices as $item) {
			if ($item != "") {
				$subService = $item;
			}
		}
		$arFiles = array();
		if ($_FILES['file-warning']['name']) {
			$file = array(
				"name" => $fileName,
				"size" => $_FILES['file-warning']['size'],
				"tmp_name" => $_FILES['file-warning']['tmp_name'],
				"type" => $_FILES['file-warning']['type'],
				"del" => "",
			);
			$fid = CFile::SaveFile($file, "fnep");
			$arFiles[] = $fid;
		}

		$arEventFields = array(
			"SERVICE" => $service,
			"LABS" => $subService,
			"PERSON" => $person,
			"NAME" => $fioask2,
			"COMPANY" => $fioask,
			'PHONE' => $phone,
			'EMAIL' => $email,
			'DESCRIBE' => $describe,
		);

		if (!(empty($arFiles))) {
			CEvent::Send("APPLY", 's1', $arEventFields, 'N', 40, $arFiles);
		} else {
			CEvent::Send("APPLY", 's1', $arEventFields);
		}
		$result['status'] = 'success';
        $result['message'] = 'Сообщение успешно отправлено';
	}else{
		$result['status'] = 'error';
        $result['message'] = 'Капча введена неверно';
	}
} else {
	$result['status'] = 'error';
    $result['message'] = 'Произошла ошибка. Попробуйте отправить форму позже.';
}
 echo json_encode($result);exit;