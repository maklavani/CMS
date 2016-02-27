<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Makalvani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	12/26/2015
	*	last edit		12/26/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class Menu_ItemsField {
	public static function output($name , $tabindex , $default , $children , $attributes , $fields_name , $placeholder)
	{
		$db = new Database;
		$db->table('menu')->where('`location` = "site"')->select()->process();
		$menus = $db->output();

		Templates::package('select2');
		Templates::add_js('jQuery(document).ready(function(){jQuery(".menus_field_' . $tabindex . '").select2();});' , true);

		$output = "<select class=\"menus_field_" . $tabindex . "\" name=\"field_input_" . $name . ($attributes != "" ? "[]" : "") . "\" tabindex=\"" . $tabindex . "\" " . $attributes . ">";
		if(!empty($menus))
			foreach ($menus as $key => $value) {
				$output .= "<option  value=\"" . $value->id . "\"";
				if(is_array($default) && in_array($value->id , $default))
					$output .= " selected ";
				else if($default == $value->id)
					$output .= " selected ";

				$output .= ">" . Language::_(strtoupper($value->name)) . "</option>";
			}

		$output .= "</select>";
		
		return $output;
	}
}