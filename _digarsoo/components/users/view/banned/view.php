<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/28/2015
	*	last edit		07/28/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class BannedView extends View {
	public function base($params)
	{
		if(Controller::$action == 'default')
		{
			Templates::$title = Language::_('COM_USERS_BANNED');
			self::set_toolbar(array('title' => Templates::$title , 'buttons' => array('delete' , 'edit')));
			self::set_search(array('item' => array('ip' , 'count') , 'sort' => array('ip' , 'count' , 'id')));
		}
		else if(Controller::$action == 'edit')
		{
			Templates::$title = Language::_('COM_USERS_BANNED') . ' - ' . Language::_('EDIT');
			self::set_toolbar(array('title' => Templates::$title , 'buttons' => array('close' , 'saveclose' , 'save')));
		}

		$buttons = 	array(
						array('name' => Language::_('COM_USERS_USER') , 'link' => 'index.php?component=users&amp;view=users') , 
						array('name' => Language::_('COM_USERS_GROUP') , 'link' => 'index.php?component=users&amp;view=group') , 
						array('name' => Language::_('COM_USERS_PERMISSIONS') , 'link' => 'index.php?component=users&amp;view=permissions') , 
						array('name' => Language::_('COM_USERS_BANNED') , 'link' => 'index.php?component=users&amp;view=banned')
					);

		self::set_buttons($buttons , 3);
		self::read_action($params);
	}
}