<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	06/15/2015
	*	last edit		09/22/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class Fields {
	public $name;
	public $action;
	public $method;
	public $pages;
	public $field;
	public $attributes;

	// constructor
	public function __construct()
	{
		$this->pages = array();
		$this->field = array();
		$this->attributes = array();
	}

	// output fileds ha
	public function output($print = false)
	{
		// ezafe kardane package
		Templates::package('fields');

		$attributes = "";
		if(!empty($this->attributes))
			foreach ($this->attributes as $key => $value)
				$attributes .= " " . $key . "=\"" . $value ."\"";

		$output = "";
		$output .= "\n\t\t\t<form id=\"" . strtolower($this->name) . "-form\" action=\"" . $this->action . "\" class=\"xa\" method=\"" . $this->method . "\"" . ($attributes != "" ? " " . $attributes : "") . ">";
			$output .= $this->get_buttons();
			$output .= "\n\t\t\t\t<div class=\"fileds-pages xa\">";
				foreach ($this->pages as $key => $value) {
					$output .= "\n\t\t\t\t\t<div class=\"fileds-page xa\" page=\"" . strtolower($key) . "\">" . $this->read_fileds($value) . "\n\t\t\t\t\t</div>";
				}
			$output .= "\n\t\t\t\t</div>";
			$output .= "\n\t\t\t\t<input type=\"hidden\" name=\"form-button\" value=\"\"/>";
			$output .= "\n\t\t\t\t<input type=\"hidden\" name=\"form-action\" value=\"" . $this->action . "\"/>";
		$output .= "\n\t\t\t</form>";

		if($print)
			return $output;
		echo $output;
	}

	// khandane field ha
	public function read_fileds($value , $table = false)
	{
		$numbers = 0;

		$output = '';

		foreach ($value as $key => $value) {
			if(isset($value['type']) && in_array($value['type'] , 
					array(	'text' , 'radio' , 'menu' , 'list' , 'tinymce' , 
							'textarea' , 'icons' , 'menu_position' , 'password' , 'ajax' , 
							'image' , 'listajax' , 'tel' , 'html' , 'date' , 
							'creator' , 'price' , 'map' , 'hidden' , 'menus' , 
							'menu_select' , 'color' , 'category' , 'folderlist' , 'lists' , 
							'article' , 'menu_items' , 'file' , 'numeric' , 'adddetails' , 
							'forms_creator')))
			{
				// class ra bekhan
				require_once _INC . 'output/fields/' . $value['type'] . '.php';
				$input_class = ucwords(strtolower($value['type'])) . 'Field';

				$default = null;

				$name = "";
				if(isset($value['name']))
					$name = $value['name'];

				if(isset($_POST['field_input_' . strtolower($name)]))
					$default = $_POST['field_input_' . strtolower($name)];
				else if(isset($_GET['field_input_' . strtolower($name)]))
					$default = $_GET['field_input_' . strtolower($name)];
				else if(isset($value['default']))
					$default = $value['default'];

				if(!$table)
					$output .= "\n\t\t\t\t\t\t<div class=\"fields-group xa\" type=\"" . $value['type'] . "\">";

				$children = null;
				if(isset($value['children']))
					$children = $value['children'];

				$attributes = "";
				if(isset($value['attributes']))
					$attributes = $value['attributes'];

				$name_string = "";
				if(isset($value['name_string']) && $value['name_string'] != "")
					$name_string = $value['name_string'];

				$language = $this->name . '_';
				if(isset($value['language']) && $value['language'] != false)
					$language = $value['language'] . '_';
				else if(isset($value['language']) && $value['language'] == false || $name_string != "")
					$language = '';

				$placeholder = true;
				if(isset($value['placeholder']))
					$placeholder = $value['placeholder'];

				$show_label = false;
				if(isset($value['show_label']))
					$show_label = $value['show_label'];

				$numbers++;
				$type_in = in_array($value['type'] , array('tinymce' , 'menu_position' , 'map' , 'hidden' , 'menu_select' , 'lists'));

				if($table)
					$output .= '<td>' . $input_class::output($name , $numbers , $default , $children , $attributes , $language , $placeholder) . '</td>';
				else
				{
					if(!$placeholder && $name == "" || ($type_in && !$show_label))
					{
						$output .= "\n\t\t\t\t\t\t\t<div class=\"fields-input xa\">\n\t\t\t\t\t\t\t\t";
							$output .= $input_class::output($name , $numbers , $default , $children , $attributes , $language , $placeholder);
						$output .= "\n\t\t\t\t\t\t\t</div>";
					}
					else
					{
						if($name_string == "")
							$output .= "\n\t\t\t\t\t\t\t<div class=\"fields-label x3 s25 m3 l25\">" . Language::_($this->name . '_' . strtoupper($name)) . "</div>";
						else
							$output .= "\n\t\t\t\t\t\t\t<div class=\"fields-label x3 s25 m3 l25\">" . $name_string . "</div>";

						$output .= "\n\t\t\t\t\t\t\t<div class=\"fields-input x7 s75 m7 l75\">\n\t\t\t\t\t\t\t\t";
							$output .= $input_class::output($name , $numbers , $default , $children , $attributes , $language , $placeholder);
						$output .= "\n\t\t\t\t\t\t\t</div>";
					}
				}

				if(!$table)
					$output .= "\n\t\t\t\t\t\t</div>";
			}
		}

		return $output;

	}

	// sakhtane buttonha
	private function get_buttons()
	{
		$output = "\n\t\t\t\t<div class=\"fileds-buttons xa\">";
			foreach ($this->pages as $key => $value) {
				$output .= "\n\t\t\t\t\t<div class=\"fileds-button\" page=\"" . strtolower($key) . "\">" . Language::_($this->name . '_' . strtoupper($key)) . "</div>";
			}
		$output .= "\n\t\t\t\t</div>";
		return $output;
	}
}