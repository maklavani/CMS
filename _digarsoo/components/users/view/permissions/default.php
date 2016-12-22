<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/08/2015
	*	last edit		10/03/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

require_once _INC . 'output/lists.php';

$list = new Lists;
$list->name = 'permissions';
$list->action = Site::$full_link_text;
$list->method = 'post';
$list->component = 'users';
$list->head = array(
				'checkbox' => array('type' => 'checkbox' , 'size' => '5') , 
				'name' => array('type' => 'button' , 'size' => '25') , 
				'groups' => array('type' => 'button' , 'size' => '60') , 
				'id' => array('type' => 'button' , 'size' => '10'));

if(!empty($params))
{
	$permission = array();
	$db = new Database;

	// Groups
	$db->table('group')->select()->process();
	$group = $db->output();

	$groups = array();
	foreach ($group as $key => $value)
		$groups[$value->id] = Language::_($value->name);

	foreach ($params as $key => $value) {
		$icon = '';
		if($value->lock_key)
			$icon = 'icon-lock';

		$permission[] = array(
							'checkbox' => array('type' => 'checkbox' , 'value' => $value->id) , 
							'name' => array('type' => 'text' , 'value' => '<a href="' . Site::$base . _ADM . 'index.php?component=users&amp;view=permissions&amp;action=edit&amp;id=' . $value->id . '">' . Language::_($value->name) . '</a>' , 'icon' => $icon) ,
							'groups' => array('type' => 'text' , 'value' => Gropus($value->groups , $groups)) , 
							'id' => array('type' => 'text' , 'value' => $value->id)
						);
	}

	$list->body = $permission;
}

$list->output();

function Gropus($groups , $values)
{
	$ids = explode(',' , $groups);
	$output = "";

	foreach ($ids as $value) {
		$output .= "<div class=\"div-item\">" . $values[$value] . "</div>";
	}

	return $output;
}