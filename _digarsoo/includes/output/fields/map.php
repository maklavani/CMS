<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Makalvani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	08/04/2015
	*	last edit		08/05/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class MapField {
	public static function output($name , $tabindex , $default , $children , $attributes , $fields_name , $placeholder)
	{
		Templates::add_js('http://maps.googleapis.com/maps/api/js?libraries=drawing');
		Templates::package('map');

		$default = json_decode(htmlspecialchars_decode($default) , true);

		$latitude = Configuration::$latitude;
		if(isset($default['latitude']))
			$latitude = $default['latitude'];

		$longitude = Configuration::$longitude;
		if(isset($default['longitude']))
			$longitude = $default['longitude'];

		$zoom = 15;
		if(isset($default['zoom']))
			$zoom = $default['zoom'];

		if($default != "")
			$default = htmlspecialchars(json_encode($default , JSON_UNESCAPED_UNICODE));

		$output = "<input id=\"map-input\" name=\"field_input_" . $name .  "\" type=\"hidden\" location=\"" . Site::$base . "media/images/\" value=\"" . $default . "\" " . $attributes ." 
						latitude=\"" . $latitude . "\" 
						longitude=\"" . $longitude . "\"
						zoom=\"" . $zoom . "\"
						>";
		$output .= "<div id=\"map\" class=\"xa\" style=\"height: 400px;\"></div>";
		
		return $output;
	}
}