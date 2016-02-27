<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Makalvani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	09/07/2015
	*	last edit		12/03/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class CategoryField {
	public static function output($name , $tabindex , $default , $children , $attributes , $fields_name , $placeholder)
	{
		$db = new Database;
		$db->table('category')->order('parent ASC , id ASC')->select()->process();
		$category = $db->output();

		$categories = array();
		if($category)
			self::cats($category , $categories , 0 , 0 , -1);

		Templates::package('select2');
		Templates::add_js('jQuery(document).ready(function(){jQuery(".category_field_' . $tabindex . '").select2();});' , true);

		$output = "<select class=\"category_field_" . $tabindex . "\" name=\"field_input_" . $name . ($attributes != "" ? "[]" : "") . "\" tabindex=\"" . $tabindex . "\" " . $attributes . ">";
		if(!empty($categories))
			foreach ($categories as $key => $value) {
				$output .= "<option  value=\"" . $key . "\"";
				if(is_array($default) && in_array($key , $default))
					$output .= " selected ";
				else if($default == $key)
					$output .= " selected ";

				$output .= ">" . Language::_(strtoupper($value)) . "</option>";
			}

		$output .= "</select>";
		
		return $output;
	}

	private static function cats($category , &$categories , $parent , $level , $edit_parent)
	{
		foreach ($category as $value)
			if($value->parent == $parent)
			{
				if(!isset($categories[$value->id]) && $value->id != $edit_parent){
					$categories[$value->id] = str_repeat("__ " , $level) . $value->title;
					self::cats($category , $categories , $value->id , $level + 1 , $edit_parent);
				}
			}
	}
}