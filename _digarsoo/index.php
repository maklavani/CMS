<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	06/18/2015
	*	last edit		12/21/2015
	* --------------------------------------------------------------------------
*/
// $datetime1 = microtime(true);
// agar versione php karbar payintar az 5.6.0 bood mimiranad
if (version_compare(PHP_VERSION , '5.6.0' , '<'))
{
	header('HTTP/1.1 503 Still running an old version(' . PHP_VERSION . ') of PHP');
	die('Still running an old version(' . PHP_VERSION . ') of PHP :(<br>Please upgrade php');
}

// tarif define baraye jologiri az dastresi gheyre mojaz be baghiye file haye php
define('_ALLOW' , 1);

// tarif define baraye sakhtane masire ebtedayi site baraye khandane filehaye php va directory ha
define('_SRC' , __DIR__ . '/');
// tarife sabethayi ke lazem baraye khandane directory hast ast
require_once _SRC . 'container/constants.php';
// file paye baraye pardazeshe url
require_once _SRC . 'container/basic.php';

// khuruji dadane natayej
Start::get_site();

// $datetime2 = microtime(true);
// echo $datetime2 - $datetime1;

error_reporting(E_ALL);