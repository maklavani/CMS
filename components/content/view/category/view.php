<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	07/14/2015
	*	last edit		07/14/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class CategoryView extends View {
	public function base($params)
	{
		self::read_action($params);
	}
}