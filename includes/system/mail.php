<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	07/27/2015
	*	last edit		06/01/2017
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class Mail {
	public $template;
	public $class;

	public $from;
	public $to;
	public $subject;
	public $message;
	public $headers;

	function __construct($template = 'classic')
	{
		$this->subject = "";
		$this->message = "";
		$this->template = $template;

		require_once _INC . 'system/mail_templates/' . $this->template . '.php';
		$class_name = ucwords(strtolower($this->template)) . 'Mail';

		// agar class vujud dasht
		if(class_exists($class_name))
			$this->class = new $class_name();
	}

	public function send()
	{
		if(Configuration::$email_function == "smtp")
		{
			require_once _INC . "system/smtp/PHPMailerAutoload.php";
			$mail = new PHPMailer;

			$mail->isSMTP();
			$mail->CharSet = 'UTF-8';
			$mail->Host = Configuration::$host_email;
			$mail->SMTPAuth = Configuration::$smtp_authentication ? true : false;
			$mail->Username = Configuration::$email;
			$mail->Password = Configuration::$email_password;
			$mail->SMTPSecure = 'tls';
			$mail->Port = Configuration::$port;

			$mail->setFrom($this->from);
			$mail->addAddress($this->to);
			$mail->isHTML(true);
			$mail->Subject = $this->subject;
			$mail->Body = $this->message;
			$mail->smtpConnect(array("ssl" => array("verify_peer" => false , "verify_peer_name" => false , "allow_self_signed" => true)));

			return $mail->send();
		}
		else
		{
			$this->headers = "From: " . $this->from . "\r\n";
			$this->headers .= "Reply-To: ". $this->from . "\r\n";
			$this->headers .= "MIME-Version: 1.0\r\n";
			$this->headers .= "Content-Type: text/html; charset=UTF-8\r\n";

			if(method_exists($this->class , 'get'))
				$this->message = call_user_func_array(array($this->class , 'get') , array($this->subject , $this->message));

			mail($this->to , $this->subject , $this->message , $this->headers);
		}
	}
}