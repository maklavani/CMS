<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	08/13/2015
	*	last edit		12/03/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

require_once _INC . 'output/fields.php';

$db = new Database;
$db->table('extension')->where('`type` = "widget" AND `location` = "site"')->order('id DESC')->select()->process();
$widget = $db->output();

$db_widgets = array();
foreach ($widget as $value){
	if(System::has_file('languages/' . Language::$lang . '/wid_' . $value->name . '.json'))
		Language::add_ini_file(_SRC_SITE . 'languages/' . Language::$lang . '/wid_' . $value->name . '.json');

	$db_widgets[$value->id] = 'WID_' . strtoupper($value->name);
}

$fields = New Fields;
$fields->name = 'COM_EXTENSION_WIDGET_FIELD_INPUT';
$fields->action = Site::$full_link_text;
$fields->method = 'post';
$pages = $widgets = array();
$widgets = array(0 => array('type' => 'list' , 'name' => 'widget_select' , 'children' => $db_widgets , 'language' => false));
$pages['widgets'] = $widgets;
$fields->pages = $pages;
$fields->output();