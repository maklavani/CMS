<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	08/13/2015
	*	last edit		08/17/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

require_once _INC . 'output/lists.php';

$list = new Lists;
$list->name = 'setting';
$list->action = Site::$full_link_text;
$list->method = 'post';
$list->component = 'extension';
$list->head = array(
				'checkbox' => array('type' => 'checkbox' , 'size' => '5') , 
				'name' => array('type' => 'button' , 'size' => '45') , 
				'type' => array('type' => 'button' , 'size' => '20') , 
				'location' => array('type' => 'button' , 'size' => '20') , 
				'id' => array('type' => 'button' , 'size' => '10'));

if(!empty($params))
{
	$setting = array();

	foreach ($params as $key => $value) {
		$icon = "";
		if($value->lock)
			$icon = 'icon-lock';

		$setting[] = 	array(
							'checkbox' => array('type' => 'checkbox' , 'value' => $value->id) , 
							'name' => array('type' => 'text' , 'value' => $value->name , 'icon' => $icon) , 
							'type' => array('type' => 'text' , 'value' => $value->type) , 
							'location' => array('type' => 'text' , 'value' => $value->location) , 
							'id' => array('type' => 'text' , 'value' => $value->id)
						);
	}

	$list->body = $setting;
}

$list->output();