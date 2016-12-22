<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	10/03/2016
	*	last edit		10/03/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

require_once _INC . 'output/lists.php';
require_once _INC . 'system/calendar.php';

if(Language::$lang == "fa-ir")
	$calendar = new Calendar('shamsi');
else
	$calendar = new Calendar();

$list = new Lists;
$list->name = 'redirects';
$list->action = Site::$full_link_text;
$list->method = 'post';
$list->component = 'redirects';
$list->head = array(
				'checkbox' => array('type' => 'checkbox' , 'size' => '5') , 
				'url' => array('type' => 'button' , 'size' => '30') , 
				'status' => array('type' => 'button' , 'size' => '10') , 
				'redirects' => array('type' => 'text' , 'size' => '25') , 
				'views' => array('type' => 'text' , 'size' => '10') , 
				'create_date' => array('type' => 'text' , 'size' => '10') , 
				'id' => array('type' => 'button' , 'size' => '10'));

if(!empty($params))
{
	$redirects = array();

	foreach ($params as $key => $value) {
		$redirects[] = 	array(
							'checkbox' => array('type' => 'checkbox' , 'value' => $value->id) , 
							'url' => array('type' => 'text' , 'value' => '<a href="' . Site::$base . _ADM . 'index.php?component=redirects&amp;view=redirects&amp;action=edit&amp;id=' . $value->id . '">' . $value->url . '</a>') , 
							'status' => array('type' => 'status' , 'value' => array("id" => $value->id , "status" => $value->status)) ,  
							'redirects' => array('type' => 'text' , 'value' => '<a href="' . Site::$base . _ADM . 'index.php?component=redirects&amp;view=redirects&amp;action=edit&amp;id=' . $value->id . '">' . $value->redirect_to . '</a>') , 
							'views' => array('type' => 'text' , 'value' => number_format($value->views)) , 
							'create_date' => array('type' => 'text' , 'value' => $calendar->convert($value->create_date , 'Y-m-d H:i:s')) , 
							'id' => array('type' => 'text' , 'value' => $value->id)
						);
	}

	$list->body = $redirects;
}

$list->output();