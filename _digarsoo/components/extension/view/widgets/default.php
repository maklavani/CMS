<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/06/2015
	*	last edit		12/17/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

require_once _INC . 'output/lists.php';

$list = new Lists;
$list->name = 'widgets';
$list->action = Site::$full_link_text;
$list->method = 'post';
$list->component = 'extension';
$list->head = array(
				'checkbox' => array('type' => 'checkbox' , 'size' => '5') , 
				'name' => array('type' => 'button' , 'size' => '20') , 
				'status' => array('type' => 'button' , 'size' => '10') , 
				'position' => array('type' => 'button' , 'size' => '15') , 
				'menu_type' => array('type' => 'text' , 'size' => '12.5') , 
				'languages' => array('type' => 'text' , 'size' => '12.5') , 
				'type' => array('type' => 'text' , 'size' => '15') , 
				'id' => array('type' => 'button' , 'size' => '10'));

if(!empty($params))
{
	$widgets = array();

	foreach ($params as $key => $value) {
		$widgets[] = 	array(
							'checkbox' => array('type' => 'checkbox' , 'value' => $value->id) , 
							'name' => array('type' => 'text' , 'value' => '<a href="' . Site::$base . _ADM . 'index.php?component=extension&amp;view=widgets&amp;action=edit&amp;id=' . $value->id . '">' . $value->name . '</a>') , 
							'status' => array('type' => 'status' , 'value' => array("id" => $value->id , "status" => $value->status)) , 
							'position' => array('type' => 'text' , 'value' => $value->position) , 
							'menu_type' => array('type' => 'text' , 'value' => menu_type($value->menu_type)) , 
							'languages' => array('type' => 'text' , 'value' => Language::_(strtoupper($value->languages))) , 
							'type' => array('type' => 'text' , 'value' => $value->type) , 
							'id' => array('type' => 'text' , 'value' => $value->id)
						);
	}

	$list->body = $widgets;
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