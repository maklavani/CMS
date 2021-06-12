<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	07/06/2015
	*	last edit		08/04/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class TextareaField {
	public static function output($name , $tabindex , $default , $children , $attributes , $fields_name , $placeholder)
	{
		return "<textarea name=\"field_input_" . $name .  "\" tabindex=\"" . $tabindex . "\" " . ($placeholder ? "placeholder=\"" . Language::_($fields_name . strtoupper($name)) . "\"" : "") . " " . $attributes . ">" . $default . "</textarea>";
	}
}