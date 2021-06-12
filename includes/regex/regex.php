<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	03/29/2015
	*	last edit		01/04/2017
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class Regex {
	// bargardandane yek reshte random
	public static function random_string($string_length , $mod = "alphabetical")
	{
		if($mod == "alphabetical")
			$chars = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghijklmnopqrstuvwxyz";
		else if($mod == "alpha")
			$chars = "ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghijklmnopqrstuvwxyz";
		else if($mod == "number")
			$chars = "0123456789";
		else if($mod == "uppercase")
			$chars = "ABCDEFGHIJKLMNOPQRSTUVWXTZ";
		else if($mod == "lowercase")
			$chars = "abcdefghijklmnopqrstuvwxyz";

		$random_string = '';

		for ($i = 0;$i < $string_length;$i++) {
			$rnum = rand(0 , strlen($chars) - 1);
			$random_string .= $chars[$rnum];
		}

		return $random_string;
	}

	// pak kardane kod haye html css javascript multi line comment
	private static function strip_out_codes($input)
	{
		$patterns = array('@<script[^>]*?>.*?</script>@si' , '@<[/!]*?[^<>]*?>@si' , '@<style[^>]*?>.*?</style>@siU' , '@<![sS]*?--[ tnr]*>@');
		$inputb = preg_replace($patterns , '' , $input);

		if($inputb == $input)
			return false;
		return true;
	}

	// peyda kardane code haye shell
	private static function strip_out_shell($input)
	{
		return preg_match('/\.\.\//' , $input);
	}

	// check kardane string made nazar
	public static function cs($str , $type = '' , $ignores = "")
	{
		$result = false;

		if($ignores != "")
		{
			$characters = str_split($ignores);
			$str = str_replace($characters , "" , $str);
		}

		$result += static::strip_out_codes($str);
		$result += static::strip_out_shell($str);

		if($type == 'ansi')
			$result += !preg_match('/^[a-z0-9]*$/i' , $str);

		else if($type == 'utf8')
			$result += !preg_match('/^[a-z0-9\pL\p{Arabic}]*$/iu' , $str);

		else if($type == 'numeric')
			$result += !is_numeric($str);

		else if($type == 'text')
			$result += !preg_match('/^[a-z0-9_\-]*$/i' , $str);

		else if($type == 'text_utf8')
			$result += !preg_match('/^[a-z0-9_\-\pL\p{Arabic}]*$/iu' , $str);

		else if($type == 'text_with_space')
			$result += !preg_match('/^[a-z0-9_\-\s]*$/i' , $str);

		else if($type == 'text_with_space_utf8')
			$result += !preg_match('/^[a-z0-9_\-\s\pL\p{Arabic}]*$/iu' , $str);

		else if($type == 'username')
			$result += !preg_match('/^[a-z0-9_\.]*$/i' , $str);

		else if($type == 'url')
			$result += !preg_match('/^(http:\/\/)?(https:\/\/)?(www.)?[a-z-_0-9\pL\p{Arabic}]{2,}(\.[a-z]{2,})?[a-z0-9\pL\p{Arabic}\/\-\._\%\=\&\?]*$/iu' , $str);

		else if($type == 'search')
			$result += !preg_match('/^[a-z0-9\s\pL\p{Arabic}]*$/ui' , $str);

		else if($type == 'status')
			$result += !preg_match('/^[0-9,]*$/' , $str);

		else if($type == 'source')
			$result += !preg_match('@^[a-zA-Z0-9_\-:.\\/]*$@' , $str);

		else if($type == 'int')
			$result += !is_int($str);

		if(!$result)
			return $str;

		return false;
	}
}