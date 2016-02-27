<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/12/2015
	*	last edit		12/21/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class Plugins {
	private static $instance;
	public static $plugins;

	// ejraye constructor
	function __construct()
	{
		self::$plugins = array();
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

	public static function read(&$buffer , $type = false)
	{
		preg_match_all('@<digarsoo\s*type="plugins"\s*name="([^"]+)"\s*\/?>@iU' , $buffer , $plugins);

		if(isset($plugins[1]) && is_array($plugins[1]) && !empty($plugins[1]))
			foreach ($plugins[1] as $value)
				$buffer = preg_replace('@<digarsoo\s*type="plugins"\s*name="' . $value . '"\s*\/?>@iU' , Plugins::get($value , $buffer , _LOC) , $buffer);

		if($type != 'components')
		{
			Plugins::get('system' , $buffer , _LOC , true);
		}
	}

	private static function get($name , &$buffer , $location , $replace = false)
	{
		if(System::has_file('plugins/' . strtolower($name) . '/' . strtolower($name) . '.php'))
		{
			require_once _PLG . strtolower($name) . '/' . strtolower($name) . '.php';
			$class_name = ucwords(strtolower($name)) . 'Plugins';

			// agar class vujud dasht
			if(class_exists($class_name))
			{
				$class = new $class_name();
				if(method_exists($class , 'read'))
					if($replace)
						$buffer = call_user_func_array(array($class , 'read') , array($buffer , $location));
					else
						return call_user_func_array(array($class , 'read') , array($buffer , $location));
			}
		}
	}
}

$plugins = Plugins::get_instance();