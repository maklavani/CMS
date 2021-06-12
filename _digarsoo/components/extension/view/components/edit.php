<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	08/13/2015
	*	last edit		12/03/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

require_once _INC . 'output/fields.php';

$db = new Database;
$db->table('permissions')->select()->process();
$permission = $db->output();
$permissions = array();

foreach ($permission as $value)
	$permissions[$value->id] = Language::_($value->name);

$fields = New Fields;
$fields->name = 'COM_EXTENSION_COMPONENTS_FIELD_INPUT';
$fields->action = Site::$full_link_text;
$fields->method = 'post';
$pages = $components = array();
$components = array(
					0 => array('type' => 'text' , 'name' => 'name' , 'default' => $params[0]->name) ,  
					1 => array('type' => 'list' , 'name' => 'permission' , 'default' => $params[0]->permission , 'children' => $permissions , 'language' => false)
					);
$pages['components'] = $components;
$fields->pages = $pages;
$fields->output();