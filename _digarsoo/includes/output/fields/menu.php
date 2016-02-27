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

class MenuField {
	public static function output($name , $tabindex , $default , $children , $attributes , $fields_name , $placeholder)
	{
		Templates::package('popup');
		Templates::package('menu');
		Templates::package('select2');
		Templates::package('view_buttons');
		Templates::package('table');

		if(isset($_POST['field_input_type']) && isset($_POST['field_input_link']))
			$default = array('type' => $_POST['field_input_type'] , 'link' => $_POST['field_input_link']);
		else if(isset($_GET['field_input_type']) && isset($_GET['field_input_link']))
			$default = array('type' => $_GET['field_input_type'] , 'link' => $_GET['field_input_link']);
		else if(!is_array($default))
			$default = array('type' => '' , 'link' => '');

		$output = "<input type=\"hidden\" name=\"field_input_type\" value=\"" . $default['type'] . "\">";
		$output .= "<input type=\"hidden\" name=\"field_input_link\" value=\"" . $default['link'] . "\">";

		$output .= "<div class=\"field-parent field-type showing\" address=\"" . Site::$base . _ADM . 'index.php?component=menus&amp;ajax' . "\">";
			$output .= "<div class=\"field-text\">" . ($default['type'] != "" ? $default['type'] : "&nbsp;") . "</div>";
			$output .= "<div class=\"field-clean icon-refresh\"></div>";
			$output .= "<div class=\"field-button checked\">" . Language::_('SELECT') . "</div>";
		$output .= "</div>";

		$output .= "<div class=\"field-parent field-link " . ($default['link'] != "" || $default['type'] != "" ? "showing" : "") . "\" address=\"" . Site::$base . _ADM . 'index.php?component=menus&amp;ajax' . "\">";
			$output .= "<div class=\"field-text\">" . ($default['link'] != "" ? $default['link'] : "&nbsp;") . "</div>";
			$output .= "<div class=\"field-clean icon-refresh\"></div>";
			$output .= "<div class=\"field-button checked\">" . Language::_('SELECT') . "</div>";
		$output .= "</div>";

		return $output;
	}
}