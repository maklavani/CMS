<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/04/2015
	*	last edit		01/16/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

require_once _INC . 'output/lists.php';
require_once _INC . 'system/calendar.php';

$list = new Lists;
$list->name = 'category';
$list->action = Site::$full_link_text;
$list->method = 'post';
$list->component = 'content';
$list->head = array(
				'checkbox' => array('type' => 'checkbox' , 'size' => '5') , 
				'title' => array('type' => 'button' , 'size' => '40') , 
				'article' => array('type' => 'text' , 'size' => '20') , 
				'user' => array('type' => 'text' , 'size' => '25') , 
				'id' => array('type' => 'button' , 'size' => '10'));

if(!empty($params))
{
	$category = array();
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

	$db = new Database;
	$db->table('users')->where($total_id)->select()->process();
	$user = $db->output();
	$users = array();

	foreach ($user as $key => $value)
		$users[$value->id] = "<small>" . $value->name . " " . $value->family . "</small> (" . $value->username . ")";

	if(Language::$lang == 'fa-ir')
		$calendar = new Calendar('shamsi');
	else
		$calendar = new Calendar();

	foreach ($params as $key => $value) {
		$db->table('article')->select(false , 'count(*)')->where('`category` = ' . $value->id)->process();
		$article = $db->output('assoc');

		$category[] = 	array(
							'checkbox' => array('type' => 'checkbox' , 'value' => $value->id) , 
							'title' => array('type' => 'text' , 'value' => '<a href="' . Site::$base . _ADM . 'index.php?component=content&amp;view=category&amp;action=edit&amp;id=' . $value->id . '">' . $value->title . '</a>' , 'class' => $value->class) , 
							'article' => array('type' => 'text' , 'value' => number_format($article[0]['count(*)'])) , 
							'user' => array('type' => 'text' , 'value' => $users[$value->user]) , 
							'id' => array('type' => 'text' , 'value' => $value->id)
						);
	}

	$list->body = $category;
}

$list->output();