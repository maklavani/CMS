<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Makalvani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	01/31/2016
	*	last edit		01/31/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class AddDetailsField {
	public static function output($name , $tabindex , $default , $children , $attributes , $fields_name , $placeholder)
	{
		Templates::package('adddetails');

		$output = "<input class=\"adddetails-input\" type=\"hidden\" name=\"field_input_" . $name . "\" value=\"" . htmlspecialchars($default) . "\">";
		$output .= "<div class=\"adddetails xa\" select=\"" . Language::_('SELECT') . "\" save=\"" . Language::_('SAVE') . "\">";
			$output .= "<div class=\"adddetails-items xa\">";

			if($default)
			{

				if(!empty($items))
					foreach ($items as $key => $value) {
						$output .= "<div class=\"adddetails-item xa\" item=\"" . $key . "\">";
							$output .= "<div class=\"icon-move x1\"></div>";
							$output .= "<div class=\"name x75\">" . $value->name . "</div>";
							$output .= "<div class=\"icon-delete x15\"></div>";
						$output .= "</div>";
					}
			}

			$output .= "</div>";
			$output .= "<div class=\"adddetails-button xa\">" . Language::_('NEW') . "</div>";
		$output .= "</div>";

		return $output;
	}
}