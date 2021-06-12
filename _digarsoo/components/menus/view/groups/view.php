<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	06/29/2015
	*	last edit		06/29/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class GroupsView extends View {
	public function base($params)
	{
		if(Controller::$action == 'default')
		{
			Templates::$title = Language::_('COM_MENUS_GROUPS');
			self::set_toolbar(array('title' => Templates::$title , 'buttons' => array('delete' , 'edit' , 'new')));
		}
		else if(Controller::$action == 'new')
		{
			Templates::$title = Language::_('COM_MENUS_GROUPS') . ' - ' . Language::_('NEW');
			self::set_toolbar(array('title' => Templates::$title , 'buttons' => array('close' , 'saveclose' , 'savenew' , 'save')));
		}
		else if(Controller::$action == 'edit')
		{
			Templates::$title = Language::_('COM_MENUS_GROUPS') . ' - ' . Language::_('EDIT');
			self::set_toolbar(array('title' => Templates::$title , 'buttons' => array('close' , 'saveclose' , 'save')));
		}

		self::read_action($params);
	}
}