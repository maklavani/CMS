<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	08/24/2015
	*	last edit		09/28/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class UserView extends View {
	public function base($params)
	{
		// set kardane title
		if(Controller::$action == 'default')
		{
			// Templates::$title = Language::_('COM_PROFILE_USER');
			// self::set_toolbar(array('title' => Templates::$title , 'buttons' => array('save')));
		}

		// $buttons =	array(
		// 				array('name' => Language::_('COM_PROFILE_USER') , 'link' => 'index.php?component=profile&amp;view=user')
		// 			);

		// self::set_buttons($buttons , 0);
		self::read_action($params);
	}
}