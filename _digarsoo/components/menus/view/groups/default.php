<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/04/2015
	*	last edit		10/03/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

require_once _INC . 'output/lists.php';

$list = new Lists;
$list->name = 'menu_group';
$list->action = Site::$full_link_text;
$list->method = 'post';
$list->component = 'menus';
$list->head = array(
				'checkbox' => array('type' => 'checkbox' , 'size' => '5') , 
				'name' => array('type' => 'text' , 'size' => '25'),
				'show' => array('type' => 'text' , 'size' => '20'),
				'hide' => array('type' => 'text' , 'size' => '20'),
				'widget' => array('type' => 'text' , 'size' => '30'),
				);

$menu_group = array();

$db = new Database;

$db->table('widgets')->where('`type` = "menu"')->select()->process();
$widgets = $db->output();

foreach ($params as $key => $value) {
	$db->table('menu')->where('`group_number` = ' . $value->id)->select()->process();
	$menus = $db->output();
	$hide = 0;
	$show = 0;

	if($menus)
		foreach ($menus as $valueb)
			if($valueb->status)
				$hide++;
			else
				$show++;

	if($widgets)
	{
		$widgets_out = '';
		foreach ($widgets as $valueb)
		{
			$setting = json_decode(htmlspecialchars_decode($valueb->setting));
			if($setting->menu == $value->id)
				$widgets_out .= '&nbsp;<a href="' . Site::$base . _ADM .  'index.php?component=extension&view=widgets&action=edit&id=' . $valueb->id . '">' . $valueb->name . '</a>';
		}
	}
	
	if($widgets_out == '')
		$widgets_out = '<a href="' . Site::$base . _ADM . 'index.php?component=extension&view=widgets&action=new&widget=4101&wd_menu=' . $value->id . '">' . Language::_('COM_MENUS_ADD_WIDGET') . '</a>';

	$menu_group[] = array(
						'checkbox' => array('type' => 'checkbox' , 'value' => $value->id) , 
						'title' => array('type' => 'text' , 'value' => '<a href="' . Site::$base . _ADM . 'index.php?component=menus&amp;view=group&amp;id=' . $value->id . '">' . Language::_($value->name) . '</a>') , 
						'show' => array('type' => 'text' , 'value' => $show) , 
						'hide' => array('type' => 'text' , 'value' => $hide) , 
						'widget' => array('type' => 'text' , 'value' => $widgets_out)
					);
}

$list->body = $menu_group;
$list->output();