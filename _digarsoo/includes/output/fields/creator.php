<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Makalvani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	08/04/2015
	*	last edit		08/04/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class CreatorField {
	public static function output($name , $tabindex , $default , $children , $attributes , $fields_name , $placeholder)
	{
		Templates::package('popup');
		Templates::package('select2');
		Templates::package('creator');

		$items = json_decode(htmlspecialchars_decode($default));
		$default = json_decode(htmlspecialchars_decode($default) , true);

		if (!empty($default))
			foreach ($default as $key => $value)
					if(!empty($value['items']))
						foreach ($value['items'] as $keyb => $valueb)
							if(is_array($valueb))
								$default[$key]['items'][$keyb] = implode("\n" , $valueb);

		$output = "<input class=\"creator-input\" type=\"hidden\" name=\"field_input_" . $name . "\" value=\"" . htmlspecialchars(json_encode($default , JSON_UNESCAPED_UNICODE)) . "\">";
		$output .= "<div class=\"creator xa\" ajax=\"" . Site::$base . _ADM . "index.php?component=system&ajax=creator\" select=\"" . Language::_('SELECT') . "\" save=\"" . Language::_('SAVE') . "\">";
			$output .= "<div class=\"creator-items xa\">";

			if($default)
			{

				if(!empty($items))
					foreach ($items as $key => $value) {
						$output .= "<div class=\"creator-item xa\" item=\"" . $key . "\">";
							$output .= "<div class=\"icon-move x1\"></div>";
							$output .= "<div class=\"name x4\">" . $value->name . "</div>";
							$output .= "<div class=\"type x2\">" . $value->type . "</div>";
							$output .= "<div class=\"icon-edit x15\"></div>";
							$output .= "<div class=\"icon-delete x15\"></div>";
						$output .= "</div>";
					}
			}

			$output .= "</div>";
			$output .= "<div class=\"creator-button xa\">" . Language::_('NEW') . "</div>";
		$output .= "</div>";

		return $output;
	}
}