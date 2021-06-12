<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	07/28/2015
	*	last edit		07/28/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class NewsfeedView extends View {
	public function base($params)
	{
		// set kardane title
		Templates::$title = Language::_('COM_NEWSFEED_NEWSFEED');

		self::read_action($params);
	}
}