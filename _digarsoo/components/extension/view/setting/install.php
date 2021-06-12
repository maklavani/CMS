<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	12/29/2015
	*	last edit		12/29/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

require_once _INC . 'output/fields.php';

$fields = New Fields;
$fields->name = 'COM_EXTENSION_SETTING_FIELD_INPUT';
$fields->action = Site::$full_link_text;
$fields->method = 'post';
$fields->attributes = array("enctype" => "multipart/form-data");
$pages = array();
$install = array(0 => array('type' => 'file' , 'name' => 'file'));
$pages['install'] = $install;
$fields->pages = $pages;
$fields->output();