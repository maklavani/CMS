<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	07/28/2015
	*	last edit		07/28/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

require_once _INC . 'output/lists.php';
require_once _INC . 'system/calendar.php';

$list = new Lists;
$list->name = 'banned';
$list->action = Site::$full_link_text;
$list->method = 'post';
$list->component = 'users';
$list->head = array(
				'checkbox' => array('type' => 'checkbox' , 'size' => '5') , 
				'ip' => array('type' => 'button' , 'size' => '10') , 
				'count' => array('type' => 'button' , 'size' => '15') , 
				'date' => array('type' => 'text' , 'size' => '15') , 
				'id' => array('type' => 'button' , 'size' => '10'));

if(!empty($params))
{
	$banned = array();

	if(Language::$lang == 'fa-ir')
		$calendar = new Calendar('shamsi');
	else
		$calendar = new Calendar();

	foreach ($params as $key => $value) {
		$banned[] = array(
						'checkbox' => array('type' => 'checkbox' , 'value' => $value->id), 
						'ip' => array('type' => 'text' , 'value' => '<a href="' . Site::$base . _ADM . 'index.php?component=users&amp;view=banned&amp;action=edit&amp;id=' . $value->id . '">' . $value->ip . '</a>') ,
						'count' => array('type' => 'text' , 'value' => $value->count) ,
						'date' => array('type' => 'text' , 'value' => $calendar->convert($value->date , 'y-m-d H:i')),
						'id' => array('type' => 'text' , 'value' => $value->id)
					);
	}

	$list->body = $banned;
}

$list->output();