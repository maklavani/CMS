<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Makalvani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	06/15/2015
	*	last edit		06/15/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class MonthInputs {
	public static function output($form_input_number , $default = false , $attributes = false , $children = false)
	{
		return "<input name=\"form_input_" . $form_input_number .  "\" tabindex=\"" . $form_input_number . "\" type=\"month\" value=\"" . $default . "\"" . $attributes . ">";
	}
}