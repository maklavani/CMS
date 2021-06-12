<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Makalvani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	12/17/2015
	*	last edit		12/17/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class ArticleField {
	public static function output($name , $tabindex , $default , $children , $attributes , $fields_name , $placeholder)
	{
		Templates::package('popup');
		Templates::package('article');

		$output = "<input class=\"article_input\" type=\"hidden\" name=\"field_input_" . $name . "\" value=\"" . $default . "\">";

		$output .= "<div class=\"field-parent field-article showing\" address=\"" . Site::$base . _ADM . 'index.php?component=menus&ajax' . "\">";
			$output .= "<div class=\"field-text\">" . ($default != "" ? $default : "&nbsp;") . "</div>";
			$output .= "<div class=\"field-clean icon-refresh\"></div>";
			$output .= "<div class=\"field-button checked\">" . Language::_('SELECT') . "</div>";
		$output .= "</div>";
		
		return $output;
	}
}