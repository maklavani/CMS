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
Templates::add_css(Site::$base . 'templates/' . Templates::$name . '/css/error.css');
?>
<!DOCTYPE html>
<html dir="<?php echo Language::$direction; ?>" lang="<?php echo Language::$lang; ?>">
	<head>
		<digarsoo type="head">
	</head>

	<body>
		<div id="wrapper" class="xa">
			<div id="wrapper-in" class="xa s9 m85 l8 es05 em12 el1 aes05 aem12 ael1">
				<div id="header" class="xa">
					<h2><?php echo Templates::$title; ?></h2>
				</div>
				
				<digarsoo type="message">
				<digarsoo type="component">
				
				<div id="footer" class="xa">
					<a href="http://digarsoo.com">@&nbsp;<?php echo Language::_('SITE_COPYRIGHT'); ?></a>
				</div>
			</div>
		</div>
	</body>
</html>