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
	private $prefix = '';
	
	private $use_phpmailer = false;
	
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
	
	private $requires_authentication = '';
	private $helo_command = '';
	
	private $login = '';
	private $password = '';
	private $use_xoauth2 = '';
	
	private $sender_login = '';
	private $sender_password = '';
	
	private $replace_from = '';
	private $replace_from_to_email = '';
	
	private $charset = '';
	private $priority = '';
	private $from = '';
	private $reply_to = '';
	
	private $debug = false;
	private $debug_commands = false;
	private $include_send_info = false;
	private $log_send_ok = false;
	
	private $duplicate = false;
	private $bcc = '';
	
	public function CWebprostorSmtp($siteId = false)
	{
		if(!$siteId)
		{
			$rsSites = CSite::GetList($by = 'sort', $order = 'desc');
			while ($arSite = $rsSites->Fetch())
			{
				if(trim(strtoupper($arSite['SERVER_NAME'])) == strtoupper($_SERVER['SERVER_NAME']))
				{
					$siteId = $arSite['ID'];
					continue;
				}
			}
		}
		
		$this->site_id = $siteId;
		$this->prefix = strtoupper($siteId);
		
		$this->use_phpmailer = COption::GetOptionString($this->module_id, "USE_PHPMAILER", false);
		if($this->use_phpmailer == "N")
			$this->use_phpmailer = false;
		
		$this->smtp_server = COption::GetOptionString($this->module_id, $this->prefix.'_'."SMTP_SERVER");
		$this->smtp_port = COption::GetOptionString($this->module_id, $this->prefix.'_'."SMTP_PORT");
		$this->smtp_secure = COption::GetOptionString($this->module_id, $this->prefix.'_'."SECURE");
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
		$this->sender_smtp_secure = COption::GetOptionString($this->module_id, "SECURE");
		if($this->sender_smtp_secure && $this->sender_smtp_secure == "ssl")
		{
			$this->sender_smtp_host = $this->sender_smtp_secure.'://'.$this->sender_smtp_server;
		}
		else
			$this->sender_smtp_host = $this->sender_smtp_server;
		
		$this->login = COption::GetOptionString($this->module_id, $this->prefix.'_'."LOGIN");
		$this->password = COption::GetOptionString($this->module_id, $this->prefix.'_'."PASSWORD");
		$this->use_xoauth2 = COption::GetOptionString($this->module_id, $this->prefix.'_'."USE_XOAUTH2");
		if($this->use_xoauth2 == "N")
			$this->use_xoauth2 = false;
		
		$this->sender_login = COption::GetOptionString($this->module_id, "LOGIN");
		$this->sender_password = COption::GetOptionString($this->module_id, "PASSWORD");
		
		$this->replace_from = COption::GetOptionString($this->module_id, $this->prefix.'_'."REPLACE_FROM");
		$this->replace_from_to_email = COption::GetOptionString($this->module_id, $this->prefix.'_'."REPLACE_FROM_TO_EMAIL");
		$this->requires_authentication = COption::GetOptionString($this->module_id, $this->prefix.'_'."REQUIRES_AUTHENTICATION", true);
		if($this->requires_authentication == "N")
			$this->requires_authentication = false;
		
		$this->helo_command = COption::GetOptionString($this->module_id, $this->prefix.'_'."HELO_COMMAND", true);
		if(!$this->helo_command || strlen($this->helo_command) != 4)
			$this->helo_command = "EHLO";
		
		$this->charset = strtoupper(COption::GetOptionString($this->module_id, $this->prefix.'_'."CHARSET", "utf-8"));
		$this->priority = COption::GetOptionString($this->module_id, $this->prefix.'_'."PRIORITY", 3);
		$this->from = COption::GetOptionString($this->module_id, $this->prefix.'_'."FROM", false);
		$this->reply_to = COption::GetOptionString($this->module_id, $this->prefix.'_'."REPLY_TO", false);
		
		$this->debug = COption::GetOptionString($this->module_id, "LOG_ERRORS", false);
		if($this->debug == "N")
			$this->debug = false;
		$this->debug_commands = COption::GetOptionString($this->module_id, "LOG_COMMANDS", false);
		if($this->debug_commands == "N")
			$this->debug_commands = false;
		$this->include_send_info = COption::GetOptionString($this->module_id, "INCLUDE_SEND_INFO_TO_LOG", false);
		if($this->include_send_info == "N")
			$this->include_send_info = false;
		$this->log_send_ok = COption::GetOptionString($this->module_id, "LOG_SEND_OK", false);
		if($this->log_send_ok == "N")
			$this->log_send_ok = false;
		
		$this->duplicate = COption::GetOptionString($this->module_id, $this->prefix.'_'."DUPLICATE", false);
		if($this->duplicate == "N")
			$this->duplicate = false;
		if($this->duplicate)
			$this->bcc = COption::GetOptionString("main", "all_bcc");
	}
	
	public function SendMail($to = false, $subject = false, $message = false, $additional_headers = '', $additional_parameters = '')
	{
		if($this->debug)
		{
			global $logError;
			$logError = new CWebprostorSmtpLogs;
			
			$logFields = Array();
			if($this->debug_commands)
			{
				$logCommandFields = Array();
			}
		}
		
		$sendInfo = '';
		$sendInfo .= 'Subject: '.$subject."\r\n";
		
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
		
		if($this->use_sender_smtp)
		{
			if($additional_headers) 
			{
				preg_match('/\bBitrix-Sender: (.+)\n/i', $additional_headers, $matches);
				list(, $bitrixSender) = $matches;
				
				preg_match('/\bX-Bitrix-Posting: (.+)\n/i', $additional_headers, $matches);
				list(, $bitrixPosting) = $matches;
				
				if($bitrixSender)
				{
					$this->is_sender = true;
					if($this->debug)
						$logFields["MODULE_ID"] = "sender";
				}
				elseif($bitrixPosting)
				{
					$this->is_sender = true;
					if($this->debug)
						$logFields["MODULE_ID"] = "subscribe";
				}
			}
			if($this->is_sender !== true && strpos($additional_parameters, "bitrix_subscribe=Y") !== false) 
			{
				$this->is_sender = true;
				if($this->debug)
					$logFields["MODULE_ID"] = "subscribe";
			}
		}
		
		$sendInfo .= "\r\n".$message."\r\n";
		
		if($this->debug)
		{
			if($this->is_sender !== true)
			{
				$logFields["SITE_ID"] = $this->site_id;
				
				if($this->debug_commands)
				{
					$logCommandFields["SITE_ID"] = $this->site_id;
				}
			}
		}
		
		if($this->use_phpmailer)
		{
				
			try {
				
				$mail = new PHPMailer(true);
				$mail->setLanguage('ru', $_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/webprostor.smtp/lang/ru/classes/phpmailer/");
				
				//Server settings
				$mail->isSMTP();
					
				$mail->Host       = $this->is_sender?$this->sender_smtp_host:$this->smtp_host;
				$mail->SMTPAuth   = $this->is_sender?true:$this->requires_authentication;
				$mail->AuthType	  = $this->use_xoauth2?'XOAUTH2':'LOGIN';
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
				$mail->Username   = $this->is_sender?$this->sender_login:$this->login;
				$mail->Password   = $this->is_sender?$this->sender_password:$this->password;
				$mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
				$mail->Port       = $this->is_sender?$this->sender_smtp_port:$this->smtp_port;

				//Recipients
				if(!$this->is_sender)
				{
					if($this->replace_from_to_email != "" && filter_var($this->replace_from_to_email, FILTER_VALIDATE_EMAIL))
					{
						$mail->setFrom($this->replace_from_to_email);
					}
					elseif($this->replace_from == "Y")
					{
						$mail->setFrom($this->login);
					}
				}
				else
				{
					if(!filter_var($this->from, FILTER_VALIDATE_EMAIL))
					{
						preg_match('/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i', $this->from, $matches);
						if(filter_var($matches[0], FILTER_VALIDATE_EMAIL))
							$this->from = $matches[0];
					}
					
					$mail->setFrom($this->from);
				}
				$mail->addAddress($to);
				$senToEmails[] = $to;
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
				
				//Content       
				$mail->CharSet = 'utf-8';
				$mail->Subject = $subject;
				$mail->Body    = $message;
				
				if ($additional_headers != '') 
				{
					$additionalHeadersArray = explode("\n", $additional_headers);
					
					if(is_array($additionalHeadersArray))
					{
						foreach($additionalHeadersArray as $code=>$value)
						{
							$header = explode(":", $value, 2);
							switch(strtoupper($header[0]))
							{
								case "CC":
								case "BCC":
								case "TO":
								case "FROM":
								case "CONTENT-TYPE":
								case "CONTENT-TRANSFER-ENCODING":
								case "SUBJECT":
									break;
								case "REPLY-TO":
									$mail->addReplyTo(trim($header[1]));
									break;
								default:
									$mail->AddCustomHeader($header[0], trim($header[1]));
									break;
							}
						}
					}
				}
				
				$mail->AddCustomHeader("X-Mailer", "webprostor.smtp (https://marketplace.1c-bitrix.ru/solutions/webprostor.smtp/)");
				
				if($this->is_sender)
					$mail->AddCustomHeader("X-Sender-Service", $this->sender_smtp_server);
			
				preg_match('/-------alt(.+)\n/i', $message, $matches);
				list(, $boundary_alt) = $matches;
			
				preg_match('/-------mix(.+)\n/i', $message, $matches);
				list(, $boundary_mix) = $matches;
				
				if($boundary_alt != '')
				{
					$mail->AddCustomHeader('Content-Type', 'multipart/alternative; boundary="-------alt'.$boundary_alt.'"');
					$mail->isHTML(false, true); 
				}
				elseif($boundary_mix != '')
				{
					$mail->AddCustomHeader('Content-Type', 'multipart/mixed; boundary="-------mix'.$boundary_mix.'"');
					$mail->isHTML(false, true); 
				}
				else
				{
					if(strlen($message) == strlen(strip_tags($message)))
						$mail->isHTML(false); 
					else
						$mail->isHTML(true); 
				}
				
				$mail->send();
			}
			catch (Exception $e) 
			{
				
				if ($this->debug) 
				{
					$logFields["ERROR_TEXT"] = $mail->ErrorInfo;
					if($this->include_send_info)
						$logFields["SEND_INFO"] = $sendInfo;
					$logError->Add($logFields);
				}
				
				return false;
			}
			
			if ($this->debug && $this->log_send_ok) 
			{
		
				$logFields["ERROR_TEXT"] = GetMessage("OK_2", Array("#RECIPIENTS#" => implode(", ", array_unique($senToEmails))));
				if($this->include_send_info)
					$logFields["SEND_INFO"] = $sendInfo;
				$logError->Add($logFields);
			}
		}
		else
		{
		
			if(!$this->smtp_host || !$this->smtp_port)
			{
				if ($this->debug) 
				{
					$logFields["ERROR_TEXT"] = GetMessage("SETTING_1");
					$logError->Add($logFields);
				}
				return false;
			}
			elseif(!$this->login || !$this->password)
			{
				if ($this->debug) 
				{
					$logFields["ERROR_TEXT"] = GetMessage("SETTING_2");
					$logError->Add($logFields);
				}
				return false;
			}
			elseif($this->smtp_port == 465 && !$this->smtp_secure)
			{
				if ($this->debug) 
				{
					$logFields["ERROR_TEXT"] = GetMessage("SETTING_3");
					$logError->Add($logFields);
				}
				return false;
			}
			elseif($this->smtp_port == 587 && !$this->smtp_secure)
			{
				if ($this->debug) 
				{
					$logFields["ERROR_TEXT"] = GetMessage("SETTING_4");
					$logError->Add($logFields);
				}
				return false;
			}
		
			if(!$socket = fsockopen($this->smtp_host, $this->smtp_port, $errnum, $errstr, 30)) 
			{
				if ($this->debug) 
				{
					$logFields["ERROR_TEXT"] = GetMessage("ERROR_0", Array("#SMTP_HOST#" => $this->smtp_host, "#SMTP_PORT#" => $this->smtp_port, "#ERROR_NUMBER#" => $errnum, "#ERROR_TEXT#" => $errstr));
					$logError->Add($logFields);
				}
				return false;
			}
			
			if (!$this->ParseData($socket, "220", __LINE__)) 
				return false;
			
			fputs($socket, $this->helo_command." " . $this->smtp_server . "\r\n");
			if ($this->debug && $this->debug_commands) 
			{
				$logCommandFields["ERROR_TEXT"] = $this->helo_command." " . $this->smtp_server . "\r\n";
				$logError->Add($logCommandFields);
			}
			if (!$this->ParseData($socket, "250", __LINE__)) 
			{
				if ($this->debug) 
				{
					$logFields["ERROR_TEXT"] = GetMessage("ERROR_1", Array("#SMTP_HOST#" => $this->smtp_host, "#HELO_COMMAND#" => $this->helo_command));
					$logError->Add($logFields);
				}
				fclose($socket);
				return false;
			}
			
			if($this->smtp_secure == "tls")
			{
				fputs($socket, "STARTTLS\r\n");
				if ($this->debug && $this->debug_commands) 
				{
					$logCommandFields["ERROR_TEXT"] = "STARTTLS\r\n";
					$logError->Add($logCommandFields);
				}
				if (!$this->ParseData($socket, "220", __LINE__)) 
				{
					if ($this->debug) 
					{
						$logFields["ERROR_TEXT"] = GetMessage("ERROR_10");
						$logError->Add($logFields);
					}
					fclose($socket);
					return false;
				}
				
				if(false == stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)){
					if ($this->debug) 
					{
						$logFields["ERROR_TEXT"] = GetMessage("ERROR_11");
						$logError->Add($logFields);
					}
					fclose($socket);
					return false;
				}
			
				fputs($socket, $this->helo_command." " . $this->smtp_server . "\r\n");
				if ($this->debug && $this->debug_commands) 
				{
					$logCommandFields["ERROR_TEXT"] = $this->helo_command." " . $this->smtp_server . "\r\n";
					$logError->Add($logCommandFields);
				}
				if (!$this->ParseData($socket, "250", __LINE__)) 
				{
					if ($this->debug) 
					{
						$logFields["ERROR_TEXT"] = GetMessage("ERROR_1", Array("#SMTP_HOST#" => $this->smtp_host));
						$logError->Add($logFields);
					}
					fclose($socket);
					return false;
				}
			}
			
			if($this->requires_authentication)
			{
			
				fputs($socket, "AUTH LOGIN\r\n");
				if ($this->debug && $this->debug_commands) 
				{
					$logCommandFields["ERROR_TEXT"] = "AUTH LOGIN\r\n";
					$logError->Add($logCommandFields);
				}
				if (!$this->ParseData($socket, "334", __LINE__))
				{
					if ($this->debug) 
					{
						$logFields["ERROR_TEXT"] = GetMessage("ERROR_2");
						$logError->Add($logFields);
					}
					fclose($socket);
					return false;
				}
				
				fputs($socket, base64_encode($this->login) . "\r\n");
				if ($this->debug && $this->debug_commands) 
				{
					$logCommandFields["ERROR_TEXT"] = base64_encode($this->login) . "\r\n";
					$logError->Add($logCommandFields);
				}
				if (!$this->ParseData($socket, "334", __LINE__))
				{
					if ($this->debug) 
					{
						$logFields["ERROR_TEXT"] = GetMessage("ERROR_3", Array("#LOGIN#" => $this->login));
						$logError->Add($logFields);
					}
					fclose($socket);
					return false;
				}
				
				fputs($socket, base64_encode($this->password) . "\r\n");
				if ($this->debug && $this->debug_commands) 
				{
					$logCommandFields["ERROR_TEXT"] = base64_encode($this->password) . "\r\n";
					$logError->Add($logCommandFields);
				}
				if (!$this->ParseData($socket, "235", __LINE__)) 
				{
					if ($this->debug) 
					{
						$logFields["ERROR_TEXT"] = GetMessage("ERROR_4");
						$logError->Add($logFields);
					}
					fclose($socket);
					return false;
				}
			
			}
			
			fputs($socket, "MAIL FROM: <{$this->login}>\r\n");
			if ($this->debug && $this->debug_commands) 
			{
				$logCommandFields["ERROR_TEXT"] = "MAIL FROM: <{$this->login}>\r\n";
				$logError->Add($logCommandFields);
			}
			if (!$this->ParseData($socket, "250", __LINE__))
			{
				if ($this->debug) 
				{
					$logFields["ERROR_TEXT"] = GetMessage("ERROR_5");
					$logError->Add($logFields);
				}
				fclose($socket);
				return false;
			}
			
			$senToEmailsArray[] = trim($to);
			if($copyTo)
				$senToEmailsArray[] = $copyTo;
			if($hideCopyTo)
				$senToEmailsArray[] = $hideCopyTo;
			
			$senToEmails = $this->ParseRecipient($senToEmailsArray);
			
			if(is_array($senToEmails) && count($senToEmails)>0)
			{
				foreach($senToEmails as $recipinet)
				{
					$recipinet = trim($recipinet);
					fputs($socket, "RCPT TO: <" . $recipinet . ">\r\n");
					if ($this->debug && $this->debug_commands) 
					{
						$logCommandFields["ERROR_TEXT"] = "RCPT TO: <" . $recipinet . ">\r\n";
						$logError->Add($logCommandFields);
					}
					if (!$this->ParseData($socket, "250", __LINE__))
					{
						if ($this->debug) 
						{
							$logFields["ERROR_TEXT"] = GetMessage("ERROR_6", Array("#RECIPIENT#" => $recipinet));
							$logError->Add($logFields);
						}
						fclose($socket);
						return false;
					}
				}
			}
			else
			{
				if ($this->debug) 
				{
					$logFields["ERROR_TEXT"] = GetMessage("SETTING_5");
					$logError->Add($logFields);
				}
				return false;
			}
			
			fputs($socket, "DATA\r\n");
			if ($this->debug && $this->debug_commands) 
			{
				$logCommandFields["ERROR_TEXT"] = "DATA\r\n";
				$logCommandFields["SEND_INFO"] = $sendInfo."\r\n.\r\n";
				$logError->Add($logCommandFields);
				unset($logCommandFields["SEND_INFO"]);
			}
			if (!$this->ParseData($socket, "354", __LINE__))
			{
				if ($this->debug) 
				{
					$logFields["ERROR_TEXT"] = GetMessage("ERROR_7");
					$logError->Add($logFields);
				}
				fclose($socket);
				return false;
			}
			
			fputs($socket, $sendInfo."\r\n.\r\n");
			if (!$this->ParseData($socket, "250", __LINE__))
			{
				if ($this->debug) 
				{
					$logFields["ERROR_TEXT"] = GetMessage("ERROR_8");
					$logError->Add($logFields);
				}
				fclose($socket);
				return false;
			}
			
			fputs($socket, "QUIT\r\n");
			if ($this->debug && $this->debug_commands) 
			{
				$logCommandFields["ERROR_TEXT"] = "QUIT\r\n";
				$logError->Add($logCommandFields);
			}
			fclose($socket);
			
			if ($this->debug && $this->log_send_ok) 
			{
				if($this->include_send_info)
					$logFields["SEND_INFO"] = $sendInfo;
				
				$logFields["ERROR_TEXT"] = GetMessage("OK_2", Array("#RECIPIENTS#" => implode(", ", $senToEmails)));
				$logError->Add($logFields);
			}
		
		}
			
		if ($this->debug) 
		{
			$logListRes = $logError->GetList();
			if(intVal($logListRes->SelectedRowsCount())>=1000)
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
		
		return true;
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
	
	private function ParseEmail($recipient)
	{
		if(filter_var($recipient, FILTER_VALIDATE_EMAIL))
			return $recipient;
		else
		{
			preg_match_all("/[\._a-zA-Z0-9-]+@[\._a-zA-Z0-9-]+/i", $recipient, $matches);
			if(is_array($matches[0]))
			{
				return implode($matches[0], ',');
			}
		}
		return false;
	}
	
	private function ParseData($socket, $response, $line = __LINE__)
	{
		global $logError;
		
		while (@substr($server_response, 3, 1) != ' ')
		{
			if (!($server_response = fgets($socket, 256)))
			{
				if ($this->debug) 
				{
					$logFields["ERROR_TEXT"] = GetMessage("ERROR_9", Array("#SERVER_RESPONSE#" => $server_response));
					$logError->Add($logFields);
				}
				return false;
			}
		}
		$response_code = substr($server_response, 0, 3);
		$response_text = substr($server_response, 3, strlen($server_response));
		if (!($response_code == $response))
		{
			if ($this->debug) 
			{
				$logFields["ERROR_NUMBER"] = $response_code;
				$logFields["ERROR_TEXT"] = $response_text;
				$logError->Add($logFields);
			}
			return false;
		}
		return true;
	}
}
?>