<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	06/15/2015
	*	last edit		10/03/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class UsersController extends Controller {
	// khandane view
	public function view($view , $action)
	{
		if($view == 'users')
		{
			if($action == 'default')
			{
				$model = self::model('users');
				$users = json_encode($model->get('users' , 'users') , JSON_UNESCAPED_UNICODE);
				View::read($users);
			}
			else if ($action == 'new') 
				View::read();
			else if($action == 'edit')
			{
				$model = self::model('users');

				if(isset($_GET['id']) && Regex::cs($_GET['id'] , 'number') && $model->has_item($_GET['id'] , 'users'))
				{
					$user = $model->get_with_id($_GET['id'] , 'users');

					if(($user[0]->group_number <= 6 && User::$group <= $user[0]->group_number) || $user[0]->group_number > 6)
					{
						$users = json_encode($user , JSON_UNESCAPED_UNICODE);
						View::read($users);
					}
					else
					{
						Messages::add_message('error' , sprintf(Language::_('COM_USERS_ERROR_EDIT') , Language::_('COM_USERS_USERS')));
						// Site::goto_link(Site::$base . _ADM . 'index.php?component=users&view=users');
					}
				}
				else
					Site::goto_link(Site::$base . _ADM . 'index.php?component=users&view=users');
			}
		}
		else if($view == 'group')
		{
			if($action == 'default')
			{
				$model = self::model('group');
				$group = json_encode($model->get('group' , 'users' , true) , JSON_UNESCAPED_UNICODE);
				View::read($group);
			}
			else if ($action == 'new') 
				View::read();
			else if($action == 'edit')
			{
				$model = self::model('group');

				if(isset($_GET['id']) && Regex::cs($_GET['id'] , 'number') && $model->has_item($_GET['id'] , 'group'))
				{
					if(($_GET['id'] <= 6 && User::$group <= $_GET['id']) || $_GET['id'] > 6)
					{
						$group = json_encode($model->get_with_id($_GET['id'] , 'group') , JSON_UNESCAPED_UNICODE);
						View::read($group);
					}
					else
					{
						Messages::add_message('error' , sprintf(Language::_('COM_USERS_ERROR_EDIT') , Language::_('COM_USERS_GROUP')));
						Site::goto_link(Site::$base . _ADM . 'index.php?component=users&view=group');
					}
				}
				else
					Site::goto_link(Site::$base . _ADM . 'index.php?component=users&view=group');
			}
		}
		else if($view == 'permissions')
		{
			if($action == 'default')
			{
				$model = self::model('permissions');
				$permissions = json_encode($model->get('permissions' , 'users') , JSON_UNESCAPED_UNICODE);
				View::read($permissions);
			}
			else if ($action == 'new') 
				View::read();
			else if($action == 'edit')
			{
				$model = self::model('permissions');

				if(isset($_GET['id']) && Regex::cs($_GET['id'] , 'number') && $model->has_item($_GET['id'] , 'permissions'))
				{
					if(($_GET['id'] <= 5 && (User::$group - 1) <= $_GET['id']) || $_GET['id'] > 5)
					{
						$permissions = json_encode($model->get_with_id($_GET['id'] , 'permissions') , JSON_UNESCAPED_UNICODE);
						View::read($permissions);
					}
					else
					{
						Messages::add_message('error' , sprintf(Language::_('COM_USERS_ERROR_EDIT') , Language::_('COM_USERS_PERMISSION')));
						Site::goto_link(Site::$base . _ADM . 'index.php?component=users&view=permissions');
					}
				}
				else
					Site::goto_link(Site::$base . _ADM . 'index.php?component=users&view=permissions');
			}
		}
		else if($view == 'banned')
		{
			if($action == 'default')
			{
				$model = self::model('banned');
				$banned = json_encode($model->get('banned' , 'users') , JSON_UNESCAPED_UNICODE);
				View::read($banned);
			}
			else if($action == 'edit')
			{
				$model = self::model('banned');

				if(isset($_GET['id']) && Regex::cs($_GET['id'] , 'number') && $model->has_item($_GET['id'] , 'banned'))
				{
					$banned = json_encode($model->get_with_id($_GET['id'] , 'banned') , JSON_UNESCAPED_UNICODE);
					View::read($banned);
				}
				else
					Site::goto_link(Site::$base . _ADM . 'index.php?component=users&view=banned');
			}
		}
	}

	//check kardane form
	public function action($view , $action)
	{
		if($view == 'users') 
		{
			if($action == 'default' && isset($_POST['form-button']) && isset($_POST['form-values']))
			{
				$model = self::model('users');

				if($_POST['form-button'] == 'new')
					return Site::$base . _ADM . 'index.php?component=users&view=users&action=new';
				else if($_POST['form-button'] == 'edit' && $model->has_item($_POST['form-values'] , 'users'))
				{
					if(($_POST['form-values'] <= 6 && User::$group <= $_POST['form-values']) || $_POST['form-values'] > 6)
						return Site::$base . _ADM . 'index.php?component=users&view=users&action=edit&id=' . $_POST['form-values'];
					else
						Messages::add_message('error' , sprintf(Language::_('COM_USERS_ERROR_EDIT') , Language::_('COM_USERS_USERS')));
				}
				else if($_POST['form-button'] == 'block' && Regex::cs($_POST['form-values'] , 'status') && $model->has_item($_POST['form-values'] , 'users'))
				{
					$result = false;

					foreach (explode(',' , $_POST['form-values']) as $value) {
						$user = $model->get_with_id($value , 'users');

						if(($user[0]->group_number <= 6 && User::$group > $user[0]->group_number) || $user[0]->id == User::$id)
							$result = true;
					}

					if(!$result)
						$model->set_block($_POST['form-values'] , 'users');
					else
						Messages::add_message('error' , sprintf(Language::_('COM_USERS_ERROR_EDIT_BLOCK') , Language::_('COM_USERS_USERS')));
				}
				else if($_POST['form-button'] == 'unblock' && Regex::cs($_POST['form-values'] , 'status') && $model->has_item($_POST['form-values'] , 'users'))
				{
					$result = false;

					foreach (explode(',' , $_POST['form-values']) as $value) {
						$user = $model->get_with_id($value , 'users');

						if(($user[0]->group_number <= 6 && User::$group > $user[0]->group_number) || $user[0]->id == User::$id)
							$result = true;
					}

					if(!$result)
						$model->set_unblock($_POST['form-values'] , 'users');
					else
						Messages::add_message('error' , sprintf(Language::_('COM_USERS_ERROR_EDIT_UNBLOCK') , Language::_('COM_USERS_USERS')));
				}
				else if($_POST['form-button'] == 'delete' && Regex::cs($_POST['form-values'] , 'status') && $model->has_item($_POST['form-values'] , 'users'))
				{
					$result = false;

					foreach (explode(',' , $_POST['form-values']) as $value) {
						$user = $model->get_with_id($value , 'users');

						if(($user[0]->group_number <= 6 && User::$group > $user[0]->group_number) || $user[0]->id == User::$id)
							$result = true;
					}

					if(!$result)
					{
						$model->delete_source_image($_POST['form-values']);
						$model->delete_items($_POST['form-values'] , 'users');
					}
					else
						Messages::add_message('error' , sprintf(Language::_('COM_USERS_ERROR_EDIT_DELETE') , Language::_('COM_USERS_USERS')));
				}
			}
			else if($action == 'new' && isset($_POST['form-button']))
			{
				if($_POST['form-button'] == 'close')
					return Site::$base . _ADM . 'index.php?component=users&view=users';
				else
				{
					$model = self::model('users');	
					$result = false;

					if($model->get_with_field('username' , $_POST['field_input_username']))
					{
						Messages::add_message('error' , Language::_('COM_USERS_ERROR_EXIST_USERSNAME'));
						return false;
					}

					if($model->get_with_field('email' , $_POST['field_input_email']))
					{
						Messages::add_message('error' , Language::_('COM_USERS_ERROR_EXIST_EMAIL'));
						return false;
					}

					if($_POST['field_input_password'] != $_POST['field_input_repassword'])
					{
						Messages::add_message('error' , Language::_('COM_USERS_ERROR_PASSWORD_DOES_NOT_MATCH'));
						return false;
					}

					if($_POST['field_input_group'] <= 6 && User::$group > $_POST['field_input_group'])
					{
						Messages::add_message('error' , Language::_('COM_USERS_ERROR_VALID_GROUP'));
						return false;
					}

					require_once _INC . 'output/checks.php';
					$checks = New Checks(file_get_contents(_COMP . 'users/view/users/new.json') , 'COM_USERS_USERS_' , 'field_input_');
					$result += $checks->check();

					if(!$result && $_POST['form-button'] == 'save')
					{
						$id = $model->save();
						Messages::add_message('success' , sprintf(Language::_('SUCCESS_SAVE') , Language::_('COM_USERS_USERS')));
						return Site::$base . _ADM . 'index.php?component=users&view=users&action=edit&id=' . $id;
					}
					else if(!$result && $_POST['form-button'] == 'savenew')
					{
						$model->save();
						Messages::add_message('success' , sprintf(Language::_('SUCCESS_SAVE') , Language::_('COM_USERS_USERS')));
						return Site::$base . _ADM . 'index.php?component=users&view=users&action=new';
					}
					else if(!$result && $_POST['form-button'] == 'saveclose')
					{
						$model->save();
						Messages::add_message('success' , sprintf(Language::_('SUCCESS_SAVE') , Language::_('COM_USERS_USERS')));
						return Site::$base . _ADM . 'index.php?component=users&view=users';
					}
				}
			}
			else if($action == 'edit' && isset($_POST['form-button']))
			{
				if($_POST['form-button'] == 'close')
					return Site::$base . _ADM . 'index.php?component=users&view=users';
				else
				{
					$model = self::model('users');
				
					if(isset($_GET['id']) && $model->has_item($_GET['id'] , 'users'))
					{
						$result = false;

						$user = $model->get_with_id($_GET['id'] , 'users');

						if($user[0]->username != $_POST['field_input_username'] && $model->get_with_field('username' , $_POST['field_input_username']))
						{
							Messages::add_message('error' , Language::_('COM_USERS_ERROR_EXIST_USERSNAME'));
							return false;
						}

						if($user[0]->email != $_POST['field_input_email'] && $model->get_with_field('email' , $_POST['field_input_email']))
						{
							Messages::add_message('error' , Language::_('COM_USERS_ERROR_EXIST_EMAIL'));
							return false;
						}

						if($_POST['field_input_password'] != $_POST['field_input_repassword'])
						{
							Messages::add_message('error' , Language::_('COM_USERS_ERROR_PASSWORD_DOES_NOT_MATCH'));
							return false;
						}

						if($_POST['field_input_group'] <= 6 && User::$group > $_POST['field_input_group'])
						{
							Messages::add_message('error' , Language::_('COM_USERS_ERROR_VALID_GROUP'));
							return false;
						}

						require_once _INC . 'output/checks.php';
						$checks = New Checks(file_get_contents(_COMP . 'users/view/users/edit.json') , 'COM_USERS_USERS_' , 'field_input_');
						$result += $checks->check();

						if(!$result && $_POST['form-button'] == 'save')
						{
							$model->update_users();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_UPDATE') , Language::_('COM_USERS_USERS')));
							return Site::$base . _ADM . 'index.php?component=users&view=users&action=edit&id=' . $_GET['id'];
						}
						else if(!$result && $_POST['form-button'] == 'saveclose')
						{
							$model->update_users();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_UPDATE') , Language::_('COM_USERS_USERS')));
							return Site::$base . _ADM . 'index.php?component=users&view=users';
						}
					}
					else
						return Site::$base . _ADM . 'index.php?component=users&view=users';
				}
			}
		}
		else if($view == 'group')
		{
			if($action == 'default' && isset($_POST['form-button']) && isset($_POST['form-values']))
			{
				$model = self::model('group');

				if($_POST['form-button'] == 'new')
					return Site::$base . _ADM . 'index.php?component=users&view=group&action=new';
				else if($_POST['form-button'] == 'edit' && $model->has_item($_POST['form-values'] , 'group'))
				{
					if(($_POST['form-values'] <= 6 && User::$group <= $_POST['form-values']) || $_POST['form-values'] > 6)
						return Site::$base . _ADM . 'index.php?component=users&view=group&action=edit&id=' . $_POST['form-values'];
					else
						Messages::add_message('error' , sprintf(Language::_('COM_USERS_ERROR_EDIT') , Language::_('COM_USERS_GROUP')));
				}
				else if($_POST['form-button'] == 'delete' && Regex::cs($_POST['form-values'] , 'status') && $model->has_item($_POST['form-values'] , 'group'))
				{
					$result = false;
					$ids = $model->get_lock_group_id();
					$ids_check = explode("," , $_POST['form-values']);

					foreach ($ids_check as $value)
						if(isset($ids[$value]))
							$result = true;

					if($result)
					{
						Messages::add_message('error' , sprintf(Language::_('COM_USERS_ERROR_LOCK') , Language::_('COM_USERS_GROUP')));
						return false;
					}
					else
						$model->delete_items($_POST['form-values'] , 'group');
				}
			}
			else if($action == 'new' && isset($_POST['form-button']))
			{
				if($_POST['form-button'] == 'close')
					return Site::$base . _ADM . 'index.php?component=users&view=group';
				else
				{
					$model = self::model('group');					
					$result = false;

					require_once _INC . 'output/checks.php';
					$checks = New Checks(file_get_contents(_COMP . 'users/view/group/new.json') , 'COM_USERS_GROUP_' , 'field_input_');
					$result += $checks->check();

					if(!$result && $_POST['form-button'] == 'save')
					{
						$id = $model->save();
						Messages::add_message('success' , sprintf(Language::_('SUCCESS_SAVE') , Language::_('COM_USERS_GROUP')));
						return Site::$base . _ADM . 'index.php?component=users&view=group&action=edit&id=' . $id;
					}
					else if(!$result && $_POST['form-button'] == 'savenew')
					{
						$model->save();
						Messages::add_message('success' , sprintf(Language::_('SUCCESS_SAVE') , Language::_('COM_USERS_GROUP')));
						return Site::$base . _ADM . 'index.php?component=users&view=group&action=new';
					}
					else if(!$result && $_POST['form-button'] == 'saveclose')
					{
						$model->save();
						Messages::add_message('success' , sprintf(Language::_('SUCCESS_SAVE') , Language::_('COM_USERS_GROUP')));
						return Site::$base . _ADM . 'index.php?component=users&view=group';
					}
				}
			}
			else if($action == 'edit' && isset($_POST['form-button']))
			{
				if($_POST['form-button'] == 'close')
					return Site::$base . _ADM . 'index.php?component=users&view=group';
				else
				{
					$model = self::model('group');

					if(isset($_GET['id']) && $model->has_item($_GET['id'] , 'group'))
					{
						// check kardane form
						$result = false;

						require_once _INC . 'output/checks.php';
						$checks = New Checks(file_get_contents(_COMP . 'users/view/group/edit.json') , 'COM_USERS_GROUP_' , 'field_input_');
						$result += $checks->check();

						if(!$result && $_POST['form-button'] == 'save')
						{
							$model->update_permission();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_UPDATE') , Language::_('COM_USERS_GROUP')));
							return Site::$base . _ADM . 'index.php?component=users&view=group&action=edit&id=' . $_GET['id'];
						}
						else if(!$result && $_POST['form-button'] == 'saveclose')
						{
							$model->update_permission();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_UPDATE') , Language::_('COM_USERS_GROUP')));
							return Site::$base . _ADM . 'index.php?component=users&view=group';
						}
					}
					else
						return Site::$base . _ADM . 'index.php?component=users&view=group';
				}
			}
		}
		else if($view == 'permissions')
		{
			if($action == 'default' && isset($_POST['form-button']) && isset($_POST['form-values']))
			{
				$model = self::model('permissions');

				if($_POST['form-button'] == 'new')
					return Site::$base . _ADM . 'index.php?component=users&view=permissions&action=new';
				else if($_POST['form-button'] == 'edit' && $model->has_item($_POST['form-values'] , 'permissions'))
				{
					if(($_POST['form-values'] <= 6 && (User::$group - 1) <= $_POST['form-values']) || $_POST['form-values'] > 6)
						return Site::$base . _ADM . 'index.php?component=users&view=permissions&action=edit&id=' . $_POST['form-values'];
					else
						Messages::add_message('error' , sprintf(Language::_('COM_USERS_ERROR_EDIT') , Language::_('COM_USERS_PERMISSION')));
				}
				else if($_POST['form-button'] == 'delete' && Regex::cs($_POST['form-values'] , 'status') && $model->has_item($_POST['form-values'] , 'permissions'))
				{
					$result = false;
					$ids = $model->get_lock_permissions_id();
					$ids_check = explode("," , $_POST['form-values']);

					foreach ($ids_check as $value)
						if(isset($ids[$value]))
							$result = true;

					if($result)
					{
						Messages::add_message('error' , sprintf(Language::_('COM_USERS_ERROR_LOCK') , Language::_('COM_USERS_PERMISSION')));
						return false;
					}
					else
						$model->delete_items($_POST['form-values'] , 'permissions');
				}
			}
			else if($action == 'new' && isset($_POST['form-button']))
			{
				if($_POST['form-button'] == 'close')
					return Site::$base . _ADM . 'index.php?component=users&view=permissions';
				else
				{
					$model = self::model('permissions');					
					$result = false;

					require_once _INC . 'output/checks.php';
					$checks = New Checks(file_get_contents(_COMP . 'users/view/permissions/new.json') , 'COM_USERS_PERMISSIONS_' , 'field_input_');
					$result += $checks->check();

					if(!$result && $_POST['form-button'] == 'save')
					{
						$id = $model->save();
						Messages::add_message('success' , sprintf(Language::_('SUCCESS_SAVE') , Language::_('COM_USERS_PERMISSIONS')));
						return Site::$base . _ADM . 'index.php?component=users&view=permissions&action=edit&id=' . $id;
					}
					else if(!$result && $_POST['form-button'] == 'savenew')
					{
						$model->save();
						Messages::add_message('success' , sprintf(Language::_('SUCCESS_SAVE') , Language::_('COM_USERS_PERMISSIONS')));
						return Site::$base . _ADM . 'index.php?component=users&view=permissions&action=new';
					}
					else if(!$result && $_POST['form-button'] == 'saveclose')
					{
						$model->save();
						Messages::add_message('success' , sprintf(Language::_('SUCCESS_SAVE') , Language::_('COM_USERS_PERMISSIONS')));
						return Site::$base . _ADM . 'index.php?component=users&view=permissions';
					}
				}
			}
			else if($action == 'edit' && isset($_POST['form-button']))
			{
				if($_POST['form-button'] == 'close')
					return Site::$base . _ADM . 'index.php?component=users&view=permissions';
				else
				{
					$model = self::model('permissions');

					if(isset($_GET['id']) && $model->has_item($_GET['id'] , 'permissions'))
					{
						// check kardane form
						$result = false;

						require_once _INC . 'output/checks.php';
						$checks = New Checks(file_get_contents(_COMP . 'users/view/permissions/edit.json') , 'COM_USERS_PERMISSIONS_' , 'field_input_');
						$result += $checks->check();

						if(!$result && $_POST['form-button'] == 'save')
						{
							$model->update_permission();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_UPDATE') , Language::_('COM_USERS_PERMISSIONS')));
							return Site::$base . _ADM . 'index.php?component=users&view=permissions&action=edit&id=' . $_GET['id'];
						}
						else if(!$result && $_POST['form-button'] == 'saveclose')
						{
							$model->update_permission();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_UPDATE') , Language::_('COM_USERS_PERMISSIONS')));
							return Site::$base . _ADM . 'index.php?component=users&view=permissions';
						}
					}
					else
						return Site::$base . _ADM . 'index.php?component=users&view=permissions';
				}
			}
		}
		else if($view == 'banned')
		{
			if($action == 'default' && isset($_POST['form-button']) && isset($_POST['form-values']))
			{
				$model = self::model('banned');

				if($_POST['form-button'] == 'edit' && $model->has_item($_POST['form-values'] , 'banned'))
					return Site::$base . _ADM . 'index.php?component=users&view=banneds&action=edit&id=' . $_POST['form-values'];
				else if($_POST['form-button'] == 'delete' && Regex::cs($_POST['form-values'] , 'status') && $model->has_item($_POST['form-values'] , 'banned'))
					$model->delete_items($_POST['form-values'] , 'banned');
			}
			else if($action == 'edit' && isset($_POST['form-button']))
			{
				if($_POST['form-button'] == 'close')
					return Site::$base . _ADM . 'index.php?component=users&view=banned';
				else
				{
					$model = self::model('banned');

					if(isset($_GET['id']) && $model->has_item($_GET['id'] , 'banned'))
					{
						// check kardane form
						$result = false;

						require_once _INC . 'output/checks.php';
						$checks = New Checks(file_get_contents(_COMP . 'users/view/banned/edit.json') , 'COM_USERS_BANNED_' , 'field_input_');
						$result += $checks->check();

						if(!$result && $_POST['form-button'] == 'save')
						{
							$model->update_banned();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_UPDATE') , Language::_('COM_USERS_BANNED')));
							return Site::$base . _ADM . 'index.php?component=users&view=banned&action=edit&id=' . $_GET['id'];
						}
						else if(!$result && $_POST['form-button'] == 'saveclose')
						{
							$model->update_banned();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_UPDATE') , Language::_('COM_USERS_BANNED')));
							return Site::$base . _ADM . 'index.php?component=users&view=banned';
						}
					}
					else
						return Site::$base . _ADM . 'index.php?component=users&view=banned';
				}
			}
		}
	}
}