<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	02/15/2016
	*	last edit		02/18/2016
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
$list->name = 'comments';
$list->action = Site::$full_link_text;
$list->method = 'post';
$list->component = 'content';
$list->head = array(
				'checkbox' => array('type' => 'checkbox' , 'size' => '5') , 
				'code' => array('type' => 'button' , 'size' => '20') , 
				'status' => array('type' => 'button' , 'size' => '7.5') , 
				'name' => array('type' => 'button' , 'size' => '10') , 
				'comment' => array('type' => 'button' , 'size' => '15') , 
				'article' => array('type' => 'button' , 'size' => '22.5') , 
				'create' => array('type' => 'button' , 'size' => '12.5') , 
				'id' => array('type' => 'button' , 'size' => '7.5'));

if(!empty($params))
{
	$comments = array();
	$db = new Database;

	// Article
	$total_id = "";

	foreach ($params as $value) {
		if($total_id != "")
			$total_id .= " OR ";
		$total_id .= '`id` = ' . $value->article;
	}

	$db->table('article')->where($total_id)->select()->process();
	$article = $db->output();
	$articles = array();

	foreach ($article as $key => $value)
		$articles[$value->id] = $value->title . " <small>(" . $value->code . ")</small>";

	foreach ($params as $key => $value)
		$comments[] = 	array(
							'checkbox' => array('type' => 'checkbox' , 'value' => $value->id) , 
							'code' => array('type' => 'text' , 'value' => '<a href="' . Site::$base . _ADM . 'index.php?component=content&amp;view=comments&amp;action=edit&amp;id=' . $value->id . '">' . $value->code . '</a>' , "class" => $value->class) , 
							'status' => array('type' => 'status' , 'value' => array("id" => $value->id , "status" => $value->status)) , 
							'name' => array('type' => 'text' , 'value' => $value->name) , 
							'comment' => array('type' => 'text' , 'value' => $value->comment) , 
							'article' => array('type' => 'text' , 'value' => $articles[$value->article]) , 
							'create' => array('type' => 'text' , 'value' => $calendar->convert($value->publish_date , 'Y-m-d H:i')) , 
							'id' => array('type' => 'text' , 'value' => $value->id)
						);

	$list->body = $comments;
}

$list->output();