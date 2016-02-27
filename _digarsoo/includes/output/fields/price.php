<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Makalvani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	08/04/2015
	*	last edit		08/04/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class PriceField {
	public static function output($name , $tabindex , $default , $children , $attributes , $fields_name , $placeholder)
	{
		Templates::package('price');
		$output = "<input class=\"xa price\" name=\"field_input_" . $name .  "\" type=\"hidden\" value=\"" . $default . "\" " . $attributes .">";
		$output .= "<input class=\"xa price-field\" price=\"field_input_" . $name .  "\" tabindex=\"" . $tabindex . "\" type=\"text\" " . ($placeholder ? "placeholder=\"" . Language::_($fields_name . strtoupper($name)) . "\"" : "") . ">";
		
		return $output;
	}
}