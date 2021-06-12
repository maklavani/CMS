<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	06/23/2015
	*	last edit		06/23/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

Templates::add_css(Site::$base . _ADM . 'widgets/search/css/template.css');
Templates::add_js(Site::$base . _ADM . 'widgets/search/js/template.js');
?>
<div class="search xa">
	<input type="text" name="search" placeholder="<?php echo Language::_('WID_SEARCH_SEARCH'); ?>" />
</div>