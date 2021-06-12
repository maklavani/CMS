<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	08/13/2015
	*	last edit		08/16/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

require_once _INC . 'output/lists.php';

$list = new Lists;
$list->name = 'languages';
$list->action = Site::$full_link_text;
$list->method = 'post';
$list->component = 'extension';
$list->head = array(
				'checkbox' => array('type' => 'checkbox' , 'size' => '5') , 
				'name' => array('type' => 'button' , 'size' => '40') , 
				'default_administrator' => array('type' => 'button' , 'size' => '15') , 
				'default_site' => array('type' => 'button' , 'size' => '15') , 
				'label' => array('type' => 'button' , 'size' => '15') , 
				'id' => array('type' => 'button' , 'size' => '10'));

if(!empty($params))
{
	$languages = array();

	foreach ($params as $key => $value) {
		$languages[] = 	array(
							'checkbox' => array('type' => 'checkbox' , 'value' => $value->id) , 
							'name' => array('type' => 'text' , 'value' => '<a href="' . Site::$base . _ADM . 'index.php?component=extension&amp;view=languages&amp;action=edit&amp;id=' . $value->id . '">' . $value->name . '</a>') , 
							'default_administrator' => array('type' => 'button' , 'value' => array("id" => $value->id , "status" => $value->default_administrator , 'icon' => 'star')) , 
							'default_site' => array('type' => 'button' , 'value' => array("id" => $value->id , "status" => $value->default_site , 'icon' => 'star')) , 
							'label' => array('type' => 'text' , 'value' => $value->label) , 
							'id' => array('type' => 'text' , 'value' => $value->id)
						);
	}

	$list->body = $languages;
}

$list->output();