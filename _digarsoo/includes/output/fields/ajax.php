<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Makalvani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	07/04/2015
	*	last edit		01/14/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class AjaxField {
	public static function output($name , $tabindex , $default , $children , $attributes , $fields_name , $placeholder)
	{
		$default_select = array();

		if(!empty($default))
			foreach ($default as $key => $value) {
				$default_select[] = array('name' => $value);
			}

		Templates::package('select2');
		Templates::add_js('
		jQuery(document).ready(function(){
			jQuery(".ajax_field_' . $tabindex . '").select2({
				dir: "' . Language::$direction . '",
				ajax: {
					url: "' . $attributes['ajax'] . '",
					type: "GET",
					dataType: "json" ,
					delay: 250 ,
					data: function(params){
						return {
							q: params.term
						};
					},
					processResults: function(data) {
						var res = [];
						for(var i  = 0 ; i < data.length; i++)
							res.push({id: data[i].name , text: data[i].name});

						return {
							results: res
						}
					},
					cache: true
				},
				minimumInputLength: 1
				' . (is_array($children) && in_array('tags' , $children) ? ',tags: true' : '')  . '
			});
		});' , true);

		$output = "<select class=\"ajax_field ajax_field_" . $tabindex . "\" name=\"field_input_" . $name . "[]\" multiple tabindex=\"" . $tabindex . "\" >";
		if(!empty($default_select))
			foreach ($default_select as $value)
				$output .= "<option selected value=\"" . $value['name'] . "\">" . $value['name'] . "</option>";
		$output .= "</select>";
		
		return $output;
	}
}