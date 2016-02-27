<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/09/2015
	*	last edit		12/03/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

$db = new Database;
$db->table('group')->select()->process();
$group = $db->output();

$groups = array();
foreach ($group as $key => $value)
	if(!in_array($value->id , array(1 , 2)))
		$groups[$value->id] = Language::_($value->name);

require_once _INC . 'output/fields.php';

$fields = New Fields;
$fields->name = 'COM_USERS_PERMISSIONS_FIELD_INPUT';
$fields->action = Site::$full_link_text;
$fields->method = 'post';
$pages = $permissions =  array();
$permissions = array(0 => array('type' => 'text' , 'name' => 'name') , 1 => array('type' => 'list' , 'name' => 'groups' , 'children' => $groups , 'attributes' => 'multiple' , 'language' => false));
$pages['permissions'] = $permissions;
$fields->pages = $pages;
$fields->output();