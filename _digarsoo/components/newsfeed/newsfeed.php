<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/20/2015
	*	last edit		07/20/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class NewsfeedController extends Controller {
	// khandane view
	public function view($view , $action)
	{
		if($view == 'newsfeed')
		{
			if($action == 'default')
			{
				$model = self::model('newsfeed');
				$newsfeed = json_encode($model->get('newsfeed' , 'newsfeed') , JSON_UNESCAPED_UNICODE);
				View::read($newsfeed);
			}
			else if($action == 'new')
				View::read();
			else if($action == 'edit')
			{
				$model = self::model('newsfeed');

				if(isset($_GET['id']) && Regex::cs($_GET['id'] , 'number') && $model->has_item($_GET['id'] , 'newsfeed'))
				{
					$newsfeed = json_encode($model->get_with_id($_GET['id'] , 'newsfeed') , JSON_UNESCAPED_UNICODE);
					View::read($newsfeed);
				}
				else
					Site::goto_link(Site::$base . _ADM . 'index.php?component=newsfeed&view=newsfeed');
			}
		}
	}

	//check kardane form
	public function action($view , $action)
	{
		if($view == 'newsfeed')
		{
			if($action == 'default' && isset($_POST['form-button']) && isset($_POST['form-values']))
			{
				$model = self::model('newsfeed');

				if($_POST['form-button'] == 'new')
					return Site::$base . _ADM . 'index.php?component=newsfeed&view=newsfeed&action=new';
				else if($_POST['form-button'] == 'edit' && $model->has_item($_POST['form-values'] , 'newsfeed'))
					return Site::$base . _ADM . 'index.php?component=newsfeed&view=newsfeed&action=edit&id=' . $_POST['form-values'];
				else if($_POST['form-button'] == 'block' && Regex::cs($_POST['form-values'] , 'status') && $model->has_item($_POST['form-values'] , 'newsfeed'))
					$model->set_block($_POST['form-values'] , 'newsfeed');
				else if($_POST['form-button'] == 'unblock' && Regex::cs($_POST['form-values'] , 'status') && $model->has_item($_POST['form-values'] , 'newsfeed'))
					$model->set_unblock($_POST['form-values'] , 'newsfeed');
				else if($_POST['form-button'] == 'delete' && Regex::cs($_POST['form-values'] , 'status') && $model->has_item($_POST['form-values'] , 'newsfeed'))
					$model->delete_items($_POST['form-values'] , 'newsfeed');
			}
			else if($action == 'new' && isset($_POST['form-button']))
			{
				if($_POST['form-button'] == 'close')
					return Site::$base . _ADM . 'index.php?component=newsfeed&view=newsfeed';
				else
				{
					$model = self::model('newsfeed');
					
					$result = false;

					require_once _INC . 'output/checks.php';
					$checks = New Checks(file_get_contents(_COMP . 'newsfeed/view/newsfeed/new.json') , 'COM_NEWSFEED_NEWSFEED_' , 'field_input_');
					$result += $checks->check();

					if(!isset($_POST['field_input_category'])){
						Messages::add_message('error' , Language::_('COM_NEWSFEED_ERROR_EMPTY_CATEGORY'));
						$result = true;
					}

					if(!$result)
					{
						$newsfeed = $model->get_with_name($_POST['field_input_name']);
						if($newsfeed)
						{
							Messages::add_message('error' , sprintf(Language::_('COM_NEWSFEED_ERROR_SAME_NAME') , $_POST['field_input_name']));
							$result = true;
						}
					}

					if(!$result && $_POST['form-button'] == 'save')
					{
						$id = $model->save();
						Messages::add_message('success' , sprintf(Language::_('SUCCESS_SAVE') , Language::_('COM_NEWSFEED_NEWSFEED')));
						return Site::$base . _ADM . 'index.php?component=newsfeed&view=newsfeed&action=edit&id=' . $id;
					}
					else if(!$result && $_POST['form-button'] == 'savenew')
					{
						$model->save();
						Messages::add_message('success' , sprintf(Language::_('SUCCESS_SAVE') , Language::_('COM_NEWSFEED_NEWSFEED')));
						return Site::$base . _ADM . 'index.php?component=newsfeed&view=newsfeed&action=new';
					}
					else if(!$result && $_POST['form-button'] == 'saveclose')
					{
						$model->save();
						Messages::add_message('success' , sprintf(Language::_('SUCCESS_SAVE') , Language::_('COM_NEWSFEED_NEWSFEED')));
						return Site::$base . _ADM . 'index.php?component=newsfeed&view=newsfeed';
					}
				}
			}
			else if($action == 'edit' && isset($_POST['form-button']))
			{
				if($_POST['form-button'] == 'close')
					return Site::$base . _ADM . 'index.php?component=newsfeed&view=newsfeed';
				else
				{
					$model = self::model('newsfeed');
					
					if(isset($_GET['id']) && $model->has_item($_GET['id'] , 'newsfeed'))
					{
						// check kardane form
						$result = false;

						require_once _INC . 'output/checks.php';
						$checks = New Checks(file_get_contents(_COMP . 'newsfeed/view/newsfeed/edit.json') , 'COM_NEWSFEED_NEWSFEED_' , 'field_input_');
						$result += $checks->check();

						if(!isset($_POST['field_input_category'])){
							Messages::add_message('error' , Language::_('COM_NEWSFEED_ERROR_EMPTY_CATEGORY'));
							$result = true;
						}

						if(!$result && $_POST['form-button'] == 'save')
						{
							$model->update_newsfeed();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_UPDATE') , Language::_('COM_NEWSFEED_NEWSFEED')));
							return Site::$base . _ADM . 'index.php?component=newsfeed&view=newsfeed&action=edit&id=' . $_GET['id'];
						}
						else if(!$result && $_POST['form-button'] == 'saveclose')
						{
							$model->update_newsfeed();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_UPDATE') , Language::_('COM_NEWSFEED_NEWSFEED')));
							return Site::$base . _ADM . 'index.php?component=newsfeed&view=newsfeed';
						}
						else if(!$result && $_POST['form-button'] == 'savenew')
						{
							$model->update_newsfeed();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_UPDATE') , Language::_('COM_NEWSFEED_NEWSFEED')));
							return Site::$base . _ADM . 'index.php?component=newsfeed&view=newsfeed&action=new';
						}
					}
					else
						return Site::$base . _ADM . 'index.php?component=newsfeed&view=newsfeed';
				}
			}
		}
	}
}