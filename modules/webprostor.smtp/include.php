<?
global $DB;
CModule::AddAutoloadClasses(
	"webprostor.smtp",
	array(
		"CWebprostorSmtp" => "classes/general/smtp.php",
		"CWebprostorSmtpSite" => "classes/general/site.php",
		"CWebprostorSmtpLogs" => "classes/".strtolower($DB->type)."/logs.php",
		
		"PHPMailer\PHPMailer\PHPMailer" => "classes/phpmailer/PHPMailer.php",
		"PHPMailer\PHPMailer\SMTP" => "classes/phpmailer/SMTP.php",
		"PHPMailer\PHPMailer\OAuth" => "classes/phpmailer/OAuth.php",
		"PHPMailer\PHPMailer\Exception" => "classes/phpmailer/Exception.php",
	)
);
?>