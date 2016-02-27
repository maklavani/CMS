<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	06/15/2015
	*	last edit		07/11/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class Language {
	private static $instance;
	private static $srcs;

	public static $DEFINE;
	public static $lang;
	public static $direction;
	public static $abbreviation;

	// ejraye constructor
	function __construct()
	{
		self::$srcs = array();

		self::$lang = false;
		self::$direction = false;
		self::$abbreviation = false;
	}

	// sakhtane instance baraye estefade az tavabe static
	public static function get_instance() 
	{
		if (!isset(self::$instance))
		{
			$c = __CLASS__;
			$instance = new $c;
		}
		return self::$instance;
	}

	// bargardandane define made nazar
	public static function _($value)
	{
		if(isset(self::$DEFINE[$value]))
			return self::$DEFINE[$value];
		return $value;
	} 

	// tabe khandane filehaye ini
	public static function add_ini_file($file)
	{
		if(!in_array($file , self::$srcs))
		{
			self::$srcs[] = $file;

			$ini_array = json_decode(file_get_contents($file));
			foreach ($ini_array as $key => $value)
				self::$DEFINE[$key] = $value;
		}
	}

	public static function get_key($str)
	{
		if(false !== $key = array_search($str , self::$DEFINE))
			return $key;
		return false;
	}
}

$languages = Language::get_instance();