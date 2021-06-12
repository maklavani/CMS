<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	05/17/2017
	*	last edit		05/17/2017
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class AuthenticationView extends View {
	public function base($params)
	{
		// set kardane title
		Templates::$title = Language::_('COM_USERS_AUTHENTICATION');

		self::read_action($params);
	}
}