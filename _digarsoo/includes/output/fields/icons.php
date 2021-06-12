<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Makalvani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	07/07/2015
	*	last edit		08/04/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class IconsField {
	public static function output($name , $tabindex , $default , $children , $attributes , $fields_name , $placeholder)
	{
		Templates::package('popup');
		Templates::package('icon');

		$output = "<input type=\"hidden\" name=\"field_input_icon\" value=\"" . $default . "\">";

		$output .= "<div class=\"field-parent field-icon showing\" name=\"" . Language::_('ICONS') . "\" buttons=\"" . htmlspecialchars(json_encode(array('save' => Language::_('SAVE') , 'cancel' => Language::_('CANCEL')) , JSON_UNESCAPED_UNICODE)) . "\">";
			$output .= "<div class=\"field-text\">" . ($default != "" ? "<div class=\"" . $default . "\"></div>" : "&nbsp;") . "</div>";
			$output .= "<div class=\"field-clean icon-refresh\"></div>";
			$output .= "<div class=\"field-button checked\">" . Language::_('SELECT') . "</div>";
		$output .= "</div>";

		return $output;
	}
}