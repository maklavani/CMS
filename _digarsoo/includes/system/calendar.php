<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/21/2015
	*	last edit		09/09/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class Calendar {
	public $name;
	public $class;

	function __construct($name = 'gregorian')
	{
		$this->name = $name;

		require_once _INC . 'system/calendars/' . $this->name . '.php';
		$class_name = ucwords(strtolower($this->name)) . 'Calendar';

		// agar class vujud dasht
		if(class_exists($class_name))
			$this->class = new $class_name();
	}

	public function convert($date , $format)
	{
		$current_date = new DateTime($date);

		if(method_exists($this->class , 'get'))
			return call_user_func_array(array($this->class , 'get') , array($current_date , $format));

		return $date;
	}

	public function get_gregorian($year , $month , $day)
	{
		if(method_exists($this->class , 'get_gregorian'))
			return call_user_func_array(array($this->class , 'get_gregorian') , array($year , $month , $day));

		return new DateTime();
	}
}