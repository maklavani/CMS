<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	02/15/2016
	*	last edit		02/15/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

require_once _INC . 'output/fields.php';

$db = new Database;
$db->table('article')->select()->order('title ASC')->process();
$article = $db->output();

if(!empty($article))
{
	$articles = array();
	foreach ($article as $value)
	{

		$articles[$value->id] = str_replace('"' , "" , $value->title);
	}


	$fields = New Fields;
	$fields->name = 'COM_CONTENT_COMMENTS_FIELD_INPUT';
	$fields->action = Site::$full_link_text;
	$fields->method = 'post';
	$pages = array();
	$select_article = array(0 => array('type' => 'list' , 'name' => 'article_select' , 'children' => $articles , 'language' => false));
	$pages['select_article'] = $select_article;
	$fields->pages = $pages;
	$fields->output();
	die();
}
else
{
	Messages::add_message('warning' , Language::_('COM_CONTENT_ERROR_FIRST_CREATE_ARTICLE'));
	Site::goto_link(Site::$base . _ADM . 'index.php?component=content&view=comments');
}