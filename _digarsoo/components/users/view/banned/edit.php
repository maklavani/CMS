<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/09/2015
	*	last edit		07/11/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

require_once _INC . 'output/fields.php';
require_once _INC . 'system/calendar.php';

$fields = New Fields;
$fields->name = 'COM_USERS_BANNED_FIELD_INPUT';
$fields->action = Site::$full_link_text;
$fields->method = 'post';

$pages = $banned = array();

$messages = json_decode(html_entity_decode($params[0]->message));
$output = "";

if(!empty($messages))
{
	if(Language::$lang == 'fa-ir')
		$calendar = new Calendar('shamsi');
	else
		$calendar = new Calendar();

	$output .= "<table><thead><tr><th width=\"30%\">" . Language::_('COM_USERS_BANNED_FIELD_INPUT_DATE') . "</th><th width=\"70%\">" . Language::_('COM_USERS_BANNED_FIELD_INPUT_MESSAGE') . "</th></tr></thead><tbody>";

	foreach ($messages as $key => $value)
		$output .= "<tr><td>" . $calendar->convert($key , 'y-m-d H:i') . "</td><td>" . $value . "</td></td>";

	$output .= "</tbody></table>";
}

$banned = array(
				0 => array('type' => 'html' , 'name' => 'ip' , 'default' => $params[0]->ip),
				1 => array('type' => 'text' , 'name' => 'count' , 'default' => $params[0]->count),
				2 => array('type' => 'html' , 'name' => 'message' , 'default' => $output)
			  );
$pages['banned'] = $banned;
$fields->pages = $pages;
$fields->output();