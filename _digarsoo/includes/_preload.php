<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	03/28/2015
	*	last edit		01/16/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class Preload {
	public static $status;
	public static $error;
	public static $login;

	public static $active_menu_group_homepage;
	public static $active_menu_homepage;

	public static $link;

	// khandane file haye moshtarak
	private static function read_public_file()
	{
		// agar file cookie khande nashode bud aval an ra mikhanim
		require_once _INC . 'information/cookies.php';

		// khandane session
		require_once _INC . 'information/sessions.php';

		// khandane class message
		require_once _INC . 'output/messages.php';

		// khandane class system
		require_once _INC . 'system/system.php';

		// khandane class database
		require_once _INC . 'database/database.php';

		// khandane class regex
		require_once _INC . 'regex/regex.php';

		// khandane class system
		require_once _INC . 'system/language.php';

		// khandane class site
		require_once _INC . 'information/site.php';

		// khandane user
		require_once _INC . 'information/user.php';

		// barressi login budan user
		User::is_login();

		// khanfane template
		require_once _INC . 'output/templates.php';

		// khandane class components
		require_once _INC . 'components/components.php';
	}

	// check kardane url mad nazar
	public static function check_url()
	{
		// khandane file haye moshtarak
		static::read_public_file();

		// khandane file jobs
		require _INC . 'jobs/jobs.php';
		$jobs = new Jobs();
		$jobs->run();

		// Empty Temp
		self::empty_temp();

		// agar server busy bud site ra paeen miyavarad be sharti ke tanzimate an faal shode bashad
		if (Configuration::$busy && Site::$sla > 95){
			header('HTTP/1.1 503 Too busy , try again later');
			die('Server too busy :(<br>Please try again later<br><br><a href="http://www.' . _COPS . '">' . _COPR . '</a>');
		} else {
			if(User::$login && User::has_permission(3))
			{
				if(isset($_GET['ajax']))
					static::$status = 'ajax';
				else
					static::$status = 'url';

				$has_component = false;

				// halate mamuliye url check mishavad
				if(isset($_GET['component']) && $has_component = Components::has_component($_GET['component']))
					Components::process();
				// aya dar safhe asli hastim
				else if(Site::$homepage)
				{
					// peyda kardane componente pish farz az menue homepage
					$db = New Database;

					$db->table('menu')->select()->where('`status` = 0 AND `homepage` = 1 AND (`languages` = "all" OR `languages` = "' . Language::$lang . '")')->process();
					$menu = $db->output();

					$has_component = true;

					self::$active_menu_group_homepage = $menu[0]->group;
 					self::$active_menu_homepage = $menu[0]->id;

					// set kardane title
					if($menu[0]->setting != null)
					{
						$setting = json_decode(htmlspecialchars_decode($menu[0]->setting));
						if(!$setting->show_status)
							Templates::$title = Language::_($setting->title);
					}

					if($menu)
					{
						// peyda kardane component
						$db->table('components')->select()->where('`type` = "' . $menu[0]->type . '" AND `location` = "' . _LOC . '"')->process();
						$component = $db->output();

						if($db->found == 1 && User::has_permission($component[0]->permission))
						{
							Components::$name = $component[0]->type;
							Components::$permission_id = $component[0]->permission;
							Components::$location = $component[0]->location;

							// proccess compoenent
							Components::process();
						}
					}
				}

				// agar component vujud nadasht error bede
				if(!$has_component) 
				{
					// set kardane peyghame error
					self::$error = true;
					Messages::add_message('error' , Language::_('ERROR_COMPONENT_NOT_FOUND'));
				}
			}
			else
			{
				self::$login = true;

				// peyda kardane component signin
				$db = New Database;
				$db->table('components')->select()->where('`type` = "signin" AND `location` = "' . _LOC . '"')->process();
				$component = $db->output();

				if($db->found == 1)
				{
					Components::$name = $component[0]->type;
					Components::$permission_id = $component[0]->permission;
					Components::$location = $component[0]->location;

					// proccess compoenent
					Components::process();
				}
			}
		}
	}

	private static function empty_temp()
	{
		$inners = scandir(_SRC_SITE . 'temp/');
		foreach ($inners as $value)
			if(!in_array($value , array("." , ".." , "index.html")))
			{
				if(is_dir(_SRC_SITE . 'temp/' . $value))
					System::delete_files(array('temp/' . $value . '/'));
				else
					System::delete_files(array('temp/' . $value));
			}
	}
}