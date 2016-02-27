<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/19/2015
	*	last edit		07/19/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

Templates::add_css(Site::base() . 'widgets/login/css/template.css');

echo "<div class=\"login xa\">";
	if(User::$login)
	{
		echo "<div class=\"login-in xa\">";
			echo "<div class=\"login-text x67 s7 m72 l75 ex025\">" . Language::_('WID_LOGIN_HELLO') . ' , <span>' . User::$name . "</span></div>";

			echo "<div class=\"login-setting x3 s27 m25 l22 icon-cogs\"></div>";

			echo "<ul>";
				echo "<li><a href=\"index.php?component=profile&amp;view=user\"><span class=\"icon-user\"></span>" . Language::_('WID_LOGIN_USER') . "</a></li>";
				echo "<li><a href=\"index.php?component=users&amp;view=signout\"><span class=\"icon-signout\"></span>" . Language::_('WID_LOGIN_LOGOUT') . "</a></li>";
			echo "</ul>";
		echo "</div>";
	}
	else
	{
		echo '<a class="login-button login-button-singin" href="index.php?component=users&amp;view=signin"><span class="icon-users"></span>' . Language::_('WID_LOGIN_SIGNIN') . '</a>';
		echo '<a class="login-button login-button-singup" href="index.php?component=users&amp;view=signup"><span class="icon-plus"></span>' . Language::_('WID_LOGIN_SIGNUP') . '</a>';
	}
echo "</div>";