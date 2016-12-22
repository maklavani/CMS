<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/04/2015
	*	last edit		10/06/2016
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
$db->table('menu')->where('`group_number` = ' . $params[0]->id)->order('`index_number` ASC , `parent` ASC')->select()->process();
$menu = $db->output();

$menus = array();
if($menu)
	foreach($menu as $value)
		$menus[$value->id] = array('name' => Language::_($value->name) , 'parent' => $value->parent , 'index' => $value->index_number);

require_once _INC . 'output/fields.php';

$fields = New Fields;
$fields->name = 'COM_MENUS_GROUP_FIELD_INPUT';
$fields->action = Site::$full_link_text;
$fields->method = 'post';

$pages = $category = $position = $show = $meta = array();

$category = array(
					0 => array('type' => 'text' , 'name' => 'name') , 
					1 => array('type' => 'menu' , 'name' => 'link') , 
					2 => array('type' => 'radio' , 'name' => 'status' , 'default' => '0' , 'children' => array('0' => 'show' , '1' => 'hide')) , 
					3 => array('type' => 'radio' , 'name' => 'homepage' , 'default' => 'hide' , 'children' => array('show' => 'show' , 'hide' => 'hide')) , 
					4 => array('type' => 'list' , 'name' => 'permission' , 'children' => $permissions , 'language' => false , 'default' => 5),
					5 => array('type' => 'list' , 'name' => 'languages' , 'children' => $languages , 'language' => false , 'default' => 'all'),
					6 => array('type' => 'icons' , 'name' => 'icon')
				);

$position = array(0 => array('type' => 'menu_position' , 'name' => 'position' , 'children' => $menus));

$show = array(
				0 => array('type' => 'text' , 'name' => 'alias') , 
				1 => array('type' => 'text' , 'name' => 'title') , 
				2 => array('type' => 'radio' , 'name' => 'show_status' , 'default' => '1' , 'children' => array('0' => 'show' , '1' => 'hide')) , 
				3 => array('type' => 'text' , 'name' => 'class')
			);

$meta = array(
				0 => array('type' => 'radio' , 'name' => 'robots_index' , 'default' => 'off' , 'children' => array('off' => 'off' , 'index' => 'index' , 'noindex' => 'noindex') , 'language' => false) , 
				1 => array('type' => 'radio' , 'name' => 'robots_follow' , 'default' => 'off' , 'children' => array('off' => 'off' , 'follow' => 'follow' , 'nofollow' => 'nofollow') , 'language' => false) , 
				2 => array('type' => 'textarea' , 'name' => 'meta_tag') , 
				3 => array('type' => 'textarea' , 'name' => 'meta_description')
			);

$pages['menu'] = $category;
$pages['position'] = $position;
$pages['show_b'] = $show;
$pages['meta'] = $meta;
$fields->pages = $pages;
$fields->output();