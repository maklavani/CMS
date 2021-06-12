<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	07/12/2015
	*	last edit		12/03/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class SystemPlugins {
	public $plugins;

	function __construct()
	{
		$this->setting = array();

		$db = new Database;
		$db->table('plugins')->where('`category` = "system"')->select()->process();
		$this->plugins = $db->output();
	}

	public function read($buffer , $location)
	{
		if(!empty($this->plugins))
			foreach ($this->plugins as $key => $value)
				if($value->location == "all" || $value->location == $location)
				{
					require_once _PLG . 'system/' . $value->type . '/' . $value->type . '.php';
					$class_name = ucwords($value->type) . 'System';
					$class = new $class_name();
					$buffer = call_user_func_array(array($class , 'get') , array($buffer));
				}

		return $buffer;
	}
}