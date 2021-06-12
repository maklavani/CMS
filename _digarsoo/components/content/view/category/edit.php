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
require_once _INC . 'system/calendar.php';

if(Language::$lang == 'fa-ir')
	$calendar = new Calendar('shamsi');
else
	$calendar = new Calendar();

$db = new Database;
$db->table('category')->order('parent ASC , id ASC')->select()->process();
$category = $db->output();

$categories = array();
$categories[0] = Language::_('ROOT');

if($category)
	Cats($category , $categories , 0 , 0 , $params[0]->id);

$db->table('permissions')->order('id DESC')->select()->process();
$permission = $db->output();

$permissions = array();
foreach ($permission as $key => $value)
	$permissions[$value->id] = Language::_($value->name);

$fields = New Fields;
$fields->name = 'COM_CONTENT_CATEGORY_FIELD_INPUT';
$fields->action = Site::$full_link_text;
$fields->method = 'post';

$pages = $category = $details = array();

$category = array(
					0 => array('type' => 'text' , 'name' => 'title' , 'default' => $params[0]->title) , 
					1 => array('type' => 'list' , 'name' => 'category' , 'children' => $categories , 'language' => false , 'default' => $params[0]->parent),
					2 => array('type' => 'list' , 'name' => 'permission' , 'children' => $permissions , 'language' => false , 'default' => $params[0]->permission) , 
					3 => array('type' => 'hidden' , 'name' => 'code' , 'default' => $params[0]->code)
				);

$par_setting = json_decode(htmlspecialchars_decode($params[0]->setting));

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
					0 => array("type" => "radio" , "name" => "setting_special" , "default" => $par_setting->setting_special , "children" => array(0 => "show" , 1 => "hide" , 2 => "parent")) , 
					1 => array("type" => "radio" , "name" => "setting_special_related" , "default" => $par_setting->setting_special_related , "children" => array(0 => "show" , 1 => "hide" , 2 => "parent")) , 
					2 => array("type" => "text" , "name" => "setting_special_limit" , "default" => $par_setting->setting_special_limit , "children" => array(0 => "show" , 1 => "hide" , 2 => "parent")) , 
					3 => array("type" => "text" , "name" => "setting_limit" , "default" => $par_setting->setting_limit , "children" => array(0 => "show" , 1 => "hide" , 2 => "parent")) , 
					4 => array("type" => "text" , "name" => "setting_countdesc" , "default" => $par_setting->setting_countdesc , "children" => array(0 => "show" , 1 => "hide" , 2 => "parent")) , 
					5 => array("type" => "radio" , "name" => "setting_pagination" , "default" => $par_setting->setting_pagination , "children" => array(0 => "show" , 1 => "hide" , 2 => "parent")) , 
					6 => array("type" => "radio" , "name" => "setting_article_heading" , "default" => $par_setting->setting_article_heading , "children" => array(0 => "show" , 1 => "hide" , 2 => "parent")) , 
					7 => array("type" => "radio" , "name" => "setting_newsfeed" , "default" => $par_setting->setting_newsfeed , "children" => array(0 => "show" , 1 => "hide" , 2 => "parent")) , 
					8 => array("type" => "list" , "name" => "setting_sort" , "default" => $par_setting->setting_sort , "children" => $sort)
				);

$details = array(
					0 => array("type" => "html" , "name" => "code" , "default" => $params[0]->code) , 
					1 => array("type" => "html" , "name" => "edit" , "default" => $calendar->convert($params[0]->edit_date , 'Y/m/d - H:i')) , 
					2 => array("type" => "html" , "name" => "create" , "default" => $calendar->convert($params[0]->create_date , 'Y/m/d - H:i'))
				);

$pages['category'] = $category;
$pages['setting'] = $settings;
$pages['details'] = $details;

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