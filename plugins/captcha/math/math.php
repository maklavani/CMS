<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/12/2015
	*	last edit		01/20/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class MathCaptcha extends CaptchaPlugins {
	public $image;
	public $width;
	public $height;

	public function get()
	{
		Templates::add_css(Site::$base . 'plugins/captcha/math/css/template.css');
		Templates::add_js(Site::$base . 'plugins/captcha/math/js/template.js');

		$first_num = rand(1 , 20);
		$second_num = rand(1 , 20);
		$forth = rand(1 , 9);
		$fifth = rand(1 , 9);
		$max = max($first_num , $second_num);
		$min = min($first_num , $second_num);
		if ($max == $min) 
			$max++;
		$captcha_sum = array(($max + $min) , '+');
		$captcha_maines = array(($max - $min) , '-');
		$captcha_zarb = array(($forth * $fifth) , '*');
		$captcha_final = array($captcha_sum , $captcha_maines , $captcha_zarb);
		$x = rand(0 , 2);
		$captcha = implode(" " , array($captcha_final[$x][1]));
		$font = _MEDIA . 'fonts/arial.ttf';

		$this->width = 250;
		$this->height = 80;
		$this->image = $this->init_image($this->width , $this->height);

		$bc = imagecolorallocate($this->image , rand(60 , 200) , rand(60 , 200) , rand(60 , 200));

		if ($x == 0 || $x == 1) 
		{
			$box = imagettfbbox(30 , 0 , $font , $max);
			imagettftext($this->image , 30 , rand(-20 , 20) , 20 + rand(0 , 10) , $box[5] * -1 + rand(15 , 36) , $bc , $font , $max);
			$bc = imagecolorallocate($this->image , rand(60 , 200) , rand(60 , 200) , rand(60 , 200));
			imagettftext($this->image , 30 , rand(-20 , 20) , 200 + rand(-10 , 10) , $box[5] * -1 + rand(15 , 36) , $bc , $font , $min);
			$bc = imagecolorallocate($this->image , rand(60 , 200) , rand(60 , 200) , rand(60 , 200));
		}
		else
		{
			$box = imagettfbbox(30 , 0 , $font , $forth);
			imagettftext($this->image , 30 , rand(-20 , 20) , 20 + rand(0 , 10) , $box[5] * -1 + rand(15 , 36) , $bc , $font , $forth);
			$bc = imagecolorallocate($this->image , rand(60 , 200) , rand(60 , 200) , rand(60 , 200));
			imagettftext($this->image , 30 , rand(-20 , 20) , 200 + rand(-10 , 10) , $box[5] * -1 + rand(15 , 36) , $bc , $font , $fifth);
			$bc = imagecolorallocate($this->image , rand(60 , 200) , rand(60 , 200) , rand(60 , 200));		
		}

		imagettftext($this->image , 30 , 0 , 100 + rand(-10 , 40) , $box[5] * -1 + rand(15 , 36) , $bc , $font , $captcha);

		Sessions::set_session('captcha' , $captcha_final[$x][0]);
		$name = Regex::random_string(10);
		$this->create_image($this->image , $name);

		$output = "<div class=\"captcha xa\" ajax=\"" . Site::$base . "ajax/index.php?component=plugins\">";
		$output .= "<div class=\"captcha-image xa\"><img src=\"" . Site::$base . "plugins/captcha/math/images/" . $name . ".png\"></div>";
		// $output .= "<small class=\"xa\"><div class=\"captcha-button icon-refresh\"></div>" . sprintf(Language::_('CASE_SENSETIVE') , Language::_('CAPTCHA')) . "</small>";
		$output .= "<small class=\"xa\"><div class=\"captcha-button icon-refresh\"></div></small>";
		$output .= "</div>";

		return $output;
	}
}