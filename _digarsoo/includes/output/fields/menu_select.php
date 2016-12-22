<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Makalvani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	06/15/2015
	*	last edit		10/06/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class Menu_selectField {
	private static $menus;

	public static function output($name , $tabindex , $default , $children , $attributes , $fields_name , $placeholder)
	{
		Templates::package('menu_select');

		$db = new Database;
		static::$menus = array();

		$db->table('menu_group')->where('`location` = "site"')->select()->process();
		$menus = $db->output();

		if(!empty($menus))
			foreach ($menus as $key => $value) {
				$db->table('menu')->where('`group_number` = ' . $value->id)->order('`index_number` ASC , `parent` ASC')->select()->process();
				$menu = $db->output('assoc');

				static::$menus[$key] = array('node' => array('name' => Language::_($value->name) , 'parent' => -1 , 'index_number' => 0) , 'child' => array() , 'class' => 'level-0');
				if(!empty($menu))
					static::create_menu($menu , static::$menus[$key]['child'] , 0 , 0);
			}

		if(isset($_POST['field_input_menus']))
			$default = $_POST['field_input_menus'];
		else if(isset($_GET['field_input_menus']))
			$default = $_GET['field_input_menus'];

		$output = "<input type=\"hidden\" name=\"field_input_menus\" value=\"" . $default . "\">";
		$output .= "<div class=\"menu_select xa\" after_text=\"" . Language::_('AFTER') . "\" in_text=\"" . Language::_('IN') . "\" self_text=\"" . Language::_('MENU') . "\">";
		$output .= "<ul>" . static::output_menu(static::$menus) . "</ul>";
		$output .= "</div>";

		return $output;
	}

	private static function create_menu($menu , &$menus , $parent , $level)
	{
		foreach ($menu as $key => $value)
			if($value['parent'] == $parent)
			{
				$menus[$key] = array('node' => $value , 'child' => array() , 'class' => 'level-' . ($level + 1));
				static::create_menu($menu , $menus[$key]['child'] , $value['id'] , $level + 1);

				if(!empty($menus[$key]['child']))
					$menus[$key]['class'] .= ' parent showing ';	
			}
	}

	private static function output_menu($menus)
	{
		$output = '';

		if(!empty($menus))
			foreach ($menus as $key => $value){
				$outputb = static::output_menu($value['child']);
				
				$output .= "<li class=\"select-item item-" . $key . " " . $value['class'] . "\" ";
				if(isset($value['node']['id']))
					$output .= "item=\"" . $value['node']['id'] . "\"";

				$output .= " parent=\"" . $value['node']['parent'] . "\" index=\"" . $value['node']['index_number'] . "\">";
				$output .= "<input class=\"select_checkbox\" type=\"checkbox\"><div class=\"select_button\">" . Language::_($value['node']['name']) . '</div>';

				if($outputb != '')
					$output .= "<ul>" . $outputb . "</ul>";

				$output .= "</li>";
			}

		return $output;
	}
}