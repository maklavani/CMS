<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	09/02/2015
	*	last edit		09/02/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class PayView extends View {
	public function base($params)
	{
		Templates::$title = Language::_('COM_BANK_PAY');
		self::read_action($params);
	}
}