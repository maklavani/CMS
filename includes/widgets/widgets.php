<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	03/28/2015
	*	last edit		12/03/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class Widgets {
	private static $instance;

	public static $widgets;
	public static $templates_read_file;

	public static $active_menu_group;
	public static $active_menu;

	public static $title_diff;
	public static $meta_diff;
	public static $css_diff;
	public static $js_diff;

	// ejraye constructor
	function __construct()
	{
		self::$widgets = array();
		self::$templates_read_file = array();

		self::$active_menu_group = -1;
		self::$active_menu = -1;

		self::set_active_menu();
	}

	// sakhtane instance baraye estefade az tavabe static
	public static function get_instance() 
	{
		if (!isset(self::$instance)) {
			$c = __CLASS__;
			$instance = new $c;
		}
		return self::$instance;
	}

	// khandane wigets ha
	public static function read($names)
	{
		$name_arr = array();
		foreach ($names as $value)
			$name_arr[] = $value;

		$widgets = '';

		foreach ($names as $key => $value) {
			if($key)
				$widgets .= ' OR ';
			else
				$widgets .= '(';

			$widgets .= '`position` = "' . $value . '"';

			if(count($name_arr) - 1 == $key)
				$widgets .= ')';
		}

		$db = new Database;
		$db->table('widgets')->where($widgets . ' AND `location` = "' . _LOC . '" AND `status` = 0 AND (`languages` = "all" OR `languages` = "' . Language::$lang . '")')->select()->process();
		$widgets = $db->output();

		self::$title_diff = Templates::$title;
		self::$meta_diff = Templates::$meta;
		self::$css_diff = Templates::$css;
		self::$js_diff = Templates::$js;


		if(is_array($widgets))
			foreach ($widgets as $key => $widget_item)
				if(User::has_permission($widget_item->permission) && self::in_page($widget_item->menu_type , $widget_item->menus)){
					ob_start();
					$setting = json_decode(htmlspecialchars_decode($widget_item->setting));
					$widget = $widget_item;

					if(System::has_file('widgets/' . $widget_item->type . '/details.json')){
						$details = json_decode(file_get_contents(_WIDG . $widget_item->type . '/details.json'));

						if(isset($details->language))
							foreach ($details->language as $lang)
								if($lang->name == Language::$lang && System::has_file('languages/' . $lang->name . '/' . $lang->src))
									Language::add_ini_file(_LANG . $lang->name . '/' . $lang->src);
					} 
					else
						$details = false;

					// css and js before call
					self::$templates_read_file[$widget_item->position] = array('title' => '' , 'meta' => array() , 'css' => array() , 'js' => array());

					$title = Templates::$title;
					$meta = Templates::$meta;
					$css = Templates::$css;
					$js = Templates::$js;

					if(!$widget_item->show_name)
					{
						echo "<div class=\"widget-parent xa\">";
							echo "<h3>" . $widget_item->name . '</h3>';
							echo "<div class=\"widget xa\">";
					}

					require _WIDG . $widget_item->type . '/' . $widget_item->type . '.php';

					if(!$widget_item->show_name)
					{
							echo "</div>";
						echo "</div>";
					}

					self::$widgets[$widget_item->position] = ob_get_clean();

					// css and js diff before call
					$title_diff = Templates::$title;
					$meta_diff = Templates::$meta;
					$css_diff = Templates::$css;
					$js_diff = Templates::$js;

					// reset kardane fileha
					Templates::$title = $title;
					Templates::$meta = $meta;
					Templates::$css = $css;
					Templates::$js = $js;

					if($title != Templates::$title)
						self::$templates_read_file[$widget_item->position]['title'] = $title_diff;

					if(!empty($meta))
						foreach ($meta as $key => $value)
							if(isset($meta_diff[$key]))
								unset($meta_diff[$key]);

					self::$templates_read_file[$widget_item->position]['meta'] = $meta_diff;

					if(!empty($css))
						foreach ($css as $key => $value)
							if(isset($css_diff[$key]))
								unset($css_diff[$key]);

					self::$templates_read_file[$widget_item->position]['css'] = $css_diff;

					if(!empty($js))
						foreach ($js as $key => $value)
							if(isset($js_diff[$key]))
								unset($js_diff[$key]);

					self::$templates_read_file[$widget_item->position]['js'] = $js_diff;
				}
	}

	// khuruji dadane widget
	public static function get($position)
	{
		if(isset(self::$widgets[$position])){
			return self::$widgets[$position];
		}

		return '';
	}

	// aya widgeti dar in position vujud darad
	public static function exist_widget($position)
	{
		if(isset(self::$widgets[$position]))
			return true;

		return false;
	}

	// ezafe kardane  file ha va tagirate widget dar surate vujud dashtan
	public static function add_files_read($name)
	{
		if(isset(self::$templates_read_file[$name]))
		{
			$files = self::$templates_read_file[$name];

			if($files['title'] != "")
				Templates::$title = $files['title'];

			if(!empty($files['meta']))
				foreach($files['meta'] as $key => $value)
					Templates::$meta[$key] = $value;

			if(!empty($files['css']))
				foreach($files['css'] as $key => $value){
					if(Regex::cs($key , "numeric"))
						Templates::$css[] = $value;
					else
						Templates::$css[$key] = $value;
				}

			if(!empty($files['js']))
				foreach($files['js'] as $key => $value){
					if(Regex::cs($key , "numeric"))
						Templates::$js[] = $value;
					else
						Templates::$js[$key] = $value;
				}
		}
	}

	// aya dar in safhe ast
	private static function in_page($type , $menus)
	{
		if($type == 1)
			return true;
		else if($type == 2)
		{
			$ids = json_decode(htmlspecialchars_decode($menus) , true);
			if(is_array($ids) && in_array(self::$active_menu , $ids))
				return true;
		}
		else if($type == 3)
		{
			$ids = json_decode(htmlspecialchars_decode($menus) , true);
			if(is_array($ids) && !in_array(self::$active_menu , $ids))
				return true;
		}

		return false;
	}

	// set kardane menuye active
	private static function set_active_menu()
	{
		if(Site::$homepage || Preload::$active_menu_homepage != -1)
		{
			self::$active_menu_group = Preload::$active_menu_group_homepage;
			self::$active_menu = Preload::$active_menu_homepage;
		}
		else
		{
			$link = str_replace(Site::$base , "" , Site::$full_link);
			$link_out = '`link` = "' . $link . '"';

			if(mb_strpos($link , "/") !== false && Components::$name != "" && !is_array(Controller::$view) && Controller::$view != "")
			{
				if(isset(Components::$name) && User::has_permission(Components::$permission_id))
				{
					$link_b = explode('/' , $link);
					$link_out_b = 'index.php?component=' . Components::$name;
					$languages = $link_check = array();

					if(System::has_file('languages/' . Language::$lang . '/com_' . strtolower(Components::$name) . '_url.json'))
						$languages = json_decode(file_get_contents(_LANG . Language::$lang . '/com_' . strtolower(Components::$name) . '_url.json') , true);

					if(isset($languages['COM_' . strtoupper(Components::$name) . '_' . strtoupper(Components::$name) . '_' . strtoupper(Controller::$view)]))
						$link_check = $languages['COM_' . strtoupper(Components::$name) . '_' . strtoupper(Components::$name) . '_' . strtoupper(Controller::$view)];

					foreach ($link_b as $key => $value)
						if(in_array($value , $languages))
							unset($link_b[$key]);

					foreach ($link_b as $key => $value)
						if(isset($link_check[$key]))
							$link_out_b .= "&amp;" . $link_check[$key] . "=" . $value;

					$link_out .= ' OR `link` = "' . $link_out_b . '"';
				}
			}

			// gereftane menuye active
			$db = New Database;
			$db->table('menu')->where($link_out)->select()->process();
			$menu = $db->output();

			if(isset($menu[0]) && count($menu) == 1)
			{
				if(Templates::$title == '')
					Templates::$title = Language::_($menu[0]->name);

				self::$active_menu_group = $menu[0]->group;
				self::$active_menu = $menu[0]->id;
			}
		}
	}
}

$widgets = Widgets::get_instance();