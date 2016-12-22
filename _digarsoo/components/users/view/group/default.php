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
$list->name = 'group';
$list->action = Site::$full_link_text;
$list->method = 'post';
$list->component = 'users';
$list->head = array(
				'checkbox' => array('type' => 'checkbox' , 'size' => '5') , 
				'name' => array('type' => 'button' , 'size' => '85') , 
				'id' => array('type' => 'button' , 'size' => '10'));

if(!empty($params))
{
	$group = array();

	foreach ($params as $key => $value) {
		$icon = '';
		if($value->lock_key)
				$icon = 'icon-lock';

		$group[] = 	array(
							'checkbox' => array('type' => 'checkbox' , 'value' => $value->id), 
							'name' => array('type' => 'text' , 'value' => '<a href="' . Site::$base . _ADM . 'index.php?component=users&amp;view=group&amp;action=edit&amp;id=' . $value->id . '">' . Language::_($value->name) . '</a>' , 'class' => $value->class , 'icon' => $icon) ,
							'id' => array('type' => 'text' , 'value' => $value->id)
						);
	}

	$list->body = $group;
}

$list->output();