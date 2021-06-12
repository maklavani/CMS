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

// khandane file logic
require _TEMP . Templates::$name . '/logic.php';
?>
<!DOCTYPE html>
<html dir="<?php echo Language::$direction; ?>" lang="<?php echo Language::$lang; ?>" class="<?php if(Site::$homepage) echo "homepage"; ?>">
	<head>
		<digarsoo type="head">
	</head>
	<body>
		<digarsoo type="seo">
		<div id="wrapper" class="xa">
			<?php if(!Site::$homepage): ?>
			<div id="center" class="xa">
				<digarsoo type="message">
				<digarsoo type="component">
			</div>
			<?php endif; ?>
		</div>
	</body>
</html>