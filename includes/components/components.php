<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	03/28/2015
	*	last edit		09/06/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class Components {
	public static $name;
	public static $permission_id;
	public static $location;
	public static $setting;

	public static $output_buffer;

	// aya componenti ba in moshakhasat vujud darad ya na
	public static function has_component($name)
	{
		if(Regex::cs($name , "text"))
		{
			$database = New Database;
			$database->table('components');
			$database->select();
			$database->where('`type` = "' . htmlspecialchars($name) . '" AND `status` = 0 AND `location` = "' . _LOC . '"');
			$database->process();
			$result = $database->output();

			if($database->found == 1 && System::has_file('components/' . $name . '/' . $name . '.php') && User::has_permission($result[0]->permission))
			{

				self::$name = $result[0]->type;
				self::$permission_id = $result[0]->permission;
				self::$location = $result[0]->location;
				self::$setting = json_decode(htmlspecialchars_decode($result[0]->setting));

				return self::$name;
			}
		}

		return false;
	}

	// proccess kardane link
	public static function proccess_link($link)
	{
		// peyda kardane component
		$link = str_replace("index.php?" , "" , $link);
		$gets = explode("&" , $link);

		if(!empty($gets))
			foreach ($gets as $value) {
				$get = explode("=" , $value);
				if(isset($get[0]) && isset($get[1]) && Regex::cs($get[0] , "text") && Regex::cs($get[1] , "text_utf8"))
					$_GET[$get[0]] = $get[1];
			}
	}

	// pardazesh component mad nazar
	public static function process($view = false){
		// khandane class haye bakhsh component
		require_once _INC . 'components/controller.php';
		require_once _INC . 'components/view.php';
		require_once _INC . 'components/model.php';

		if($view)
			Controller::$view = $view;

		Controller::read_component();
		// gereftane buffer khuruji va pak kardane an
		Components::$output_buffer = ob_get_contents();
		ob_clean();
	}
}