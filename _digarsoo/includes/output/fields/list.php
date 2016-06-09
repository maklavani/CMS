<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Makalvani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/04/2015
	*	last edit		05/03/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class ListField {
	public static function output($name , $tabindex , $default , $children , $attributes , $fields_name , $placeholder)
	{
		Templates::package('select2');
		Templates::add_js('jQuery(document).ready(function(){jQuery(".list_field_' . $tabindex . '").select2();});' , true);

		$output = "<select class=\"list_field_" . $tabindex . "\" name=\"field_input_" . $name . ($attributes != "" ? "[]" : "") . "\" tabindex=\"" . $tabindex . "\" " . $attributes . ">";
		if(!empty($children))
			foreach ($children as $key => $value) {
				$output .= "<option  value=\"" . $key . "\"";

				if(!empty($default)){
					if(is_array($default) && in_array($key , $default))
						$output .= " selected ";
					else if($default == $key)
						$output .= " selected ";
				}

				$output .= ">" . Language::_(strtoupper($fields_name . $value)) . "</option>";
			}

		$output .= "</select>";
		
		return $output;
	}

	public static function properties()
	{
		return "<textarea class=\"field-item\" name=\"children\"></textarea>";
	}
}