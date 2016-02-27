<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Makalvani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/12/2015
	*	last edit		07/12/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class CaptchaInputs {
	public static function output($form_input_number , $default = false , $attributes = false , $children = false)
	{
		return "<digarsoo type=\"plugins\" name=\"captcha\"><input name=\"form_input_" . $form_input_number .  "\" tabindex=\"" . $form_input_number . "\" type=\"text\" " . $attributes . ">";
	}
}