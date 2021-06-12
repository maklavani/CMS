<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	03/29/2015
	*	last edit		10/03/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class Site {
	private static $instance;

	// system load average
	public static $sla;
	public static $base;
	public static $full_link;
	public static $full_link_text;
	public static $homepage;
	public static $ip;
	public static $datetime;
	public static $domain_name;
	public static $banned_time;

	function __construct()
	{
		static::$sla = static::get_server_load();
		static::$base = static::base();
		static::$full_link = static::get_full_link();
		static::$full_link_text = static::get_full_link(true);
		static::$homepage = static::has_hompage();
		static::$ip = static::get_ip();
		static::$datetime = static::get_now_datetime();
		static::$domain_name = static::get_domain_name();
	}

	// sakhtane instance baraye estefade az tavabe static
	public static function get_instance()
	{
		if (!isset(self::$instance)) {
			$c = __CLASS__;
			$instance = new $c;
		}
		return self::$instance;
	}

	// bargardandane address site
	public static function base()
	{
		if(dirname($_SERVER['PHP_SELF']) != '/')
			return dirname($_SERVER['PHP_SELF']) . '/';
		return '/';
	}

	//link koli ra barmigardanad
	public static function get_full_link($amp = false)
	{
		if($amp)
			return urldecode(str_replace('&' , '&amp;' , getenv('REQUEST_URI')));

		return urldecode(getenv('REQUEST_URI'));
	}

	// aya alan dar homepae hastim
	public static function has_hompage()
	{
		if(Language::$abbreviation && str_replace(static::$base . Language::$abbreviation , "" , static::$full_link) == "")
			return true;
		if(Language::$abbreviation && str_replace(static::$base . Language::$abbreviation . '/' , "" , static::$full_link) == "")
			return true;
		else if(str_replace(static::$base , "" , static::$full_link) == '')
			return true;
		return false;
	}

	// peyda kardan value morede nazar dar table khas
	public static function get_ip()
	{
		if(getenv('HTTP_CLIENT_IP'))
			return (string)getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
			return (string)getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
			return (string)getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
			return (string)getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
			return (string)getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
			return (string)getenv('REMOTE_ADDR');
		return false;
	}

	// dadane time alan
	public static function get_now_datetime()
	{
		$now_time = new DateTime();
		return $now_time->format('Y-m-d H:i:s');
	}

	// dadane nam domain site
	public static function get_domain_name()
	{
		return isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https://' . $_SERVER['HTTPS_HOST'] . '/' : 'http://' . $_SERVER['HTTP_HOST'] . '/';
	}

	// unset kardane $_POST
	public static function unset_post()
	{
		if(isset($_POST))
			foreach ($_POST as $key => $value)
				unset($_POST[$key]);
	}

	// unset kardane $_GET
	public static function unset_get()
	{
		if(isset($_GET))
			foreach ($_GET as $key => $value)
				unset($_GET[$key]);
	}

	// raftan be address madenazar
	public static function goto_link($link = false)
	{
		if(!empty(Messages::$messages)){
			Cookies::set_cookie('messages_site' , json_encode(Messages::$messages , JSON_UNESCAPED_UNICODE) , time() + 60);
		}
		header("Location: " . $link);
		exit;
	}

	// indakhtane ip dar database banned
	public static function insert_banned($ip , $str){
		$db = new Database;
		$db->table('banned')->where('`ip` = "' . $ip . '"')->select()->process();
		$banned = $db->output();

		$str = htmlentities($str , ENT_COMPAT | ENT_QUOTES , "UTF-8");

		if($banned){
			$date = strtotime($banned[0]->date);
			$now_date = strtotime(self::$datetime);
			$diff = $now_date - $date;

			if($diff > 259200)
			{
				$strs = array(self::$datetime => $str);
				$strs = json_encode($strs , JSON_UNESCAPED_UNICODE);
				$strs = htmlentities($strs , ENT_COMPAT | ENT_QUOTES , "UTF-8");

				$db->table('banned')->where('`ip` = "' . $ip . '"')->update(array(array('count' , 1) , array('message' , $strs) , array('date' , self::$datetime) , array('expire' , date("Y-m-d H:i:s" , strtotime('+7 day')))))->process();
			}
			else
			{
				$message = json_decode(htmlspecialchars_decode($banned[0]->message) , true);
				$message[self::$datetime] = $str;
				$strs = json_encode($message , JSON_UNESCAPED_UNICODE);
				$strs = htmlentities($strs , ENT_COMPAT | ENT_QUOTES , "UTF-8");

				$db->table('banned')->where('`ip` = "' . $ip . '"')->update(array(array('count' , ($banned[0]->count + 1)) , array('message' , $strs) , array('date' , self::$datetime) , array('expire' , date("Y-m-d H:i:s" , strtotime('+7 day')))))->process();
			}
		} else {
			$strs = array(self::$datetime => $str);
			$strs = json_encode($strs , JSON_UNESCAPED_UNICODE);
			$strs = htmlentities($strs , ENT_COMPAT | ENT_QUOTES , "UTF-8");

			$db->table('banned')->insert(array('ip' , 'message' , 'date' , 'expire') , array(self::$ip , $strs , self::$datetime , date("Y-m-d H:i:s" , strtotime('+7 day'))))->process();
		}
	}

	public static function has_banned()
	{
		$db = new Database;
		$db->table('banned')->where('`ip` = "' . Site::$ip . '"')->select()->process();
		$output = $db->output();

		$banned = false;

		if(isset($output[0]))
		{
			$date = strtotime($output[0]->date);
			$now_date = strtotime(Site::$datetime);
			$diff = $now_date - $date;

			if(($output[0]->count > 20 && $diff < 3600) || ($output[0]->count > 10 && $diff < 900))
			{
				$banned = $output[0]->count;
				static::$banned_time = $diff;
			}
		}

		return $banned;
	}

	// bargardandane addresse site
	public static function get_address_site()
	{
		$address = sprintf("%s://%s%s" , isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off' ? 'https' : 'http' , $_SERVER['SERVER_NAME'] , self::$base);
		return $address;
	}

	// fahmidan load shodan server
	private static function get_server_load()
	{
		if(function_exists('sys_getloadavg'))
		{
			$sla = sys_getloadavg();
			return $sla[0];
		} else {
			return 0;
		}
	}
}

$Site = Site::get_instance();