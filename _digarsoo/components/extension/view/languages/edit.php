<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	08/16/2015
	*	last edit		08/16/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

require_once _INC . 'output/fields.php';

$fields = New Fields;
$fields->name = 'COM_EXTENSION_LANGUAGES_FIELD_INPUT';
$fields->action = Site::$full_link_text;
$fields->method = 'post';
$pages = $details = $templates_details = array();

$default_administrator = 'hide';
if($params[0]->default_administrator)
	$default_administrator = 'show';

$default_site = 'hide';
if($params[0]->default_site)
	$default_site = 'show';

$details = array(
				0 => array('type' => 'text' , 'name' => 'name' , 'default' => $params[0]->name) , 
				1 => array('type' => 'radio' , 'name' => 'default_administrator' , 'default' => $default_administrator , 'children' => array('hide' => 'hide' , 'show' => 'show')) , 
				2 => array('type' => 'radio' , 'name' => 'default_site' , 'default' => $default_site , 'children' => array('hide' => 'hide' , 'show' => 'show')) , 
				3 => array('type' => 'text' , 'name' => 'label' , 'default' => $params[0]->label) , 
				4 => array('type' => 'text' , 'name' => 'abbreviation' , 'default' => $params[0]->abbreviation)
			);

$pages['details'] = $details;

$fields->pages = $pages;
$fields->output();