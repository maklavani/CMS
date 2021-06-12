<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	07/09/2015
	*	last edit		11/15/2016
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
require_once _INC . 'system/calendar.php';

$fields = New Fields;
$fields->name = 'COM_USERS_USERS_FIELD_INPUT';
$fields->action = Site::$full_link_text;
$fields->method = 'post';

$pages = $users = $profile = $details =  array();

$images_check = json_decode(htmlspecialchars_decode($params[0]->image) , true);
if(!empty($images_check))
	foreach ($images_check as $key => $value) {
			$value_check = explode("/" , $value);
			if($value_check[0] == "download")
				$value_check[0] = 'uploads';
			$images_check[$key] = implode("/" , $value_check);
		}

$images = "";
if(is_array($images_check))
	$images = implode(',' , $images_check);

$users = array(
				0 => array('type' => 'text' , 'name' => 'name' , 'default' => $params[0]->name),
				1 => array('type' => 'text' , 'name' => 'family' , 'default' => $params[0]->family),
				2 => array('type' => 'radio' , 'name' => 'status' , 'default' => $params[0]->status , 'children' => array(0 => 'active' , 1 => 'deactivate')),
				3 => array('type' => 'text' , 'name' => 'username' , 'default' => $params[0]->username),
				4 => array('type' => 'list' , 'name' => 'group' , 'default' => $params[0]->group_number , 'children' => $groups , 'language' => false),
				5 => array('type' => 'password' , 'name' => 'password'),
				6 => array('type' => 'password' , 'name' => 'repassword'),
				7 => array('type' => 'text' , 'name' => 'email' , 'default' => $params[0]->email),
				8 => array('type' => 'text' , 'name' => 'mobile' , 'default' => $params[0]->mobile),
				9 => array('type' => 'image' , 'name' => 'image' , 'default' => $images , 'children' => array('source' => 'uploads/component/users/' . $params[0]->code . '/')),
				10 => array('type' => 'hidden' , 'name' => 'code' , 'default' => $params[0]->code)
			  );

$profile_default = json_decode(htmlspecialchars_decode($params[0]->profile));

$profile = array(
				0 => array('type' => 'text' , 'name' => 'tel' , 'default' => $profile_default->tel , 'children' => array('warning' => true)),
				1 => array('type' => 'textarea' , 'name' => 'address' , 'default' => $profile_default->address),
				2 => array('type' => 'textarea' , 'name' => 'favorites' , 'default' => $profile_default->favorites)
				);

if(Language::$lang == "fa-ir")
	$calendar = new Calendar('shamsi');
else
	$calendar = new Calendar();

$details = array(
				0 => array('type' => 'html' , 'name' => 'code' , 'default' => $params[0]->code),
				1 => array('type' => 'html' , 'name' => 'visit' , 'default' => ($params[0]->visit == "0000-00-00 00:00:00" ? Language::_('COM_USERS_NOT_LOGIN') : $calendar->convert($params[0]->visit , 'Y/m/d - H:i'))),
				2 => array('type' => 'html' , 'name' => 'register' , 'default' => $calendar->convert($params[0]->register , 'Y/m/d - H:i')),
				3 => array('type' => 'html' , 'name' => 'ip' , 'default' => ($params[0]->visit == "0000-00-00 00:00:00" ? Language::_('COM_USERS_NOT_LOGIN') : $params[0]->ip)),
				);

$pages['user'] = $users;
$pages['profile'] = $profile;
$pages['details'] = $details;
$fields->pages = $pages;
$fields->output();