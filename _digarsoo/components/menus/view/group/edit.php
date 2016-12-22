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

// Permission
$db = new Database;
$db->table('permissions')->order('id DESC')->select()->process();
$permission = $db->output();

$permissions = array();
foreach ($permission as $key => $value)
	$permissions[$value->id] = Language::_($value->name);

// languages
$db->table('languages')->order('id ASC')->select()->process();
$language = $db->output();
$languages = array();

$languages['all'] = Language::_('ALL');
foreach ($language as $value)
	$languages[$value->label] = Language::_($value->label);

// menus
$db->table('menu')->where('`group_number` = ' . $params[0]->group_number)->order('`index_number` ASC , `parent` ASC')->select()->process();
$menu = $db->output();

$menus = array();
if($menu)
	foreach($menu as $value)
		if($value->id != $params[0]->id && $value->parent != $params[0]->id)
			$menus[$value->id] = array('name' => Language::_($value->name) , 'parent' => $value->parent , 'index' => $value->index_number);

require_once _INC . 'output/fields.php';

$fields = New Fields;
$fields->name = 'COM_MENUS_GROUP_FIELD_INPUT';
$fields->action = Site::$full_link_text;
$fields->method = 'post';

$pages = $category = $position = $show = $details = array();

$category = array(
					0 => array('type' => 'text' , 'name' => 'name' , 'default' => $params[0]->name) , 
					1 => array('type' => 'menu' , 'name' => 'link' , 'default' => array('link' => htmlspecialchars_decode($params[0]->link) , 'type' => $params[0]->type)) , 
					2 => array('type' => 'radio' , 'name' => 'status' , 'default' => $params[0]->status , 'children' => array('0' => 'show' , '1' => 'hide')) , 
					3 => array('type' => 'radio' , 'name' => 'homepage' , 'default' => ($params[0]->homepage == 1 ? "show" : "hide") , 'children' => array('show' => 'show' , 'hide' => 'hide')) , 
					4 => array('type' => 'list' , 'name' => 'permission' , 'default' => $params[0]->permission , 'children' => $permissions , 'language' => false , 'default' => 5),
					5 => array('type' => 'list' , 'name' => 'languages' , 'default' => $params[0]->languages , 'children' => $languages , 'language' => false),
					6 => array('type' => 'icons' , 'name' => 'icon' , 'default' => $params[0]->icon)
				);

$position = array(0 => array('type' => 'menu_position' , 'name' => 'position' , 'default' => array('parent' => $params[0]->parent , 'index' => $params[0]->index_number) , 'children' => $menus));
$setting = json_decode(htmlspecialchars_decode($params[0]->setting));

$show = array(
				0 => array('type' => 'text' , 'name' => 'alias' , 'default' => $params[0]->alias) , 
				1 => array('type' => 'text' , 'name' => 'title' , 'default' => (isset($setting->title) ? $setting->title : "")) , 
				2 => array('type' => 'radio' , 'name' => 'show_status' , 'default' => (isset($setting->show_status) ? $setting->show_status : 1) , 'children' => array('0' => 'show' , '1' => 'hide')) , 
				3 => array('type' => 'text' , 'name' => 'class' , 'default' => (isset($setting->class) ? $setting->class : ""))
			);

$meta = array(
				0 => array('type' => 'radio' , 'name' => 'robots_index' , 'default' => $params[0]->robots_index , 'children' => array('off' => 'off' , 'index' => 'index' , 'noindex' => 'noindex') , 'language' => false) , 
				1 => array('type' => 'radio' , 'name' => 'robots_follow' , 'default' => $params[0]->robots_follow , 'children' => array('off' => 'off' , 'follow' => 'follow' , 'nofollow' => 'nofollow') , 'language' => false) , 
				2 => array('type' => 'textarea' , 'name' => 'meta_tag' , 'default' => $params[0]->meta_tag) , 
				3 => array('type' => 'textarea' , 'name' => 'meta_description' , 'default' => $params[0]->meta_description)
			);

$details = array(0 => array('type' => 'html' , 'name' => 'location' , 'default' => $params[0]->location));

$pages['menu'] = $category;
$pages['position'] = $position;
$pages['show_b'] = $show;
$pages['meta'] = $meta;
$pages['details'] = $details;
$fields->pages = $pages;
$fields->output();