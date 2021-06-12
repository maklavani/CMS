<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	12/22/2015
	*	last edit		12/22/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class CSSMin {
	public static function minify($minify)
	{
		return self::compress($minify);
	}

	private static function compress($minify) 
	{
		$minify = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '' , $minify);
		$minify = str_replace(array('    ' , '   ' , '  ') , ' ' , $minify);
		$minify = str_replace(array(' {' , '{ ' , ' }' , '} ' , ': ' , ' :' , ' ,' , ', ') , array('{' , '{' , '}' , '}' , ':' , ':' , ',' , ',') , $minify);
		$minify = str_replace(';}' , '}' , $minify);
		$minify = str_replace(array("\r\n" , "\r" , "\n" , "\t") , '' , $minify);
		$minify = str_replace("﻿" , "" , $minify);
		return $minify;
	}
}