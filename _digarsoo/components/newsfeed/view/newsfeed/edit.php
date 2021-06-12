<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	07/25/2015
	*	last edit		12/03/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

require_once _INC . 'output/fields.php';

$db = new Database;
$db->table('category')->order('parent ASC , id ASC')->select()->process();
$category = $db->output();

$categories = array();
foreach ($category as $value)
	$categories[$value->id] = Language::_($value->title);

$fields = New Fields;
$fields->name = 'COM_NEWSFEED_NEWSFEED_FIELD_INPUT';
$fields->action = Site::$full_link_text;
$fields->method = 'post';

$pages = array();

$sort = array(
			0 => "setting_last_publish",
			1 => "setting_first_publish",
			2 => "setting_alphabet_title_asc",
			3 => "setting_alphabet_title_desc",
			4 => "setting_views_desc",
			5 => "setting_views_asc",
			6 => "setting_last_id",
			7 => "setting_first_id"
		);

$newsfeed = array(
					0 => array('type' => 'text' , 'name' => 'name' , 'default' => $params[0]->name) , 
					1 => array('type' => 'radio' , 'name' => 'status' , 'default' => $params[0]->status , 'children' => array(0 => 'show' , 1 => 'hide')) , 
					2 => array('type' => 'list' , 'name' => 'category' , 'children' => $categories , 'language' => false , 'default' => json_decode(html_entity_decode($params[0]->category) , true) , 'attributes' => 'multiple'),
					3 => array('type' => 'text' , 'name' => 'count' , 'default' => $params[0]->count),
					4 => array('type' => 'text' , 'name' => 'countdesc' , 'default' => $params[0]->countdesc),
					5 => array('type' => 'list' , 'name' => 'sort' , 'default' => $params[0]->sort , 'children' => $sort),
					6 => array('type' => 'image' , 'name' => 'image' , 'default' => $params[0]->image),
					7 => array('type' => 'textarea' , 'name' => 'description' , 'default' => $params[0]->description)
				);

$pages['newsfeed'] = $newsfeed;
$fields->pages = $pages;
$fields->output();