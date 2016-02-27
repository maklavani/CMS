<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	03/28/2015
	*	last edit		01/25/2016
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
		// khandane class database
		require_once _INC . 'database/database.php';

		// khandane class regex
		require_once _INC . 'regex/regex.php';

		// khandane class system
		require_once _INC . 'system/language.php';

		// khandane class site
		require_once _INC . 'information/site.php';

		//set kardane etelaate avaliye
		if(isset($_GET['file']))
		{
			static::$status = 'file';
			static::$link = $_GET['file'];
		}
		else if(isset($_GET['ajax']))
		{
			static::$status = 'ajax';
			static::$link = $_GET['ajax'];
		}
		else if(isset($_GET['checkurl']))
		{
			static::$status = 'checkurl';
			static::$link = $_GET['checkurl'];
		}
		else if(isset($_GET['url']))
		{
			static::$status = 'url';
			static::$link = $_GET['url'];
		}

		$checks = explode('/' , self::$link);

		// baraye peyda kardane zabane digar dar site
		if(Configuration::$languages && strlen($checks[0]) == 2 && Regex::cs($checks[0] , 'text'))
		{
			$db = new Database;
			$db->table('languages')->where('`abbreviation` = "' . $checks[0] . '"')->select()->process();
			$languages = $db->output();

			if($languages)
			{
				Language::$lang = $languages[0]->label;
				Language::add_ini_file(_LANG . Language::$lang . '/' . Language::$lang . '.json');
				$details = json_decode(file_get_contents(_LANG . Language::$lang . '/details.json'));
				Language::$direction = $details->direction;
				Language::$abbreviation = $languages[0]->abbreviation;
				array_shift($checks);
			}
		}

		self::$link = implode('/' , $checks);
		Site::$homepage = Site::has_hompage();

		// set default language setting
		if(!Language::$direction)
		{
			$db = new Database;
			$db->table('languages')->where('`default_' . _LOC . '` = 1')->select()->process();
			$languages = $db->output();

			Language::$lang = $languages[0]->label;
			Language::add_ini_file(_LANG . Language::$lang . '/' . Language::$lang . '.json');
			$details = json_decode(file_get_contents(_LANG . Language::$lang . '/details.json'));
			Language::$direction = $details->direction;
		}

		$has_component = false;

		// agar server busy bud site ra paeen miyavarad be sharti ke tanzimate an faal shode bashad
		if (Configuration::$busy && Site::$sla > 95){
			header('HTTP/1.1 503 Too busy , try again later');
			die('Server too busy :(<br>Please try again later<br><br><a href="http://www.' . _COPS . '">' . _COPR . '</a>');
		} else {
			// khandane file haye moshtarak
			static::read_public_file();

			// khandane file jobs
			require _INC . 'jobs/jobs.php';
			$jobs = new Jobs();
			$jobs->run();

			// Empty Temp
			self::empty_temp();

			if(isset($_GET['file']))
			{
				if($checks[0] == 'deny'){
					// set kardane peyghame error
					self::$error = true;
					Messages::add_message('error' , 'access denied!');
					return false;
				} else {
					// download shodan file
					$file = 'uploads/' . implode("/" , $checks);

					if(System::has_file($file) && is_file(_SRC . $file))
					{
						require_once _INC . 'system/file.php';
						$file = new File($file);

						if($file->is_readable())
							$file->read_file();
						else
						{
							self::$error = true;
							Messages::add_message('error' , 'file not found!');
							return false;
						}
					}
					else
					{
						self::$error = true;
						Messages::add_message('error' , 'file not found!');
						return false;
					}
				}
			}

			else if(isset($_GET['ajax']))
			{
				$component_type = false;

				// halate mamuliye url check mishavad
				if(strpos(self::$link , 'index.php') == 0 && isset($_GET['component']))
					$component_type = $_GET['component'];
				// halate user friendly check mishavad
				else if(strpos(self::$link , 'index.php') === false && isset($_GET['checkurl']))
					$component_type = $_GET['checkurl'];

				// agar esme componenti vared shod va vujud dasht ana ra seda bezan
				if($component_type && $has_component = Components::has_component($component_type))
					Components::process();
			}

			else if(isset($_GET['component']) && $has_component = Components::has_component($_GET['component']))
				Components::process();
			else if(isset($_GET['checkurl']))
			{
				$db = New Database;

				// check alias
				if(isset($checks[0]) && count($checks) == 1 && Regex::cs($checks[0] , "text_utf8"))
				{
					$db->table('menu')->where('`status` = 0 AND `alias` = "' . $checks[0] . '" AND (`languages` = "all" OR `languages` = "' . Language::$lang . '") AND `location` = "' . _LOC . '"')->select()->process();
					$menu = $db->output();

					if($menu)
					{
						// set kardane title
						if($menu[0]->setting != null)
						{
							$setting = json_decode(htmlspecialchars_decode($menu[0]->setting));
							if(!$setting->show_status)
								Templates::$title = Language::_($setting->title);
						}

						Components::proccess_link(html_entity_decode($menu[0]->link));

						if(isset($_GET['component']) && $has_component = Components::has_component($_GET['component']))
						{
							$has_component = true;
							Components::process();
						}
						else if($menu[0]->homepage)
							$has_component = true;

						self::$active_menu_group_homepage = $menu[0]->group;
						self::$active_menu_homepage = $menu[0]->id;
					}
				}
				if(!$has_component && isset($checks[0]) && Regex::cs($checks[0] , "text_utf8"))
				{
					$db->table('components')->where('`status` = 0 AND `location` = "' . _LOC . '"')->select()->process();
					$components = $db->output();

					if(!empty($components))
						foreach ($components as $value)
							if(User::has_permission($value->permission))
							{
								$language = array();

								if(System::has_file('languages/' . Language::$lang . '/com_' . $value->type . '_url.json'))
									foreach (json_decode(file_get_contents(_LANG . Language::$lang . '/com_' . $value->type . '_url.json')) as $keyb => $valueb)
										$language[$keyb] = $valueb;

								if(in_array(str_replace("-" , " " , $checks[0]) , $language) || $checks[0] == $value->type)
								{
									$has_component = true;

									self::$active_menu_group_homepage = -1;
									self::$active_menu_homepage = -1;

									Components::$name = $value->type;
									Components::$permission_id = $value->permission;
									Components::$location = $value->location;
									Components::$setting = json_decode(htmlspecialchars_decode($value->setting));;

									array_shift($checks);
									$view = "check";

									if(!empty($checks))
									{
										$view = array();
										foreach ($checks as $keyc => $valuec)
											if(Regex::cs($valuec , "text_utf8" , ','))
												$view[] = $valuec;
									}

									Components::process($view);
									break;
								}
							}
				}
			}

			// halate mamuliye url check mishavad
			// aya dar safhe asli hastim
			if(!$has_component && Site::$homepage)
			{
				// peyda kardane componente pish farz az menue homepage
				$db = New Database;
				$db->table('menu')->select();

				if(Language::$abbreviation || Configuration::$languages)
					$db->where('`status` = 0 AND `homepage` = 1 AND (`languages` = "' . Language::$lang . '") AND `location` = "' . _LOC . '"')->process();
				else
					$db->where('`status` = 0 AND `homepage` = 1 AND (`languages` = "all" OR `languages` = "' . Language::$lang . '") AND `location` = "' . _LOC . '"')->process();

				$menu = $db->output();
				$has_component = true;
				$menu_active = $menu[0];

				if(count($menu) > 1)
					foreach ($menu as $value)
						if($value->languages != 'all')
						{
							$menu_active = $value;
							break;
						}

				self::$active_menu_group_homepage = $menu_active->group;
				self::$active_menu_homepage = $menu_active->id;

				// set kardane title
				if($menu_active->setting != null)
				{
					$setting = json_decode(htmlspecialchars_decode($menu_active->setting));
					if(!$setting->show_status)
						Templates::$title = Language::_($setting->title);
				}

				Components::proccess_link(html_entity_decode($menu_active->link));

				if(isset($_GET['component']) && $has_component = Components::has_component($_GET['component']))
					Components::process();
			}

			// agar component vujud nadasht error bede
			if(!$has_component)
			{
				// set kardane peyghame error
				self::$error = true;
				Messages::add_message('error' , Language::_('ERROR_INVLAID_LINK'));
			}
		}
	}

	private static function empty_temp()
	{
		$inners = scandir(_SRC . 'temp/');
		foreach ($inners as $value)
			if(!in_array($value , array("." , ".." , "index.html")))
			{
				if(is_dir(_SRC . 'temp/' . $value))
					System::delete_files(array('temp/' . $value . '/'));
				else
					System::delete_files(array('temp/' . $value));
			}
	}
}