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

$read_details = json_decode(file_get_contents(_SRC_SITE . 'plugins/' . $params[0]->category .'/' . $params[0]->type . '/details.json') , true);

if(isset($read_details['language']))
	foreach ($read_details['language'] as $lang)
		if($lang['name'] == Language::$lang && System::has_file('languages/' . $lang['name'] . '/' . $lang['src']))
			Language::add_ini_file(_SRC_SITE . 'languages/' . $lang['name'] . '/' . $lang['src']);

$fields = New Fields;
$fields->name = 'COM_EXTENSION_PLUGINS_FIELD_INPUT';
$fields->action = Site::$full_link_text;
$fields->method = 'post';
$pages = $details = $plugin_details = array();

$details = array(
					0 => array('type' => 'text' , 'name' => 'name' , 'default' => $params[0]->name) , 
					1 => array('type' => 'hidden' , 'name' => 'category' , 'default' => $params[0]->category) , 
					2 => array('type' => 'hidden' , 'name' => 'type' , 'default' => $params[0]->type)
				);

$pd = json_decode(htmlspecialchars_decode($params[0]->setting) , true);

if(isset($read_details['fields']))
{
	foreach ($read_details['fields'] as $key => $value){
		$plugin_details[$key] = array();

		$name = "";
		if(isset($value['name']))
			$name = strtoupper($value['name']);

		foreach ($value as $keyb => $valueb) {
			if($keyb == 'name')
				$plugin_details[$key][$keyb] = 'pd_' . $valueb;
			else
				$plugin_details[$key][$keyb] = $valueb;


			$plugin_details[$key]['language'] = 'PLG_' . strtoupper($params[0]->category) . '_' . strtoupper($params[0]->type);
			$plugin_details[$key]['name_string'] = Language::_('PLG_' . strtoupper($params[0]->category) . '_' . strtoupper($params[0]->type) . '_' . $name);
		}

		if(isset($value['name']) && isset($pd[$value['name']]))
			$plugin_details[$key]['default'] = $pd[$value['name']];
	}
}
else
	$plugin_details = array(0 => array('type' => 'html' , 'default' => Language::_('COM_EXTENSION_NO_ITEM') , 'placeholder' => false , 'show_label' => true));

$pages['details'] = $details;
$pages['plugin_details'] = $plugin_details;

$fields->pages = $pages;
$fields->output();