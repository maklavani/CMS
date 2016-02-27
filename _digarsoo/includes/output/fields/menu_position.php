<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Makalvani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	06/15/2015
	*	last edit		08/04/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class Menu_positionField {
	private static $menus;

	public static function output($name , $tabindex , $default , $children , $attributes , $fields_name , $placeholder)
	{
		static::$menus = array();

		static::$menus[0] = array('node' => array('name' => Language::_('ROOT') , 'parent' => -1 , 'index' => 0) , 'child' => array() , 'class' => 'level-0');
		if(!empty($children))
			static::create_menu($children , static::$menus[0]['child'] , 0 , 0);

		Templates::package('menu_position');

		if(isset($_POST['field_input_parent']) && isset($_POST['field_input_index']))
			$default = array('parent' => $_POST['field_input_parent'] , 'index' => $_POST['field_input_index']);
		else if(isset($_GET['field_input_parent']) && isset($_GET['field_input_index']))
			$default = array('parent' => $_GET['field_input_parent'] , 'index' => $_GET['field_input_index']);
		else if(!is_array($default))
		{
			$index = 1;

			if(!empty(static::$menus[0]['child']))
				$index = count(static::$menus[0]['child']) + 1;

			$default = array('parent' => 0 , 'index' => $index);
		}


		$output = "<input type=\"hidden\" name=\"field_input_parent\" value=\"" . $default['parent'] . "\">";
		$output .= "<input type=\"hidden\" name=\"field_input_index\" value=\"" . $default['index'] . "\">";

		$output .= "<div class=\"menu_position xa\" after_text=\"" . Language::_('AFTER') . "\" in_text=\"" . Language::_('IN') . "\" self_text=\"" . Language::_('MENU') . "\">";
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
				static::create_menu($menu , $menus[$key]['child'] , $key , $level + 1);

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
				
				$output .= "<li class=\"item-" . $key . " " . $value['class'] . "\" item=\"" . $key . "\" parent=\"" . $value['node']['parent'] . "\" index=\"" . $value['node']['index'] . "\">";
				$output .= "<div class=\"position_button\">" . $value['node']['name'] . '</div>';

				if($outputb != '')
					$output .= "<ul>" . $outputb . "</ul>";

				$output .= "</li>";
			}

		return $output;
	}
}