<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Makalvani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/04/2015
	*	last edit		11/01/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class ListajaxField {
	public static function output($name , $tabindex , $default , $children , $attributes , $fields_name , $placeholder)
	{
		Templates::package('select2');
		Templates::add_js('jQuery(document).ready(function(){jQuery(".listajax_field_' . $tabindex . '").select2();});' , true);
		Templates::package('listajax');

		$output = "<select class=\"listajax listajax_field_" . $tabindex . "\" default=\"" . $default . "\" name=\"field_input_" . $name . "\" tabindex=\"" . $tabindex . "\" index=\"" . $tabindex . "\"";
		if(!empty($children))
			foreach ($children as $key => $attribute)
				$output .= " " . $key . "=\"" . $attribute . "\"";
		$output .= "></select>";
		
		return $output;
	}
}