<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	07/22/2015
	*	last edit		07/22/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class GregorianCalendar {
	public function get($date , $format)
	{
		$current_date = date_timezone_set($date , timezone_open('Europe/London'));
		return $current_date->format($format);
	}
}