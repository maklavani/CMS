<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	06/15/2015
	*	last edit		12/14/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class MenusController extends Controller {
	public function ajax()
	{
		if(isset($_GET['type']))
		{
			$model = self::model('menu');

			$value = $list = array();

			$list[] = array('text' => Language::_("COM_MENUS_LINKS") , 'children' => array(array('vals' => 'link' , 'text' => Language::_("COM_MENUS_OUTER_LINK"))));

			$model->get_menu($list);

			$value[] = array('type' => 'listmenu' , 'value' => $list);

			echo json_encode(array('name' => Language::_('SELECT') , 'value' => $value , 'buttons' => array('save' => Language::_('SAVE') , 'cancel' => Language::_('CANCEL'))) , JSON_UNESCAPED_UNICODE);
		}
		else if(isset($_GET['link']))
		{
			$value = array();

			if($_GET['link'] == 'link')
				$value[] = array('type' => 'textmenu' , 'value' => array('text' => Language::_("COM_MENUS_LINK")));
			else if(Regex::cs($_GET['link'] , "text"))
			{
				$model = self::model('menu');

				$links = explode('_' , $_GET['link']);

				if($com = $model->has_component($links[0]))
				{
					require_once _COMP . $links[0] . '/' . $links[0] . '.php';
					$class_name = ucwords(strtolower($com[0]->type)) . 'Controller';

					if(class_exists($class_name))
					{
						$class = new $class_name();

						if(method_exists($class , 'menu'))
						{
							if(System::has_file(_ADM . 'components/' . $com[0]->type . '/details.json'))
							{
								$details = json_decode(file_get_contents(_COMP . $com[0]->type . '/details.json'));

								if(isset($details->language))
									foreach ($details->language as $lang)
										if($lang->name == Language::$lang && System::has_file(_ADM . 'languages/' . $lang->name . '/' . $lang->src))
											Language::add_ini_file(_LANG . $lang->name . '/' . $lang->src);
							}

							$menu = call_user_func_array(array($class , 'menu') , array(str_replace($links[0] . '_' , '' , $_GET['link'])));
							if($menu)
								$value[] = $menu;
						}
					}
				}
			}

			if(!empty($value))
				echo json_encode(array('name' => Language::_('SELECT') , 'value' => $value , 'buttons' => array('save' => Language::_('SAVE') , 'cancel' => Language::_('CANCEL'))) , JSON_UNESCAPED_UNICODE);
		}
	}

	// khandane view
	public function view($view , $action)
	{
		if($view == "groups")
		{
			if($action == "default")
			{
				$model = self::model('groups');
				$menu_group = json_encode($model->get_menu_group('menu_group' , 'menus') , JSON_UNESCAPED_UNICODE);
				View::read($menu_group);
			}
			else if($action == "new")
				View::read();
			else if($action == "edit")
			{
				$model = self::model('groups');

				if(isset($_GET['id']) && Regex::cs($_GET['id'] , 'number') && $model->has_item($_GET['id'] , 'menu_group'))
				{
					$menu_group = json_encode($model->get_with_id($_GET['id'] , 'menu_group') , JSON_UNESCAPED_UNICODE);
					View::read($menu_group);
				}
				else
					Site::goto_link(Site::$base . _ADM . 'index.php?component=menus&view=groups');
			}
		}
		else if($view == 'group')
		{
			if($action == 'default' && isset($_GET['id']) && Regex::cs($_GET['id'] , 'number'))
			{
				$model = self::model('group');
				$menus = json_encode($model->get('menu' , 'menus' , true , '`group` = ' . $_GET['id'] , 'index') , JSON_UNESCAPED_UNICODE);
				View::read($menus);
			}
			else if($action == 'new')
			{
				$model = self::model('group');

				if(isset($_GET['id']) && Regex::cs($_GET['id'] , 'number') && $model->has_item($_GET['id'] , 'menu_group'))
				{
					$menu_group = json_encode($model->get_with_id($_GET['id'] , 'menu_group') , JSON_UNESCAPED_UNICODE);
					View::read($menu_group);
				}
				else
					Site::goto_link(Site::$base . _ADM . 'index.php?component=menus&view=groups');
			}
			else if($action == 'edit')
			{
				$model = self::model('group');

				if(	isset($_GET['id']) && Regex::cs($_GET['id'] , 'number') && $model->has_item($_GET['id'] , 'menu_group') &&
					isset($_GET['menu_id']) && Regex::cs($_GET['menu_id'] , 'number') && $model->has_item($_GET['menu_id'] , 'menu'))
				{
					$menu = json_encode($model->get_with_id($_GET['menu_id'] , 'menu') , JSON_UNESCAPED_UNICODE);
					View::read($menu);
				}
				else
					Site::goto_link(Site::$base . _ADM . 'index.php?component=menus&view=groups');
			}
		}
	}

	//check kardane form
	public function action($view , $action)
	{
		if($view == 'groups')
		{
			if($action == 'default' && isset($_POST['form-button']) && isset($_POST['form-values']))
			{
				$model = self::model('groups');

				if($_POST['form-button'] == 'new')
					return Site::$base . _ADM . 'index.php?component=menus&view=groups&action=new';
				else if($_POST['form-button'] == 'edit' && $model->has_item($_POST['form-values'] , 'menu_group'))
					return Site::$base . _ADM . 'index.php?component=menus&view=groups&action=edit&id=' . $_POST['form-values'];
				else if($_POST['form-button'] == 'delete' && Regex::cs($_POST['form-values'] , 'status') && $model->has_item($_POST['form-values'] , 'menu_group'))
				{
					if($model->has_item($_POST['form-values'] , 'menu' , 'group' , true))
						Messages::add_message('warning' , sprintf(Language::_('WARNING_DELETE') , Language::_('COM_MENUS_GROUPS') , Language::_('COM_MENUS_MENU')));
					else
						$model->delete_items($_POST['form-values'] , 'menu_group');
				}
			}
			else if($action == 'new' && isset($_POST['form-button']))
			{
				if($_POST['form-button'] == 'close')
					return Site::$base . _ADM . 'index.php?component=menus&view=groups';
				else
				{
					$model = self::model('groups');
					
					// check kardane form
					$result = false;

					require_once _INC . 'output/checks.php';
					$checks = New Checks(file_get_contents(_COMP . 'menus/view/groups/new.json') , 'COM_MENUS_GROUPS_' , 'field_input_');
					$result += $checks->check();

					if(!$result && $_POST['form-button'] == 'save')
					{
						$id = $model->save();
						Messages::add_message('success' , sprintf(Language::_('SUCCESS_SAVE') , Language::_('COM_MENUS_GROUPS')));
						return Site::$base . _ADM . 'index.php?component=menus&view=groups&action=edit&id=' . $id;
					}
					else if(!$result && $_POST['form-button'] == 'savenew')
					{
						$model->save();
						Messages::add_message('success' , sprintf(Language::_('SUCCESS_SAVE') , Language::_('COM_MENUS_GROUPS')));
						return Site::$base . _ADM . 'index.php?component=menus&view=groups&action=new';
					}
					else if(!$result && $_POST['form-button'] == 'saveclose')
					{
						$model->save();
						Messages::add_message('success' , sprintf(Language::_('SUCCESS_SAVE') , Language::_('COM_MENUS_GROUPS')));
						return Site::$base . _ADM . 'index.php?component=menus&view=groups';
					}
					else if($_POST['form-button'] == 'close')
						return Site::$base . _ADM . 'index.php?component=menus&view=groups';
				}
			}
			else if($action == 'edit' && isset($_POST['form-button']))
			{
				if($_POST['form-button'] == 'close')
					return Site::$base . _ADM . 'index.php?component=menus&view=groups';
				else
				{
					$model = self::model('groups');
					
					if(isset($_GET['id']) && $model->has_item($_GET['id'] , 'menu_group'))
					{
						// check kardane form
						$result = false;

						require_once _INC . 'output/checks.php';
						$checks = New Checks(file_get_contents(_COMP . 'menus/view/groups/edit.json') , 'COM_MENUS_GROUPS_' , 'field_input_');
						$result += $checks->check();

						if(!$result && $_POST['form-button'] == 'save')
						{
							$model->update_meun_group();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_UPDATE') , Language::_('COM_MENUS_GROUPS')));
							return Site::$base . _ADM . 'index.php?component=menus&view=groups&action=edit&id=' . $_GET['id'];
						}
						else if(!$result && $_POST['form-button'] == 'saveclose')
						{
							$model->update_meun_group();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_UPDATE') , Language::_('COM_MENUS_GROUPS')));
							return Site::$base . _ADM . 'index.php?component=menus&view=groups';
						}
						else if($_POST['form-button'] == 'close')
							return Site::$base . _ADM . 'index.php?component=menus&view=groups';
					}
					else
						return Site::$base . _ADM . 'index.php?component=menus&view=groups';
				}
			}
		}
		else if($view == 'group')
		{
			if($action == 'default' && isset($_POST['form-button']) && isset($_POST['form-values']))
			{
				$model = self::model('group');

				if(isset($_GET['id']) && Regex::cs($_GET['id'] , 'number') && $model->has_item($_GET['id'] , 'menu_group'))
				{
					if($_POST['form-button'] == 'new')
						return Site::$base . _ADM . 'index.php?component=menus&view=group&action=new&id=' . $_GET['id'];
					else if($_POST['form-button'] == 'edit' && $model->has_item($_POST['form-values'] , 'menu'))
						return Site::$base . _ADM . 'index.php?component=menus&view=group&action=edit&id=' . $_GET['id'] . '&menu_id=' . $_POST['form-values'];
					else if($_POST['form-button'] == 'block' && Regex::cs($_POST['form-values'] , 'status') && $model->has_item($_POST['form-values'] , 'menu')
							&& isset($_GET['id']) && Regex::cs($_GET['id'] , 'number') && $model->has_item($_GET['id'] , 'menu_group'))
					{
						$homepage = $model->get_homepage($_GET['id']);
						$ids = explode("," , $_POST['form-values']);

						if(isset($homepage[0]) && in_array($homepage[0]->id , $ids))
							Messages::add_message('warning' , Language::_('COM_MENU_ERROR_CONTAIN_HOMEPAGE'));
						else
							$model->set_block($_POST['form-values'] , 'menu');
					}
					else if($_POST['form-button'] == 'unblock' && Regex::cs($_POST['form-values'] , 'status') && $model->has_item($_POST['form-values'] , 'menu'))
						$model->set_unblock($_POST['form-values'] , 'menu');
					else if($_POST['form-button'] == 'delete' && Regex::cs($_POST['form-values'] , 'status') && $model->has_item($_POST['form-values'] , 'menu'))
					{
						$homepage = $model->get_homepage($_GET['id']);
						$ids = explode("," , $_POST['form-values']);

						if(isset($homepage[0]) && in_array($homepage[0]->id , $ids))
							Messages::add_message('warning' , Language::_('COM_MENU_ERROR_CONTAIN_HOMEPAGE'));
						else
						{
							$model->delete_items($_POST['form-values'] , 'menu');
							$model->set_index($_GET['id']);
						}
					}
					else if($_POST['form-button'] == 'homepage' && Regex::cs($_POST['form-values'] , 'number') && $model->has_item($_POST['form-values'] , 'menu'))
					{
						$homepage = $model->get_homepage($_GET['id']);
						$ids = explode("," , $_POST['form-values']);

						$menu = $model->get_with_id($_POST['form-values'] , 'menu');

						if(isset($homepage[0]) && in_array($homepage[0]->id , $ids) && $homepage[0]->languages == 'all')
							Messages::add_message('warning' , Language::_('COM_MENU_ERROR_AT_LEAST_HOMEPAGE'));
						else if(isset($homepage[0]) && $homepage[0]->languages != $menu[0]->languages)
							Messages::add_message('warning' , Language::_('COM_MENU_ERROR_TWO_HOMEPAGE'));
						else if($menu[0]->homepage == 0)
							$model->homepage($_POST['form-values'] , 1 , $menu[0]->languages , $menu[0]->location);
						else if($menu[0]->homepage == 1)
							$model->homepage($_POST['form-values'] , 0 , $menu[0]->languages , $menu[0]->location);
					}
				}
				else
					return Site::$base . _ADM . 'index.php?component=menus&view=groups';
			}
			else if($action == 'new' && isset($_POST['form-button']))
			{
				$model = self::model('group');

				if(isset($_GET['id']) && Regex::cs($_GET['id'] , 'number') && $model->has_item($_GET['id'] , 'menu_group'))
				{
					if($_POST['form-button'] == 'close')
						return Site::$base . _ADM . 'index.php?component=menus&view=group&id=' . $_GET['id'];
					else
					{
						// check kardane form
						$result = false;

						require_once _INC . 'output/checks.php';
						$checks = New Checks(file_get_contents(_COMP . 'menus/view/group/new.json') , 'COM_MENUS_GROUP_' , 'field_input_');
						$result += $checks->check();

						if(!$result && $_POST['form-button'] == 'save')
						{
							$id = $model->save();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_SAVE') , Language::_('COM_MENUS_GROUP')));
							return Site::$base . _ADM . 'index.php?component=menus&view=group&action=edit&id=' . $_GET['id'] . '&menu_id=' . $id;
						}
						else if(!$result && $_POST['form-button'] == 'savenew')
						{
							$model->save();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_SAVE') , Language::_('COM_MENUS_GROUP')));
							return Site::$base . _ADM . 'index.php?component=menus&view=group&action=new&id=' . $_GET['id'];
						}
						else if(!$result && $_POST['form-button'] == 'saveclose')
						{
							$model->save();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_SAVE') , Language::_('COM_MENUS_GROUP')));
							return Site::$base . _ADM . 'index.php?component=menus&view=group&id=' . $_GET['id'];
						}
					}
				}
				else
					return Site::$base . _ADM . 'index.php?component=menus&view=groups';
			}
			else if($action == 'edit' && isset($_POST['form-button']))
			{
				$model = self::model('group');

				if(	isset($_GET['id']) && Regex::cs($_GET['id'] , 'number') && $model->has_item($_GET['id'] , 'menu_group') && 
					isset($_GET['menu_id']) && Regex::cs($_GET['menu_id'] , 'number') && $model->has_item($_GET['menu_id'] , 'menu'))
				{
					if($_POST['form-button'] == 'close')
						return Site::$base . _ADM . 'index.php?component=menus&view=group&id=' . $_GET['id'];
					else
					{
						// check kardane form
						$result = false;

						$menu = $model->get_with_id($_GET['menu_id'] , 'menu');
						$homepage = $model->get_homepage($_GET['id']);

						if($_POST['field_input_homepage'] == 'show')
							$homepage_val = 1;
						else
							$homepage_val = 0;

						if($menu[0]->homepage != $homepage_val)
							if(isset($homepage[0]) && $homepage[0]->id == $menu[0]->id && $homepage_val == 0 && $menu[0]->languages == 'all')
							{
								Messages::add_message('warning' , Language::_('COM_MENU_ERROR_AT_LEAST_HOMEPAGE'));
								return false;
							}
							else if(isset($homepage[0]) && $homepage_val == 1)
							{
								Messages::add_message('warning' , Language::_('COM_MENU_ERROR_TWO_HOMEPAGE'));
								return false;
							}
							else
								$model->homepage($menu[0]->id , $homepage_val , $menu[0]->languages , $menu[0]->location);

						if($menu[0]->status != $_POST['field_input_status'])
							if($menu[0]->homepage == 1 && $menu[0]->languages == 'all')
							{
								Messages::add_message('warning' , Language::_('COM_MENU_ERROR_AT_LEAST_HOMEPAGE'));
								return false;
							}
							else
							{
								if($_POST['field_input_status'] == 1)
									$model->set_block($menu[0]->id , 'menu');
								else
									$model->set_unblock($menu[0]->id , 'menu');
							}

						if($menu[0]->languages != $_POST['field_input_languages'])
						{
							if($homepage_val == 1 && $model->get_homepage_with_languages($_POST['field_input_languages']))
							{
								Messages::add_message('warning' , Language::_('COM_MENU_ERROR_TWO_HOMEPAGE_LANGUAGE'));
								return false;
							}
						}

						require_once _INC . 'output/checks.php';
						$checks = New Checks(file_get_contents(_COMP . 'menus/view/group/edit.json') , 'COM_MENUS_GROUP_' , 'field_input_');
						$result += $checks->check();

						if(!$result)
						{
							if(($_POST['field_input_alias'] == "" || str_replace(" " , "-" , $_POST['field_input_alias']) != $_POST['field_input_name']) && Configuration::$alias)
								$model->set_alias($_POST['field_input_name'] , $_GET['menu_id'] , $_POST['field_input_languages']);
							else if($menu[0]->alias != $_POST['field_input_alias'] && $_POST['field_input_alias'] != "")
								$model->set_alias($_POST['field_input_alias'] , $_GET['menu_id'] , $_POST['field_input_languages']);
							else if($menu[0]->languages != $_POST['field_input_languages'])
								$model->set_alias($menu[0]->alias , $_GET['menu_id'] , $_POST['field_input_languages']);
						}

						if(!$result && $_POST['form-button'] == 'save')
						{
							$model->update_menu();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_UPDATE') , Language::_('COM_MENUS_GROUP')));
							return Site::$base . _ADM . 'index.php?component=menus&view=group&action=edit&id=' . $_GET['id'] . '&menu_id=' . $_GET['menu_id'];
						}
						else if(!$result && $_POST['form-button'] == 'saveclose')
						{
							$model->update_menu();
							Messages::add_message('success' , sprintf(Language::_('SUCCESS_UPDATE') , Language::_('COM_MENUS_GROUP')));
							return Site::$base . _ADM . 'index.php?component=menus&view=group&id=' . $_GET['id'];
						}
						else if($_POST['form-button'] == 'close')
							return Site::$base . _ADM . 'index.php?component=menus&view=group&id=' . $_GET['id'];
					}
				}
				else
					return Site::$base . _ADM . 'index.php?component=menus&view=groups';
			}
		}

		return false;
	}
}