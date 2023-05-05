<?
if (!function_exists('custom_mail') && COption::GetOptionString("webprostor.smtp", "USE_MODULE") == "Y")
{
	function custom_mail($to, $subject, $message, $additional_headers='', $additional_parameters='')
	{
		if(CModule::IncludeModule("webprostor.smtp"))
		{
			$smtp = new CWebprostorSmtp("#SITE_ID#");
			$result = $smtp->SendMail($to, $subject, $message, $additional_headers, $additional_parameters);

			if($result)
				return true;
			else
				return false;
		}
	}
}
?>