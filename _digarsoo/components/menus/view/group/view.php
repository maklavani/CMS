<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	07/05/2015
	*	last edit		10/03/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class GroupView extends View {
	public function base($params)
	{
		if(Controller::$action == 'default')
		{
			Templates::$title = Language::_('COM_MENUS_MENU');
			self::set_toolbar(array('title' => Templates::$title , 'buttons' => array('delete' , 'edit' , 'new')));
			self::set_search(array('item' => array('name') , 'sort' => array('name' , 'status' , 'homepage' , 'languages' , 'index_number')));
		}
		else if(Controller::$action == 'new')
		{
			Templates::$title = Language::_('COM_MENUS_MENU') . ' - ' . Language::_('NEW');
			self::set_toolbar(array('title' => Templates::$title , 'buttons' => array('close' , 'saveclose' , 'savenew' , 'save')));
		}
		else if(Controller::$action == 'edit')
		{
			Templates::$title = Language::_('COM_MENUS_MENU') . ' - ' . Language::_('EDIT');
			self::set_toolbar(array('title' => Templates::$title , 'buttons' => array('close' , 'saveclose' , 'save')));
		}

		self::read_action($params);
	}
}