<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	06/15/2015
	*	last edit		12/04/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class SignupModel extends Model {
	public function has_user($variable , $value)
	{
		$this->table('users')->where('`' . $variable . '` = "' . $value . '"')->select()->process();
		return $this->output();
	}

	public function save()
	{
		require_once _INC . 'regex/hash.php';

		$code = "";

		while(true){
			$code = Regex::random_string(20);
			$this->table('users')->where('`code` = "' . $code . '"')->select()->process();

			if(!$this->output())
				break;
		}

		$this->create_source($code);

		$profile = array('tel' => "" , 'address' => "" , 'favorites' => "");

		$this->table('users');
		$this->insert(
						array(	'name' , 'family' , 'username' , 'code' , 'group' , 'email' , 
								'register' , 'status' , 'profile') , 
						array(	$_POST['form_input_1'] , $_POST['form_input_2'] , $_POST['form_input_3'] , $code , 5 , $_POST['form_input_4'] , 
								Site::$datetime , 1 , htmlspecialchars(json_encode($profile , JSON_UNESCAPED_UNICODE)))
					);
		$this->process();

		$last_id = $this->last_insert_id;
		$this->table('users');
		$this->update(	array(
							array('password' , Hash::create('sha512' , $_POST['form_input_5'] , 1000 + $last_id)) , 
							array('password_edit' , Site::$datetime)
							)
					);
		$this->where('`id` = ' . $last_id);
		$this->process();
		$this->create_identification($last_id , $_POST['form_input_3']);
		return $last_id;
	}

	public function create_source($code)
	{
		if(!System::has_file('uploads/component') && mkdir(_SRC_SITE . "uploads/component" , 0755 , true))
		{
			$html_file = fopen(_SRC_SITE . "uploads/component/index.html" , "w");
			fwrite($html_file , "<!DOCTYPE html><title></title>");
			fclose($html_file);
		}

		if(!System::has_file('uploads/component/user') && mkdir(_SRC_SITE . "uploads/component/user" , 0755 , true))
		{
			$html_file = fopen(_SRC_SITE . "uploads/component/user/index.html" , "w");
			fwrite($html_file , "<!DOCTYPE html><title></title>");
			fclose($html_file);
		}

		if(!System::has_file('uploads/component/user/' . $code) && mkdir(_SRC_SITE . "uploads/component/user/" . $code , 0755 , true))
		{
			$html_file = fopen(_SRC_SITE . "uploads/component/user/" . $code . "/index.html" , "w");
			fwrite($html_file , "<!DOCTYPE html><title></title>");
			fclose($html_file);
		}
	}

	public function create_identification($id , $username)
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
		$mail->to = $_POST['form_input_4'];
		$mail->from = Configuration::$email;
		$mail->subject = Language::_('COM_USERS_SIGNUP');
		$mail->message = 
						sprintf(Language::_('COM_USERS_MESSAGE_SIGNUP_HELLO') , $username , Site::$domain_name) . '<hr>' . 
						sprintf(Language::_('COM_USERS_MESSAGE_IDENTIFICATION') , $link) . '<hr>' . 
						sprintf(Language::_('COM_USERS_MESSAGE_VALIDITY') , 7 , $calendar->convert(Site::$datetime , 'y-m-d H:i')) . '<hr>' . 
						Language::_('COM_USERS_MESSAGE_AUTOMATIC');
		$mail->send();

		$this->table('identification');
		$this->insert(	array('code' , 'component' , 'val' , 'expire') , 
						array($code , 'users' , $id , date("Y-m-d H:i:s" , strtotime('+7 day')))
					);
		$this->process();
	}
}