<?
function cyrMailHeader($t)
{
	return '=?windows-1251?B?' . base64_encode($t) . '?=';
}

class Mail
{
	var $to;
	var $from;
	var $subject;
	var $text;
	var $files;
	var $charset;
	var $boundary;
	var $error = "";
	# .........................................................
	# .... конструктор класса	
	# [charset] = w | k
	function Mail($charset = 'w', $texttype = "plain")
	{
		$this->charset = ($charset == 'w') ? 'w' : 'k';
		$this->texttype = ($texttype == 'plain') ? 'plain' : 'html';
		$this->boundary = md5(date("YmdHis"));
		$this->files = false;
	}
	# от кого
	function from($mail, $name = "")
	{
		// $this->from = "From: ".cyrMailHeader($name)." <".$mail.">\n";		
		// if($this->charset=='k') $this->from = convert_cyr_string($this->from, 'w', 'k');
		$this->from = "From: " . $mail . ">\n";
	}
	# кому
	function to($mail, $name = "")
	{
		// $this->to = "".cyrMailHeader($name)." <".$mail.">";
		// if($this->charset=='k') $this->to = convert_cyr_string($this->to, 'w', 'k');
		$this->to = $mail;
	}
	# заголовок
	function subj($subject)
	{
		$this->subject = $subject;
		if ($this->charset == 'k')	$this->subject .= convert_cyr_string($subject, 'w', 'k');
	}
	# текст
	function text($text)
	{
		$this->text = $text . "\n\n\n";
		if ($this->charset == 'k')	$this->text = convert_cyr_string($this->text, 'w', 'k');
	}
	# прикрепленный файл
	function attach_file($filename)
	{
		$this->files[] = $filename;
	}
	# отправка
	function send()
	{
		$header = "X-Mailer: PHP/" . phpversion() . " (www.r52.ru)\n";
		$header .= $this->from;
		$mailtext = "";
		if ($this->files) {
			$header .= "MIME-Version: 1.0\n";
			$header .= "Content-Type: multipart/mixed; boundary=\"----------" . $this->boundary . "\"\n\n";

			$mailtext .= "------------" . $this->boundary . "\n";
			$mailtext .= "Content-Type: text/" . $this->texttype . "; charset=" . (($this->charset == 'k') ? "koi-8r" : "Windows-1251") . "\n\n";
			$mailtext .= $this->text;
			foreach ($this->files as $filename) {
				$fp = fopen($filename, "rb");
				if ($fp) {
					$mailtext .= "------------" . $this->boundary . "\n";
					$mailtext .= 'Content-Disposition: attachment; filename="' . basename($filename) . '"' . "\n";
					$mailtext .= 'Content-Type:  application/octet-stream; name="' . basename($filename) . '"' . "\n";
					$mailtext .= 'Content-Transfer-Encoding: base64' . "\n\n";
					$content = fread($fp, filesize($filename));
					$mailtext .= chunk_split(base64_encode($content)) . "\n";
					fclose($fp);
				}
			}
		} else {
			$header .= "Content-Type: text/" . $this->texttype . "; charset=" . (($this->charset == 'k') ? "koi-8r" : "Windows-1251") . "\n\n";
			$mailtext = $this->text;
		}
		//return mail($this->to,"?".(($this->charset=='k')?"koi-8r":"Windows-1251")."?B?"+$this->subject, $mailtext, $header);
		$fd = fopen("/home/host4404/nicp.ru/log.txt", 'a') or die("не удалось открыть файл");
		fwrite($fd, "date :" . date("Y-m-d H:i:s") . "\n");
		fwrite($fd, "this->to :" . $this->to . "\n");
		fwrite($fd, "this->subject :" . $this->subject . "\n");
		fwrite($fd, $mailtext);
		fwrite($fd, $header);
		fclose($fd);
		return mail($this->to, cyrMailHeader($this->subject), $mailtext, $header);
	}
}

