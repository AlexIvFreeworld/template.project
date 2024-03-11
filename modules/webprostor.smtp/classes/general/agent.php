<?
Class CWebprostorSmtpAgent
{
	const MODULE_ID = 'webprostor.smtp';
	
	public static function Resend(): string
	{
		
		$enable_resend = COption::GetOptionString(self::MODULE_ID, "ENABLE_RESEND", "N");
		if($enable_resend == "Y")
		{
			try
			{
				$retry_count = COption::GetOptionString(self::MODULE_ID, "RESEND_RETRY_COUNT", 3);
				$message_count = COption::GetOptionString(self::MODULE_ID, "RESEND_MESSAGE_COUNT", 50);
				
				$CSMTPLog = new CWebprostorSmtpLogs;
				$messageRes = $CSMTPLog->GetList(["SORT"=>"ASC"], ["<RETRY_COUNT" => $retry_count, "SENDED" => "N"], false, $message_count);

				while($messageArr = $messageRes->Fetch())
				{
					if(is_array($messageArr))
					{
						$smtp = new CWebprostorSmtp($messageArr["SITE_ID"]);
						$send = $smtp->SendMail($messageArr["SOURCE_TO"], $messageArr["SOURCE_SUBJECT"], $messageArr["SOURCE_MESSAGE"], $messageArr["SOURCE_HEADERS"], $messageArr["SOURCE_PARAMETERS"], false, $messageArr["ID"]);
					}
				}
			}
			catch (Exception $e) 
			{
			}
		}
		
		return 'CWebprostorSmtpAgent::Resend();';
	}
}
?>