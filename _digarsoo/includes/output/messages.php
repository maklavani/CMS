<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	03/28/2015
	*	last edit		05/28/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class Messages {
	// zakhire kardane tamame message ha
	public static $messages = array();

	// add kardane yek message jadid
	public static function add_message($type , $message){
		self::$messages[] = array('type' => $type , 'message' => $message);
	}

	// khuruji dadane message ha
	public static function output(){
		$output = '';

		if(!empty(self::$messages))
		{
			$output .= "<div class=\"messages xa\">";
			foreach (self::$messages as $value)
				$output .= "<div class=\"message xa\"><span class=\"icon-" . $value['type'] . "\"></span> " . $value['message'] . "</div>";
			$output .= "<div class=\"icon-close\"></div></div>";
		}

		return $output;
	}

	// set kardane message ha dar mogheE ke message ruye cookie site neshaste bashad
	public static function set_messages($msg){
		self::$messages = json_decode($msg , true);
	}
}

if($messages = Cookies::get_cookie('messages_site')){
	Messages::set_messages($messages);
	Cookies::delete_cookie('messages_site');
}