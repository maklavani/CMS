<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	06/15/2015
	*	last edit		01/12/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class Controller {
	public static $component;
	public static $details;
	public static $view;
	public static $action;

	// khandane component
	public static function read_component()
	{
		require_once $file = _COMP . Components::$name . "/" . Components::$name . ".php";
		$class = ucwords(strtolower(Components::$name)) . 'Controller';

		// agar class vujud dasht
		if(class_exists($class))
		{
			// pak kardane buffer
			ob_start();

			self::$component = new $class();
			// khandane deatils.json dar surat vujud dashtan
			if(System::has_file(_ADM . 'components/' . Components::$name . '/details.json'))
			{
				self::$details = json_decode(file_get_contents(_COMP . Components::$name . '/details.json'));

				if(isset(self::$details->language))
					foreach (self::$details->language as $lang)
						if($lang->name == Language::$lang && System::has_file(_ADM . 'languages/' . $lang->name . '/' . $lang->src))
							Language::add_ini_file(_LANG . $lang->name . '/' . $lang->src);
			}
			else
			{
				self::$details = false;
			}

			// set kardane title
			Templates::$title = Language::_('COM_' . strtoupper(Components::$name));

			if(Preload::$status == 'ajax')
			{
				if(method_exists(self::$component , 'ajax') && !Site::has_banned())
					call_user_func_array(array(self::$component , 'ajax') , array(self::$view , self::$action));
				else
					Messages::add_message('error' , Language::_('ERROR_COMPONENT_NOT_FOUND'));
			}
			else
			{
				// aya view E darad
				if(((isset($_POST['form-action']) && $_POST['form-action'] === Site::$full_link) || 
					(isset($_GET['form-action']) && $_GET['form-action'] === Site::$full_link)) && 
					self::check_view() &&
					method_exists(self::$component , 'action') && !Site::has_banned())
				{
						// set kardane action
						self::set_action();

						$address = call_user_func_array(array(self::$component , 'action') , array(self::$view , self::$action));
						// feedback
						if($address)
							Site::goto_link($address);
				}
				// aya view vujud darad
				if(self::check_view())
				{
					self::set_action();

					if(method_exists(self::$component , 'view'))
					{
						call_user_func_array(array(self::$component , 'view') , array(self::$view , self::$action));
					}
					else
					{
						Messages::add_message('error' , Language::_('ERROR_COMPONENT_NOT_FOUND'));
					}
				}
				else
				{
					// set kardane peyghame error
					Messages::add_message('error' , Language::_('ERROR_COMPONENT_NOT_FOUND'));
				}
			}
		}
		else
		{
			self::$component = false;

			// set kardane peyghame error
			Messages::add_message('error' , Language::_('ERROR_COMPONENT_NOT_FOUND'));
		}
	}

	// check kardane view
	public static function check_view()
	{
		if(self::$view)
			return true;
		else if(	isset($_GET['view']) && 
			Regex::cs($_GET['view'] , "text") && 
			System::has_file(_ADM . 'components/' . Components::$name . '/view/' . $_GET['view'] . '/view.php'))
		{
			self::$view = $_GET['view'];
			return true;
		}
		// agar defaulti vujud dasht
		else if(isset(Controller::$details->view->default))
		{
			self::$view = Controller::$details->view->default;
			return true;
		}

		else
		{
			return false;
		}
	}

	// set kardane action
	public static function set_action()
	{
		if(!self::$action)
		{
			if(	isset($_GET['action']) && 
				Regex::cs($_GET['action'] , "text") &&
				System::has_file(_ADM . 'components/' . Components::$name . '/view/' . self::$view . '/' . $_GET['action'] . '.php'))
				self::$action = $_GET['action'];
			else
				self::$action = 'default';
		}
	}

	// sakhtane model
	public static function model($model)
	{
		if(System::has_file(_ADM . 'components/' . Components::$name . '/model/' . $model . '/model.php'))
		{
			require_once _COMP . Components::$name . '/model/' . $model . '/model.php';
			$class = ucwords(strtolower($model)) . 'Model';

			if(class_exists($class))
				return new $class();
		}

		return false;
	}
}