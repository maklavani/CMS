<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	03/29/2015
	*	last edit		05/17/2017
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class User {
	private static $instance;

	public static $id;
	public static $name;
	public static $family;
	public static $username;
	public static $code;
	public static $group;
	public static $email;
	public static $mobile;
	public static $authentication;
	public static $visit;
	public static $ip;
	public static $register;
	public static $status;
	public static $permissions;

	public static $login;

	// ejraye constructor
	function __construct()
	{
		self::$id = -1;
		self::$name = false;
		self::$family = false;
		self::$username = false;
		self::$code = false;
		self::$group = 6;
		self::$email = false;
		self::$mobile = false;
		self::$authentication = false;
		self::$visit = false;
		self::$ip = false;
		self::$register = false;
		self::$status = -1;
		self::$permissions = array();
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

	// aya login ast
	public static function is_login()
	{
		$key = $key_session = Sessions::get_session("enter_key");
		$key_cookie = "";

		if($key_session == "")
			$key = $key_cookie = Cookies::get_cookie("enter_key");

		if($key)
		{
			if($key_session)
				$time = Sessions::get_session($key) - time();
			else if($key_cookie)
				$time = 1;

			if(Regex::cs($key , "ansi") && $time >= 0)
			{
				$db = new Database;
				$db->table('users')->where('`status` = 0 AND `logged` = "' . $key . '"')->select()->process();
				$user = $db->output();

				if(count($user) == 1)
				{
					if($user[0]->ip == Site::$ip){
						$session = Sessions::get_session($key);
						$time = 21600;

						if($remember = Cookies::get_cookie("remember"))
							$time = 1209600;

						if($session && !$user[0]->change_logged)
						{
							Cookies::set_cookie("enter_key" , $key , time() + $time);
							Sessions::set_session($key , time() + $time);
							$db->table('users')->update(array(array('visit' , Site::$datetime) , array('ip' , Site::$ip)))->where('`id` = ' . $user[0]->id)->process();
						} else {
							if($session){
								Sessions::delete_session("enter_key");
								Sessions::delete_session($key);
							}

							$rand_str = Regex::random_string(100);

							if($remember = Cookies::get_cookie("remember"))
								Cookies::set_cookie("remember" , '1' , time() + $time);
							else
								Cookies::delete_cookie("remember");

							Cookies::set_cookie("enter_key" , $key , time() + $time);
							Sessions::set_session('enter_key' , $rand_str);
							Sessions::set_session($rand_str , time() + $time);

							$db->table('users')->update(array(array('visit' , Site::$datetime) , array('ip' , Site::$ip) , array('logged' , $rand_str) , array('change_logged' , '0')))->where('`id` = ' . $user[0]->id)->process();
						}

						self::$login = true;
						self::set_information($user[0]);
						self::set_permissions();
						return true;
					}
					else
						$db->table('users')->update(array(array('change_logged' , '1')))->where('`id` = ' . $user[0]->id)->process();
				}
				else if(count($user) > 1)
					foreach ($user as $value)
						$db->table('users')->update(array(array('visit' , Site::$datetime) , array('ip' , Site::$ip) , array('logged' , '')))->where('`id` = ' . $value->id)->process();
			}
			
			// remove cookie and session
			Cookies::delete_cookie("remember");
			Cookies::delete_cookie("enter_key");
			Sessions::delete_session("enter_key");
			Sessions::delete_session($key);
		}

		self::$login = false;
		self::set_permissions();
		return false;
	}

	// aye premission mad nazar ra darad
	public static function has_permission($permission)
	{
		if(in_array($permission , self::$permissions))
			return true;
		return false;
	}

	public static function add_visit()
	{
		// hesab kardsane visit
		$date = date('Y-m-d H:i:s' , strtotime('-1 hour'));

		$db = new Database;
		$db->table('users_today_visit')->where('`ip` = "' . Site::$ip . '" AND `last_seen` >= "' . $date . '"')->select()->process();

		if($visit = $db->output())
		{
			$count = $visit[0]->count + 1;
			$db->table('users_today_visit')->where('`id` = ' . $visit[0]->id)->update(array(array('count' , $count) , array('last_seen' , Site::$datetime)))->process();
		}
		else
			$db->table('users_today_visit')->insert(array('ip' , 'count' , 'last_seen') , array(Site::$ip , 1 , Site::$datetime))->process();
	}

	// set kardane etelaat
	private static function set_information($user)
	{
		self::$id = $user->id;
		self::$name = $user->name;
		self::$family = $user->family;
		self::$username = $user->username;
		self::$code = $user->code;
		self::$group = $user->group_number;
		self::$email = $user->email;
		self::$mobile = $user->mobile;
		self::$authentication = $user->authentication;
		self::$visit = $user->visit;
		self::$ip = $user->ip;
		self::$register = $user->register;
		self::$status = $user->status;
	}

	// set kardane permission ha
	private static function set_permissions()
	{
		$db = new Database;
		$db->table('permissions')->select()->process();

		$permissions = $db->output();
		foreach ($permissions as $value)
			if(in_array(self::$group , explode("," , $value->groups)))
				self::$permissions[] = $value->id;
	}
}

$user = User::get_instance();