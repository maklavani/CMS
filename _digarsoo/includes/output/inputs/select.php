<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Makalvani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	06/15/2015
	*	last edit		06/15/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class SelectInputs {
	public static function output($form_input_number , $default = false , $attributes = false , $children = false)
	{
		$output = "<select name=\"form_input_" . $form_input_number .  "\" tabindex=\"" . $form_input_number . "\"" . $attributes . ">";
		if(is_array($children))
			foreach($children as $key => $value){
				$output .= "\n\t\t\t\t\t\t\t<option";
				if($default == $key)
					$output .= " selected";
				$output .= " value=\"" . $key . "\">" . $value . "</option>";
			}
		$output .= "\n\t\t\t\t\t\t</select>";
		return $output;
	}
}