class MailSmtp
{
	function smtp_mail(
		$smtp = "cpanel13.d.fozzy.com",			// SMTP-сервер
		$port = "25",			// порт SMTP-сервера
		$login = "noreply@mailer.r52.ru",			// имя пользователя для доступа к почтовому ящику
		$password = "CniRTibVy#WOtbJe", 		// пароль для доступа к почтовому ящику
		$from = "noreply@mailer.r52.ru",			// адрес электронной почты отправителя
		$from_name = "noreply@mailer.r52.ru",		// имя отправителя
		$to = "ivanov@r52.ru", 			// адрес электронной почты получателя
		$subject = "test", 		// тема сообщения
		$message = "test",		// текст сообщения
		$res = "ok"
	)			// сообщение, выводимое при успешной отправке
	{

		//    header('Content-Type: text/plain;');	// необязательный параметр, особенно если включаем через include()
		//    error_reporting(E_ALL ^ E_WARNING);	// необязательный параметр, включает отображение всех ошибок и предупреждений
		//    ob_implicit_flush();					// необязательный параметр, включает неявную очистку

		//    блок для других кодировок, отличных от UTF-8
		//    $message = iconv("UTF-8","KOI8-R",$message); // конвертируем в koi8-r
		//    $message = "Content-Type: text/plain; charset=\"koi8-r\"\r\nContent-Transfer-Encoding: 8bit\r\n\r\n".$message; // конвертируем в koi8-r
		//    $subject=base64_encode(iconv("UTF-8","KOI8-R",$subject)); // конвертируем в koi8-r
		//    $subject=base64_encode($subject); // конвертируем в koi8-r

		$from_name = base64_encode($from_name);
		$subject = base64_encode($subject);
		$message = base64_encode($message);
		$message = "Content-Type: text/plain; charset=\"utf-8\"\r\nContent-Transfer-Encoding: base64\r\nUser-Agent: Koks Host Mail Robot\r\nMIME-Version: 1.0\r\n\r\n" . $message;
		$subject = "=?utf-8?B?{$subject}?=";
		$from_name = "=?utf-8?B?{$from_name}?=";

		try {

			$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
			if ($socket < 0) {
				throw new Exception('socket_create() failed: ' . socket_strerror(socket_last_error()) . "\n");
			}

			$result = socket_connect($socket, $smtp, $port);
			if ($result === false) {
				throw new Exception('socket_connect() failed: ' . socket_strerror(socket_last_error()) . "\n");
			}

			$this->smtp_read($socket);

			$this->smtp_write($socket, 'EHLO ' . $login);
			$this->smtp_read($socket);
			$this->smtp_write($socket, 'AUTH LOGIN');
			$this->smtp_read($socket);
			$this->smtp_write($socket, base64_encode($login));
			$this->smtp_read($socket);
			$this->smtp_write($socket, base64_encode($password));
			$this->smtp_read($socket);
			$this->smtp_write($socket, 'MAIL FROM:<' . $from . '>');
			$this->smtp_read($socket);
			$this->smtp_write($socket, 'RCPT TO:<' . $to . '>');
			$this->smtp_read($socket);
			$this->smtp_write($socket, 'DATA');
			$this->smtp_read($socket);
			$message = "FROM:" . $from_name . "<" . $from . ">\r\n" . $message;
			$message = "To: $to\r\n" . $message;
			$message = "Subject: $subject\r\n" . $message;

			date_default_timezone_set('UTC');
			$utc = date('r');

			$message = "Date: $utc\r\n" . $message;
			$this->smtp_write($socket, $message . "\r\n.");
			$this->smtp_read($socket);
			$this->smtp_write($socket, 'QUIT');
			$this->smtp_read($socket);
			return $res;
		} catch (Exception $e) {
			echo "\nError: " . $e->getMessage();
		}


		if (isset($socket)) {
			socket_close($socket);
		}
	}

	function smtp_read($socket)
	{
		$read = socket_read($socket, 1024);
		if ($read{0} != '2' && $read{0} != '3') {
			if (!empty($read)) {
				throw new Exception('SMTP failed: ' . $read . "\n");
			} else {
				throw new Exception('Unknown error' . "\n");
			}
		}
	}

	function smtp_write($socket, $msg)
	{
		$msg = $msg . "\r\n";
		socket_write($socket, $msg, strlen($msg));
	}
}
$mail = new MailSmtp;
$mail->smtp_mail();