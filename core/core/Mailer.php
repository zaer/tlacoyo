<?php
	use PHPMailer\PHPMailer\PHPMailer;
	use PHPMailer\PHPMailer\Exception;
	use PHPMailer\PHPMailer\SMTP;
	define("SMTP_AUTH", true);
	define("SMTP_HOST", "smtp.gmail.com");
	define("SMTP_PORT", "587");
	define("SMTP_USER", "");
	define("SMTP_EMAIL_FROM","");
	define("SMTP_FROM", "Notificaciones BlueDeb");
	define("SMTP_PASS", "");
	define("SMTP_SECURE", "tls");

	class Mailer
	{
		private $SMTP_AUTH;
		private $SMTP_HOST;
		private $SMTP_PORT;
		private $SMTP_USER;
		private $SMTP_EMAIL_FROM;
		private $SMTP_FROM;
		private $SMTP_PASS;
		private $SMTP_SECURE;

		public function setServer($host,$port,$user,$pass)
		{
			$this->SMTP_HOST=$host;
			$this->SMTP_PORT=$port;
			$this->SMTP_USER=$user;
			$this->SMTP_PASS=$pass;
		}

		public function setAuth($auth,$tipo)
		{
			$this->SMTP_AUTH=$auth;
			$this->SMTP_SECURE=$tipo;
		}

		public function setInfo($from,$email_from)
		{
			$this->SMTP_FROM=$from;
			$this->SMTP_EMAIL_FROM=$email_from;
		}

		public function enviarCorreo($to, $subject, $content, $attachment=null)
		{
			include("core/php_mailer/class.phpmailer.php");

			$mail = new PHPMailer(true);
			$mail->IsSMTP();

			try {
					$mail->SMTPDebug = 0;
					$mail->CharSet    = "UTF-8";
					$mail->SMTPSecure = SMTP_SECURE;
					$mail->SMTPAuth   = SMTP_AUTH;
					$mail->Host       = SMTP_HOST;
					$mail->Port       = SMTP_PORT;
					$mail->Username   = SMTP_USER;
					$mail->Password   = SMTP_PASS;

					if (is_array($to))
					{
						foreach($to as $addr) $mail->AddAddress($addr, '');
					}
					else
					{
						$mail->AddAddress($to, '');
					}

					$mail->SetFrom(SMTP_EMAIL_FROM,SMTP_FROM);
					$mail->Subject = $subject;
					$mail->MsgHTML($content);

					if ($attachment != null)
					{
						$mail->AddAttachment($attachment);
					}
					$mail->Send();
					return true;
				}
				catch (Exception $e)
				{
					var_dump($e);
					return false;
				}
		}
	}
?>
