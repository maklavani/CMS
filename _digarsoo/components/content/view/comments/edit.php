<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	02/15/2016
	*	last edit		02/15/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

require_once _INC . 'output/fields.php';
require_once _INC . 'system/calendar.php';

if(Language::$lang == "fa-ir")
	$calendar = new Calendar('shamsi');
else
	$calendar = new Calendar();

$db = new Database();
$db->table("comments")->select()->where("`article` = " . $params[0]->article . " AND `code` != '" . $params[0]->code . "'")->process();
$comment = $db->output();

$comments = array();
$comments[0] = Language::_("COM_CONTENT_NONE_PARENT");
if($comment)
	comments_sort($comment , $comments , 0 , 0 , -1);

$fields = New Fields;
$fields->name = 'COM_CONTENT_COMMENTS_FIELD_INPUT';
$fields->action = Site::$full_link_text;
$fields->method = 'post';

$page = array();

$comments = array(
					0 => array('type' => 'radio' , 'name' => 'status' , 'default' => $params[0]->status , 'children' => array(0 => 'show' , 1 => 'hide')) , 
					1 => array('type' => 'list' , 'name' => 'parent' , 'default' => $params[0]->parent , 'children' => $comments , 'language' => false),
					2 => array('type' => 'text' , 'name' => 'name' , 'default' => $params[0]->name) , 
					3 => array('type' => 'text' , 'name' => 'email' , 'default' => $params[0]->email) , 
					4 => array('type' => 'textarea' , 'name' => 'comment' , 'default' => $params[0]->comment) , 
					5 => array('type' => 'hidden' , 'name' => 'code' , 'default' => $params[0]->code)
				);

$details = array(
					0 => array("type" => "html" , "name" => "code" , "default" => $params[0]->code) , 
					1 => array("type" => "html" , "name" => "publish" , "default" => $calendar->convert($params[0]->publish_date , 'Y/m/d - H:i')) , 
					2 => array("type" => "html" , "name" => "create" , "default" => $calendar->convert($params[0]->create_date , 'Y/m/d - H:i')) , 
					3 => array("type" => "html" , "name" => "likes" , "default" => number_format($params[0]->likes)) , 
					4 => array("type" => "html" , "name" => "dislikes" , "default" => number_format($params[0]->dislikes))
				);

$pages['comments'] = $comments;
$pages['details'] = $details;

$fields->pages = $pages;
$fields->output();

function comments_sort($comment , &$comments , $parent , $level , $edit_parent)
{
	foreach ($comment as $value)
		if($value->parent == $parent)
		{
			if(!isset($comments[$value->id]) && $value->id != $edit_parent){
				$comments[$value->id] = str_repeat("__ " , $level) . $value->code;
				comments_sort($comment , $comments , $value->id , $level + 1 , $edit_parent);
			}
		}
}