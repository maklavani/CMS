<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	06/23/2015
	*	last edit		06/23/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

// khandane class menu
require_once _INC . 'widgets/menus.php';

$menu = New Menus($setting->menu);

echo $menu->output();