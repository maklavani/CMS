<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	09/06/2015
	*	last edit		09/06/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class UTF8_GD {
	private $utf8_characters =	 array(
									'آ' => array('ﺂ' , 'ﺂ' , 'آ') , 'ا' => array('ﺎ' , 'ﺎ' , 'ا') , 'ب' => array('ﺐ' , 'ﺒ' , 'ﺑ') , 'پ' => array('ﭗ' , 'ﭙ' , 'ﭘ') , 
									'ت' => array('ﺖ' , 'ﺘ' , 'ﺗ') , 'ث' => array('ﺚ' , 'ﺜ' , 'ﺛ') , 'ج' => array('ﺞ' , 'ﺠ' , 'ﺟ') , 'چ' => array('ﭻ' , 'ﭽ' , 'ﭼ') , 
									'ح' => array('ﺢ' , 'ﺤ' , 'ﺣ') , 'خ' => array('ﺦ' , 'ﺨ' , 'ﺧ') , 'د' => array('ﺪ' , 'ﺪ' , 'ﺩ') , 'ذ' => array('ﺬ' , 'ﺬ' , 'ﺫ') , 
									'ر' => array('ﺮ' , 'ﺮ' , 'ﺭ') , 'ز' => array('ﺰ' , 'ﺰ' , 'ﺯ') , 'ژ' => array('ﮋ' , 'ﮋ' , 'ﮊ') , 'س' => array('ﺲ' , 'ﺴ' , 'ﺳ') , 
									'ش' => array('ﺶ' , 'ﺸ' , 'ﺷ') , 'ص' => array('ﺺ' , 'ﺼ' , 'ﺻ') , 'ض' => array('ﺾ' , 'ﻀ' , 'ﺿ') , 'ط' => array('ﻂ' , 'ﻄ' , 'ﻃ') , 
									'ظ' => array('ﻆ' , 'ﻈ' , 'ﻇ') , 'ع' => array('ﻊ' , 'ﻌ' , 'ﻋ') , 'غ' => array('ﻎ' , 'ﻐ' , 'ﻏ') , 'ف' => array('ﻒ' , 'ﻔ' , 'ﻓ') , 
									'ق' => array('ﻖ' , 'ﻘ' , 'ﻗ') , 'ک' => array('ﻚ' , 'ﻜ' , 'ﻛ') , 'گ' => array('ﮓ' , 'ﮕ' , 'ﮔ') , 'ل' => array('ﻞ' , 'ﻠ' , 'ﻟ') , 
									'م' => array('ﻢ' , 'ﻤ' , 'ﻣ') , 'ن' => array('ﻦ' , 'ﻨ' , 'ﻧ') , 'و' => array('ﻮ' , 'ﻮ' , 'ﻭ') , 'ی' => array('ﯽ' , 'ﯿ' , 'ﯾ') , 
									'ك' => array('ﻚ' , 'ﻜ' , 'ﻛ') , 'ي' => array('ﻲ' , 'ﻴ' , 'ﻳ') , 'أ' => array('ﺄ' , 'ﺄ' , 'ﺃ') , 'ؤ' => array('ﺆ' , 'ﺆ' , 'ﺅ') , 
									'إ' => array('ﺈ' , 'ﺈ' , 'ﺇ') , 'ئ' => array('ﺊ' , 'ﺌ' , 'ﺋ') , 'ة' => array('ﺔ', 'ﺘ', 'ﺗ')
								);

	private $tahoma = array('ه' => array('ﻪ' , 'ﮭ' , 'ﮬ'));
	private $normal = array('ه' => array('ﻪ' , 'ﻬ' , 'ﻫ'));

	private $numbers = array("٠" , "١" , "٢" , "٣" , "٤" , "٥" , "٦" , "٧" , "٨" , "٩" , "۴" , "۵" , "۶");

	private $ignore_list = array('');
	private $next_ignore_list = array('آ' , 'ا' , 'د' , 'ذ' , 'ر' , 'ز' , 'ژ' , 'و' , 'أ' , 'إ' , 'ؤ');
	private $alamat_list = array('ٌ' , 'ٍ' , 'ً' , 'ُ' , 'ِ' , 'َ' , 'ّ' , 'ٓ' , 'ٰ' , 'ٔ' , 'ﹶ' , 'ﹺ' , 'ﹸ' , 'ﹼ' , 'ﹾ' , 'ﹴ' , 'ﹰ' , 'ﱞ' , 'ﱟ' , 'ﱠ' , 'ﱡ' , 'ﱢ' , 'ﱣ');

	private $open_close 	= array('>' , ')' , '}' , ']' , '<' , '(' , '{' , '[');
	private $open_close_rev = array('<' , '(' , '{' , '[' , '>' , ')' , '}' , ']');

	public function persian_text($str_input , $method = "normal" , $numbers = true , $language = 'persian')
	{
		$output = "";

		if($method == 'tahoma')
			$this->utf8_characters = array_merge($this->utf8_characters , $this->tahoma);
		else
			$this->utf8_characters = array_merge($this->utf8_characters , $this->normal);

		// Ignore
		$str_input = str_replace($this->ignore_list , '' , $str_input);
		// allah
		$str_input = str_replace("الله" , "اللّه" , $str_input);
		// Len
		$len = preg_match_all('/[\x00-\x7F\xC0-\xFD]/' , $str_input);
		// Reverse Number
		$str_input = preg_replace_callback('/\d+/' , function(array $s){ return strrev($s[0]); } , $str_input);

		preg_match_all("/./u" , $str_input , $str_explode);
		$str_check = $str_explode[0];

		$prev_character = null;
		$next_character = null;

		for($i = $len - 1;$i >= 0;$i--)
		{
			$prev_character = null;

			if(isset($str_check[$i - 1]) && (isset($this->utf8_characters[$str_check[$i - 1]]) || in_array($str_check[$i - 1] , $this->alamat_list)))
				$prev_character = $str_check[$i - 1];

			if(in_array($prev_character , $this->next_ignore_list))
				$prev_character = null;

			if(false !== $key_found = array_search($str_check[$i] , $this->open_close))
			{
				$output .= $this->open_close_rev[$key_found];
				$next_character = null;
			}
			else
			{
				if(isset($this->utf8_characters[$str_check[$i]]))
				{
					if($next_character == null && $prev_character == null)
						$next_character = $output .= $str_check[$i];
					else
					{
						if($prev_character == null)
							$case = 2;
						else if($next_character == null)
							$case = 0;
						else
							$case = 1;

						$next_character = $output .= $this->utf8_characters[$str_check[$i]][$case];
					}
				}
				else if($numbers && Regex::cs($str_check[$i] , "numeric"))
				{
					if($language == 'arabic')
						$output .= $this->numbers[$str_check[$i]];
					else
					{
						if(in_array($str_check[$i] , array('4' , '5' , '6')))
							$output .= $this->numbers[$str_check[$i] + 6];
						else
							$output .= $this->numbers[$str_check[$i]];
					}

					$next_character = null;
				}
				else
				{
					$next_character = $output .= $str_check[$i];
					if(!in_array($str_check[$i] , $this->alamat_list))
						$next_character = null;
				}
			}
		}

		return $output;
	}
}