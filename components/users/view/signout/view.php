<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	06/23/2015
	*	last edit		06/23/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class SignoutView extends View {
	public function base($params)
	{
		// set kardane title
		Templates::$title = Language::_('COM_USERS_SIGNOUT');

		self::read_action($params);
	}
}