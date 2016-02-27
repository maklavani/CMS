<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/30/2015
	*	last edit		07/30/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

Templates::add_css(Site::$base . _ADM . 'widgets/system/css/template.css');
Templates::add_js(Site::$base . _ADM . 'widgets/system/js/template.js');

$total = System::total_space(_SRC_SITE);
$used = $total - System::free_space(_SRC_SITE);
?>
<div id="system" class="box xa s57 m62 l67 aes025" ajax="<?php echo Site::$base . _ADM . 'index.php?component=system&ajax=cpu'; ?>">
	<h2><?php echo Language::_('WID_SYSTEM'); ?></h2>
	<svg id="system-graph" class="xa s65 m55 l5 es025" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="1000" viewBox="0 0 1000 200" enable-background="new 0 0 1000 200" xml:space="preserve"></svg>

	<div class="system-details xa s37 m42 l47">
		<div class="system-percentage xa"></div>
		<div class="system-datetime xa" ajax="<?php echo Site::$base . _ADM . 'index.php?component=system&ajax=datetime'; ?>"></div>
	</div>
</div>
<div id="hardware" class="box xa s4 m35 l3">
	<h2><?php echo Language::_('WID_SYSTEM_HARD_DISC'); ?></h2>
	<div id="status" class="xa">
		<div class="status-item status-users-online xa">
			<h3><?php echo Language::_('WID_SYSTEM_VOLUME'); ?></h3>
			<div class="xa status-line" val="<?php echo $used; ?>" all="<?php echo $total; ?>" val-show="<?php echo System::get_file_size($used); ?>" all-show="<?php echo System::get_file_size($total); ?>"></div>
		</div>
	</div>
</div>