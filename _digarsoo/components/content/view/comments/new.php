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

$db = new Database;
$db->table('category')->order('parent ASC , id ASC')->select()->process();
$category = $db->output();

if($category)
{
	require_once _INC . 'output/fields.php';
	require_once _INC . 'system/calendar.php';

	if(Language::$lang == "fa-ir")
		$calendar = new Calendar('shamsi');
	else
		$calendar = new Calendar();

	$categories = array();
	if($category)
		Cats($category , $categories , 0 , 0 , -1);

	$db->table('permissions')->order('id DESC')->select()->process();
	$permission = $db->output();

	$permissions = array();
	foreach ($permission as $key => $value)
		$permissions[$value->id] = Language::_($value->name);

	$fields = New Fields;
	$fields->name = 'COM_CONTENT_ARTICLE_FIELD_INPUT';
	$fields->action = Site::$full_link_text;
	$fields->method = 'post';

	$pages = array();

	$article = array(
						0 => array('type' => 'text' , 'name' => 'title') , 
						1 => array('type' => 'radio' , 'name' => 'status' , 'default' => 0 , 'children' => array(0 => 'show' , 1 => 'hide')) , 
						2 => array('type' => 'radio' , 'name' => 'special' , 'default' => 1 , 'children' => array(0 => 'show' , 1 => 'hide')) , 
						3 => array('type' => 'list' , 'name' => 'category' , 'children' => $categories , 'language' => false),
						4 => array('type' => 'list' , 'name' => 'permission' , 'children' => $permissions , 'language' => false , 'default' => 5),
						5 => array('type' => 'tinymce' , 'name' => 'content' , 'children' => array('image' , 'article'))
					);

	$settings = array(
						0 => array("type" => "radio" , "name" => "setting_title" , "default" => 2 , "children" => array(0 => "show" , 1 => "hide" , 2 => "parent")) , 
						1 => array("type" => "radio" , "name" => "setting_heading" , "default" => 2 , "children" => array(0 => "show" , 1 => "hide" , 2 => "parent")) , 
						2 => array("type" => "radio" , "name" => "setting_author" , "default" => 2 , "children" => array(0 => "show" , 1 => "hide" , 2 => "parent")) , 
						3 => array("type" => "radio" , "name" => "setting_publish_date" , "default" => 2 , "children" => array(0 => "show" , 1 => "hide" , 2 => "parent")) , 
						4 => array("type" => "radio" , "name" => "setting_views" , "default" => 2 , "children" => array(0 => "show" , 1 => "hide" , 2 => "parent")) , 
						5 => array("type" => "radio" , "name" => "setting_tags" , "default" => 2 , "children" => array(0 => "show" , 1 => "hide" , 2 => "parent")) , 
						6 => array("type" => "radio" , "name" => "setting_likes" , "default" => 2 , "children" => array(0 => "show" , 1 => "hide" , 2 => "parent")) , 
						7 => array("type" => "list" , "name" => "setting_likes_permission" , "default" => 2 , "children" => array(0 => "all" , 1 => "just_users" , 2 => "parent")) , 
						8 => array("type" => "radio" , "name" => "setting_comments" , "default" => 2 , "children" => array(0 => "show" , 1 => "hide" , 2 => "parent")) , 
						9 => array("type" => "list" , "name" => "setting_comments_permission" , "default" => 2 , "children" => array(0 => "all" , 1 => "just_users" , 2 => "parent")) , 
						10 => array("type" => "radio" , "name" => "setting_comments_confirmation" , "default" => 2 , "children" => array(0 => "all" , 1 => "none" , 2 => "parent")) , 
						11 => array("type" => "radio" , "name" => "setting_code" , "default" => 2 , "children" => array(0 => "show" , 1 => "hide" , 2 => "parent")) , 
						12 => array("type" => "list" , "name" => "setting_article_info" , "default" => 2 , "children" => array(0 => "up" , 1 => "down" , 2 => "parent"))
					);

	$seo_option = array(
						1 => array("type" => "textarea" , "name" => "meta_tag") , 
						0 => array("type" => "textarea" , "name" => "meta_desc")
					);

	$tags = array(0 => array("type" => "ajax" , "name" => "tags" , "attributes" => array("ajax" => Site::$base . _ADM . 'index.php?component=content&ajax=tags') , "children" => array("tags")));

	$extra_option = array(
							0 => array("type" => "text" , "name" => "heading") , 
							1 => array("type" => "image" , "name" => "image") , 
							2 => array("type" => "date" , "name" => "publish_date" , "default" => $calendar->convert(Site::$datetime , "Y-m-d"))
						);

	$pages['article'] = $article;
	$pages['setting'] = $settings;
	$pages['seo_setting'] = $seo_option;
	$pages['tags'] = $tags;
	$pages['extra_option'] = $extra_option;

	$fields->pages = $pages;
	$fields->output();
}
else
{
	Messages::add_message('warning' , Language::_('COM_CONTENT_ERROR_FIRST_CREATE_CATEGORY'));
	Site::goto_link(Site::$base . _ADM . 'index.php?component=content&view=article');
}

function Cats($category , &$categories , $parent , $level , $edit_parent)
{
	foreach ($category as $value)
		if($value->parent == $parent)
		{
			if(!isset($categories[$value->id]) && $value->id != $edit_parent){
				$categories[$value->id] = str_repeat("__ " , $level) . $value->title;
				Cats($category , $categories , $value->id , $level + 1 , $edit_parent);
			}
		}
}