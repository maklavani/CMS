<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/06/2015
	*	last edit		01/16/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

$db = new Database;
$db->table('category')->order('parent ASC , id ASC')->select()->process();
$category = $db->output();

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

$page = array();

$article = array(
					0 => array('type' => 'text' , 'name' => 'title' , 'default' => $params[0]->title) , 
					1 => array('type' => 'radio' , 'name' => 'status' , 'default' => $params[0]->status , 'children' => array(0 => 'show' , 1 => 'hide')) , 
					2 => array('type' => 'radio' , 'name' => 'special' , 'default' => $params[0]->special , 'children' => array(0 => 'show' , 1 => 'hide')) , 
					3 => array('type' => 'list' , 'name' => 'category' , 'default' => $params[0]->category , 'children' => $categories , 'language' => false),
					4 => array('type' => 'list' , 'name' => 'permission' , 'default' => $params[0]->permission , 'children' => $permissions , 'language' => false , 'default' => 5),
					5 => array('type' => 'tinymce' , 'name' => 'content' , 'default' => $params[0]->content , 'children' => array('image' , 'article')) , 
					6 => array('type' => 'hidden' , 'name' => 'code' , 'default' => $params[0]->code)
				);

$par_setting = json_decode(htmlspecialchars_decode($params[0]->setting));

$settings = array(
					0 => array("type" => "radio" , "name" => "setting_title" , "default" => $par_setting->setting_title , "children" => array(0 => "show" , 1 => "hide" , 2 => "parent")) , 
					1 => array("type" => "radio" , "name" => "setting_heading" , "default" => $par_setting->setting_heading , "children" => array(0 => "show" , 1 => "hide" , 2 => "parent")) , 
					2 => array("type" => "radio" , "name" => "setting_author" , "default" => $par_setting->setting_author , "children" => array(0 => "show" , 1 => "hide" , 2 => "parent")) , 
					3 => array("type" => "radio" , "name" => "setting_publish_date" , "default" => $par_setting->setting_publish_date , "children" => array(0 => "show" , 1 => "hide" , 2 => "parent")) , 
					4 => array("type" => "radio" , "name" => "setting_views" , "default" => $par_setting->setting_views , "children" => array(0 => "show" , 1 => "hide" , 2 => "parent")) , 
					5 => array("type" => "radio" , "name" => "setting_tags" , "default" => $par_setting->setting_tags , "children" => array(0 => "show" , 1 => "hide" , 2 => "parent")) , 
					6 => array("type" => "radio" , "name" => "setting_likes" , "default" => $par_setting->setting_likes , "children" => array(0 => "show" , 1 => "hide" , 2 => "parent")) , 
					7 => array("type" => "list" , "name" => "setting_likes_permission" , "default" => $par_setting->setting_likes_permission , "children" => array(0 => "all" , 1 => "just_users" , 2 => "parent")) , 
					8 => array("type" => "radio" , "name" => "setting_comments" , "default" => $par_setting->setting_comments , "children" => array(0 => "show" , 1 => "hide" , 2 => "parent")) , 
					9 => array("type" => "list" , "name" => "setting_comments_permission" , "default" => $par_setting->setting_comments_permission , "children" => array(0 => "all" , 1 => "just_users" , 2 => "parent")) , 
					10 => array("type" => "radio" , "name" => "setting_comments_confirmation" , "default" => $par_setting->setting_comments_confirmation , "children" => array(0 => "all" , 1 => "none" , 2 => "parent")) , 
					11 => array("type" => "radio" , "name" => "setting_code" , "default" => $par_setting->setting_code , "children" => array(0 => "show" , 1 => "hide" , 2 => "parent")) , 
					12 => array("type" => "list" , "name" => "setting_article_info" , "default" => $par_setting->setting_article_info , "children" => array(0 => "up" , 1 => "down" , 2 => "parent"))
				);

$seo_option = array(
					0 => array("type" => "textarea" , "name" => "meta_tag" , "default" => $params[0]->meta_tag) , 
					1 => array("type" => "textarea" , "name" => "meta_desc" , "default" => $params[0]->meta_desc) 
				);

$par_tags = json_decode(htmlspecialchars_decode($params[0]->tags));
$tags = array(0 => array("type" => "ajax" , "name" => "tags" , "default" => $par_tags , "attributes" => array("ajax" => Site::$base . _ADM . 'index.php?component=content&ajax=tags') , "children" => array("tags")));

$image = $params[0]->image;
$images = explode("/" , $image);
if($images[0] == "download")
	$images[0] = 'uploads';
$image = implode("/" , $images);

$extra_option = array(
						0 => array("type" => "text" , "name" => "heading" , "default" => $params[0]->heading) , 
						1 => array("type" => "image" , "name" => "image" , "default" => $image) , 
						2 => array("type" => "date" , "name" => "publish_date" , "default" => $calendar->convert($params[0]->publish_date , 'Y-m-d'))
					);

$details = array(
					0 => array("type" => "html" , "name" => "code" , "default" => $params[0]->code) , 
					1 => array("type" => "html" , "name" => "edit" , "default" => $calendar->convert($params[0]->edit_date , 'Y/m/d - H:i')) , 
					2 => array("type" => "html" , "name" => "create" , "default" => $calendar->convert($params[0]->create_date , 'Y/m/d - H:i')) , 
					3 => array("type" => "html" , "name" => "like" , "default" => number_format($params[0]->likes)) , 
					4 => array("type" => "html" , "name" => "dislike" , "default" => number_format($params[0]->dislikes)) , 
					5 => array("type" => "html" , "name" => "views" , "default" => number_format($params[0]->views))
				);

$pages['article'] = $article;
$pages['setting'] = $settings;
$pages['seo_setting'] = $seo_option;
$pages['tags'] = $tags;
$pages['extra_option'] = $extra_option;
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