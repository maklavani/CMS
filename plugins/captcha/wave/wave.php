<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	07/12/2015
	*	last edit		10/28/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class WaveCaptcha extends CaptchaPlugins {
	public $image;
	public $width;
	public $height;
	public $number;
	public $space;
	public $scale;
	public $Yperiod;
    public $Yamplitude;
    public $Xperiod;
    public $Xamplitude;

	public function get()
	{
		Templates::add_css(Site::$base . 'plugins/captcha/wave/css/template.css');
		Templates::add_js(Site::$base . 'plugins/captcha/wave/js/template.js');

		$this->width = 250;
		$this->height = 80;
		$this->number = 6;
		$this->space = 100;

		$this->scale = 3;
		$this->Yperiod = 12;
		$this->Yamplitude = 14;
		$this->Xperiod = 11;
		$this->Xamplitude = 5;

		$this->image = $this->init_image($this->width , $this->height);

		// Text
		$captcha = Regex::random_string($this->number);
		Sessions::set_session('captcha' , $captcha);

		$chars = str_split($captcha);
		$font = _MEDIA . 'fonts/arial.ttf';

		$wid = (int)(($this->width - $this->space) / $this->number);
		$hei = $this->height;

		$font_size = rand(30 , 35);

		foreach ($chars as $key => $value) {
			$box = imagettfbbox($font_size , 0 , $font , $value);
			$wid_b = $box[4];
			$hei_b = $box[5] * -1;
			$image = imagecreatetruecolor($wid , $hei);
			$bc = imagecolorallocate($image , 60 , 60 , 60);

			imagefilledrectangle($image , 0 , 0 , $wid , $hei , imagecolorallocate($image , 240 , 240 , 240));
			imagettftext($image , $font_size , 0 , (int)(($wid - $wid_b) / 2) , (int)(($hei + $hei_b) / 2) , $bc , $font , $value);			
			imagecopy($this->image , $image , (int)($key * $wid) + (int)($this->space / 2) , 0 , 0 , 0 , $wid , $this->height);
		}

		// Line
		for($i = 0;$i < 10;$i++){
			$line_color = imagecolorallocate($this->image , rand(30 , 255) , rand(30 , 255) , rand(30 , 255)); 
			imageline($this->image , 0 , rand() % $this->height , $this->width , rand() % $this->height , $line_color);
		}

		// Pixels
		for($i = 0;$i < 2000;$i++) {
			$pixel_color = imagecolorallocate($this->image , rand(30 , 255) , rand(30 , 255) , rand(30 , 255));
			imagesetpixel($this->image , rand() % $this->width , rand() % $this->height , $pixel_color);
		}

		$this->wave_image();
		$name = Regex::random_string(10);
		$this->create_image($this->image , $name);
		$base64 = "data:image/png;base64," . base64_encode(file_get_contents(_SRC_SITE . "plugins/captcha/wave/images/" . $name . ".png"));
		System::delete_files(array("plugins/captcha/wave/images/" . $name . ".png"));

		$output = "<div class=\"captcha xa\" ajax=\"" . Site::$base . "ajax/index.php?component=plugins\">";
		$output .= "<div class=\"captcha-image xa\"><img src=\"" . $base64 . "\"></div>";
		// $output .= "<small class=\"xa\"><div class=\"captcha-button icon-refresh\"></div>" . sprintf(Language::_('CASE_SENSETIVE') , Language::_('CAPTCHA')) . "</small>";
		$output .= "<small class=\"xa\"><div class=\"captcha-button icon-refresh\"></div>" . Language::_('PLG_CAPTCHA_WAVE_NOT_ROBOT') . "</small>";
		$output .= "</div>";

		return $output;
	}

	protected function wave_image() {
		// X-axis wave generation
		$xp = $this->scale * $this->Xperiod * rand(1 , 3);
		$k = rand(0 , 100);

		for ($i = 0;$i < ($this->width * $this->scale);$i++)
			imagecopy($this->image , $this->image , $i - 1 , sin($k + $i / $xp) * ($this->scale * $this->Xamplitude) , $i , 0 , 1 , $this->height);

		// Y-axis wave generation
		$k = rand(0 , 100);
		$yp = $this->scale * $this->Yperiod * rand(1 , 2);
		
		for ($i = 0;$i < ($this->height * $this->scale);$i++)
			imagecopy($this->image , $this->image , sin($k + $i / $yp) * ($this->scale * $this->Yamplitude) , $i - 1 , 0 , $i , $this->width , 1);
	}
}