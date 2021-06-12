<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	07/25/2015
	*	last edit		01/16/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

require_once _INC . 'output/fields.php';

$db = new Database;
$db->table('category')->order('parent ASC , id ASC')->select()->process();
$category = $db->output();

if($category)
{
	$categories = $cat_def = array();
	foreach ($category as $value){
		$categories[$value->id] = Language::_($value->title);
		$cat_def[] = $value->id;
	}

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
						0 => array('type' => 'text' , 'name' => 'name') , 
						1 => array('type' => 'radio' , 'name' => 'status' , 'children' => array(0 => 'show' , 1 => 'hide')) , 
						2 => array('type' => 'list' , 'name' => 'category' , 'children' => $categories , 'language' => false , 'default' => $cat_def , 'attributes' => 'multiple'),
						3 => array('type' => 'text' , 'name' => 'count' , 'default' => 10),
						4 => array('type' => 'text' , 'name' => 'countdesc' , 'default' => 150),
						5 => array('type' => 'list' , 'name' => 'sort' , 'children' => $sort),
						6 => array('type' => 'image' , 'name' => 'image'), 
						7 => array('type' => 'textarea' , 'name' => 'description')
					);

	$pages['newsfeed'] = $newsfeed;
	$fields->pages = $pages;
	$fields->output();
}
else
{
	Messages::add_message('warning' , Language::_('COM_NEWSFEED_ERROR_FIRST_CHOOSE_CATEGORY'));
	Site::goto_link(Site::$base . _ADM . 'index.php?component=newsfeed&view=newsfeed');
}