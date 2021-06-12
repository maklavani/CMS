<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	06/15/2015
	*	last edit		12/22/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class Checks {
	public $json;
	public $component;
	public $type;

	// khandane file
	public function __construct($file , $component , $type)
	{
		$this->json = json_decode($file);
		$this->component = $component;
		$this->type = $type;
	}

	// check kardane form
	public function check()
	{
		$result = false;
		if(isset($this->json->check))
			foreach ($this->json->check as $key => $value){
				$str = "";
				$str_defined = false;

				if(isset($_POST[$this->type . $key]))
				{
					$str = $_POST[$this->type . $key];
					$str_defined = true;
				}
				else if(isset($_GET[$this->type . $key]))
				{
					$str = $_GET[$this->type . $key];
					$str_defined = true;
				}
				else if(!isset($value->defined) || $value->defined != 'false')
				{
					$result += true;
					$str_defined = false;
					Messages::add_message('warning' , sprintf(Language::_('ERROR_FIELD_NOT_FOUND') , $this->type . $key));
				}

				if(isset($value->igonre) && $value->igonre != "")
				{
					$characters = str_split($value->igonre);
					$str = str_replace($characters , "" , $str);
				}

				if($str_defined && (!isset($value->empty) || ($value->empty != 'false' && $str != "")))
					foreach ($value as $keyb => $valueb)
						if(method_exists($this , $keyb))
						{
							if(is_array($str))
								foreach ($str as $valuec)
									$result += !call_user_func_array(array($this , $keyb) , array($valuec , $valueb , $this->type . $key));
							else
								$result += !call_user_func_array(array($this , $keyb) , array($str , $valueb , $this->type . $key));
						}
			}
		return $result;
	}

	// min
	public function min($text , $number , $field)
	{
		if(mb_strlen($text) >= $number)
			return true;

		Messages::add_message('error' , sprintf(Language::_('ERROR_FIELD_MIN') , Language::_(strtoupper($this->component . $field)) , $number));
		return false;
	}

	// min
	public function max($text , $number , $field)
	{
		if(mb_strlen($text) <= $number)
			return true;

		Messages::add_message('error' , sprintf(Language::_('ERROR_FIELD_MAX') , Language::_(strtoupper($this->component . $field)) , $number));
		return false;
	}

	// kind
	public function kind($text , $kind , $field)
	{
		if(method_exists($this , $kind . '_kind'))
			return call_user_func_array(array($this , $kind . '_kind') , array($text , $field));
		return true;
	}

	// ansi_kind
	public function ansi_kind($text , $field)
	{
		if($text == "0" || Regex::cs($text , 'ansi'))
			return true;

		Messages::add_message('error' , sprintf(Language::_('ERROR_FIELD_ANSI') , Language::_(strtoupper($this->component . $field))));
		return false;
	}

	// utf8_kind
	public function utf8_kind($text , $field)
	{
		if($text == "0" || Regex::cs($text , 'utf8'))
			return true;

		Messages::add_message('error' , sprintf(Language::_('ERROR_FIELD_UTF8') , Language::_(strtoupper($this->component . $field))));
		return false;
	}

	// numeric_kind
	public function numeric_kind($text , $field)
	{
		if($text == "0" || Regex::cs($text , 'numeric'))
			return true;

		Messages::add_message('error' , sprintf(Language::_('ERROR_FIELD_NUMERIC') , Language::_(strtoupper($this->component . $field))));
		return false;
	}

	// text_kind
	public function text_kind($text , $field)
	{
		if($text == "0" || Regex::cs($text , 'text'))
			return true;

		Messages::add_message('error' , sprintf(Language::_('ERROR_FIELD_TEXT') , Language::_(strtoupper($this->component . $field))));
		return false;
	}

	// text_utf8_kind
	public function text_utf8_kind($text , $field)
	{
		if($text == "0" || Regex::cs($text , 'text_utf8'))
			return true;

		Messages::add_message('error' , sprintf(Language::_('ERROR_FIELD_TEXT_UTF8') , Language::_(strtoupper($this->component . $field))));
		return false;
	}

	// text_with_space_kind
	public function text_with_space_kind($text , $field)
	{
		if($text == "0" || Regex::cs($text , 'text_with_space'))
			return true;

		Messages::add_message('error' , sprintf(Language::_('ERROR_FIELD_TEXT_WITH_SPACE') , Language::_(strtoupper($this->component . $field))));
		return false;
	}

	// text_with_space_kind
	public function text_with_space_utf8_kind($text , $field)
	{
		if($text == "0" || Regex::cs($text , 'text_with_space_utf8'))
			return true;

		Messages::add_message('error' , sprintf(Language::_('ERROR_FIELD_TEXT_WITH_SPACE_UTF8') , Language::_(strtoupper($this->component . $field))));
		return false;
	}

	// username_kind
	public function username_kind($text , $field)
	{
		if(Regex::cs($text , 'username'))
			return true;

		Messages::add_message('error' , sprintf(Language::_('ERROR_FIELD_USERNAME') , Language::_(strtoupper($this->component . $field))));
		return false;
	}

	// text_with_space_utf8_kind
	public function url_kind($text , $field)
	{
		if(Regex::cs($text , 'url'))
			return true;

		Messages::add_message('error' , sprintf(Language::_('ERROR_FIELD_URL') , Language::_(strtoupper($this->component . $field))));
		return false;
	}

	// search_kind
	public function search_kind($text , $field)
	{
		if(Regex::cs($text , 'search'))
			return true;

		Messages::add_message('error' , sprintf(Language::_('ERROR_FIELD_SEARCH') , Language::_(strtoupper($this->component . $field))));
		return false;
	}

	// status_kind
	public function status_kind($text , $field)
	{
		if(Regex::cs($text , 'status'))
			return true;

		Messages::add_message('error' , sprintf(Language::_('ERROR_FIELD_STATUS') , Language::_(strtoupper($this->component . $field))));
		return false;
	}

	// source_kind
	public function source_kind($text , $field)
	{
		if(Regex::cs($text , 'source'))
			return true;

		Messages::add_message('error' , sprintf(Language::_('ERROR_FIELD_SOURCE') , Language::_(strtoupper($this->component . $field))));
		return false;
	}

	// password_kind
	public function password_kind($text , $field)
	{
		if(preg_match('/^[a-zA-Z0-9!@#$%]*$/' , $text))
			return true;

		Messages::add_message('error' , sprintf(Language::_('ERROR_FIELD_PASSWORD') , Language::_(strtoupper($this->component . $field))));
		return false;
	}

	// email_kind
	public function email_kind($text , $field)
	{
		if(preg_match('/[a-z\d._%+-]+@[a-z\d.-]+\.[a-z]{2,4}\b/i' , $text))
			return true;

		Messages::add_message('error' , Language::_('ERROR_FIELD_EMAIL'));
		return false;
	}

	// text_or_email_kind
	public function text_or_email_kind($text , $field)
	{
		if(preg_match('/[a-z\d._%+-]+@[a-z\d.-]+\.[a-z]{2,4}\b/i' , $text))
			return true;
		else if(preg_match('/^[a-z0-9_\.]*$/i' , $text))
			return true;

		Messages::add_message('error' , sprintf(Language::_('ERROR_FIELD_TEXT_OR_EMAIL') , Language::_(strtoupper($this->component . $field))));
		return false;
	}

	// tf_kind
	public function tf_kind($text , $field)
	{
		if($text == 0 || $text == 1)
			return true;

		Messages::add_message('error' , sprintf(Language::_('ERROR_FIELD_TF') , Language::_(strtoupper($this->component . $field))));
		return false;
	}

	// tf_b_kind
	public function tf_b_kind($text , $field)
	{
		if($text == 0 || $text == 1 || $text == 2)
			return true;

		Messages::add_message('error' , sprintf(Language::_('ERROR_FIELD_TF_B') , Language::_(strtoupper($this->component . $field))));
		return false;
	}

	// textarea_kind
	public function textarea_kind($text , $field)
	{
		if(preg_match('/^[a-zA-Z0-9\pL\pN\pPd\s_\-+,!@#$%\*\.\(\)]*$/iu' , $text))
			return true;

		Messages::add_message('error' , sprintf(Language::_('ERROR_FIELD_TEXTAREA') , Language::_(strtoupper($this->component . $field))));
		return false;
	}

	// tinymce_kind
	public function tinymce_kind($text , $field)
	{
		$result = false;

		preg_match_all("/<([\w]+)+[.*]*>/" , $text , $matches , PREG_SET_ORDER);

		foreach ($matches as $val)
			if(in_array(strtolower($val[1]) , array('iframe' , 'style' , 'link' , 'applet' , 'object' , 'embed' , 'form' , 'digarsoo'))){
				Messages::add_message('error' , sprintf(Language::_('ERROR_FIELD_TAG_INVALID') , $val[1] , Language::_(strtoupper($this->component . $field))));
				$result = true;
			}


		if(preg_match('@<script[^>]*?>.*?</script>@si' , $text) || preg_match('@<!.*>@' , $text)){
			Messages::add_message('error' , sprintf(Language::_('ERROR_FIELD_TINYMCE') , Language::_(strtoupper($this->component . $field))));
			return false;
		}

		if($result)
			return false;

		return true;
	}

	// link_kind
	public function link_kind($text , $field)
	{
		if(preg_match('/^[a-zA-Z0-9_\pL-%.:\/?=&#]*$/iu' , $text))
			return true;

		Messages::add_message('error' , sprintf(Language::_('ERROR_FIELD_LINK') , Language::_(strtoupper($this->component . $field))));
		return false;
	}

	// tel_kind
	public function tel_kind($text , $field)
	{
		if(preg_match('/^[0-9\s-+]*$/' , $text))
			return true;

		Messages::add_message('error' , sprintf(Language::_('ERROR_FIELD_TEL') , Language::_(strtoupper($this->component . $field))));
		return false;
	}

	// tel_b_kind
	public function tel_b_kind($text , $field)
	{
		if(preg_match('/^\+?([0-9]*)$/' , $text))
			return true;

		Messages::add_message('error' , sprintf(Language::_('ERROR_FIELD_TEL_B') , Language::_(strtoupper($this->component . $field))));
		return false;
	}

	// captcha_kind
	public function captcha_kind($text , $field)
	{
		if(Sessions::get_session('captcha') == $text)
			return true;

		Messages::add_message('error' , Language::_('ERROR_FIELD_CAPTCHA'));
		return false;
	}

	// date_kind
	public function date_kind($text , $field)
	{
		if(preg_match('/^([0-9]{2,4})+\-+([0-9]{1,2})+\-+([0-9]{1,2})$/' , $text))
			return true;

		Messages::add_message('error' , sprintf(Language::_('ERROR_FIELD_DATE') , Language::_(strtoupper($this->component . $field))));
		return false;
	}

	// check kardane zoj ya fard budane
	public function length($text , $type , $field)
	{
		if ($type == 'even')
		{
			$remain = mb_strlen($text) % 2;
			if(!$remain)
				return true;

			Messages::add_message('error' , sprintf(Language::_('ERROR_FIELD_EVEN_LENGTH') , Language::_(strtoupper($this->component . $field))));
			return false;
		}

		else if ($type == 'odd')
		{
			$remain = mb_strlen($text) % 2;
			if($remain)
				return true;

			Messages::add_message('error' , sprintf(Language::_('ERROR_FIELD_ODD_LENGTH') , Language::_(strtoupper($this->component . $field))));
			return false;
		}

		else if (preg_match("/^(\d?)n([+-](\d))?$/" , $type , $numbers))
		{
			if ($numbers[1] == '')
				$a = 1;
			else
				$a = $numbers[1];

			if (isset($numbers[2]))
				$b = $numbers[2];
			else
				$b = '+0';

			$remain = mb_strlen($text);

			if (is_int(($remain + $b) / $a)) 
				return true;

			Messages::add_message('error' , sprintf(Language::_('ERROR_FIELD_FORMUL_LENGTH') , Language::_(strtoupper($this->component . $field)) , $type));
			return false;
		}

		Messages::add_message('error' , sprintf(Language::_('ERROR_FIELD_FORMUL_INCORRECT') , Language::_(strtoupper($this->component . $field))));
		return true;
	}

	//tashkhise utf-8 budan
	public function utf8($text , $type , $field)
	{
		$utf8 = !preg_match('/^[a-zA-Z]*$/' , $text);

		if($type == 'true')
		{
			if($utf8)
				return true;

			Messages::add_message('error' , sprintf(Language::_('ERROR_FIELD_IS_UTF8') , Language::_(strtoupper($this->component . $field))));
			return false;
		}

		else if($type == 'false')
		{
			if(!$utf8)
				return true;

			Messages::add_message('error' , sprintf(Language::_('ERROR_FIELD_NOT_UTF8') , Language::_(strtoupper($this->component . $field))));
			return false;
		}

		Messages::add_message('error' , sprintf(Language::_('ERROR_FIELD_UTF8_INCORRECT') , Language::_(strtoupper($this->component . $field))));
		return false;
	}
}