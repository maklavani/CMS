<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	06/15/2015
	*	last edit		12/29/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class ExtensionController extends Controller {
	// khandane view
	public function view($view , $action)
	{
		if($view == 'components')
		{
			if($action == 'default')
			{
				$model = self::model('components');
				$components = json_encode($model->get('components' , 'extension') , JSON_UNESCAPED_UNICODE);
				View::read($components);
			}
			else if($action == 'edit')
			{
				$model = self::model('components');

				if(isset($_GET['id']) && Regex::cs($_GET['id'] , 'number') && $model->has_item($_GET['id'] , 'components'))
				{
					$components = json_encode($model->get_with_id($_GET['id'] , 'components') , JSON_UNESCAPED_UNICODE);
					View::read($components);
				}
				else
					Site::goto_link(Site::$base . _ADM . 'index.php?component=extension&view=components');
			}
		}

		else if($view == 'widgets')
		{
			if($action == 'default')
			{
				$model = self::model('widgets');
				$widgets = json_encode($model->get('widgets' , 'extension' , false , '`location` = "site"') , JSON_UNESCAPED_UNICODE);
				View::read($widgets);
			}
			else if ($action == 'new')
			{
				$model = self::model('widgets');

				if(isset($_GET['widget']) && Regex::cs($_GET['widget'] , 'number') && $model->has_item($_GET['widget'] , 'extension'))
				{
					$widgets = json_encode($model->get_with_id($_GET['widget'] , 'extension') , JSON_UNESCAPED_UNICODE);
					View::read($widgets);
				}
				else
				{
					Controller::$action = 'select';
					View::read();
				}
			}
			else if($action == 'edit')
			{
				$model = self::model('widgets');

				if(isset($_GET['id']) && Regex::cs($_GET['id'] , 'number') && $model->has_item($_GET['id'] , 'widgets'))
				{
					$widgets = $model->get_with_id($_GET['id'] , 'widgets');
					View::read(json_encode($widgets , JSON_UNESCAPED_UNICODE));
				}
				else
					Site::goto_link(Site::$base . _ADM . 'index.php?component=extension&view=widgets');
			}
		}

		else if($view == 'templates')
		{
			if($action == 'default')
			{
				$model = self::model('templates');
				$templates = json_encode($model->get('templates' , 'extension') , JSON_UNESCAPED_UNICODE);
				View::read($templates);
			}
			else if($action == 'edit')
			{
				$model = self::model('templates');

				if(isset($_GET['id']) && Regex::cs($_GET['id'] , 'number') && $model->has_item($_GET['id'] , 'templates'))
				{
					$templates = json_encode($model->get_with_id($_GET['id'] , 'templates') , JSON_UNESCAPED_UNICODE);
					View::read($templates);
				}
				else
					Site::goto_link(Site::$base . _ADM . 'index.php?component=extension&view=templates');
			}
		}
		else if($view == 'languages')
		{
			if($action == 'default')
			{
				$model = self::model('languages');
				$languages = json_encode($model->get('languages' , 'extension') , JSON_UNESCAPED_UNICODE);
				View::read($languages);
			}
			else if($action == 'edit')
			{
				$model = self::model('languages');

				if(isset($_GET['id']) && Regex::cs($_GET['id'] , 'number') && $model->has_item($_GET['id'] , 'languages'))
				{
					$languages = json_encode($model->get_with_id($_GET['id'] , 'languages') , JSON_UNESCAPED_UNICODE);
					View::read($languages);
				}
				else
					Site::goto_link(Site::$base . _ADM . 'index.php?component=extension&view=languages');
			}
		}
		else if($view == 'plugins')
		{
			if($action == 'default')
			{
				$model = self::model('plugins');
				$plugins = json_encode($model->get('plugins' , 'extension') , JSON_UNESCAPED_UNICODE);
				View::read($plugins);
			}
			else if($action == 'edit')
			{
				$model = self::model('plugins');

				if(isset($_GET['id']) && Regex::cs($_GET['id'] , 'number') && $model->has_item($_GET['id'] , 'plugins'))
				{
					$plugins = json_encode($model->get_with_id($_GET['id'] , 'plugins') , JSON_UNESCAPED_UNICODE);
					View::read($plugins);
				}
				else
					Site::goto_link(Site::$base . _ADM . 'index.php?component=extension&view=plugins');
			}
		}

		else if($view == 'setting')
		{
			if($action == 'default')
			{
				$model = self::model('setting');
				$setting = json_encode($model->get('extension' , 'extension') , JSON_UNESCAPED_UNICODE);
				View::read($setting);
			}
			else if ($action == 'install')
				View::read();
		}
	}

	//check kardane form
	public function action($view , $action)
	{
		if($view == 'components')
		{
			if($action == 'default' && isset($_POST['form-button']) && isset($_POST['form-values']))
			{
				$model = self::model('components');

				if($_POST['form-button'] == 'edit' && $model->has_item($_POST['form-values'] , 'components'))
					return Site::$base . _ADM . 'index.php?component=extension&view=components&action=edit&id=' . $_POST['form-values'];
				else if($_POST['form-button'] == 'block' && Regex::cs($_POST['form-values'] , 'status') && $model->has_item($_POST['form-values'] , 'components'))
					$model->set_block($_POST['form-values'] , 'components');
				else if($_POST['form-button'] == 'unblock' && Regex::cs($_POST['form-values'] , 'status') && $model->has_item($_POST['form-values'] , 'components'))
					$model->set_unblock($_POST['form-values'] , 'components');
			}
			else if($action == 'edit' && isset($_POST['form-button']))
			{
				if($_POST['form-button'] == 'close')
					return Site::$base . _ADM . 'index.php?component=extension&view=components';
				else
				{
					$model = self::model('components');
					
					if(isset($_GET['id']) && $model->has_item($_GET['id'] , 'components'))
					{
						// check kardane form
						$result = false;

						require_once _INC . 'output/checks.php';
						$checks = New Checks(file_get_contents(_COMP . 'extension/view/components/edit.json') , 'COM_EXTENSION_COMPONENTS_' , 'field_input_');
						$result += $checks->check();

						if(!$result && $_POST['form-button'] == 'save')
						{
							$model->update_components();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_UPDATE') , Language::_('COM_EXTENSION_COMPONENTS')));
							return Site::$base . _ADM . 'index.php?component=extension&view=components&action=edit&id=' . $_GET['id'];
						}
						else if(!$result && $_POST['form-button'] == 'saveclose')
						{
							$model->update_components();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_UPDATE') , Language::_('COM_EXTENSION_COMPONENTS')));
							return Site::$base . _ADM . 'index.php?component=extension&view=components';
						}
					}
					else
						return Site::$base . _ADM . 'index.php?component=extension&view=components';
				}
			}
		}
		else if($view == 'widgets')
		{
			if($action == 'default' && isset($_POST['form-button']) && isset($_POST['form-values']))
			{
				$model = self::model('widgets');

				if($_POST['form-button'] == 'new')
					return Site::$base . _ADM . 'index.php?component=extension&view=widgets&action=new';
				else if($_POST['form-button'] == 'edit' && $model->has_item($_POST['form-values'] , 'widgets'))
					return Site::$base . _ADM . 'index.php?component=extension&view=widgets&action=edit&id=' . $_POST['form-values'];
				else if($_POST['form-button'] == 'block' && Regex::cs($_POST['form-values'] , 'status') && $model->has_item($_POST['form-values'] , 'widgets'))
					$model->set_block($_POST['form-values'] , 'widgets');
				else if($_POST['form-button'] == 'unblock' && Regex::cs($_POST['form-values'] , 'status') && $model->has_item($_POST['form-values'] , 'widgets'))
					$model->set_unblock($_POST['form-values'] , 'widgets');
				else if($_POST['form-button'] == 'delete' && Regex::cs($_POST['form-values'] , 'status') && $model->has_item($_POST['form-values'] , 'widgets'))
					$model->delete_items($_POST['form-values'] , 'widgets');
			}
			else if($action == 'new' && isset($_POST['form-button']))
			{
				if($_POST['form-button'] == 'close')
					return Site::$base . _ADM . 'index.php?component=extension&view=widgets';
				else
				{
					$model = self::model('widgets');
					$result = false;

					if(isset($_POST['field_input_widget_select']) && Regex::cs($_POST['field_input_widget_select'] , "numeric") && $model->has_item($_POST['field_input_widget_select'] , 'extension'))
						return Site::$base . _ADM . 'index.php?component=extension&view=widgets&action=new&widget=' . $_POST['field_input_widget_select'];
					else
					{
						require_once _INC . 'output/checks.php';
						$checks = New Checks(file_get_contents(_COMP . 'extension/view/widgets/new.json') , 'COM_EXTENSION_WIDGETS_' , 'field_input_');
						$result += $checks->check();

						if(!$result && $_POST['form-button'] == 'save')
						{
							$id = $model->save();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_SAVE') , Language::_('COM_EXTENSION_WIDGETS')));
							return Site::$base . _ADM . 'index.php?component=extension&view=widgets&action=edit&id=' . $id;
						}
						else if(!$result && $_POST['form-button'] == 'savenew')
						{
							$model->save();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_SAVE') , Language::_('COM_EXTENSION_WIDGETS')));
							return Site::$base . _ADM . 'index.php?component=extension&view=widgets&action=new&widget=' . $_GET['widget'];
						}
						else if(!$result && $_POST['form-button'] == 'saveclose')
						{
							$model->save();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_SAVE') , Language::_('COM_EXTENSION_WIDGETS')));
							return Site::$base . _ADM . 'index.php?component=extension&view=widgets';
						}
					}
				}
			}
			else if($action == 'edit' && isset($_POST['form-button']))
			{
				if($_POST['form-button'] == 'close')
					return Site::$base . _ADM . 'index.php?component=extension&view=widgets';
				else
				{
					$model = self::model('widgets');
				
					if(isset($_GET['id']) && $model->has_item($_GET['id'] , 'widgets'))
					{
						$result = false;

						require_once _INC . 'output/checks.php';
						$checks = New Checks(file_get_contents(_COMP . 'extension/view/widgets/edit.json') , 'COM_EXTENSION_WIDGETS_' , 'field_input_');
						$result += $checks->check();

						if(!$result && $_POST['form-button'] == 'save')
						{
							$model->update_widgets();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_UPDATE') , Language::_('COM_EXTENSION_WIDGETS')));
							return Site::$base . _ADM . 'index.php?component=extension&view=widgets&action=edit&id=' . $_GET['id'];
						}
						else if(!$result && $_POST['form-button'] == 'saveclose')
						{
							$model->update_widgets();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_UPDATE') , Language::_('COM_EXTENSION_WIDGETS')));
							return Site::$base . _ADM . 'index.php?component=extension&view=widgets';
						}
					}
					else
						return Site::$base . _ADM . 'index.php?component=extension&view=widgets';
				}
			}
		}
		else if($view == 'templates')
		{
			if($action == 'default' && isset($_POST['form-button']) && isset($_POST['form-values']))
			{
				$model = self::model('templates');

				if($_POST['form-button'] == 'edit' && $model->has_item($_POST['form-values'] , 'templates'))
					return Site::$base . _ADM . 'index.php?component=extension&view=templates&action=edit&id=' . $_POST['form-values'];
				else if($_POST['form-button'] == 'showing' && Regex::cs($_POST['form-values'] , 'number') && $model->has_item($_POST['form-values'] , 'templates'))
				{
					$template = $model->get_with_id($_POST['form-values'] , 'templates');

					if($template[0]->showing)
						Messages::add_message('warning' , Language::_('COM_EXTENSION_ERROR_AT_LEAST_SHOWING'));
					else
						$model->set_showing($_POST['form-values'] , $template[0]->location);
				}
			}
			else if($action == 'edit' && isset($_POST['form-button']))
			{
				if($_POST['form-button'] == 'close')
					return Site::$base . _ADM . 'index.php?component=extension&view=templates';
				else
				{
					$model = self::model('templates');
					
					if(isset($_GET['id']) && $model->has_item($_GET['id'] , 'templates'))
					{
						// check kardane form
						$result = false;

						require_once _INC . 'output/checks.php';
						$checks = New Checks(file_get_contents(_COMP . 'extension/view/templates/edit.json') , 'COM_EXTENSION_TEMPLATES_' , 'field_input_');
						$result += $checks->check();

						if(!$result && $_POST['form-button'] == 'save')
						{
							$model->update_templates();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_UPDATE') , Language::_('COM_EXTENSION_TEMPLATES')));
							return Site::$base . _ADM . 'index.php?component=extension&view=templates&action=edit&id=' . $_GET['id'];
						}
						else if(!$result && $_POST['form-button'] == 'saveclose')
						{
							$model->update_templates();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_UPDATE') , Language::_('COM_EXTENSION_TEMPLATES')));
							return Site::$base . _ADM . 'index.php?component=extension&view=templates';
						}
					}
					else
						return Site::$base . _ADM . 'index.php?component=extension&view=templates';
				}
			}
		}
		else if($view == 'languages')
		{
			if($action == 'default' && isset($_POST['form-button']) && isset($_POST['form-values']))
			{
				$model = self::model('languages');

				if($_POST['form-button'] == 'edit' && $model->has_item($_POST['form-values'] , 'languages'))
					return Site::$base . _ADM . 'index.php?component=extension&view=languages&action=edit&id=' . $_POST['form-values'];
				else if($_POST['form-button'] == 'default_administrator' && Regex::cs($_POST['form-values'] , 'number') && $model->has_item($_POST['form-values'] , 'languages'))
				{
					$template = $model->get_with_id($_POST['form-values'] , 'languages');

					if($template[0]->default_administrator)
						Messages::add_message('warning' , Language::_('COM_EXTENSION_ERROR_AT_LEAST_DEFAULT_ADMNISTARTOR'));
					else
					{
						$model->set_default_administrator($_POST['form-values']);
						return Site::$base . _ADM . 'index.php?component=extension&view=languages';
					}
				}
				else if($_POST['form-button'] == 'default_site' && Regex::cs($_POST['form-values'] , 'number') && $model->has_item($_POST['form-values'] , 'languages'))
				{
					$template = $model->get_with_id($_POST['form-values'] , 'languages');

					if($template[0]->default_site)
						Messages::add_message('warning' , Language::_('COM_EXTENSION_ERROR_AT_LEAST_DEFAULT_ADMNISTARTOR'));
					else
					{
						$model->set_default_site($_POST['form-values']);
						return Site::$base . _ADM . 'index.php?component=extension&view=languages';
					}
				}
			}
			else if($action == 'edit' && isset($_POST['form-button']))
			{
				if($_POST['form-button'] == 'close')
					return Site::$base . _ADM . 'index.php?component=extension&view=languages';
				else
				{
					$model = self::model('languages');
					
					if(isset($_GET['id']) && $model->has_item($_GET['id'] , 'languages'))
					{
						// check kardane form
						$result = false;

						require_once _INC . 'output/checks.php';
						$checks = New Checks(file_get_contents(_COMP . 'extension/view/languages/edit.json') , 'COM_EXTENSION_LANGUAGES_' , 'field_input_');
						$result += $checks->check();

						if(!$result && $_POST['form-button'] == 'save')
						{
							$model->update_languages();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_UPDATE') , Language::_('COM_EXTENSION_LANGUAGES')));
							return Site::$base . _ADM . 'index.php?component=extension&view=languages&action=edit&id=' . $_GET['id'];
						}
						else if(!$result && $_POST['form-button'] == 'saveclose')
						{
							$model->update_languages();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_UPDATE') , Language::_('COM_EXTENSION_LANGUAGES')));
							return Site::$base . _ADM . 'index.php?component=extension&view=languages';
						}
					}
					else
						return Site::$base . _ADM . 'index.php?component=extension&view=languages';
				}
			}
		}
		else if($view == 'plugins')
		{
			if($action == 'default' && isset($_POST['form-button']) && isset($_POST['form-values']))
			{
				$model = self::model('plugins');

				if($_POST['form-button'] == 'edit' && $model->has_item($_POST['form-values'] , 'plugins'))
					return Site::$base . _ADM . 'index.php?component=extension&view=plugins&action=edit&id=' . $_POST['form-values'];
			}
			else if($action == 'edit' && isset($_POST['form-button']))
			{
				if($_POST['form-button'] == 'close')
					return Site::$base . _ADM . 'index.php?component=extension&view=plugins';
				else
				{
					$model = self::model('plugins');
					
					if(isset($_GET['id']) && $model->has_item($_GET['id'] , 'plugins'))
					{
						// check kardane form
						$result = false;

						require_once _INC . 'output/checks.php';
						$checks = New Checks(file_get_contents(_COMP . 'extension/view/plugins/edit.json') , 'COM_EXTENSION_PLUGINS_' , 'field_input_');
						$result += $checks->check();

						if(!$result && $_POST['form-button'] == 'save')
						{
							$model->update_plugins();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_UPDATE') , Language::_('COM_EXTENSION_PLUGINS')));
							return Site::$base . _ADM . 'index.php?component=extension&view=plugins&action=edit&id=' . $_GET['id'];
						}
						else if(!$result && $_POST['form-button'] == 'saveclose')
						{
							$model->update_plugins();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_UPDATE') , Language::_('COM_EXTENSION_PLUGINS')));
							return Site::$base . _ADM . 'index.php?component=extension&view=plugins';
						}
					}
					else
						return Site::$base . _ADM . 'index.php?component=extension&view=plugins';
				}
			}
		}
		else if($view == 'setting')
		{
			if($action == 'default' && isset($_POST['form-button']) && isset($_POST['form-values']))
			{
				$model = self::model('setting');

				if($_POST['form-button'] == 'install')
					return Site::$base . _ADM . 'index.php?component=extension&view=setting&action=install';
				else if($_POST['form-button'] == 'delete' && Regex::cs($_POST['form-values'] , 'status') && $model->has_item($_POST['form-values'] , 'extension'))
					$model->delete_extension($_POST['form-values']);
			}
			else if($action == 'install' && isset($_POST['form-button']))
			{
				if($_POST['form-button'] == 'close')
					return Site::$base . _ADM . 'index.php?component=extension&view=setting';
				else
				{
					$model = self::model('setting');
					$model->install();
				}
			}
		}

		return false;
	}
}