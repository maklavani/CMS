<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	06/29/2015
	*	last edit		09/22/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class Lists {
	public $name;
	public $action;
	public $method;
	public $head;
	public $body;
	public $component;

	// constructor
	public function __construct()
	{
		$this->field = array();
		$this->head = array();
		$this->body = array();
		$this->component = '';
	}

	// output fileds ha
	public function output()
	{
		Templates::package('table');

		echo "\n\t\t\t<form id=\"" . strtolower($this->name) . "-form\" class=\"xa\" action=\"" . $this->action . "\" class=\"xa\" method=\"" . $this->method . "\" result=\"" . Language::_('RESULT_FOUND') . "\" error-one-select=\"" . Language::_('ERROR_ONE_SELECT') . "\" error-more-select=\"" . Language::_('ERROR_MORE_SELECT') . "\" error-confirm-delete=\"" . Language::_('ERROR_CONFIRM_DELETE') . "\">";

			echo "\n\t\t\t\t<table class=\"xa\">";
				echo "\n\t\t\t\t\t<thead>";
					echo $this->print_head();
				echo "\n\t\t\t\t\t</thead>";
				echo "\n\t\t\t\t\t<tbody>";
					echo $this->print_body();
				echo "\n\t\t\t\t\t</tbody>";
			echo "\n\t\t\t\t</table>";
			
			echo "\n\t\t\t\t<input type=\"hidden\" name=\"form-search\" value=\"" . (isset($_POST['form-search']) ? $_POST['form-search'] : "") . (isset($_GET['form-search']) ? $_GET['form-search'] : "") . "\" />";
			echo "\n\t\t\t\t<input type=\"hidden\" name=\"form-search-category\" value=\"" . (isset($_POST['form-search-category']) ? $_POST['form-search-category'] : "") . (isset($_GET['form-search-category']) ? $_GET['form-search-category'] : "") . "\" />";
			echo "\n\t\t\t\t<input type=\"hidden\" name=\"form-sort\" value=\"" . (isset($_POST['form-sort']) ? $_POST['form-sort'] : "") . (isset($_GET['form-sort']) ? $_GET['form-sort'] : "") . "\" />";
			echo "\n\t\t\t\t<input type=\"hidden\" name=\"form-sort-order\" value=\"" . (isset($_POST['form-sort-order']) ? $_POST['form-sort-order'] : "") . (isset($_GET['form-sort-order']) ? $_GET['form-sort-order'] : "") . "\" />";
			echo "\n\t\t\t\t<input type=\"hidden\" name=\"form-number\" value=\"" . (isset($_POST['form-number']) ? $_POST['form-number'] : "") . (isset($_GET['form-number']) ? $_GET['form-number'] : "") . "\" />";
			echo "\n\t\t\t\t<input type=\"hidden\" name=\"form-page\" value=\"" . (isset($_POST['form-page']) ? $_POST['form-page'] : "") . (isset($_GET['form-page']) ? $_GET['form-page'] : "") . "\" />";
			echo "\n\t\t\t\t<input type=\"hidden\" name=\"form-name\" value=\"" . $this->component . '_' . $this->name . "\" />";
			echo "\n\t\t\t\t<input type=\"hidden\" name=\"form-button\" value=\"\" />";
			echo "\n\t\t\t\t<input type=\"hidden\" name=\"form-values\" value=\"\" />";
			echo "\n\t\t\t\t<input type=\"hidden\" name=\"form-action\" value=\"" . $this->action . "\"/>";

		echo "\n\t\t\t</form>";
	}

	// chap kardane head e list
	private function print_head()
	{
		$output = "\n\t\t\t\t\t\t<tr>";

		if(!empty($this->head))
			foreach ($this->head as $key => $value) {
				$output .= "\n\t\t\t\t\t\t\t<th width=\"" . $value['size'] . "%\">";

					if($value['type'] == 'checkbox')
						$output .= "<input class=\"checkbox-all\" name=\"checkall\" type=\"checkbox\">";
					else if($value['type'] == 'button')
						$output .= '<div class="thead-button" val="' . strtolower($key) . '">' . Language::_(strtoupper('COM_' . $this->component . '_' . $key)) . '</div>';
					else
						$output .= Language::_(strtoupper('COM_' . $this->component . '_' . $key));

				$output .= "</th>";
			}

		$output .= "\n\t\t\t\t\t\t</tr>";

		return $output;
	}

	// chap kardane body e list
	private function print_body()
	{
		$output = "";

		if(!empty($this->body))
		{
			foreach ($this->body as $key => $value){
				$output .= "\n\t\t\t\t\t\t<tr>";

				foreach ($value as $keyb => $valueb) {
						$output .= "\n\t\t\t\t\t\t\t<td class=\"";
						if(isset($valueb['class']))
							$output .= 'level ' . $valueb['class'];
						$output .= "\">";
							if($valueb['type'] == 'checkbox')
								$output .= "<input class=\"checkbox\" name=\"checkall\" type=\"checkbox\" tid=\"" . $valueb['value'] . "\">";
							else if($valueb['type'] == 'status')
								$output .= "<div class=\"status icon-" . ($valueb['value']['status'] ? "block" : "unblock") . "\" tid=\"" . $valueb['value']['id'] . "\"></div>";
							else if($valueb['type'] == 'button')
								$output .= "<div type=\"" . $keyb . "\" class=\"button icon-" . $valueb['value']['icon'] . " " . ($valueb['value']['status'] ? "active" : "unblock") . "\" tid=\"" . $valueb['value']['id'] . "\"></div>";
							else
								$output .= $valueb['value'];

							if(isset($valueb['icon']) && $valueb['icon'] != '')
								$output .= '<div class="icon ' . $valueb['icon'] . '"></div>';

						$output .= "</td>";
				}

				$output .= "\n\t\t\t\t\t\t</tr>";
			}
		}
		else
		{
			$output = "\n\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t<td colspan=\"" . count($this->head) . "\">" . Language::_('NOT_INSERT') . "</td>\n\t\t\t\t\t\t</tr>";
		}

		return $output;
	}
}