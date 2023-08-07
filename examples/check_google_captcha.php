<? require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");?>
<?
define("SECRET_KEY","6LfEa1YnAAAAAEwXF2NDuOiWMi7eP6Lj6ThdVI7_");
//\Bitrix\Main\Diag\Debug::dumpToFile(array('$_POST' => $_POST), "", "/log.txt");

$Response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=" . SECRET_KEY . "&response={$_POST["token"]}");
//\Bitrix\Main\Diag\Debug::dumpToFile(array('$Response' => $Response), "", "/log.txt");

$Return = json_decode($Response,true);
//\Bitrix\Main\Diag\Debug::dumpToFile(array('$Return' => $Return), "", "/log.txt");

$extensions = ['.png', '.jpg', '.tif', '.pdf', '.doc', '.docs'];
$maxSize = 10000000;

$name = htmlspecialchars(stripslashes(trim($_POST['form_name'])));
$phone = htmlspecialchars(stripslashes(trim($_POST['form_phone'])));
$email = htmlspecialchars(stripslashes(trim($_POST['form_email'])));
$message = htmlspecialchars(stripslashes(trim($_POST['form_message'])));

$text = '';
$text .= (!empty($name))?'<p><strong>Имя:</strong> '.$name.'</p>':'';
$text .= (!empty($phone))?'<p><strong>Телефон:</strong> '.$phone.'</p>':'';
$text .= (!empty($email))?'<p><strong>email:</strong> '.$email.'</p>':'';
$text .= (!empty($message))?'<p><strong>Комментарий:</strong> '.$message.'</p>':'';
$text .= (!empty($_SERVER['HTTP_REFERER']))?'<p><strong>Адрес страницы:</strong> '.$_SERVER['HTTP_REFERER'].'</p>':'';

$filesIds = Array();

if(!empty($_FILES['files']))
{
	$sizes = 0;
	foreach($_FILES['files']['size'] as $key => $val)
	{
		$sizes += $val;
	}
	if ($sizes > $maxSize)
	{
		echo 'error#Суммарный объем файлов превышает 10Мб';
		exit;
	}
	
	$files = Array();
	foreach($_FILES['files']['name'] as $key => $val)
	{
		$name = $_FILES['files']['name'][$key];
		$name = str_replace(' ', '_', $name);
		if ($name == '') continue;
		$type = $_FILES['files']['type'][$key];
		$tmp_name = $_FILES['files']['tmp_name'][$key];
		$error = $_FILES['files']['error'][$key];
		$size = $_FILES['files']['size'][$key];
		
		if ($error > 0)
		{
			echo 'error#Произошла ошибка при загрузке файла "'.$name.'"';
			exit;
		}
		
		$ext = strtolower(substr($name, strpos($name,'.'), strlen($name)-1));
		if(!in_array($ext, $extensions))
		{
			echo 'error#Тип файла "'.$name.'" запрещен к загрузке';
			exit;
		}
		
		$files[] = Array
		(
			"name" => $name,
			"size" => $size,
			"tmp_name" => $tmp_name,
			"type" => $type,
			"MODULE_ID" => "profigs.settings",
		);
	}
	
	foreach ($files as $file)
	{
		$filesIds[] = CFile::SaveFile($file, "profigs.settings");
	}
}
	
$res = Bitrix\Main\Mail\Event::send(Array
(
	"EVENT_NAME" => "GVOZDEVSOFT_FORMS_s1",
	"LID" => Bitrix\Main\Context::getCurrent()->getSite(),
	"C_FIELDS" => Array
	(
		"TEXT" => $text,
	),
	"FILE" => $filesIds,
));

if ($res->getId() && $Return["success"])
{
	echo "Спасибо! Ваша заявка отправлена";
}
else
{
	echo "Ваша заявка не отправлена! Попробуйте еще раз";
}
?>