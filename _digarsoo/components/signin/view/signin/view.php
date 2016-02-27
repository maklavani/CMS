<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	06/15/2015
	*	last edit		06/15/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class SigninView extends View {
	public function base()
	{
		// set kardane title
		Templates::$title = Language::_('COM_SIGNIN');

		self::read_action();
	}
}