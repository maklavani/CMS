<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	06/21/2015
	*	last edit		04/30/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class Sessions {
	private static $instance;

	// ejraye constructor
	function __construct()
	{
		session_start();
		session_regenerate_id();
	}

	// sakhtane instance baraye estefade az tavabe static
	public static function get_instance() 
	{
		if (!isset(self::$instance)) {
			$c = __CLASS__;
			$instance = new $c;
		}
		return self::$instance;
	}

	// gereftane session made nazar
	public static function get_session($name)
	{
		if(array_key_exists($name , $_SESSION) && !empty($_SESSION[$name]))
			return $_SESSION[$name];
		return false;
	}

	// khandane session made nazar
	public static function set_session($name , $value = false)
	{
		if($value)
			$_SESSION[$name] = $value;
		else
			$_SESSION[$name] = time();
	}

	// khandane session made nazar
	public static function delete_session($name)
	{
		unset($_SESSION[$name]);
	}
}

$sessions = Sessions::get_instance();