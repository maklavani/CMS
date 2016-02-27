<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	06/15/2015
	*	last edit		12/22/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

require_once _INC . 'output/fields.php';

$fields = New Fields;
$fields->name = 'COM_SETTING_SETTING_FIELD_INPUT';
$fields->action = Site::$full_link_text;
$fields->method = 'post';

$db = new Database;
$db->table('plugins')->where('`category` = "captcha"')->select()->process();
$captcha = $db->output();

$db = new Database;
$db->table('languages')->select()->process();
$seo_language = $db->output();

$seo_languages = array();
foreach ($seo_language as $key => $value)
	$seo_languages[$value->label] = $value->name;

$captchas = array();
foreach ($captcha as $key => $value)
	$captchas[$value->type] = $value->name;

$pages = $setting = array();

$offline = 'off';
if(Configuration::$offline)
	$offline = 'on';

$languages = 'off';
if(Configuration::$languages)
	$languages = 'on';

$alias = 'off';
if(Configuration::$alias)
	$alias = 'on';

$busy = 'off';
if(Configuration::$busy)
	$busy = 'on';

$seo = 'off';
if(Configuration::$seo)
	$seo = 'on';

$minify = 'off';
if(Configuration::$minify)
	$minify = 'on';

$theme_editor = array(	'3024-day' => '3024 day' , '3024-night' => '3024 night' , 'ambiance' => 'ambiance' , 'base16-dark' => 'base16 dark' , 'base16-light' => 'base16 light' , 
						'blackboard' => 'blackboard' , 'cobalt' => 'cobalt' , 'colorforth' => 'colorforth' , 'dracula' => 'dracula' , 'eclipse' => 'eclipse' , 
						'elegant' => 'elegant' , 'erlang-dark' => 'erlang dark' , 'icecoder' => 'icecoder' , 'lesser-dark' => 'lesser dark' , 'liquibyte' => 'liquibyte' , 
						'material' => 'material' , 'mbo' => 'mbo' , 'mdn-like' => 'mdn like' , 'midnight' => 'midnight' , 'monokai' => 'monokai' , 
						'neat' => 'neat' , 'neo' => 'neo' , 'night' => 'night' , 'paraiso dark' => 'paraiso dark' , 'paraiso-light' => 'paraiso light' , 
						'pastel-on-dark' => 'pastel on dark' , 'rubyblue' => 'rubyblue' , 'seti' => 'seti' , 'solarized' => 'solarized' , 'the-matrix' => 'the matrix' , 
						'tomorrow-night-bright' => 'tomorrow night bright' , 'tomorrow night eighties' => 'tomorrow night eighties' , 'ttcn' => 'ttcn' , 'twilight' => 'twilight' , 'vibrant-ink' => 'vibrant ink' , 
						'xq-dark' => 'xq dark' , 'xq-light' => 'xq light' , 'yeti' => 'yeti' , 'zenburn' => 'zenburn');

$setting = array(
					0 => array('type' => 'text' , 'name' => 'sitename' , 'default' => Configuration::$sitename) , 
					1 => array('type' => 'radio' , 'name' => 'offline' , 'default' => $offline , 'children' => array('on' => 'on' , 'off' => 'off')) , 
					2 => array('type' => 'radio' , 'name' => 'languages' , 'default' => $languages , 'children' => array('on' => 'on' , 'off' => 'off')) , 
					3 => array('type' => 'radio' , 'name' => 'alias' , 'default' => $alias , 'children' => array('on' => 'on' , 'off' => 'off')) , 
					4 => array('type' => 'list' , 'name' => 'captcha' , 'default' => Configuration::$captcha , 'children' => $captchas , 'language' => false) , 
					5 => array('type' => 'list' , 'name' => 'theme_editor' , 'default' => Configuration::$theme_editor , 'children' => $theme_editor , 'language' => false) , 
					6 => array('type' => 'text' , 'name' => 'file_upload_size' , 'default' => Configuration::$file_upload_size) , 
					7 => array('type' => 'radio' , 'name' => 'busy' , 'default' => $busy , 'children' => array('on' => 'on' , 'off' => 'off'))
				);

$smtp_authentication = 'off';
if(Configuration::$smtp_authentication)
	$smtp_authentication = 'on';

$validity = array(
					0 => array('type' => 'radio' , 'name' => 'validity' , 'default' => Configuration::$validity , 'children' => array('email' => 'email' , 'sms' => 'sms')) , 
					1 => array('type' => 'text' , 'name' => 'sms' , 'default' => Configuration::$sms) , 
					2 => array('type' => 'password' , 'name' => 'sms_password' , 'default' => Configuration::$sms_password) , 
					3 => array('type' => 'radio' , 'name' => 'email_function' , 'default' => Configuration::$email_function , 'children' => array('mail' => 'php mail' , 'smtp' => 'smtp') , 'language' => false) , 
					4 => array('type' => 'text' , 'name' => 'email' , 'default' => Configuration::$email) , 
					5 => array('type' => 'password' , 'name' => 'email_password' , 'default' => Configuration::$email_password) , 
					6 => array('type' => 'text' , 'name' => 'port' , 'default' => Configuration::$port) , 
					7 => array('type' => 'text' , 'name' => 'host_email' , 'default' => Configuration::$host_email) , 
					8 => array('type' => 'radio' , 'name' => 'smtp_authentication' , 'default' => $smtp_authentication , 'children' => array('on' => 'on' , 'off' => 'off'))
				);

$location = array(
					0 => array('type' => 'text' , 'name' => 'latitude' , 'default' => Configuration::$latitude) , 
					1 => array('type' => 'text' , 'name' => 'longitude' , 'default' => Configuration::$longitude)
				);

$seo = array(
				0 => array('type' => 'radio' , 'name' => 'seo' , 'default' => $seo , 'children' => array('on' => 'on' , 'off' => 'off')) , 
				1 => array('type' => 'radio' , 'name' => 'minify' , 'default' => $minify , 'children' => array('on' => 'on' , 'off' => 'off')) , 
				2 => array('type' => 'text' , 'name' => 'webmaster' , 'default' => Configuration::$webmaster) ,
				3 => array('type' => 'text' , 'name' => 'alexa' , 'default' => Configuration::$alexa) ,
				4 => array('type' => 'text' , 'name' => 'analytics' , 'default' => Configuration::$analytics) ,
				5 => array('type' => 'text' , 'name' => 'tag_manager' , 'default' => Configuration::$tag_manager) ,
				6 => array('type' => 'list' , 'name' => 'seo_language' , 'default' => Configuration::$seo_language , 'children' => $seo_languages , 'language' => false)
			);

$pages['setting'] = $setting;
$pages['validity'] = $validity;
$pages['location'] = $location;
$pages['seo'] = $seo;
$fields->pages = $pages;
$fields->output();