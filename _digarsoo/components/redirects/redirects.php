<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	10/03/2016
	*	last edit		10/03/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class RedirectsController extends Controller {
	// khandane view
	public function view($view , $action)
	{
		if($view == 'redirects')
		{
			if($action == 'default')
			{
				$model = self::model('redirects');
				$redirects = json_encode($model->get('redirects' , 'redirects') , JSON_UNESCAPED_UNICODE);
				View::read($redirects);
			}
			else if($action == 'new')
				View::read();
			else if($action == 'edit')
			{
				$model = self::model('redirects');

				if(isset($_GET['id']) && Regex::cs($_GET['id'] , 'number') && $model->has_item($_GET['id'] , 'redirects'))
				{
					$redirects = json_encode($model->get_with_id($_GET['id'] , 'redirects') , JSON_UNESCAPED_UNICODE);
					View::read($redirects);
				}
				else
					Site::goto_link(Site::$base . _ADM . 'index.php?component=redirects&view=redirects');
			}
		}
	}

	//check kardane form
	public function action($view , $action)
	{
		if($view == 'redirects')
		{
			if($action == 'default' && isset($_POST['form-button']) && isset($_POST['form-values']))
			{
				$model = self::model('redirects');

				if($_POST['form-button'] == 'new')
					return Site::$base . _ADM . 'index.php?component=redirects&view=redirects&action=new';
				else if($_POST['form-button'] == 'edit' && $model->has_item($_POST['form-values'] , 'redirects'))
					return Site::$base . _ADM . 'index.php?component=redirects&view=redirects&action=edit&id=' . $_POST['form-values'];
				else if($_POST['form-button'] == 'block' && Regex::cs($_POST['form-values'] , 'status') && $model->has_item($_POST['form-values'] , 'redirects'))
					$model->set_block($_POST['form-values'] , 'redirects');
				else if($_POST['form-button'] == 'unblock' && Regex::cs($_POST['form-values'] , 'status') && $model->has_item($_POST['form-values'] , 'redirects'))
					$model->set_unblock($_POST['form-values'] , 'redirects');
				else if($_POST['form-button'] == 'delete' && Regex::cs($_POST['form-values'] , 'status') && $model->has_item($_POST['form-values'] , 'redirects'))
					$model->delete_items($_POST['form-values'] , 'redirects');
			}
			else if($action == 'new' && isset($_POST['form-button']))
			{
				if($_POST['form-button'] == 'close')
					return Site::$base . _ADM . 'index.php?component=redirects&view=redirects';
				else
				{
					$model = self::model('redirects');
					
					$result = false;

					require_once _INC . 'output/checks.php';
					$checks = New Checks(file_get_contents(_COMP . 'redirects/view/redirects/new.json') , 'COM_REDIRECTS_REDIRECTS_' , 'field_input_');
					$result += $checks->check();

					if(!$result && $redirects = $model->get_with_url($_POST['field_input_url']))
					{
						Messages::add_message('error' , sprintf(Language::_('COM_REDIRECTS_ERROR_SAME_URL') , $_POST['field_input_name']));
						$result = true;
					}

					if(!$result && $_POST['form-button'] == 'save')
					{
						$id = $model->save();
						Messages::add_message('success' , sprintf(Language::_('SUCCESS_SAVE') , Language::_('COM_REDIRECTS_REDIRECTS')));
						return Site::$base . _ADM . 'index.php?component=redirects&view=redirects&action=edit&id=' . $id;
					}
					else if(!$result && $_POST['form-button'] == 'savenew')
					{
						$model->save();
						Messages::add_message('success' , sprintf(Language::_('SUCCESS_SAVE') , Language::_('COM_REDIRECTS_REDIRECTS')));
						return Site::$base . _ADM . 'index.php?component=redirects&view=redirects&action=new';
					}
					else if(!$result && $_POST['form-button'] == 'saveclose')
					{
						$model->save();
						Messages::add_message('success' , sprintf(Language::_('SUCCESS_SAVE') , Language::_('COM_REDIRECTS_REDIRECTS')));
						return Site::$base . _ADM . 'index.php?component=redirects&view=redirects';
					}
				}
			}
			else if($action == 'edit' && isset($_POST['form-button']))
			{
				if($_POST['form-button'] == 'close')
					return Site::$base . _ADM . 'index.php?component=redirects&view=redirects';
				else
				{
					$model = self::model('redirects');
					
					if(isset($_GET['id']) && $model->has_item($_GET['id'] , 'redirects'))
					{
						// check kardane form
						$result = false;

						require_once _INC . 'output/checks.php';
						$checks = New Checks(file_get_contents(_COMP . 'redirects/view/redirects/edit.json') , 'COM_REDIRECTS_REDIRECTS_' , 'field_input_');
						$result += $checks->check();

						if(!$result && $_POST['form-button'] == 'save')
						{
							$model->update_redirects();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_UPDATE') , Language::_('COM_REDIRECTS_REDIRECTS')));
							return Site::$base . _ADM . 'index.php?component=redirects&view=redirects&action=edit&id=' . $_GET['id'];
						}
						else if(!$result && $_POST['form-button'] == 'saveclose')
						{
							$model->update_redirects();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_UPDATE') , Language::_('COM_REDIRECTS_REDIRECTS')));
							return Site::$base . _ADM . 'index.php?component=redirects&view=redirects';
						}
						else if(!$result && $_POST['form-button'] == 'savenew')
						{
							$model->update_redirects();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_UPDATE') , Language::_('COM_REDIRECTS_REDIRECTS')));
							return Site::$base . _ADM . 'index.php?component=redirects&view=redirects&action=new';
						}
					}
					else
						return Site::$base . _ADM . 'index.php?component=redirects&view=redirects';
				}
			}
		}
	}
}