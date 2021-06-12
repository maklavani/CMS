<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	06/15/2015
	*	last edit		10/03/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class SigninController extends Controller {
	// khandane view
	public function view($view)
	{
		View::read();
	}

	//check kardane form
	public function action($view , $action)
	{
		$result = false;

		// check kardane form
		require_once _INC . 'output/checks.php';
		$checks = New Checks(file_get_contents(_COMP . 'signin/view/' . $view . '/' . $action . '.json') , 'COM_SIGNIN_' . strtoupper($view) . '_' , 'form_input_');
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
						if($model->has_permission($user[0]->group_number , 3))
						{
							Sessions::delete_session('captcha');
							$remember = isset($_POST['form_input_3']) ? true : false;
							$model->set_sign_in($user[0]->id , $remember);

							return Site::$full_link;
						}
						else
						{
							Messages::add_message('error' , Language::_('COM_SIGNIN_ERROR_USER_HAVENT_PERMISSION'));
							return false;
						}
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
						return Site::$base;
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