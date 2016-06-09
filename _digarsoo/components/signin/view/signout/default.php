<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	06/23/2015
	*	last edit		05/07/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

if(User::$login)
{
	$db = new Database;

	$db->table('users')->update(array(array('logged' , '')))->where('`id` = ' . User::$id)->process();

	// remove cookie and session
	Cookies::delete_cookie("remember_admin");
	Cookies::delete_cookie("enter_key_admin");
	Sessions::delete_session("enter_key_admin");

	// peygham
	Messages::add_message('success' , Language::_('COM_SIGNIN_SIGNOUT_SUCCESS'));

	// raftan be safhe asli
	Site::goto_link(Site::$base . _ADM);
}
else
	Messages::add_message('warning' , Language::_('COM_SIGNIN_FIRST_SIGNIN'));