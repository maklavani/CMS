<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	06/23/2015
	*	last edit		12/03/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

if(User::$login)
{
	$db = new Database;

	$db->table('users')->update(array(array('logged' , '')))->where('`id` = ' . User::$id)->process();

	// remove cookie
	Cookies::remove_cookie('enter_key');
	Cookies::remove_cookie('remember');

	// peygham
	Messages::add_message('success' , Language::_('COM_SIGNIN_SIGNOUT_SUCCESS'));

	// raftan be safhe asli
	Site::goto_link(Site::$base . _ADM);
}
else
	Messages::add_message('warning' , Language::_('COM_SIGNIN_FIRST_SIGNIN'));