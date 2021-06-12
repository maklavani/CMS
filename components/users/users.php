<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	06/15/2015
	*	last edit		05/17/2017
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class UsersController extends Controller {
	// khandane view
	public function view($view , $action)
	{
		$params = "";

		if(is_array($view) && !empty($view))
		{
			$view_check = str_replace("-" , " " , $view[0]);

			if($view_check == Language::_('COM_USERS_SIGNUP'))
				Controller::$view = $view = 'signup';
			else if($view_check == Language::_('COM_USERS_SIGNIN'))
				Controller::$view = $view = 'signin';
			else if($view_check == Language::_('COM_USERS_FORGET'))
				Controller::$view = $view = 'forget';
			else if($view_check == Language::_('COM_USERS_SIGNOUT'))
				Controller::$view = $view = 'signout';
			else if($view_check == Language::_('COM_USERS_IDENTIFICATION') && isset($view[1]) && Regex::cs($view[1] , "ansi") && strlen($view[1]) == 100)
			{
				$_GET['validity'] = $view[1];
				Controller::$view = $view = 'identification';
			}
			else if($view_check == Language::_('COM_USERS_AUTHENTICATION'))
				Controller::$view = $view = 'authentication';
		}

		if(in_array($view , array('signup' , 'signin' , 'forget' , 'signout' , 'authentication')))
			View::read($params);
		else if($view == 'identification')
		{
			$banned = Site::has_banned();

			if(!$banned)
			{
				if(isset($_GET['validity']) && Regex::cs($_GET['validity'] , "ansi"))
				{
					$model = self::model('identification');

					if($identification = $model->has_identification($_GET['validity']))
						View::read(json_encode($identification[0] , JSON_UNESCAPED_UNICODE));
					else
						Site::insert_banned(Site::$ip , Language::_("COM_USERS_ERROR_IDENTIFICATION"));
				}
				else
					Site::insert_banned(Site::$ip , Language::_("COM_USERS_ERROR_IDENTIFICATION"));
			}
			else
			{
				if($banned > 10)
					$time = 3600 - Site::$banned_time;
				else
					$time = 900 - Site::$banned_time;

				Messages::add_message('error' , sprintf(Language::_('COM_USERS_ERROR_BANNED_IP') , (int)($time / 60) . ":" . ($time % 60)));
			}
		}
		else
		{
			Preload::$error = true;
			Messages::add_message('error' , Language::_('ERROR_INVLAID_LINK'));
		}
	}

	//check kardane form
	public function action($view , $action)
	{
		if(is_array($view) && !empty($view))
		{
			if(str_replace("-" , " " , $view[0]) == Language::_('COM_USERS_SIGNUP'))
				Controller::$view = $view = 'signup';
			else if(str_replace("-" , " " , $view[0]) == Language::_('COM_USERS_SIGNIN'))
				Controller::$view = $view = 'signin';
			else if(str_replace("-" , " " , $view[0]) == Language::_('COM_USERS_FORGET'))
				Controller::$view = $view = 'forget';
			else if(str_replace("-" , " " , $view[0]) == Language::_('COM_USERS_IDENTIFICATION') && isset($view[1]) && Regex::cs($view[1] , "ansi") && strlen($view[1]) == 100)
			{
				$_GET['validity'] = $view[1];
				Controller::$view = $view = 'identification';
			}
			else if(str_replace("-" , " " , $view[0]) == Language::_('COM_USERS_AUTHENTICATION'))
				Controller::$view = $view = 'authentication';
		}

		$result = false;
		if(is_array($view))
			return false;

		if($view == "signin" && $action == 'default')
		{
			$model = self::model('signin');
			$user = $model->has_user($_POST['form_input_1']);

			require_once _INC . 'output/checks.php';
			$checks = New Checks(file_get_contents(_COMP . 'users/view/' . $view . '/' . $action . '.json') , 'COM_USERS_' . strtoupper($view) . '_' , 'form_input_');
			$result += $checks->check();

			if(!$result)
			{
				if(!empty($user))
				{
					// khandane file hash baraye check kardane password
					require_once _INC . 'regex/hash.php';

					if(Hash::validate('sha512' , $_POST['form_input_2'] , 1000 + $user[0]->id , $user[0]->password))
					{
						if(!$user[0]->status)
						{
							Sessions::delete_session('captcha');
							$remember = isset($_POST['form_input_3']) ? true : false;
							$model->set_sign_in($user[0]->id , $remember);

							return Site::$base . Language::_('COM_USERS_PROFILE') . "/" . str_replace(" " , "-" , Language::_('COM_USERS_PROFILE_ADVERTISEMENT'));
						} else {
							Messages::add_message('error' , Language::_('ERROR_USER_BLOCKED'));
							return false;
						}
					}
				}

				Site::insert_banned(Site::$ip , "user: " . $_POST['form_input_1'] . " , password: " . $_POST['form_input_2']);
				Messages::add_message('error' , Language::_('ERROR_NOT_FOUND_USER'));
			}
		}

		else if($view == "signup" && $action == 'default')
		{
			$model = self::model('signup');
			$user = $model->has_user('email' , $_POST['form_input_4']);

			require_once _INC . 'output/checks.php';
			$checks = New Checks(file_get_contents(_COMP . 'users/view/' . $view . '/' . $action . '.json') , 'COM_USERS_' . strtoupper($view) . '_' , 'form_input_');
			$result += $checks->check();

			if(!$result)
			{
				// agar karbari nabud - email
				if(!$user)
				{
					$user_b = $model->has_user('username' , $_POST['form_input_3']);
					// agar karbari nabud - username
					if(!$user_b)
					{
						// check kardane password
						if ($_POST['form_input_6'] == $_POST['form_input_7'])
						{
							$model->save();

							Messages::add_message('success' , Language::_('COM_USERS_SUCCESS'));
							return Site::$base . Language::$abbreviation;
						}
						else
							Messages::add_message('error' , Language::_('COM_USERS_ERROR_PASSWORD_DOES_NOT_MATCH'));
					}
					else
						Messages::add_message('error' , Language::_('COM_USERS_ERROR_EXIST_USERSNAME'));
				}
				else
					Messages::add_message('error' , Language::_('COM_USERS_ERROR_EXIST_EMAIL'));
			}
		}

		else if($view == "forget" && $action == 'default')
		{
			$model = self::model('forget');

			require_once _INC . 'output/checks.php';
			$checks = New Checks(file_get_contents(_COMP . 'users/view/' . $view . '/' . $action . '.json') , 'COM_USERS_' . strtoupper($view) . '_' , 'form_input_');
			$result += $checks->check();

			if(!$result)
			{
				if($user = $model->get_user_with_email($_POST['form_input_1']))
					$model->send($user[0]);

				Messages::add_message('success' , Language::_('COM_USERS_FORGET_SEND'));
				return Site::$base;
			}
		}

		else if($view == "identification" && $action == 'default')
		{
			if(isset($_POST["form_input_4"]) && $_POST['form_input_4'] == "forget")
			{
				$model = self::model('identification');

				require_once _INC . 'output/checks.php';
				$checks = New Checks(file_get_contents(_COMP . 'users/view/identification/forget.json') , 'COM_USERS_' . strtoupper($view) . '_' , 'form_input_');
				$result += $checks->check();

				if($_POST['form_input_1'] != $_POST['form_input_2'])
				{
					Messages::add_message('error' , Language::_('COM_USERS_ERROR_PASSWORD_DOES_NOT_MATCH'));
					return false;
				}

				if(!$result && $identification = $model->has_identification($_GET['validity']))
				{
					$model->change_password($identification[0] , $_POST['form_input_1']);
					Messages::add_message('success' , Language::_('COM_USERS_MESSAGE_PASSWORD_CHANGED'));
					return Site::$base . Language::_('COM_USERS') . "/" . str_replace(" " , "-" , Language::_('COM_USERS_SIGNIN'));
				}
			}
		}

		else if($view == "authentication" && $action == 'default' && User::$id != -1)
		{
			$model = self::model('authentication');

			require_once _INC . 'output/checks.php';
			$checks = New Checks(file_get_contents(_COMP . 'users/view/authentication/default.json') , 'COM_USERS_' . strtoupper($view) . '_' , 'form_input_');
			$result += $checks->check();

			if(!$model->has_user('email' , $_POST['form_input_1']))
			{
				if(!$result)
				{
					$model->change_email();
					Messages::add_message('success' , Language::_('COM_USERS_SUCCESS_AUTHENTICATION'));
					return Site::$base;
				}
			}
			else
				Messages::add_message('error' , Language::_('COM_USERS_ERROR_EXIST_EMAIL'));
		}

		return false;
	}
}