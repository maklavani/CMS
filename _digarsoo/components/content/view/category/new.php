<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	07/04/2015
	*	last edit		01/16/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

require_once _INC . 'output/fields.php';

$db = new Database;
$db->table('category')->order('parent ASC , id ASC')->select()->process();
$category = $db->output();

$categories = array();
$categories[0] = Language::_('ROOT');

if($category)
	Cats($category , $categories , 0 , 0 , -1);

$db->table('permissions')->order('id DESC')->select()->process();
$permission = $db->output();

$permissions = array();
foreach ($permission as $key => $value)
	$permissions[$value->id] = Language::_($value->name);

$fields = New Fields;
$fields->name = 'COM_CONTENT_CATEGORY_FIELD_INPUT';
$fields->action = Site::$full_link_text;
$fields->method = 'post';

$pages = array();

$category = array(
					0 => array('type' => 'text' , 'name' => 'title') , 
					1 => array('type' => 'list' , 'name' => 'category' , 'children' => $categories , 'language' => false) ,
					2 => array('type' => 'list' , 'name' => 'permission' , 'children' => $permissions , 'language' => false , 'default' => 5)
				);

$sort = array(
				0 => "setting_last_publish",
				1 => "setting_first_publish",
				2 => "setting_alphabet_title_asc",
				3 => "setting_alphabet_title_desc",
				4 => "setting_views_desc",
				5 => "setting_views_asc",
				6 => "setting_last_id",
				7 => "setting_first_id",
				8 => "parent"
			);

$settings = array(
					0 => array("type" => "radio" , "name" => "setting_special" , "default" => 2 , "children" => array(0 => "show" , 1 => "hide" , 2 => "parent")) , 
					1 => array("type" => "radio" , "name" => "setting_special_related" , "default" => 2 , "children" => array(0 => "show" , 1 => "hide" , 2 => "parent")) , 
					2 => array("type" => "text" , "name" => "setting_special_limit" , "default" => $setting->category->setting_special_limit , "children" => array(0 => "show" , 1 => "hide" , 2 => "parent")) , 
					3 => array("type" => "text" , "name" => "setting_limit" , "default" => $setting->category->setting_limit , "children" => array(0 => "show" , 1 => "hide" , 2 => "parent")) , 
					4 => array("type" => "text" , "name" => "setting_countdesc" , "default" => $setting->category->setting_countdesc , "children" => array(0 => "show" , 1 => "hide" , 2 => "parent")) , 
					5 => array("type" => "radio" , "name" => "setting_pagination" , "default" => 2 , "children" => array(0 => "show" , 1 => "hide" , 2 => "parent")) , 
					6 => array("type" => "radio" , "name" => "setting_article_heading" , "default" => 2 , "children" => array(0 => "show" , 1 => "hide" , 2 => "parent")) , 
					7 => array("type" => "radio" , "name" => "setting_newsfeed" , "default" => 2 , "children" => array(0 => "show" , 1 => "hide" , 2 => "parent")) , 
					8 => array("type" => "list" , "name" => "setting_sort" , "default" => 8 , "children" => $sort)
				);

$pages['category'] = $category;
$pages['setting'] = $settings;

$fields->pages = $pages;
$fields->output();

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