<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	03/28/2015
	*	last edit		03/28/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class Cookies {
	// gereftane Cookie made nazar
	public static function get_cookie($name)
	{
		if(isset($_COOKIE[$name]))
			return $_COOKIE[$name];
		return false;
	}

	// set kardane Cookie made nazar
	public static function set_cookie($name , $value , $expire , $path = '/')
	{
		return setcookie($name , $value , $expire , $path);
	}

	// pak kardane yek cookie
	public static function delete_cookie($name)
	{
		unset($_COOKIE[$name]);
		return static::set_cookie($name , null , -1);
	}
}