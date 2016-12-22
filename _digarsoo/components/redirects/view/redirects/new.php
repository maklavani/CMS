<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	10/03/2016
	*	last edit		10/03/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

require_once _INC . 'output/fields.php';

$fields = New Fields;
$fields->name = 'COM_REDIRECTS_REDIRECTS_FIELD_INPUT';
$fields->action = Site::$full_link_text;
$fields->method = 'post';

$pages = array();

$redirects = array(
					0 => array('type' => 'text' , 'name' => 'url') , 
					1 => array('type' => 'radio' , 'name' => 'status' , 'default' => 1 , 'children' => array(0 => 'show' , 1 => 'hide')) , 
					2 => array('type' => 'text' , 'name' => 'redirect_to')
				);

$pages['redirects'] = $redirects;
$fields->pages = $pages;
$fields->output();