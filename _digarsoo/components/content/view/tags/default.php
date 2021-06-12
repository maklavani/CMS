<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	07/14/2015
	*	last edit		07/14/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

require_once _INC . 'output/lists.php';

$list = new Lists;
$list->name = 'tags';
$list->action = Site::$full_link_text;
$list->method = 'post';
$list->component = 'content';
$list->head = array(
				'checkbox' => array('type' => 'checkbox' , 'size' => '5') , 
				'name' => array('type' => 'button' , 'size' => '80') , 
				'id' => array('type' => 'button' , 'size' => '15'));

if(!empty($params))
{
	$tags = array();

	foreach ($params as $key => $value) {
		$tags[] = 	array(
							'checkbox' => array('type' => 'checkbox' , 'value' => $value->id) , 
							'name' => array('type' => 'text' , 'value' => '<a href="' . Site::$base . _ADM . 'index.php?component=content&amp;view=tags&amp;action=edit&amp;id=' . $value->id . '">' . $value->name . '</a>') , 
							'id' => array('type' => 'text' , 'value' => $value->id)
						);
	}

	$list->body = $tags;
}

$list->output();