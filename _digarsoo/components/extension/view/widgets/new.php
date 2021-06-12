<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	08/15/2015
	*	last edit		01/12/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

require_once _INC . 'output/fields.php';

$db = new Database;

$db->table('extension')->where('`id` = ' . $_GET['widget'])->select()->process();
$widget = $db->output();

$read_details = json_decode(file_get_contents(_SRC_SITE . 'widgets/' . $widget[0]->name . '/details.json') , true);

if(isset($read_details['language']))
	foreach ($read_details['language'] as $lang)
		if($lang['name'] == Language::$lang && System::has_file('languages/' . $lang['name'] . '/' . $lang['src']))
			Language::add_ini_file(_SRC_SITE . 'languages/' . $lang['name'] . '/' . $lang['src']);

$db->table('permissions')->select()->process();
$permission = $db->output();
$permissions = array();
foreach ($permission as $value)
	$permissions[$value->id] = Language::_($value->name);

$db->table('templates')->where('`showing` = 1 AND `location` = "site"')->select()->process();
$template = $db->output();

$positions = array();

if(System::has_file('templates/' . $template[0]->type . '/details.json'))
{
	$languages = array();
	$details = json_decode(file_get_contents(_SRC_SITE . 'templates/' . $template[0]->type . '/details.json'));

	if(isset($details->language))
		foreach ($details->language as $lang)
			if($lang->name == Language::$lang && System::has_file('languages/' . $lang->name . '/' . $lang->src))
				Language::add_ini_file(_SRC_SITE . 'languages/' . $lang->name . '/' . $lang->src);

	if(isset($details->positions))
		foreach ($details->positions as $position)
			$positions[$position] = Language::_('TEMP_' . strtoupper($template[0]->type . '_' . $position)) . " (" . $position . ")";
}

$db->table('languages')->select()->process();
$language = $db->output();
$languages = array('all' => Language::_('ALL'));

foreach ($language as $value)
	$languages[$value->label] = $value->label;

$fields = New Fields;
$fields->name = 'COM_EXTENSION_WIDGETS_FIELD_INPUT';
$fields->action = Site::$full_link_text;
$fields->method = 'post';
$pages = $details = $menus = $widget_details = array();

$details = array(
				0 => array('type' => 'text' , 'name' => 'name') , 
				1 => array('type' => 'radio' , 'name' => 'status' , 'children' => array(0 => 'show' , 1 => 'hide')) , 
				2 => array('type' => 'radio' , 'name' => 'show_name' , 'default' => 1 , 'children' => array(0 => 'show' , 1 => 'hide')) , 
				3 => array('type' => 'list' , 'name' => 'permission' , 'default' => 5 , 'children' => $permissions , 'language' => false) , 
				4 => array('type' => 'list' , 'name' => 'position' , 'default' => 5 , 'children' => $positions , 'language' => false) , 
				5 => array('type' => 'list' , 'name' => 'languages' , 'children' => $languages , 'language' => false) , 
				6 => array('type' => 'hidden' , 'name' => 'type' , 'default' => $widget[0]->name) , 
				7 => array('type' => 'hidden' , 'name' => 'widget' , 'default' => $_GET['widget'])
			);

$menu_type = array(1 => Language::_('COM_EXTENSION_ALL_PAGE') , 2 => Language::_('COM_EXTENSION_ALL_PAGE_SELECTED') , 3 => Language::_('COM_EXTENSION_ALL_PAGE_NOT_SELECTED'));

$menus = array(
				0 => array('type' => 'list' , 'name' => 'menu_type' , 'children' => $menu_type , 'language' => false) , 
				1 => array('type' => 'menu_select' , 'name' => 'menus')
			);

if(isset($read_details['fields']))
{
	foreach ($read_details['fields'] as $key => $value){
		$widget_details[$key] = array();

		$name = "";
		if(isset($value['name']))
			$name = strtoupper($value['name']);

		foreach ($value as $keyb => $valueb) {
			if($keyb == 'name')
				$widget_details[$key][$keyb] = 'wd_' . $valueb;
			else
				$widget_details[$key][$keyb] = $valueb;

			$widget_details[$key]['language'] = 'WID_' . strtoupper($widget[0]->name);
			$widget_details[$key]['name_string'] = Language::_('WID_' . strtoupper($widget[0]->name) . '_' . $name);
		}

		if(isset($value['name']) && isset($_GET['wd_menu']) && Regex::cs($_GET['wd_menu'] , "numeric") && $value['name'] == 'menu')
			$widget_details[$key]['default'] = $_GET['wd_menu'];
	}
}
else
	$widget_details = array(0 => array('type' => 'html' , 'default' => Language::_('COM_EXTENSION_NO_ITEM') , 'placeholder' => false , 'show_label' => true));


$pages['details'] = $details;
$pages['menus'] = $menus;
$pages['widget_details'] = $widget_details;

$fields->pages = $pages;
$fields->output();