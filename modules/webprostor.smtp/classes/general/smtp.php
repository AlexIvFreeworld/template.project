<?
IncludeModuleLangFile(__FILE__);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\OAuth;

Class CWebprostorSmtp extends CMain
{
	private $module_id = 'webprostor.smtp';
	private $site_id = '';
	private $site_name = '';
	private $prefix = '';
	
	private $smtp_server = '';
	private $smtp_port = '';
	private $smtp_secure = false;
	private $smtp_host = '';
	
	private $is_sender = false;
	
	private $use_sender_smtp = false;
	
	private $sender_smtp_server = '';
	private $sender_smtp_port = '';
	private $sender_smtp_secure = false;
	private $sender_smtp_host = '';
	
	private $requires_authentication = true;
	
	private $login = '';
	private $password = '';
	private $use_xoauth2 = '';
	
	private $sender_login = '';
	private $sender_password = '';
	
	private $use_dkim = true;
	private $dkim_domain = '';
	private $dkim_selector = '';
	private $dkim_passphrase = '';
	private $dkim_private_string = '';
	
	private $replace_from = '';
	private $replace_from_to_email = '';
	private $replace_from_name = '';
	private $dsn = '';
	
	private $charset = '';
	private $priority = '';
	private $from = '';
	private $reply_to = '';
	
	private $debug = false;
	private $notify_limit = 10000;
	private $auto_cleaning_logs = false;
	private $dont_save_send_info = false;
	private $debug_level = 0;
	
	private $duplicate = false;
	private $bcc = '';
	//private $mail_gen_text_version = '';
	
	private $def_site_id = '';
	
	public function __construct($siteId = false, &$additional_headers = false)
	{
		
		if(!$siteId)
		{
			if($additional_headers)
			{
				$messID = false;
				
				preg_match('/\bX-MID: [0-9]*\D([0-9]*)(.+)\n/i', $additional_headers, $matches);
				list(, $messID) = $matches;
				
				if($messID > 0)
				{
					$rsEM = CEventMessage::GetByID($messID);
					$arEM = $rsEM->Fetch();
					
					if($arEM['LID'] != '')
					{
						$rsSites = CSite::GetByID($arEM['LID']);
						$arSite = $rsSites->Fetch();
						
						if(is_array($arSite))
						{
							$siteId = $arSite['ID'];
							$siteName = $arSite['SITE_NAME'];
						}
					}
				}
			}
			
			if(!$siteId && $_SERVER['SERVER_NAME'] != '')
			{
				$rsSites = CSite::GetList($by = 'sort', $order = 'desc', ['DOMAIN' => $_SERVER['SERVER_NAME']]);
				while ($arSite = $rsSites->Fetch())
				{
					$siteId = $arSite['ID'];
					$siteName = $arSite['SITE_NAME'];
				}
			}
			
			$this->def_site_id = COption::GetOptionString($this->module_id, "USE_DEFAULT_SITE_ID_IF_EMPTY", false);
			if($this->def_site_id == "N")
				$this->def_site_id = false;
			
			if(!$siteId && $this->def_site_id)
			{
				$rsSites = CSite::GetList($by = 'sort', $order = 'desc', ['DEFAULT' => 'Y']);
				while ($arSite = $rsSites->Fetch())
				{
					$siteId = $arSite['ID'];
					$siteName = $arSite['SITE_NAME'];
				}
			}
		}
		else
		{
			$rsSites = CSite::GetByID($siteId);
			$arSite = $rsSites->Fetch();
			if($arSite['SITE_NAME'])
				$siteName = $arSite['SITE_NAME'];
		}
		
		$this->site_id = $siteId;
		$this->site_name = $siteName;
		$this->prefix = strtoupper($siteId);
		
		$this->smtp_server = COption::GetOptionString($this->module_id, $this->prefix.'_'."SMTP_SERVER");
		$this->smtp_port = COption::GetOptionString($this->module_id, $this->prefix.'_'."SMTP_PORT");
		$this->smtp_secure = COption::GetOptionString($this->module_id, $this->prefix.'_'."SECURE", false);
		if($this->smtp_secure && $this->smtp_secure == "ssl")
		{
			$this->smtp_host = $this->smtp_secure.'://'.$this->smtp_server;
		}
		else
			$this->smtp_host = $this->smtp_server;
		
		$this->use_sender_smtp = COption::GetOptionString($this->module_id, "USE_SENDER_SMTP");
		if($this->use_sender_smtp == "N")
			$this->use_sender_smtp = false;
		
		$this->sender_smtp_server = COption::GetOptionString($this->module_id, "SMTP_SERVER");
		$this->sender_smtp_port = COption::GetOptionString($this->module_id, "SMTP_PORT");
		$this->sender_smtp_secure = COption::GetOptionString($this->module_id, "SECURE", false);
		if($this->sender_smtp_secure && $this->sender_smtp_secure == "ssl")
		{
			$this->sender_smtp_host = $this->sender_smtp_secure.'://'.$this->sender_smtp_server;
		}
		else
			$this->sender_smtp_host = $this->sender_smtp_server;
		
		$this->requires_authentication = COption::GetOptionString($this->module_id, $this->prefix.'_'."REQUIRES_AUTHENTICATION", true);
		if($this->requires_authentication == "N")
			$this->requires_authentication = false;
		
		$this->login = COption::GetOptionString($this->module_id, $this->prefix.'_'."LOGIN");
		$this->password = COption::GetOptionString($this->module_id, $this->prefix.'_'."PASSWORD");
		$this->use_xoauth2 = COption::GetOptionString($this->module_id, $this->prefix.'_'."USE_XOAUTH2");
		if($this->use_xoauth2 == "N")
			$this->use_xoauth2 = false;
		
		$this->sender_login = COption::GetOptionString($this->module_id, "LOGIN");
		$this->sender_password = COption::GetOptionString($this->module_id, "PASSWORD");
		
		$this->use_dkim = COption::GetOptionString($this->module_id, $this->prefix.'_'."USE_DKIM", true);
		if($this->use_dkim == "N")
			$this->use_dkim = false;
		
		$this->dkim_domain = COption::GetOptionString($this->module_id, $this->prefix.'_'."DKIM_DOMAIN");
		$this->dkim_selector = COption::GetOptionString($this->module_id, $this->prefix.'_'."DKIM_SELECTOR");
		$this->dkim_passphrase = COption::GetOptionString($this->module_id, $this->prefix.'_'."DKIM_PASSPHRASE");
		$this->dkim_private_string = COption::GetOptionString($this->module_id, $this->prefix.'_'."DKIM_PRIVATE_STRING");
		
		$this->replace_from = COption::GetOptionString($this->module_id, $this->prefix.'_'."REPLACE_FROM");
		$this->replace_from_to_email = COption::GetOptionString($this->module_id, $this->prefix.'_'."REPLACE_FROM_TO_EMAIL");
		$this->replace_from_name = COption::GetOptionString($this->module_id, $this->prefix.'_'."REPLACE_FROM_NAME");
		$this->dsn = COption::GetOptionString($this->module_id, $this->prefix.'_'."DSN");
		if($this->dsn)
		{
			$this->dsn = unserialize($this->dsn);
			if(is_array($this->dsn))
				$this->dsn = implode(',',$this->dsn);
		}
		
		$this->charset = strtoupper(COption::GetOptionString($this->module_id, $this->prefix.'_'."CHARSET", "utf-8"));
		$this->priority = COption::GetOptionString($this->module_id, $this->prefix.'_'."PRIORITY", 3);
		$this->from = COption::GetOptionString($this->module_id, $this->prefix.'_'."FROM", false);
		$this->reply_to = COption::GetOptionString($this->module_id, $this->prefix.'_'."REPLY_TO", false);
		
		$this->debug = COption::GetOptionString($this->module_id, "LOG_ERRORS", false);
		if($this->debug == "N")
			$this->debug = false;
		$this->notify_limit = COption::GetOptionString($this->module_id, "NOTIFY_LIMIT", 10000);
		if(intVal($this->notify_limit) == 0)
			$this->notify_limit = false;
		$this->dont_save_send_info = COption::GetOptionString($this->module_id, "DONT_SAVE_SEND_INFO", false);
		if($this->dont_save_send_info == "N")
			$this->dont_save_send_info = false;
		$this->auto_cleaning_logs = COption::GetOptionString($this->module_id, "AUTO_CLEANING_LOGS", false);
		if($this->auto_cleaning_logs == "N")
			$this->auto_cleaning_logs = false;
		$this->debug_level = COption::GetOptionString($this->module_id, "DEBUG_LEVEL", 0);
		
		$this->duplicate = COption::GetOptionString($this->module_id, $this->prefix.'_'."DUPLICATE", false);
		if($this->duplicate == "N")
			$this->duplicate = false;
		if($this->duplicate)
			$this->bcc = COption::GetOptionString("main", "all_bcc");
		
		/*$this->mail_gen_text_version = COption::GetOptionString("main", "mail_gen_text_version", false);
		if($this->mail_gen_text_version == "N")
			$this->mail_gen_text_version = false;*/
	}
	
	public function SendMail($to = false, $subject = false, $message = false, $additional_headers = '', $additional_parameters = '', $attachment = false, $message_id = false)
	{
		$result = true;
		
		if($this->debug)
		{
			global $logError;
			$logError = new CWebprostorSmtpLogs;
			
			$logFields = 
			[
				"SOURCE_TO" => $to,
				"SOURCE_SUBJECT" => $subject,
				"SOURCE_MESSAGE" => $message,
				"SOURCE_HEADERS" => $additional_headers,
				"SOURCE_PARAMETERS" => $additional_parameters,
			];
		}
		
		if($subject)
		{
			$sendInfo = '';
			$sendInfo .= 'Subject: '.$subject."\r\n";
		}
		
		if ($additional_headers) 
		{
			
			preg_match('/\bFrom: (.+)\n/i', $additional_headers, $matches);
			list(, $this->from) = $matches;
			
			if($this->replace_from_to_email != "" && filter_var($this->replace_from_to_email, FILTER_VALIDATE_EMAIL))
			{
				$additional_headers = preg_replace('/From: (.+)/i', "From: {$this->replace_from_to_email}", $additional_headers);
			}
			elseif($this->replace_from == "Y")
			{
				$additional_headers = preg_replace('/From: (.+)/i', "From: {$this->login}", $additional_headers);
			}
		
			$sendInfo .= $additional_headers."\r\n";
			
			preg_match('/\bCc: (.+)\n/i', $additional_headers, $matches);
			list(, $copyTo) = $matches;
			
			preg_match('/\bBcc: (.+)\n/i', $additional_headers, $matches);
			list(, $hideCopyTo) = $matches;
			
			preg_match('/\bTo: (.+)\n/i', $additional_headers, $matches);
			list(, $sendTo) = $matches;
			
			if(!$sendTo)
				$sendInfo .= "To: <{$to}>\r\n";
		}
		else
		{
			$additional_headers .= "Content-Type: text/html; charset=\"".$this->charset."\"\r\n";
			$additional_headers .= "Content-Transfer-Encoding: 8bit\r\n";
			
			$sendInfo .= "Date: ".date("D, j M Y G:i:s")." +0400\r\n"; 
			$sendInfo .= "Reply-To: ".($this->reply_to?$this->reply_to:$this->login)."\r\n";
			$sendInfo .= "To: <{$to}>\r\n";
			$sendInfo .= "MIME-Version: 1.0\r\n";
			$sendInfo .= "Content-Type: text/html; charset=\"".$this->charset."\"\r\n";
			$sendInfo .= "Content-Transfer-Encoding: 8bit\r\n";
			if($this->from)
				$sendInfo .= "From: \"=?".$this->charset."?B?".base64_encode($this->from)."=?=\" <".$this->login.">\r\n";
			else
				$sendInfo .= "From:  {$this->login}\r\n";
			$sendInfo .= "X-Priority: {$this->priority}\r\n\r\n";
			if($this->duplicate)
				$hideCopyTo = $this->bcc;
		}
		
		if($additional_headers) 
		{
			preg_match('/\bBitrix-Sender: (.+)\n/i', $additional_headers, $matches);
			list(, $bitrixSender) = $matches;
			
			preg_match('/\bX-Bitrix-Posting: (.+)\n/i', $additional_headers, $matches);
			list(, $bitrixPosting) = $matches;
			
			if($bitrixSender)
			{
				if($this->use_sender_smtp)
					$this->is_sender = true;
				
				if($this->debug)
					$logFields["MODULE_ID"] = "sender";
			}
			elseif($bitrixPosting)
			{
				if($this->use_sender_smtp)
					$this->is_sender = true;
				
				if($this->debug)
					$logFields["MODULE_ID"] = "subscribe";
			}
		}
		if($this->is_sender !== true && strpos($additional_parameters, "bitrix_subscribe=Y") !== false) 
		{
			if($this->use_sender_smtp)
				$this->is_sender = true;
			
			if($this->debug)
				$logFields["MODULE_ID"] = "subscribe";
		}
		
		if($message)
		{
			$sendInfo .= "\r\n".$message."\r\n";
		}
		
		if($this->debug)
		{
			if(!isset($logFields["MODULE_ID"]))
			{
				$logFields["SITE_ID"] = $this->site_id;
			}
			
			$logFields["SENDED"] = "Y";
		}
				
		try {
			
			$mail = new PHPMailer(true);
			$mail->setLanguage(LANGUAGE_ID, $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/webprostor.smtp/lang/".LANGUAGE_ID."/classes/phpmailer/");
			
			if($this->dsn)
				$mail->dsn = $this->dsn;
			
			//Server settings
			$mail->isSMTP();
			
			$mail->SMTPDebug = $this->debug_level;
			if($this->debug_level > 0)
			{
				$mail->Debugoutput = 'error_log';
			}
			
			$mail->Host       = $this->is_sender?$this->sender_smtp_host:$this->smtp_host;
			$mail->SMTPAuth   = $this->is_sender?true:$this->requires_authentication;
			/*$mail->setOAuth(
				new OAuth(
					[
						//'provider' => $provider,
						'clientId' => $this->clientId,
						'clientSecret' => $this->clientSecret,
						//'refreshToken' => $this->refreshToken,
						'userName' => $this->login,
					]
				)
			);*/
			if($this->is_sender || $this->requires_authentication)
			{
				$mail->AuthType	  = $this->use_xoauth2?'XOAUTH2':($this->requires_authentication?'LOGIN':'PLAIN');
			
				$mail->Username   = $this->is_sender?$this->sender_login:$this->login;
				$mail->Password   = $this->is_sender?$this->sender_password:$this->password;
			}
			
			if($this->smtp_secure)
			{
				$mail->SMTPSecure = $this->smtp_secure; 
			}
			elseif($this->is_sender && $this->sender_smtp_secure)
			{
				$mail->SMTPSecure = $this->sender_smtp_secure; 
			}
			else
			{
				$mail->SMTPAutoTLS = false;
			}
			$mail->Port       = $this->is_sender?$this->sender_smtp_port:$this->smtp_port;
			
			if($this->use_dkim)
			{
				$mail->DKIM_domain = $this->dkim_domain;
				$mail->DKIM_selector = $this->dkim_selector;
				$mail->DKIM_passphrase = $this->dkim_passphrase;
				$mail->DKIM_private_string = $this->dkim_private_string;
			}

			//Recipients
			if(!$this->is_sender)
			{
				if($this->replace_from_to_email != "" && filter_var($this->replace_from_to_email, FILTER_VALIDATE_EMAIL))
				{
					$mail->setFrom($this->replace_from_to_email, $this->replace_from_name);
				}
				elseif($this->replace_from == "Y")
				{
					$mail->setFrom($this->login, $this->site_name);
				}
			}
			else
			{
				if(!filter_var($this->from, FILTER_VALIDATE_EMAIL))
				{
					$senderName = $this->from;
					preg_match('/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i', $this->from, $matches);
					if(filter_var($matches[0], FILTER_VALIDATE_EMAIL))
						$this->from = $matches[0];
					
					if($matches[0] != $senderName)
					{
						$senderName = str_replace('>', '', $senderName);
						$senderName = str_replace(' <', '', $senderName);
						$senderName = str_replace($this->from, '', $senderName);
						$senderName = trim($senderName);
					}
					else
					{
						$senderName = '';
					}
				}
				
				$mail->setFrom($this->from, $senderName);
			}
			$toArr = explode(",", $to);
			$senToEmails = [];
			if(is_array($toArr))
			{
				$arrayTo = $this->ParseRecipient($toArr);
				foreach($arrayTo as $emailTo)
				{
					$mail->addAddress($emailTo);
				}
				$senToEmails = array_merge($senToEmails, $arrayTo);
			}
			elseif($to)
			{
				$arrayTo = $this->ParseRecipient([$to]);
				foreach($arrayTo as $emailTo)
				{
					$mail->addAddress($emailTo);
				}
				$senToEmails = array_merge($senToEmails, $arrayTo);
			}
			if($copyTo)
			{
				$arrayCC = $this->ParseRecipient([$copyTo]);
				foreach($arrayCC as $emailCC)
				{
					$mail->addCC($emailCC);
				}
				$senToEmails = array_merge($senToEmails, $arrayCC);
			}
			if($hideCopyTo)
			{
				$arrayBCC = $this->ParseRecipient([$hideCopyTo]);
				foreach($arrayBCC as $emailBCC)
				{
					$mail->addBCC($emailBCC);
				}
				$senToEmails = array_merge($senToEmails, $arrayBCC);
			}
			
			if ($this->debug) 
			{
				$logFields["RECIPIENTS"] = implode(", ", array_unique($senToEmails));
				if(!$this->dont_save_send_info)
					$logFields["SEND_INFO"] = $sendInfo;
			}
			
			$mail->AddCustomHeader("X-Mailer-Bitrix", "webprostor.smtp (https://marketplace.1c-bitrix.ru/solutions/webprostor.smtp/)");
			
			if($this->is_sender)
				$mail->AddCustomHeader("X-Sender-Service", $this->sender_smtp_server);
			
			if(defined("BX_UTF") && BX_UTF)
				$mail->CharSet = 'utf-8';
			else
				$mail->CharSet = 'windows-1251';
			
			$mail->Subject = $subject;
			$mail->Body = $message;
			
			if ($additional_headers != '') 
			{
				$additionalHeadersArray = explode("\n", $additional_headers);
				
				if(is_array($additionalHeadersArray))
				{
					foreach($additionalHeadersArray as $code=>$value)
					{
						if($value != '' && strpos($value, ':') !== false)
						{
							$header = explode(":", $value, 2);
							switch(strtoupper($header[0]))
							{
								/*case "CONTENT-TYPE":
								case "CONTENT-TRANSFER-ENCODING":
									if($plain_text == 'Y')
									{
										$mail->AddCustomHeader($header[0], trim($header[1]));
									}
									break;*/
								case "CC":
								case "BCC":
								case "TO":
								case "FROM":
								case "SUBJECT":
								case "MIME-VERSION":
								case "DATE":
								case "BITRIX-SENDER":
									break;
								case "REPLY-TO":
									$mail->addReplyTo(trim($this->ParseEmail($header[1])));
									break;
								default:
									$mail->AddCustomHeader($header[0], trim($header[1]));
									break;
							}
						}
					}
				}
			}
			
			if($attachment && count($attachment)>0)
			{
				foreach($attachment as $file)
				{
					$mail->AddAttachment($file["PATH"], $file["NAME"]);
				}
			}
			
			$mail->send();
		}
		catch (Exception $e) 
		{
			
			if ($this->debug) 
			{
				$logFields["ERROR_TEXT"] = $mail->ErrorInfo;
				$logFields["SENDED"] = "N";
				
				if($message_id>0)
				{
					$logResNew = $logError->GetById($message_id);
					$messageArr = $logResNew->Fetch();
					
					$logFields["RETRY_COUNT"] = ++$messageArr["RETRY_COUNT"];
				}
				else
				{
					$logFields["RETRY_COUNT"] = "1";
				}
			}
			
			$result = false;
		}
		
		if ($this->debug) 
		{
			
			global $LOG_ID;
			if($message_id>0)
			{
				if($logFields["SENDED"] == "Y")
					$logFields["ERROR_TEXT"] = '';
				
				$res = $logError->Update($message_id, $logFields);
				$LOG_ID = $message_id;
			}
			else
			{
				global $LOG_ID;
				$LOG_ID = $logError->Add($logFields);
			}
		}
			
		if ($this->debug) 
		{
			$logListRes = $logError->GetList();
			$totalLogs = $logListRes->SelectedRowsCount();
			
			if(intVal($totalLogs) >= $this->notify_limit)
			{
				if($this->auto_cleaning_logs)
				{
					$logError->ClearLogs();
				}
				else
				{
					$errorArray = Array(
						"MESSAGE" => GetMessage("LOGS_ARE_TOO_BIG"),
						"TAG" => "LOGS_ARE_TOO_BIG",
						"MODULE_ID" => "WEBPROSTOR.SMTP",
						"ENABLE_CLOSE" => "Y"
					);
					$notifyID = CAdminNotify::Add($errorArray);
				}
			}
		}
		
		return $result;
	}
	
	private function ParseRecipient($list)
	{
		$senToEmails = [];
		
		if(is_array($list))
		{
			foreach($list as $emailRecipient)
			{
				if(strpos($emailRecipient, ','))
				{
					$emailRecipient = explode(',', $emailRecipient);
					foreach($emailRecipient as $email)
					{
						$parseEmail = $this->ParseEmail($email);
						if($parseEmail)
						{
							$senToEmails[] = $parseEmail;
						}
					}
				}
				else
					$parseEmail = $this->ParseEmail($emailRecipient);
					if($parseEmail)
					{
						$senToEmails[] = $parseEmail;
					}
			}
		
			$senToEmails = array_unique($senToEmails);
		}
		
		return $senToEmails;
	}
	
	private static function ParseEmail($recipient)
	{
		if(filter_var($recipient, FILTER_VALIDATE_EMAIL))
			return $recipient;
		else
		{
			preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $recipient, $matches);
			if(is_array($matches[0]))
			{
				return implode(',', $matches[0]);
			}
		}
		return false;
	}
}
?>