<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	03/29/2015
	*	last edit		03/29/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class Hash {
	public static function create($algorithm , $password , $iteration)
	{
		$salt = base64_encode(mcrypt_create_iv(32 , MCRYPT_DEV_URANDOM));
		return $salt . ":" . base64_encode(static::pbkdf2($algorithm , $password , $salt , $iteration , 128 , true));
	}

	public static function validate($algorithm , $password , $iteration , $correct_hash)
	{
		$params = explode(":" , $correct_hash);

		if(count($params) < 2)
			return false;

		$pbkdf2 = base64_decode($params[1]);

		return static::slow_equals($pbkdf2 , static::pbkdf2($algorithm , $password , $params[0] , (int)$iteration , strlen($pbkdf2) , true));
	}

	// Compares two strings $a and $b in length-constant time.
	public static function slow_equals($a , $b)
	{
		$diff = strlen($a) ^ strlen($b);
		for($i = 0;$i < strlen($a) && $i < strlen($b);$i++)
			$diff |= ord($a[$i]) ^ ord($b[$i]);

		return $diff === 0; 
	}

	/*
	* PBKDF2 key derivation function as defined by Digarsoo
	* $algorithm - The hash algorithm to use.
	* $password - The password.
	* $salt - A salt that is unique to the password.
	* $count - Iteration count. Higher is better, but slower. Recommended: At least 1000.
	* $key_length - The length of the derived key in bytes. 
	* $raw_output - If true, the key is returned in raw binary format. Hex encoded otherwise.
	* Returns: A $key_length-byte key derived from the password and salt.
	*/
	public static function pbkdf2($algorithm , $password , $salt , $count , $key_length , $raw_output = false)
	{
		$algorithm = strtolower($algorithm);
		
		if(!in_array($algorithm , hash_algos() , true))
			Messages::add_message('erorr' , Language::_('ERROR_HASH_ALGORITHM'));
		
		if($count <= 0 || $key_length <= 0)
			Messages::add_message('erorr' , Language::_('ERROR_HASH_PARAMETERS'));

		$hash_length = strlen(hash($algorithm , "" , true));
		$block_count = ceil($key_length / $hash_length);

		$output = "";

		for($i = 1;$i <= $block_count;$i++)
		{
			$last = $salt . pack("N" , $i);
			$last = $xorsum = hash_hmac($algorithm , $last , $password , true);

			for ($j = 1; $j < $count; $j++)
				$xorsum ^= ($last = hash_hmac($algorithm , $last , $password , true));

			$output .= $xorsum;
		}

		if($raw_output)
			return substr($output , 0 , $key_length);
		else
			return bin2hex(substr($output , 0 , $key_length));
	}
}