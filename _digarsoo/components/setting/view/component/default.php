<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	06/15/2015
	*	last edit		01/05/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

if(isset($params->fields))
{
	// khandane file fileds
	require_once _INC . 'output/fields.php';
	$fields = New Fields;

	$fields->name = 'COM_' . strtoupper($params->name);
	$fields->action = Site::$full_link_text;
	$fields->method = 'post';

	$db = new Database;
	$db->table('components')->where('`type` = "' . str_replace("COM_" , "" , $params->name) . '"' )->select()->process();
	$component = $db->output();
	$setting = json_decode(htmlspecialchars_decode($component[0]->setting) , true);

	$component_fields = array();

	foreach ($params->fields as $key => $value){
		$component_fields[$key] = array();

		foreach ($value as $keyb => $valueb){
			foreach ($valueb as $keyc => $valuec)
				$component_fields[$key][$keyb][$keyc] = $valuec;

			$name = $valueb->name;
			if(isset($setting[$key][$name]))
				$component_fields[$key][$keyb]['default'] = $setting[$key][$name];
		}
	}

	$fields->pages = $component_fields;
	$fields->output();
}
else
	Messages::add_message('success' , sprintf(Language::_('COM_SIGNIN_ERRO_COMPONENT_HAVENT_FIELDS') , Language::_($params->name)));