<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/04/2015
	*	last edit		10/03/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

require_once _INC . 'output/lists.php';

$list = new Lists;
$list->name = 'group';
$list->action = Site::$full_link_text;
$list->method = 'post';
$list->component = 'menus';
$list->head = array(
				'checkbox' => array('type' => 'checkbox' , 'size' => '5') , 
				'name' => array('type' => 'button' , 'size' => '42.5') , 
				'status' => array('type' => 'button' , 'size' => '5') , 
				'homepage' => array('type' => 'button' , 'size' => '5') , 
				'languages' => array('type' => 'button' , 'size' => '7.5') , 
				'index_number' => array('type' => 'button' , 'size' => '5') ,
				'id' => array('type' => 'button' , 'size' => '5'));

if(!empty($params))
{
	$category = array();

	foreach ($params as $key => $value) {
		$category[] = 	array(
							'checkbox' => array('type' => 'checkbox' , 'value' => $value->id) , 
							'title' => array('type' => 'text' , 'value' => '<a href="' . Site::$base . _ADM . 'index.php?component=menus&amp;view=group&amp;action=edit&amp;id=' . $_GET['id'] . '&amp;menu_id=' . $value->id . '">' . Language::_($value->name) . '</a>' , 'class' => $value->class) , 
							'status' => array('type' => 'status' , 'value' => array("id" => $value->id , "status" => $value->status)) , 
							'homepage' => array('type' => 'button' , 'value' => array("id" => $value->id , "status" => $value->homepage , "icon" => "star")) , 
							'languages' => array('type' => 'text' , 'value' => Language::_(strtoupper($value->languages))) , 
							'index_number' => array('type' => 'text' , 'value' => $value->index_number) , 
							'id' => array('type' => 'text' , 'value' => $value->id)
						);
	}

	$list->body = $category;
}

$list->output();