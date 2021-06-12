<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	03/28/2015
	*	last edit		04/09/2016
	* --------------------------------------------------------------------------
*/
	
defined('_ALLOW') or die("access denied!");

class Configuration {
	protected $host = 'localhost';
	protected $user = 'root';
	protected $password = '';
	protected $db = 'cms';
	protected $prefix = 'dig_';

	public static $version = "2.0.0";
	public static $sitename = 'دیگرسو CMS';
	public static $offline = 0;
	public static $languages = 0;
	public static $alias = 1;
	public static $captcha = 'math';
	public static $theme_editor = 'monokai';
	public static $file_upload_size = 10485760;

	public static $busy = 1;

	public static $validity = 'email';
	public static $sms = '';
	public static $sms_password = '';
	public static $email_function = 'mail';
	public static $email = 'info@digarsoo.com';
	public static $email_password = 'GIEweWvo';
	public static $port = 25;
	public static $host_email = 'mail.digarsoo.com';
	public static $smtp_authentication = 1;

	public static $latitude = 35.716538;
	public static $longitude = 51.403424;

	public static $seo = 0;
	public static $minify = 0;
	public static $webmaster = '';
	public static $alexa = '';
	public static $analytics = '';
	public static $tag_manager = '';
	public static $seo_language = 'fa-ir';
}