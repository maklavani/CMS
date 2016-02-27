<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/20/2015
	*	last edit		12/03/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

require_once _INC . 'output/lists.php';

$list = new Lists;
$list->name = 'newsfeed';
$list->action = Site::$full_link_text;
$list->method = 'post';
$list->component = 'newsfeed';
$list->head = array(
				'checkbox' => array('type' => 'checkbox' , 'size' => '5') , 
				'name' => array('type' => 'button' , 'size' => '75') , 
				'status' => array('type' => 'button' , 'size' => '10') , 
				'id' => array('type' => 'button' , 'size' => '10'));

if(!empty($params))
{
	$newsfeed = array();

	foreach ($params as $key => $value) {
		$newsfeed[] = 	array(
							'checkbox' => array('type' => 'checkbox' , 'value' => $value->id) , 
							'title' => array('type' => 'text' , 'value' => '<a href="' . Site::$base . _ADM . 'index.php?component=newsfeed&amp;view=newsfeed&amp;action=edit&amp;id=' . $value->id . '">' . $value->name . '</a>') , 
							'status' => array('type' => 'status' , 'value' => array("id" => $value->id , "status" => $value->status)) ,  
							'id' => array('type' => 'text' , 'value' => $value->id)
						);
	}

	$list->body = $newsfeed;
}

$list->output();