<?
function cyrMailHeader($t){
    return '=?utf-8?B?'.base64_encode($t).'?=';
}

class Mail{
	var $mSMTP;
	var $arMail;
# .........................................................
# .... конструктор класса	

	function Mail($charset='u',$texttype="plain"){
		$this->mSMTP = new PHPMailer();
		$this->mSMTP->CharSet = 'UTF-8';
		// $this->mSMTP->IsSMTP();
		$this->mSMTP->IsMail();
		$this->mSMTP->SMTPAuth   = true;
		$this->mSMTP->IsHTML(true);  
		$this->mSMTP->Host = SMTP_HOST;
		$this->mSMTP->Port       = SMTP_PORT;
		$this->mSMTP->Username   = SMTP_USER;
		$this->mSMTP->Password   = SMTP_PASS;
		if(defined('SMTP_SECU')) $this->mSMTP->SMTPSecure = SMTP_SECU;
		$this->arMail = array();
	}
# от кого
	function from($mail,$name=""){
		$fd = fopen("/usr/www/users/tripwi/log.txt", 'a') or die("не удалось открыть файл");
		fwrite($fd, "from \$mail " . $mail . "\n");
		fclose($fd);	

		$this->mSMTP->SetFrom(SMTP_USER, $name);
		$this->mSMTP->AddReplyTo($mail, $name);
		$this->arMail["from_mail"] = $mail;
	}
# кому
	function to($mail,$name=""){
		$fd = fopen("/usr/www/users/tripwi/log.txt", 'a') or die("не удалось открыть файл");
		fwrite($fd, "to \$mail " . $mail . "\n");
		fclose($fd);	

		$this->mSMTP->ClearAddresses();
		$this->mSMTP->AddAddress($mail,$name);
		$this->arMail["to_mail"] = $mail;
	}
# заголовок
	function subj($subject){
		$this->mSMTP->Subject = $subject;
		$this->arMail["subject"] = $subject;
	}
# текст
	function text($text){
		$this->mSMTP->Body = $text;
		$this->arMail["text"] = $text;
	}
# прикрепленный файл
	function attach_file($filename){
		$this->mSMTP->AddAttachment($attach_file);
	}
# отправка
	function send(){
		// $res = $this->mSMTP->Send();
		$headers = array();
		$headers[] = "From: tripwithlove.site@gmail.com";
		$headers[] = 'MIME-Version: 1.0';
        // $headers[] = 'Content-type: text/html; charset=iso-8859-1';
        $headers[] = 'Content-type: text/html; charset=UTF-8';
        $headers[] = 'Content-Transfer-Encoding: 8bit';
		$resMail = mail($this->arMail["to_mail"],$this->arMail["subject"],$this->arMail["text"],implode("\r\n", $headers));
		$fd = fopen("/usr/www/users/tripwi/log.txt", 'a') or die("не удалось открыть файл");
		// fwrite($fd, "this->mSMTP->Host " . $this->mSMTP->Host . "\n");
		// fwrite($fd, "this->mSMTP->Port " . $this->mSMTP->Port . "\n");
		// fwrite($fd, "this->mSMTP->Username " . $this->mSMTP->Username . "\n");
		// fwrite($fd, "this->mSMTP->Password " . $this->mSMTP->Password . "\n");
		// fwrite($fd, "Send() res: " . $res . "\n");
		fwrite($fd, "\$resMail: " . $resMail . "\n");
		fclose($fd);	
		// return $this->mSMTP->Send();
		// return $res;		 
	}
}		
?>