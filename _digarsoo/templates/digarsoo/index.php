<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	01/04/2016
	*	last edit		01/04/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

// add kardane font
if(Language::$lang == 'fa-ir' || Language::$lang == 'ar-aa')
	Templates::add_font('a_google');
else
	Templates::add_font('roboto');

// add kardan file haye css
Templates::add_css(Site::$base . _ADM . 'templates/' . Templates::$name . '/css/menu.css');
Templates::add_css(Site::$base . _ADM . 'templates/' . Templates::$name . '/css/template.css');

// add kardan file haye js
Templates::add_js(Site::$base . _ADM . 'templates/' . Templates::$name . '/js/template.js');
Templates::package('flag');
?>
<!DOCTYPE html>
<html dir="<?php echo Language::$direction; ?>" lang="<?php echo Language::$lang; ?>">
	<head>
		<digarsoo type="head">
	</head>

	<body>
		<div id="wrapper" class="xa">
			<div id="header" class="xa">
				<div id="menu-icon-parent">
					<div id="menu-icon" class="icon-menu"></div>
				</div>
				<div id="header-content" class="xa">
					<div id="search" class="xa s4 m3 l2">
						<digarsoo type="widget" name="search">
					</div>
					<div id="users" class="xa s6 m7 l8">
					</div>
				</div>

				<digarsoo type="widget" name="users">
			</div>
			<div id="menu" class="">
				<digarsoo type="widget" name="menu">
			</div>
			<div id="content" class="xa">
				<div id="content-in" class="x97 ex015 aex015">
					<digarsoo type="widget" name="visit">
					<digarsoo type="widget" name="system">
					<digarsoo type="message">
					<digarsoo type="component">
				</div>
			</div>
			<div id="footer" class="xa">
				<div id="footer" class="x97 ex015 aex015">
					<a href="http://www.digarsoo.com"><?php echo ucwords(_COPR) . ' v' . Configuration::$version; ?></a> - <a href="<?php echo Site::$base; ?>" target="_blank"><?php echo ucwords(Configuration::$sitename); ?></a>
				</div>
			</div>
		</div>
		<div id="graph-hover">
			<div id="graph-hover-views" class="x95 ex025 aex025"></div>
			<div id="graph-hover-dates" class="x95 ex025 aex025"></div>
		</div>
	</body>
</html>