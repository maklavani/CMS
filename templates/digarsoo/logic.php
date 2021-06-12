<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	01/04/2016
	*	last edit		01/04/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

// add kardane font
if(Language::$lang == 'fa-ir' || Language::$lang == 'ar-aa')
	Templates::add_font('a_google');
else
	Templates::add_font('roboto');

// add kardan file haye css
Templates::add_css(Site::$base . 'templates/' . Templates::$name . '/css/fonts.css');
Templates::add_css(Site::$base . 'templates/' . Templates::$name . '/css/template.css');
Templates::add_css(Site::$base . 'templates/' . Templates::$name . '/css/menu.css');

// add kardan file haye js
Templates::add_js(Site::$base . 'templates/' . Templates::$name . '/js/template.js');
Templates::add_js(Site::$base . 'templates/' . Templates::$name . '/js/menu.js');