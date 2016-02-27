<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/28/2015
	*	last edit		12/03/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

$expire = strtotime($params->expire);
$now = strtotime(Site::$datetime);

if($expire - $now > 0)
{
	$db = new Database;
	$db->table('users')->where('`id` = ' . $params->val)->update(array(array('status' , 0)))->process();
	Messages::add_message('success' , Language::_('COM_USERS_SIGNUP_COMPELETE'));
}
else
	Messages::add_message('error' , Language::_('ERROR_EXPIRE_IDENTIFICATION'));

Site::goto_link(Site::$base);