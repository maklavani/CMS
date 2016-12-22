<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/07/2015
	*	last edit		10/03/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

require_once _INC . 'output/lists.php';

$list = new Lists;
$list->name = 'users';
$list->action = Site::$full_link_text;
$list->method = 'post';
$list->component = 'users';
$list->head = array(
				'checkbox' => array('type' => 'checkbox' , 'size' => '5') , 
				'name' => array('type' => 'button' , 'size' => '10') , 
				'family' => array('type' => 'button' , 'size' => '15') , 
				'status' => array('type' => 'text' , 'size' => '10') , 
				'username' => array('type' => 'button' , 'size' => '15') ,
				'email' => array('type' => 'text' , 'size' => '25') , 
				'group' => array('type' => 'button' , 'size' => '10') ,
				'id' => array('type' => 'button' , 'size' => '10'));

if(!empty($params))
{
	$users = array();
	$db = new Database;

	// Group
	$ids = array();
	$total_id = "";

	foreach ($params as $value)
		if(!in_array($value->group_number , $ids))
			$ids[] = $value->group_number;

	foreach ($ids as $value) {
		if($total_id != "")
			$total_id .= " OR ";
		$total_id .= '`id` = ' . $value;
	}

	$db->table('group')->where($total_id)->select()->process();
	$group = $db->output();
	$groups = array();

	foreach ($group as $key => $value)
		$groups[$value->id] = Language::_($value->name);

	foreach ($params as $key => $value) {
		$users[] = 	array(
							'checkbox' => array('type' => 'checkbox' , 'value' => $value->id), 
							'name' => array('type' => 'text' , 'value' => '<a href="' . Site::$base . _ADM . 'index.php?component=users&amp;view=users&amp;action=edit&amp;id=' . $value->id . '">' . $value->name . '</a>') ,
							'family' => array('type' => 'text' , 'value' => '<a href="' . Site::$base . _ADM . 'index.php?component=users&amp;view=users&amp;action=edit&amp;id=' . $value->id . '">' . $value->family . '</a>') ,
							'status' => array('type' => 'status' , 'value' => array("id" => $value->id , "status" => $value->status)) , 
							'username' => array('type' => 'text' , 'value' => '<a href="' . Site::$base . _ADM . 'index.php?component=users&amp;view=users&amp;action=edit&amp;id=' . $value->id . '">' . $value->username . '</a>'),
							'email' => array('type' => 'text' , 'value' => $value->email),
							'group' => array('type' => 'text' , 'value' => $groups[$value->group_number]),
							'id' => array('type' => 'text' , 'value' => $value->id)
						);
	}

	$list->body = $users;
}

$list->output();