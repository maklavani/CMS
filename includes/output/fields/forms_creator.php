<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Makalvani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	02/01/2016
	*	last edit		02/01/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class Forms_CreatorField {
	public static function output($name , $tabindex , $default , $children , $attributes , $fields_name , $placeholder)
	{
		Templates::package('popup');
		Templates::package('select2');
		Templates::package('forms_creator');

		$items = json_decode(htmlspecialchars_decode($default));
		$default = json_decode(htmlspecialchars_decode($default) , true);

		if (!empty($default))
			foreach ($default as $key => $value)
					if(!empty($value['items']))
						foreach ($value['items'] as $keyb => $valueb)
							if(is_array($valueb))
								$default[$key]['items'][$keyb] = implode("\n" , $valueb);

		$output = "<input class=\"forms-creator-input\" type=\"hidden\" name=\"field_input_" . $name . "\" value=\"" . htmlspecialchars(json_encode($default , JSON_UNESCAPED_UNICODE)) . "\">";
		$output .= "<div class=\"forms-creator xa\" ajax=\"" . Site::$base . _ADM . "index.php?component=system&ajax=creator\" select=\"" . Language::_('SELECT') . "\" save=\"" . Language::_('SAVE') . "\">";
			$output .= "<div class=\"forms-creator-items xa\">";

			if($default)
			{
				if(!empty($items))
					foreach ($items as $key => $value) {
						$output .= "<div class=\"forms-creator-item xa\" item=\"" . $key . "\">";
							$output .= "<div class=\"icon-move x1\"></div>";
							$output .= "<div class=\"name x3\">" . $value->name . "</div>";
							$output .= "<div class=\"type x15\">" . $value->type . "</div>";
							$output .= "<div class=\"check x25\">" . self::selects($value->check) . "</div>";
							$output .= "<div class=\"icon-edit x1\"></div>";
							$output .= "<div class=\"icon-delete x1\"></div>";
						$output .= "</div>";
					}
			}

			$output .= "</div>";
			$output .= "<div class=\"forms-creator-button xa\">" . Language::_('NEW') . "</div>";
		$output .= "</div>";

		return $output;
	}

	public static function selects($vals)
	{
		$checks = array(
						"ansi" , "utf8" , "numeric" , "text" , "text_utf8" , "text_with_space" , 
						"text_with_space_utf8" , "username" , "url" , "search" , "status" , "source" , 
						"password" , "email" , "text_or_email" , "tf" , "tf_b" , "textarea" , 
						"tinymce" , "link" , "tel" , "tel_b" , "captcha" , "date"
					);

		$output = "<select class=\"checks-input xa\">";

		foreach ($checks as $key => $value) {
			$output .= "<option value=\"" . $value . "\"";
			if($value == $vals)
				$output .= " selected";
			$output .= ">" . $value . "</option>";
		}

		$output .= "</select>";

		return $output; 
	}
}