<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/04/2015
	*	last edit		07/04/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

require_once _INC . 'output/fields.php';

$fields = New Fields;
$fields->name = 'COM_MENUS_GROUPS_FIELD_INPUT';
$fields->action = Site::$full_link_text;
$fields->method = 'post';

$pages = $category = array();

$category = array(
					0 => array('type' => 'text' , 'name' => 'name' , 'default' => $params[0]->name),
					1 => array('type' => 'html' , 'name' => 'location' , 'default' => $params[0]->location)
				);

$pages['setting'] = $category;

$fields->pages = $pages;

$fields->output();