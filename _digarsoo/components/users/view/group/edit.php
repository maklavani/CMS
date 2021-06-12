<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	07/09/2015
	*	last edit		12/03/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

$db = new Database;
$db->table('group')->order('parent ASC , id ASC')->select()->process();
$group = $db->output();

$groups = array();

if($group)
	Group($group , $groups , 0 , 0 , $params[0]->id);

require_once _INC . 'output/fields.php';

$fields = New Fields;
$fields->name = 'COM_USERS_GROUP_FIELD_INPUT';
$fields->action = Site::$full_link_text;
$fields->method = 'post';
$pages = $group =  array();
$group = array(0 => array('type' => 'text' , 'name' => 'name' , 'default' => $params[0]->name) , 1 => array('type' => 'list' , 'name' => 'parent' , 'default' => $params[0]->parent , 'children' => $groups , 'language' => false));
$pages['group'] = $group;
$fields->pages = $pages;
$fields->output();

function Group($group , &$groups , $parent , $level , $edit_parent)
{
	foreach ($group as $value)
		if($value->parent == $parent)
		{
			if(!isset($groups[$value->id]) && $value->id != $edit_parent){
				$groups[$value->id] = str_repeat(" __ " , $level) . Language::_($value->name);
				Group($group , $groups , $value->id , $level + 1 , $edit_parent);
			}
		}
}