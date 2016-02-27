<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	08/16/2015
	*	last edit		08/16/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

require_once _INC . 'output/lists.php';

$list = new Lists;
$list->name = 'plugins';
$list->action = Site::$full_link_text;
$list->method = 'post';
$list->component = 'extension';
$list->head = array(
				'checkbox' => array('type' => 'checkbox' , 'size' => '5') , 
				'name' => array('type' => 'button' , 'size' => '25') ,  
				'category' => array('type' => 'button' , 'size' => '15') , 
				'location' => array('type' => 'button' , 'size' => '15') , 
				'id' => array('type' => 'button' , 'size' => '10'));

if(!empty($params))
{
	$plugins = array();

	foreach ($params as $key => $value) {
		$plugins[] = 	array(
							'checkbox' => array('type' => 'checkbox' , 'value' => $value->id) , 
							'name' => array('type' => 'text' , 'value' => '<a href="' . Site::$base . _ADM . 'index.php?component=extension&amp;view=plugins&amp;action=edit&amp;id=' . $value->id . '">' . $value->name . '</a>') , 
							'category' => array('type' => 'text' , 'value' => $value->category) , 
							'location' => array('type' => 'text' , 'value' => $value->location) , 
							'id' => array('type' => 'text' , 'value' => $value->id)
						);
	}

	$list->body = $plugins;
}

$list->output();