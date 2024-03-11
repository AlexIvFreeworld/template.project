<?
global $DB;
CModule::AddAutoloadClasses(
	"webprostor.smtp",
	array(
		"CWebprostorSmtp" => "classes/general/smtp.php",
		"CWebprostorSmtpSite" => "classes/general/site.php",
		"CWebprostorSmtpAgent" => "classes/general/agent.php",
		"CWebprostorSmtpLogs" => "classes/".strtolower($DB->type)."/logs.php",
		
		"PHPMailer\PHPMailer\PHPMailer" => "classes/phpmailer/PHPMailer.php",
		"PHPMailer\PHPMailer\SMTP" => "classes/phpmailer/SMTP.php",
		"PHPMailer\PHPMailer\OAuth" => "classes/phpmailer/OAuth.php",
		"PHPMailer\PHPMailer\OAuthTokenProvider" => "classes/phpmailer/OAuthTokenProvider.php",
		"PHPMailer\PHPMailer\Exception" => "classes/phpmailer/Exception.php",
	)
);

if (!function_exists('custom_mail') && COption::GetOptionString("webprostor.smtp", "USE_MODULE") == "Y")
{
	function custom_mail($to, $subject, $message, $additional_headers='', $additional_parameters='')
	{
		if(CModule::IncludeModule("webprostor.smtp"))
		{
			$smtp = new CWebprostorSmtp(false, $additional_headers);
			$result = $smtp->SendMail($to, $subject, $message, $additional_headers, $additional_parameters);

			if($result)
				return true;
			else
				return false;
		}
	}
}
?>