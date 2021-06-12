<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Makalvani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	09/20/2015
	*	last edit		09/21/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class FolderlistField {
	public static function output($name , $tabindex , $default , $children , $attributes , $fields_name , $placeholder)
	{
		Templates::package('select2');
		Templates::add_js('jQuery(document).ready(function(){jQuery(".folderlist_field_' . $tabindex . '").select2();});' , true);

		$address = 'images';
		if(isset($children['address']))
			$address = $children['address'];

		$addresses = glob(_SRC_SITE . $address . '*' , GLOB_ONLYDIR);

		$output = "<select class=\"folderlist_field_" . $tabindex . "\" name=\"field_input_" . $name . "\" tabindex=\"" . $tabindex . "\" " . $attributes . ">";
		if(!empty($addresses))
			foreach ($addresses as $value)
			{
				$base = basename($value);
				$output .= "<option  value=\"" . $base . "\"";
				if(is_array($default) && in_array($base , $default))
					$output .= " selected ";
				else if($default == $base)
					$output .= " selected ";

				$output .= ">" . $base . "</option>";
			}
		$output .= "</select>";
		
		return $output;
	}
}