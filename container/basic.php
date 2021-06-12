<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	03/28/2015
	*	last edit		03/28/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

// pre-load configuration.
ob_start();
require_once _CONF . 'configuration.php';
ob_end_clean();

// check kardane url baraye pardazesh
ob_start();
require_once _INC . '_preload.php';
ob_end_clean();

// check kardane url
Preload::check_url();

// start kardane site
require_once _INC . '_start.php';