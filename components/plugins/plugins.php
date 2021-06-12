<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	06/16/2015
	*	last edit		12/03/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class PluginsController extends Controller {
	// khandane ajax
	public function ajax()
	{
		if(isset($_GET['plugins']) && Regex::cs($_GET['plugins'] , "text"))
		{
			$db = new Database;
			$db->table('plugins')->where('`type` = "' . Configuration::$captcha . '" AND `category` = "captcha"')->select()->process();
			$plugin = $db->output();

			if(isset($plugin[0]) && System::has_file('plugins/' . $_GET['plugins'] . '/' . $_GET['plugins'] . '.php'))
			{
				$buffer = "";

				require_once _PLG . $_GET['plugins'] . '/' . $_GET['plugins'] . '.php';
				$class_name = ucwords($_GET['plugins']) . 'Plugins';

				// agar class vujud dasht
				if(class_exists($class_name))
				{
					$class = new $class_name();
					if(method_exists($class , 'read'))
						echo call_user_func_array(array($class , 'read') , array($buffer , 'all'));
				}
			}
		}
	}
}