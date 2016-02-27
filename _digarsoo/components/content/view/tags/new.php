<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/14/2015
	*	last edit		07/14/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

require_once _INC . 'output/fields.php';

$fields = New Fields;
$fields->name = 'COM_CONTENT_TAGS_FIELD_INPUT';
$fields->action = Site::$full_link_text;
$fields->method = 'post';
$pages = $tags = array();
$tags = array(0 => array('type' => 'text' , 'name' => 'name'));
$pages['tags'] = $tags;
$fields->pages = $pages;
$fields->output();