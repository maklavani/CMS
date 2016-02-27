<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	06/15/2015
	*	last edit		09/09/2015
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
			else if($view_check == Language::_('COM_USERS_IDENTIFICATION'))
			{
				if(isset($view[1]) && strlen($view[1]) == 100)
					$_GET['validity'] = $view[1];

				Controller::$view = $view = 'identification';
			}
		}

		if(in_array($view , array('signup' , 'signin' , 'forget' , 'signout')))
			View::read($params);
		else if($view == 'identification')
		{
			$banned = Site::has_banned();

			if(!$banned)
			{
				if(isset($_GET['validity']) && Regex::cs($_GET['validity'] , "text"))
				{
					$model = self::model('identification');

					if($identification = $model->has_identification($_GET['validity']))
					{
						$params = json_encode($identification[0] , JSON_UNESCAPED_UNICODE);
						$model->delete_identification($identification[0]->id);
						View::read($params);
					}
					else
						Site::insert_banned(Site::$ip , "identification user error");
				}
				else
					Site::insert_banned(Site::$ip , "identification user error");
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
		}

		$result = false;

		// check kardane form
		require_once _INC . 'output/checks.php';
		$checks = New Checks(file_get_contents(_COMP . 'users/view/' . $view . '/' . $action . '.json') , 'COM_USERS_' . strtoupper($view) . '_' , 'form_input_');
		$result += $checks->check();

		if($view == "signin" && $action == 'default' && !$result)
		{
			$model = self::model('signin');
			$user = $model->has_user($_POST['form_input_1']);

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

						return Site::$full_link;
					} else {
						Messages::add_message('error' , Language::_('ERROR_USER_BLOCKED'));
						return false;
					}
				}
			}

			Site::insert_banned(Site::$ip , "user: " . $_POST['form_input_1'] . " , password: " . $_POST['form_input_2']);
			Messages::add_message('error' , Language::_('ERROR_NOT_FOUND_USER'));
		}

		else if($view == "signup" && $action == 'default' &&  !$result)
		{
			$model = self::model('signup');
			$user = $model->has_user('email' , $_POST['form_input_4']);

			// agar karbari nabud - email
			if(!$user)
			{
				$user_b = $model->has_user('username' , $_POST['form_input_3']);
				// agar karbari nabud - username
				if(!$user_b)
				{
					// check kardane password
					if ($_POST['form_input_5'] == $_POST['form_input_6'])
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

		return false;
	}
}