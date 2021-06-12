<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	08/13/2015
	*	last edit		12/29/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class SettingView extends View {
	public function base($params)
	{
		if(Controller::$action == 'default')
		{
			Templates::$title = Language::_('COM_EXTENSION_SETTING');
			self::set_toolbar(array('title' => Templates::$title , 'buttons' => array('delete' , 'icon-plus' => 'install')));
			self::set_search(array('item' => array('name') , 'sort' => array('name' , 'type' , 'location' , 'id')));
		}
		else if(Controller::$action == 'install')
		{
			Templates::$title = Language::_('COM_EXTENSION_SETTING') . ' - ' . Language::_('INSTALL');
			self::set_toolbar(array('title' => Templates::$title , 'buttons' => array('close' , 'upload')));
		}

		$buttons = array(
					array('name' => Language::_('COM_EXTENSION_COMPONENTS') , 'link' => 'index.php?component=extension&amp;view=components') ,
					array('name' => Language::_('COM_EXTENSION_WIDGETS') , 'link' => 'index.php?component=extension&amp;view=widgets') ,
					array('name' => Language::_('COM_EXTENSION_TEMPLATES') , 'link' => 'index.php?component=extension&amp;view=templates') , 
					array('name' => Language::_('COM_EXTENSION_LANGUAGES') , 'link' => 'index.php?component=extension&amp;view=languages') , 
					array('name' => Language::_('COM_EXTENSION_PLUGINS') , 'link' => 'index.php?component=extension&amp;view=plugins') , 
					array('name' => Language::_('COM_EXTENSION_SETTING') , 'link' => 'index.php?component=extension&amp;view=setting')
					);

		self::set_buttons($buttons , 5);
		self::read_action($params);
	}
}