<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	07/07/2015
	*	last edit		01/30/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

$db = new Database;
$db->table('group')->select()->process();
$group = $db->output();

$groups = array();
foreach ($group as $key => $value)
	if(($value->id <= 6 && User::$group <= $value->id) || $value->id > 6)
		$groups[$value->id] = Language::_($value->name);

require_once _INC . 'output/fields.php';

$fields = New Fields;
$fields->name = 'COM_USERS_USERS_FIELD_INPUT';
$fields->action = Site::$full_link_text;
$fields->method = 'post';

$pages = $users = $profile =  array();
$users = array(
				0 => array('type' => 'text' , 'name' => 'name'),
				1 => array('type' => 'text' , 'name' => 'family'),
				2 => array('type' => 'radio' , 'name' => 'status' , 'default' => 0 , 'children' => array(0 => 'SHOW' , 1 => 'HIDE')),
				3 => array('type' => 'text' , 'name' => 'username'),
				4 => array('type' => 'list' , 'name' => 'group' , 'children' => $groups , 'language' => false),
				5 => array('type' => 'password' , 'name' => 'password'),
				6 => array('type' => 'password' , 'name' => 'repassword'),
				7 => array('type' => 'text' , 'name' => 'email'),
				8 => array('type' => 'text' , 'name' => 'mobile'),
				9 => array('type' => 'image' , 'name' => 'image' , 'children' => array('source' => 'uploads/'))
			  );

$profile = array(
				0 => array('type' => 'text' , 'name' => 'tel'),
				1 => array('type' => 'textarea' , 'name' => 'address'),
				2 => array('type' => 'textarea' , 'name' => 'favorites')
				);

$pages['user'] = $users;
$pages['profile'] = $profile;
$fields->pages = $pages;
$fields->output();