<?
define("ADMIN_MODULE_NAME", "webprostor.smtp");

$moduleAccessLevel = $APPLICATION->GetGroupRight(ADMIN_MODULE_NAME);

if ($moduleAccessLevel > "D")
{
	if(!CModule::IncludeModule("webprostor.core"))
	{
		$APPLICATION->IncludeAdminFile("webprostor.core", $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/".ADMIN_MODULE_NAME."/install/webprostor.core.php");
	}
}

CJSCore::Init(array("jquery3"));

\Bitrix\Main\UI\Extension::load("ui.buttons");
\Bitrix\Main\UI\Extension::load("ui.buttons.icons");
\Bitrix\Main\UI\Extension::load("ui.alerts");
\Bitrix\Main\UI\Extension::load("ui.hints");
\Bitrix\Main\UI\Extension::load("ui.forms"); 

$GLOBALS['APPLICATION']->AddHeadScript('/bitrix/panel/webprostor.core/select2/js/select2.min.js');
$GLOBALS['APPLICATION']->AddHeadScript('/bitrix/panel/webprostor.core/select2/js/ru.js');
$GLOBALS['APPLICATION']->AddHeadScript('/bitrix/panel/webprostor.core/select2/js/main.js');

$GLOBALS['APPLICATION']->SetAdditionalCSS('/bitrix/panel/webprostor.core/select2/css/select2.min.css');
$GLOBALS['APPLICATION']->SetAdditionalCSS('/bitrix/panel/webprostor.core/select2/css/style.css');

function parseMailHeading(&$message)
{
	$patterns = [
		"CC" => "/\bCC: (.+)\n/i",
		"Reply-To" => "/\bReply-To: (.+)\n/i",
		"To" => "/\bTo: (.+)\n/i",
		"BCC" => "/\bBCC: (.+)\n/i",
		"Subject" => "/\bSubject: (.+)\n/i",
		"From" => "/\bFrom: (.+)\n/i",
		"Date" => "/\bDate: (.+)\n/i",
		"X-Priority" => "/\bX-Priority: (.+)\n/i",
		"Reply-Precedence" => "/\bReply-Precedence: (.+)\n/i",
		"List-Unsubscribe" => "/\bList-Unsubscribe: (.+)\n/i",
		"MIME-Version" => "/\bMIME-Version: (.+)\n/i",
		"Content-Type" => "/\bContent-Type: (.+)\n/i",
		"Reply-X-EVENT_NAME" => "/\bReply-X-EVENT_NAME: (.+)\n/i",
		"X-MID" => "/\bX-MID: (.+)\n/i",
		"Reply-Content-Transfer-Encoding" => "/\bReply-Content-Transfer-Encoding: (.+)\n/i",
		"X-EVENT_NAME" => "/\bX-EVENT_NAME: (.+)\n/i",
		"Content-Transfer-Encoding" => "/\bContent-Transfer-Encoding: (.+)\n/i",
		"X-Mailer" => "/\bX-Mailer: (.+)\n/i",
		"X-Bitrix-Posting" => "/\bX-Bitrix-Posting: (.+)\n/i",
		"Precedence" => "/\bPrecedence: (.+)\n/i",
		"Bitrix-Sender" => "/\bBitrix-Sender: (.+)\n/i",
		"Content-Disposition" => "/\bContent-Disposition: (.+)\n/i",
	];
	$result = [];
	
	foreach($patterns as $code => $pattern)
	{
		
		preg_match($pattern, $message, $matches);
		list(, $temp) = $matches;
		
		$message = preg_replace($pattern, "", $message);
		
		$result[$code] = $temp;
		
		unset($temp);
	}
	
	return $result;
	
}
?>