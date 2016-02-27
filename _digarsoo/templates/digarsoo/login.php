<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	01/04/2016
	*	last edit		01/04/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

// add kardane font
Templates::add_font('a_google');

// add kardan file haye css
Templates::add_css(Site::$base . _ADM . 'templates/' . Templates::$name . '/css/login.css');

// add kardan file haye js
Templates::add_js(Site::$base . _ADM . 'templates/' . Templates::$name . '/js/login.js');
?>
<!DOCTYPE html>
<html dir="<?php echo Language::$direction; ?>" lang="<?php echo Language::$lang; ?>">
	<head>
		<digarsoo type="head">
	</head>

	<body>
		<div id="wrapper" class="xa">
			<div id="wrapper-in" class="x97 s8 m5 l4 ex015 es1 em25 el3 aex015 aes1 aem25 ael3">
				<digarsoo type="message">
				<digarsoo type="component">
			</div>

			<div id="footer" class="x97 s8 m5 l4 ex015 es1 em25 el3 aex015 aes1 aem25 ael3">
				<?php echo '<a href="http://digarsoo.com">@&nbsp;' . Language::_('SITE_COPYRIGHT') . '</a> - <a href="' . Site::$base . '">' . Configuration::$sitename . "</a>\n"; ?>
			</div>
		</div>
	</body>
</html>