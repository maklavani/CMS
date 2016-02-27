<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	08/13/2015
	*	last edit		12/03/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

require_once _INC . 'output/lists.php';

$db = new Database;
$db->table('permissions')->select()->process();
$permission = $db->output();
$permissions = array();

foreach ($permission as $value)
	$permissions[$value->id] = Language::_($value->name);

$list = new Lists;
$list->name = 'components';
$list->action = Site::$full_link_text;
$list->method = 'post';
$list->component = 'extension';
$list->head = array(
				'checkbox' => array('type' => 'checkbox' , 'size' => '5') , 
				'name' => array('type' => 'button' , 'size' => '35') , 
				'status' => array('type' => 'text' , 'size' => '10') , 
				'type' => array('type' => 'text' , 'size' => '15') , 
				'permission' => array('type' => 'text' , 'size' => '15') , 
				'location' => array('type' => 'text' , 'size' => '10') , 
				'id' => array('type' => 'button' , 'size' => '10'));

if(!empty($params))
{
	$components = array();

	foreach ($params as $key => $value) {
		$components[] = 	array(
							'checkbox' => array('type' => 'checkbox' , 'value' => $value->id) , 
							'name' => array('type' => 'text' , 'value' => '<a href="' . Site::$base . _ADM . 'index.php?component=extension&amp;view=components&amp;action=edit&amp;id=' . $value->id . '">' . $value->name . '</a>') , 
							'status' => array('type' => 'status' , 'value' => array("id" => $value->id , "status" => $value->status)) , 
							'type' => array('type' => 'text' , 'value' => $value->type) , 
							'permission' => array('type' => 'text' , 'value' => $permissions[$value->permission]) , 
							'location' => array('type' => 'text' , 'value' => $value->location) , 
							'id' => array('type' => 'text' , 'value' => $value->id)
						);
	}

	$list->body = $components;
}

$list->output();