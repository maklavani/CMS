<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	07/06/2015
	*	last edit		08/16/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class WidgetsView extends View {
	public function base($params)
	{
		if(Controller::$action == 'default')
		{
			Templates::$title = Language::_('COM_EXTENSION_WIDGETS');
			self::set_toolbar(array('title' => Templates::$title , 'buttons' => array('unblock' , 'block' , 'delete' , 'edit' , 'new')));
			self::set_search(array('item' => array('name' , 'position') , 'sort' => array('name' , 'status' , 'position' , 'languages' , 'id')));
		}
		else if(Controller::$action == 'new')
		{
			Templates::$title = Language::_('COM_EXTENSION_WIDGETS') . ' - ' . Language::_('NEW');
			self::set_toolbar(array('title' => Templates::$title , 'buttons' => array('close' , 'saveclose' , 'savenew' , 'save')));
		}
		else if(Controller::$action == 'edit')
		{
			Templates::$title = Language::_('COM_EXTENSION_WIDGETS') . ' - ' . Language::_('EDIT');
			self::set_toolbar(array('title' => Templates::$title , 'buttons' => array('close' , 'saveclose' , 'save')));
		}
		else if(Controller::$action == 'select')
		{
			Templates::$title = Language::_('COM_EXTENSION_WIDGETS') . ' - ' . Language::_('SELECT');
			self::set_toolbar(array('title' => Templates::$title , 'buttons' => array('close' , 'save')));
		}

		$buttons = array(
					array('name' => Language::_('COM_EXTENSION_COMPONENTS') , 'link' => 'index.php?component=extension&amp;view=components') ,
					array('name' => Language::_('COM_EXTENSION_WIDGETS') , 'link' => 'index.php?component=extension&amp;view=widgets') ,
					array('name' => Language::_('COM_EXTENSION_TEMPLATES') , 'link' => 'index.php?component=extension&amp;view=templates') , 
					array('name' => Language::_('COM_EXTENSION_LANGUAGES') , 'link' => 'index.php?component=extension&amp;view=languages') , 
					array('name' => Language::_('COM_EXTENSION_PLUGINS') , 'link' => 'index.php?component=extension&amp;view=plugins') , 
					array('name' => Language::_('COM_EXTENSION_SETTING') , 'link' => 'index.php?component=extension&amp;view=setting')
					);

		self::set_buttons($buttons , 1);
		self::read_action($params);
	}
}