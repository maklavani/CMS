<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Makalvani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	12/14/2015
	*	last edit		12/14/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class ListsField {
	public static function output($name , $tabindex , $default , $children , $attributes , $fields_name , $placeholder)
	{
		Templates::package('lists');

		$output = "<input class=\"details-parent\" type=\"hidden\" name=\"field_input_" . $name . "\" value=\"" . htmlentities($default) . "\">";

		if(!empty($children))
		{
			$output .= "<div class=\"details xa\">";
				$output .= "<table>";
					$output .= "<thead>";
						$output .= "<tr>";
							foreach ($children as $key => $value)
							{
								$children[$key]['name'] = $name . '_' . $value['name'];
								$output .= "<th" . (isset($value['width']) ? " width=\"" . $value['width'] . "\"" : "") . ">" . Language::_($fields_name . strtoupper($name . '_' . $children[$key]['name'])) . "</th>";
							}
							$output .= "<th width=\"10%\">" . Language::_('DELETE') . "</th>";
						$output .= "</tr>";
					$output .= "</thead>";

					$output .= "<tbody class=\"details-tbody\">";
					$output .= "</tbody>";
				$output .= "</table>";
				$output .= "<div class=\"details-new xa\">" . Language::_('NEW') . "</div>";

				$fields = new Fields();
				$fields->name = $fields_name . strtoupper($name);
				$output .= "<div class=\"details-tables xa\"><table><tbody class=\"details-append\"><tr>" . $fields->read_fileds($children , true) . "<td><span class=\"details-item-delete icon-delete\"></span></td></tr></tbody></table></div>";
			$output .= "</div>";
		}

		return $output;
	}
}