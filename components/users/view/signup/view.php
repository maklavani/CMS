<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	06/15/2015
	*	last edit		06/15/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class SignupView extends View {
	public function base($params)
	{
		// set kardane title
		Templates::$title = Language::_('COM_USERS_SIGNUP');

		self::read_action($params);
	}
}