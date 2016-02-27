<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	08/15/2015
	*	last edit		12/03/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

require_once _INC . 'output/fields.php';

$db = new Database;

$db->table('extension')->where('`name` = "' . $params[0]->type . '" AND `type` = "widget" AND `location` = "site"')->select()->process();
$widget = $db->output();

if($params[0]->location == 'site')
	$read_details = json_decode(file_get_contents(_SRC_SITE . 'templates/' . $params[0]->type . '/details.json') , true);
else
	$read_details = json_decode(file_get_contents(_SRC . 'templates/' . $params[0]->type . '/details.json') , true);

if(isset($read_details['language']))
	foreach ($read_details['language'] as $lang)
		if($lang['name'] == Language::$lang && System::has_file('languages/' . $lang['name'] . '/' . $lang['src']))
			Language::add_ini_file(($params[0]->location == 'site' ? _SRC_SITE : _SRC) . 'languages/' . $lang['name'] . '/' . $lang['src']);

$fields = New Fields;
$fields->name = 'COM_EXTENSION_TEMPLATES_FIELD_INPUT';
$fields->action = Site::$full_link_text;
$fields->method = 'post';
$pages = $details = $templates_details = array();

$showing = 'hide';
if($params[0]->showing)
	$showing = 'show';

$details = array(
				0 => array('type' => 'text' , 'name' => 'name' , 'default' => $params[0]->name) , 
				1 => array('type' => 'radio' , 'name' => 'showing' , 'default' => $showing , 'children' => array('hide' => 'hide' , 'show' => 'show')) , 
				2 => array('type' => 'hidden' , 'name' => 'type' , 'default' => $params[0]->type) , 
				3 => array('type' => 'hidden' , 'name' => 'location' , 'default' => $params[0]->location) , 
			);

$td = json_decode(htmlspecialchars_decode($params[0]->setting) , true);

if(isset($read_details['fields']))
{
	foreach ($read_details['fields'] as $key => $value){
		$templates_details[$key] = array();

		$name = "";
		if(isset($value['name']))
			$name = strtoupper($value['name']);

		foreach ($value as $keyb => $valueb) {
			if($keyb == 'name')
				$templates_details[$key][$keyb] = 'tp_' . $valueb;
			else
				$templates_details[$key][$keyb] = $valueb;

			$templates_details[$key]['language'] = 'TEMP_' . strtoupper($params[0]->type);
			$templates_details[$key]['name_string'] = Language::_('TEMP_' . strtoupper($params[0]->type) . '_' . $name);
		}

		if(isset($value['name']) && isset($td[$value['name']]))
			$templates_details[$key]['default'] = $td[$value['name']];
	}
}
else
	$templates_details = array(0 => array('type' => 'html' , 'default' => Language::_('COM_EXTENSION_NO_ITEM') , 'placeholder' => false , 'show_label' => true));

$pages['details'] = $details;
$pages['templates_details'] = $templates_details;

$fields->pages = $pages;
$fields->output();