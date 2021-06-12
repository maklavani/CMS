<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Makalvani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	01/10/2016
	*	last edit		01/10/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class NumericField {
	public static function output($name , $tabindex , $default , $children , $attributes , $fields_name , $placeholder)
	{
		return "<input class=\"xa\" name=\"field_input_" . $name .  "\" tabindex=\"" . $tabindex . "\" type=\"number\" " . ($placeholder ? "placeholder=\"" . Language::_($fields_name . strtoupper($name)) . "\"" : "") . " value=\"" . $default . "\" " . ($attributes == "" ? "step=\"1\"" : $attributes) ." lang=\"en\">";
	}
}