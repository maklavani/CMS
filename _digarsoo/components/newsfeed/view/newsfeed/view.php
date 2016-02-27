<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/20/2015
	*	last edit		01/05/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class NewsfeedView extends View {
	public function base($params)
	{
		if(Controller::$action == 'default')
		{
			Templates::$title = Language::_('COM_NEWSFEED');
			self::set_toolbar(array('title' => Templates::$title , 'buttons' => array('unblock' , 'block' , 'delete' , 'edit' , 'new')));
			self::set_search(array('item' => array('name') , 'sort' => array('name' , 'status' , 'id')));
		}
		else if(Controller::$action == 'new')
		{
			Templates::$title = Language::_('COM_NEWSFEED') . ' - ' . Language::_('NEW');
			self::set_toolbar(array('title' => Templates::$title , 'buttons' => array('close' , 'saveclose' , 'savenew' , 'save')));
		}
		else if(Controller::$action == 'edit')
		{
			Templates::$title = Language::_('COM_NEWSFEED') . ' - ' . Language::_('EDIT');
			self::set_toolbar(array('title' => Templates::$title , 'buttons' => array('close' , 'saveclose' , 'savenew' , 'save')));
		}

		self::read_action($params);
	}
}