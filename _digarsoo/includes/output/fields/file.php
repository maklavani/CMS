<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Makalvani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	12/29/2015
	*	last edit		12/29/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class FileField {
	public static function output($name , $tabindex , $default , $children , $attributes , $fields_name , $placeholder)
	{
		return "<input class=\"xa\" name=\"field_input_" . $name .  "\" tabindex=\"" . $tabindex . "\" type=\"file\" value=\"" . $default . "\" " . $attributes . ">";
	}
}