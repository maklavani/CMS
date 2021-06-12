<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	05/17/2017
	*	last edit		05/17/2017
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class AuthenticationModel extends Model {
	public function has_user($variable , $value)
	{
		$this->table('users')->where('`' . $variable . '` = "' . $value . '"')->select()->process();
		return $this->output();
	}

	public function change_email()
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

		$link = "<a href=\"" . Site::$domain_name . Language::_('COM_USERS') . "/" . str_replace(" " , "-" , Language::_('COM_USERS_IDENTIFICATION')) . "/" . $code . "\">" . Language::_('COM_USERS_IDENTIFICATION') . "</a>";

		$mail = new Mail;
		$mail->to = $_POST['form_input_1'];
		$mail->from = Configuration::$email;
		$mail->subject = Language::_('COM_USERS_SIGNUP');
		$mail->message = 
						sprintf(Language::_('COM_USERS_MESSAGE_SIGNUP_HELLO') , User::$username , Site::$domain_name) . '<hr>' . 
						sprintf(Language::_('COM_USERS_MESSAGE_IDENTIFICATION') , $link) . '<hr>' . 
						sprintf(Language::_('COM_USERS_MESSAGE_VALIDITY') , 7 , $calendar->convert(Site::$datetime , 'y-m-d H:i')) . '<hr>' . 
						Language::_('COM_USERS_MESSAGE_AUTOMATIC');
		$mail->send();

		$this->table("users")->update(array(array("authentication" , 1) , array("email" , $_POST['form_input_1'])))->where("`id` = " . User::$id)->process();
		$this->table("identification")->delete()->where("`section` = 'signup' AND `val` = " . User::$id)->process();
		$this->table('identification')
			->insert(
				array('code' , 'section' , 'val' , 'expire') , 
				array($code , 'signup' , User::$id , date("Y-m-d H:i:s" , strtotime('+7 day')))
				)->process();
	}
}