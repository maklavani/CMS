<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	06/29/2015
	*	last edit		01/16/2016
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
$list->name = 'article';
$list->action = Site::$full_link_text;
$list->method = 'post';
$list->component = 'content';
$list->head = array(
				'checkbox' => array('type' => 'checkbox' , 'size' => '5') , 
				'title' => array('type' => 'button' , 'size' => '30') , 
				'status' => array('type' => 'button' , 'size' => '7.5') , 
				'special' => array('type' => 'button' , 'size' => '7.5') , 
				'category' => array('type' => 'button' , 'size' => '10') , 
				'views' => array('type' => 'button' , 'size' => '7.5') , 
				'publish_date' => array('type' => 'button' , 'size' => '7.5') , 
				'user' => array('type' => 'text' , 'size' => '17.5') , 
				'id' => array('type' => 'button' , 'size' => '7.5'));

if(!empty($params))
{
	$article = array();
	$db = new Database;

	// Author
	$ids = array();
	$total_id = "";

	foreach ($params as $value)
		if(!in_array($value->user , $ids))
			$ids[] = $value->user;

	foreach ($ids as $value) {
		if($total_id != "")
			$total_id .= " OR ";
		$total_id .= '`id` = ' . $value;
	}

	$db->table('users')->where($total_id)->select()->process();
	$user = $db->output();
	$users = array();

	foreach ($user as $key => $value)
		$users[$value->id] = "<small>" . $value->name . " " . $value->family . "</small> (" . $value->username . ")";

	// Category
	$ids = array();
	$total_id = "";

	foreach ($params as $value)
		if(!in_array($value->category , $ids))
			$ids[] = $value->category;

	foreach ($ids as $value) {
		if($total_id != "")
			$total_id .= " OR ";
		$total_id .= '`id` = ' . $value;
	}

	$db->table('category')->where($total_id)->select()->process();
	$cat = $db->output();
	$categories = array();

	foreach ($cat as $key => $value)
		$categories[$value->id] = $value->title;

	foreach ($params as $key => $value) {
		$article[] = 	array(
							'checkbox' => array('type' => 'checkbox' , 'value' => $value->id) , 
							'title' => array('type' => 'text' , 'value' => '<a href="' . Site::$base . _ADM . 'index.php?component=content&amp;view=article&amp;action=edit&amp;id=' . $value->id . '">' . $value->title . '</a>') , 
							'status' => array('type' => 'status' , 'value' => array("id" => $value->id , "status" => $value->status)) , 
							'special' => array('type' => 'button' , 'value' => array("id" => $value->id , "status" => !$value->special , "icon" => "star")) , 
							'category' => array('type' => 'text' , 'value' => $categories[$value->category]) , 
							'views' => array('type' => 'text' , 'value' => $value->views) , 
							'publish_date' => array('type' => 'text' , 'value' => $calendar->convert($value->publish_date , 'Y-m-d')) , 
							'user' => array('type' => 'text' , 'value' => $users[$value->user]) , 
							'id' => array('type' => 'text' , 'value' => $value->id)
						);
	}

	$list->body = $article;
}

$list->output();