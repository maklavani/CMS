<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	09/22/2016
	*	last edit		09/22/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class ForgetModel extends Model {
	public function get_user_with_email($email)
	{
		$this->table('users')->where('`email` = "' . $email . '"')->select()->process();
		return $this->output();
	}

	public function send($user)
	{
		$code = "";

		while(true){
			$code = Regex::random_string(100);
			$this->table('identification')->where('`code` = "' . $code . '"')->select()->process();

			if(!$this->output())
				break;
		}

		// mail
		require_once _INC . 'system/mail.php';
		require_once _INC . 'system/calendar.php';

		if(Language::$lang == 'fa-ir')
			$calendar = new Calendar('shamsi');
		else
			$calendar = new Calendar();

		$link = "<a href=\"" . Site::$domain_name . Language::_('COM_USERS') . "/" . str_replace(" " , "-" , Language::_('COM_USERS_IDENTIFICATION')) . "/" . $code . "\">" . Language::_('COM_USERS_FORGET') . "</a>";

		$mail = new Mail;
		$mail->to = $user->email;
		$mail->from = Configuration::$email;
		$mail->subject = Language::_('COM_USERS_FORGET');
		$mail->message = 
						sprintf(Language::_('COM_USERS_MESSAGE_FORGET_HELLO') , ($user->username != "" ? $user->username : $user->name . " " . $user->family)) . '<hr>' . 
						sprintf(Language::_('COM_USERS_MESSAGE_IDENTIFICATION_FORGET') , $link) . '<hr>' . 
						sprintf(Language::_('COM_USERS_MESSAGE_VALIDITY') , 7 , $calendar->convert(Site::$datetime , 'Y-m-d H:i')) . '<hr>' . 
						Language::_('COM_USERS_MESSAGE_AUTOMATIC');
		$mail->send();

		$this->table('identification');
		$this->insert(	array('code' , 'section' , 'val' , 'expire') , 
						array($code , 'forget' , $user->email , date("Y-m-d H:i:s" , strtotime('+7 day')))
					);
		$this->process();
	}
}