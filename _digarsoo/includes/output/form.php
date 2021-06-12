<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	06/15/2015
	*	last edit		01/09/2017
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class Form {
	public $name;
	public $action;
	public $method;
	public $attributes;
	public $inputs;
	public $label;
	public $number;

	// constructor
	public function __construct()
	{
		$this->inputs  = array();
		$this->label = true;
		$this->number = 0;
	}

	// vared kardane inputs
	public function add_input($type , $label , $default = false , $attributes = false , $children = false)
	{
		// aya type motabar ast
		if(in_array($type , array(	'text' , 'password' , 'select' , 'textarea' , 'button' , 'checkbox' , 
									'color' , 'time' , 'file' , 'hidden' , 'image' , 'month' , 
									'number' , 'radio' , 'range' , 'reset' , 'search' , 'week' , 
									'url' , 'tel' , 'submit' , 'captcha')))
		{
			$this->number++;

			$output = "\n\t\t\t\t<div id=\"form-group-id-" . $this->number . "\" class=\"form-group xa\">";
			$attrs = "";

			// class ra bekhan
			require_once _INC . 'output/inputs/' . $type . '.php';
			$input_class = ucwords(strtolower($type)) . 'Inputs';

			if(is_array($attributes) && !empty($attributes))
			{
				foreach ($attributes as $key => $value) {
					if(Regex::cs($key , "numeric"))
						$attrs .= " " . $value;
					else
						$attrs .= " " . $key . "=\"" . $value . "\"";
				}
			}

			if(isset($_POST['form_input_' . $this->number]))
				$default = $_POST['form_input_' . $this->number];
			else if(isset($_GET['form_input_' . $this->number]))
				$default = $_GET['form_input_' . $this->number];


			if($this->label)
			{
				$output .= "\n\t\t\t\t\t<div class=\"form-input x7 s75 m7 l75 after-float\">\n\t\t\t\t\t\t";
				$output .= $input_class::output($this->number , $default , $attrs , $children) . "\n\t\t\t\t\t</div>\n\t\t\t\t\t<div class=\"form-label x3 s25 m3 l25 after-float\">" . $label . "</div>";
			}
			else
			{
				$output .= "\n\t\t\t\t\t<div class=\"form-input xa\">\n\t\t\t\t\t\t" . $input_class::output($this->number , $default , $attrs , $children) . "\n\t\t\t\t\t</div>";
			}

			$output .= "\n\t\t\t\t</div>";
			$this->inputs[] = $output;
		}
	}

	// khuruji dadane form
	public function output()
	{
		Templates::package('table');

		$this->inputs[] .= "\n\t\t\t\t<input type=\"hidden\" name=\"form-button\" value=\"\" />";
		$this->inputs[] .= "\n\t\t\t\t<input type=\"hidden\" name=\"form-values\" value=\"\" />";
		$this->inputs[] .= "\n\t\t\t\t<input type=\"hidden\" name=\"form-action\" value=\"" . $this->action . "\"/>";

		echo "\n\t\t\t<form id=\"" . $this->name . "-form\" action=\"" . $this->action . "\" method=\"" . $this->method . "\" ";
		if(is_array($this->attributes))
			foreach ($this->attributes as $key => $value)
				echo " " . $key . "=\"" . $value . "\"";
		echo ">";
			if(!empty($this->inputs))
				foreach ($this->inputs as $value) {
					echo $value;
				}
		echo "\n\t\t\t</form>";
	}
}