<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	07/15/2015
	*	last edit		08/23/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class ImageField {
	public static function output($name , $tabindex , $default , $children , $attributes , $fields_name , $placeholder)
	{
		Templates::package('pupup');
		Templates::package('image');

		$numbers = 'one';
		if(isset($children['numbers']))
			$numbers = $children['numbers'];

		$source = 'uploads/';
		if(isset($children['source']))
			$source = $children['source'];

		$types = array('gif' , 'jpg' , 'jpeg' , 'png');
		if(isset($children['types']))
			$types = $children['types'];

		$setting = System::init_cookie_file('image_' . $name . '_upload' , $source);
		$toolbar = System::get_toolbar(array('view' => array('grid' , 'list') , 'toolbar' => array('delete' , 'rename' , 'new-folder' , 'upload')));
		$view = System::get_view('image_' . $name . '_upload' , $setting['source'] , $setting['view'] , '.active-page' , '#popup' , false , $numbers , $types);

		$upload = json_encode(array('toolbar' => $toolbar , 'view' => $view) , JSON_UNESCAPED_UNICODE);

		$output = "<input type=\"hidden\" name=\"field_input_" . $name .  "\" value=\"" . $default . "\" image_name=\"" . $name . "\" image=\"image\">";

		$output .= "<div class=\"field-parent field-image showing\" image_name=\"" . $name . "\" upload=\"" . htmlentities($upload) . "\" image=\"" . Language::_('IMAGE') . ' ' . Language::_('SELECT') . "\" save=\"" . Language::_('SAVE') . "\" CANCEL=\"" . Language::_('CANCEL') . "\">";
			$output .= "<div class=\"field-text\">" . ($default != "" ? $default : "&nbsp;") . "</div>";
			$output .= "<div class=\"field-clean icon-refresh\"></div>";
			$output .= "<div class=\"field-button checked\">" . Language::_('SELECT') . "</div>";
		$output .= "</div>";

		return $output;
	}
}