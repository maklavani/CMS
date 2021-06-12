<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Makalvani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	07/09/2015
	*	last edit		08/04/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class PasswordField {
	public static function output($name , $tabindex , $default , $children , $attributes , $fields_name , $placeholder)
	{
		return "<input class=\"xa\" name=\"field_input_" . $name .  "\" tabindex=\"" . $tabindex . "\" type=\"password\" " . ($placeholder ? "placeholder=\"" . Language::_($fields_name . strtoupper($name)) . "\"" : "") . " value=\"" . $default . "\" " . $attributes .">";
	}
}