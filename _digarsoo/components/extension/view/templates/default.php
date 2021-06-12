<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	08/13/2015
	*	last edit		08/15/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

require_once _INC . 'output/lists.php';

$list = new Lists;
$list->name = 'templates';
$list->action = Site::$full_link_text;
$list->method = 'post';
$list->component = 'extension';
$list->head = array(
				'checkbox' => array('type' => 'checkbox' , 'size' => '5') , 
				'name' => array('type' => 'button' , 'size' => '45') , 
				'showing' => array('type' => 'text' , 'size' => '20') , 
				'location' => array('type' => 'button' , 'size' => '20') , 
				'id' => array('type' => 'button' , 'size' => '10'));

if(!empty($params))
{
	$templates = array();

	foreach ($params as $key => $value) {
		$templates[] = 	array(
							'checkbox' => array('type' => 'checkbox' , 'value' => $value->id) , 
							'name' => array('type' => 'text' , 'value' => '<a href="' . Site::$base . _ADM . 'index.php?component=extension&amp;view=templates&amp;action=edit&amp;id=' . $value->id . '">' . $value->name . '</a>') , 
							'showing' => array('type' => 'button' , 'value' => array("id" => $value->id , "status" => $value->showing , "icon" => "eye")) , 
							'location' => array('type' => 'text' , 'value' => $value->location) , 
							'id' => array('type' => 'text' , 'value' => $value->id)
						);
	}

	$list->body = $templates;
}

$list->output();

function menu_type($type)
{
	if($type == 1)
		return Language::_('COM_EXTENSION_ALL_PAGE');
	else if($type == 2)
		return Language::_('COM_EXTENSION_ALL_PAGE_SELECTED');
	else
		return Language::_('COM_EXTENSION_ALL_PAGE_NOT_SELECTED');
}