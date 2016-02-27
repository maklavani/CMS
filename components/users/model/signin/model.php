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

class SigninModel extends Model {
	public function has_user($check)
	{
		$this->table('users')->where('`username` = "' . $check . '" OR `email` = "' . $check . '"')->select()->process();
		return $this->output();
	}

	// set kardane vaghti ke karbar vared mishavad
	public function set_sign_in($id , $remember)
	{
		$rand_str = Regex::random_string(100);
		$time = 21600;
		if($remember){
			$time = 1209600;
			Cookies::set_cookie('remember' , '1' , time() + $time);
		} else {
			Cookies::remove_cookie('remember');
		}

		Cookies::set_cookie('enter_key' , $rand_str , time() + $time);
		Sessions::set_session($rand_str);

		$this->table('users')->update(array(array('visit' , Site::$datetime) , array('ip' , Site::$ip) , array('logged' , $rand_str)))->where('`id` = ' . $id)->process();
	}
}