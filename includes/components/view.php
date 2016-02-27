<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	06/15/2015
	*	last edit		02/04/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class View {
	public static $view;
	public static $toolbar;
	public static $buttons;
	public static $search;

	// khandane view
	public static function read($params = false)
	{
		// khandane file view
		require_once _COMP . Components::$name . '/view/' . Controller::$view . '/view.php';
		$view = ucwords(strtolower(Controller::$view)) . 'View';

		// agar class view vujud dasht
		if(class_exists($view))
		{
			// an ra besaz
			View::$view = new $view();
			// agar method base vujud dasht an ra bekhan
			if(method_exists(View::$view , 'base'))
			{
				// set kardane action
				Controller::set_action();

				// khandane view
				call_user_func_array(array(View::$view , 'base') , array($params));
			}
		}
	}

	// khandane action
	public static function read_action($params = false)
	{
		$params = json_decode($params);
		$setting = Components::$setting;

		if(self::$buttons)
		{
			Templates::package('view_buttons');

			echo "\t\t\t\t<div class=\"component-parent x95 s9 m85 l8 ex025 es05 em075 el1 aex025 aes05 aem075 ael1\">";
				echo "\n\t\t\t\t\t<div class=\"buttons xa s2 m2 l15\">" . self::$buttons . "\n\t\t\t\t\t</div>";

				echo "\n\t\t\t\t\t<div class=\"component xa s77 m77 l82 es025\">\n";

					if(self::$toolbar)
						echo "\n\t\t\t\t\t\t<div class=\"toolbar xa\">" . self::$toolbar . "\n\t\t\t\t\t\t</div>";
					if(self::$search)
						echo "\n\t\t\t\t\t\t<div class=\"toolbar-search xa\">" . self::$search . "\n\t\t\t\t\t\t</div>";

					require_once _COMP . Components::$name . '/view/' . Controller::$view . '/' . Controller::$action . '.php';
				echo "\n\n\t\t\t\t\t</div>";
			echo "\n\t\t\t\t</div>";
		}
		else
		{
			if(self::$toolbar)
				echo "\n\t\t\t\t\t\t<div class=\"toolbar xa\">" . self::$toolbar . "\n\t\t\t\t\t\t</div>";

			if(self::$search)
				echo "\n\t\t\t\t\t\t<div class=\"toolbar-search xa\">" . self::$search . "\n\t\t\t\t\t\t</div>";

			require_once _COMP . Components::$name . '/view/' . Controller::$view . '/' . Controller::$action . '.php';
		}
	}

	// sakhtane buttons
	public static function set_buttons($buttons , $active = -1)
	{
		self::$buttons = "\n\t\t\t\t\t\t<ul>";

		foreach ($buttons as $key => $value) {
			self::$buttons .= "\n\t\t\t\t\t\t\t<li";
			if($active == $key)
				self::$buttons .= " class=\"active\"";
			self::$buttons .= ">";
				self::$buttons .= "\n\t\t\t\t\t\t\t\t<a href=\"" . $value['link'] . "\">" . $value["name"] . "</a>";
			self::$buttons .= "\n\t\t\t\t\t\t\t</li>";
		}

		self::$buttons .= "\n\t\t\t\t\t\t</ul>";
	}

	// skhatane toolbar
	public static function set_toolbar($toolbar = false)
	{
		Templates::package('toolbar');

		self::$toolbar = "\n\t\t\t\t\t\t\t<h2 class=\"xa s5 m4 l3 toolbar-title\">" . (isset($toolbar['title']) ? $toolbar['title'] : "") . "</h2>";
		
		self::$toolbar .= "\n\t\t\t\t\t\t\t<div class=\"xa s5 m6 l7 toolbar-buttons\">";
		if(isset($toolbar['buttons']))
			foreach ($toolbar['buttons'] as $key => $value)
				self::$toolbar .= "\n\t\t\t\t\t\t\t\t<div class=\"toolbar-button button-" . $value . "\" form-button=\"" . $value . "\"><span class=\"" . (Regex::cs($key , 'numeric') || $key == '0' ? 'icon-' . $value : $key) . "\"></span>" . Language::_(strtoupper($value)) . "</div>";

		self::$toolbar .= "\n\t\t\t\t\t\t\t</div>";
	}

	// skhtane search tool
	public static function set_search($search = false)
	{
		Templates::package('view_buttons');
		Templates::package('select2');

		Templates::add_js('jQuery(document).ready(function(){jQuery(".toolbar-item").select2();});' , true);
		Templates::add_js('jQuery(document).ready(function(){jQuery(".toolbar-sort").select2();});' , true);
		Templates::add_js('jQuery(document).ready(function(){jQuery(".toolbar-number").select2();});' , true);

		$cookie = Cookies::get_cookie(Components::$name . '_' . Controller::$view);

		if($cookie)
			$cookie = json_decode($cookie , true);
		else
			$cookie = array();

		if(isset($search['item']) && !empty($search['item'])){
			$found_search = false;
			self::$search .= "\n\t\t\t\t\t\t\t<div class=\"toolbar-div toolbar-search-input-parent\"><input name=\"toolbar-search-input\" type=\"text\" placeholder=\"" . Language::_('SEARCH') . "\" value=\"";

				if(isset($_POST['form-search']))
					self::$search .= $found_search = $_POST['form-search'];
				else if(isset($_GET['form-search']))
					self::$search .= $found_search = $_GET['form-search'];
				else if(isset($cookie['form_search']))
					self::$search .= $found_search = $cookie['form_search'];

			self::$search .= "\" val=\"";
			if($found_search)
				self::$search .= $found_search;
			self::$search .= "\"></div>";
			self::$search .= "\n\t\t\t\t\t\t\t<div class=\"toolbar-div toolbar-item-parent\">\n\t\t\t\t\t\t\t\t<select class=\"toolbar-item\">";

			foreach ($search['item'] as $value) {
				self::$search .= "\n\t\t\t\t\t\t\t\t\t<option ";

				if(isset($_POST['form-search-category']) && $_POST['form-search-category'] == $value)
					self::$search .= "selected";
				else if(isset($_GET['form-search-category']) && $_GET['form-search-category'] == $value)
					self::$search .= "selected";
				else if(isset($cookie['form_search_category']) && $cookie['form_search_category'] == $value)
					self::$search .= "selected";

				self::$search .= " value=\"" . $value . "\">" . Language::_('COM_' . strtoupper(Components::$name . '_' . $value)) . "</option>";
			}

			self::$search .= "\n\t\t\t\t\t\t\t\t</select>\n\t\t\t\t\t\t\t</div>";
		}

		if(isset($search['sort']) && !empty($search['sort'])){
			self::$search .= "\n\t\t\t\t\t\t\t<div class=\"toolbar-div toolbar-sort-parent\">\n\t\t\t\t\t\t\t\t<select class=\"toolbar-sort\">";

			foreach ($search['sort'] as $value) {
				self::$search .= "\n\t\t\t\t\t\t\t\t\t<option ";

				if(isset($_POST['form-sort']) && isset($_POST['form-sort-order']) && $_POST['form-sort'] == $value && $_POST['form-sort-order'] == "ASC")
					self::$search .= "selected";
				else if(isset($_GET['form-sort']) && isset($_GET['form-sort-order']) && $_GET['form-sort'] == $value && $_GET['form-sort-order'] == "ASC")
					self::$search .= "selected";
				else if(isset($cookie['form_sort']) && isset($cookie['form_sort_order']) && $cookie['form_sort'] == $value && $cookie['form_sort_order'] == "ASC")
					self::$search .= "selected";
				else if((!isset($_POST['form-sort']) || $_POST['form-sort'] == "") && 
						(!isset($_POST['form-sort-order']) || $_POST['form-sort-order'] == "") && 
						(!isset($_GET['form-sort']) || $_GET['form-sort'] == "") && 
						(!isset($_GET['form-sort-order']) || $_GET['form-sort-order'] == "") && 
						!isset($cookie['form_sort']) && !isset($cookie['form_sort_order']) &&
						$value == 'id')
					self::$search .= "selected";

				self::$search .= " value=\"" . $value . ".ASC\">" . Language::_('COM_' . strtoupper(Components::$name . '_' . $value)) . ' ' . Language::_('ASC') . "</option>";

				self::$search .= "\n\t\t\t\t\t\t\t\t\t<option ";

				if(isset($_POST['form-sort']) && isset($_POST['form-sort-order']) && $_POST['form-sort'] == $value && $_POST['form-sort-order'] == "DESC")
					self::$search .= "selected";
				else if(isset($_GET['form-sort']) && isset($_GET['form-sort-order']) && $_GET['form-sort'] == $value && $_GET['form-sort-order'] == "DESC")
					self::$search .= "selected";
				else if(isset($cookie['form_sort']) && isset($cookie['form_sort_order']) && $cookie['form_sort'] == $value && $cookie['form_sort_order'] == "DESC")
					self::$search .= "selected";

				self::$search .= " value=\"" . $value . ".DESC\">" . Language::_('COM_' . strtoupper(Components::$name . '_' . $value)) . ' ' . Language::_('DESC') . "</option>";
			}

			self::$search .= "\n\t\t\t\t\t\t\t\t</select>\n\t\t\t\t\t\t\t</div>";
		}

		self::$search .= "\n\t\t\t\t\t\t\t<div class=\"toolbar-div toolbar-number-parent\">\n\t\t\t\t\t\t\t\t<select class=\"toolbar-number\">";
		foreach (array(5 , 10 , 15 , 20 , 25 , 30 , 50 , 75 , 100 , 'all') as $value) {
			self::$search .= "\n\t\t\t\t\t\t\t\t<option ";

			if(isset($_POST['form-number']) && $_POST['form-number'] == $value)
				self::$search .= "selected";
			else if(isset($_GET['form-number']) && $_GET['form-number'] == $value)
				self::$search .= "selected";
			else if(isset($cookie['form_number']) && $cookie['form_number'] == $value)
				self::$search .= "selected";
			else if((!isset($_POST['form-number']) || $_POST['form-number'] == "") && 
					(!isset($_GET['form-number']) || $_GET['form-number'] == "") && 
					!isset($cookie['form_number']) &&
					$value == 20)
				self::$search .= "selected";

			self::$search .= " value=\"" . $value . "\">" . Language::_(strtoupper($value)) . "</option>";
		}
		self::$search .= "\n\t\t\t\t\t\t\t\t</select>\n\t\t\t\t\t\t\t</div>";

		self::$search .= "\n\t\t\t\t\t\t\t<div class=\"toolbar-div toolbar-clean\">" . Language::_('CLEAN') . "</div>";
	}
}