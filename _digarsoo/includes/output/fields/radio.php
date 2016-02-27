<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Makalvani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	06/15/2015
	*	last edit		08/04/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class RadioField {
	public static function output($name , $tabindex , $default , $children , $attributes , $fields_name , $placeholder)
	{
		$output = '';
		if(!empty($children))
			foreach ($children as $key => $value) {
				$output .= "<input name=\"field_input_" . $name .  "\" tabindex=\"" . $tabindex . "\" type=\"radio\" value=\"" . $key . "\" label=\"" . Language::_(strtoupper($fields_name . $value)) . "\"";
				if($default == $key)
					$output .= " checked ";
				$output .= " " . $attributes . ">";
			}
		
		return $output;
	}

	public static function properties()
	{
		return "<textarea class=\"field-item\" name=\"children\"></textarea>";
	}
}