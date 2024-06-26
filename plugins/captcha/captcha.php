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

class CaptchaPlugins {
	public $plugin;

	function __construct()
	{
		$this->setting = array();

		$db = new Database;
		$db->table('plugins')->where('`category` = "captcha" AND `type` = "' . Configuration::$captcha . '"')->select()->process();
		$plugins = $db->output();

		$this->plugin = $plugins[0];
	}

	public function read($buffer , $location)
	{
		$captcha = $this->plugin->type;

		if($this->plugin->location == "all" || $this->plugin->location == $location)
		{
			require_once _PLG . 'captcha/' . $captcha . '/' . $captcha . '.php';

			if(System::has_file('plugins/captcha/' . $captcha . '/details.json')){
				$details = json_decode(file_get_contents(_PLG . '/captcha/' . $captcha . '/details.json'));

				if(isset($details->language))
					foreach ($details->language as $lang)
						if($lang->name == Language::$lang && System::has_file('languages/' . $lang->name . '/' . $lang->src))
							Language::add_ini_file(_SRC_SITE . 'languages/' . $lang->name . '/' . $lang->src);
			} 

			$class_name = ucwords($captcha) . 'Captcha';
			$class = new $class_name();

			return call_user_func_array(array($class , 'get') , array());
		}
	}

	public function init_image($width , $height)
	{
		$image = imagecreatetruecolor($width , $height);
		// imagecolortransparent($image , imagecolorallocate($image , 240 , 240 , 240));
		imagefilledrectangle($image , 0 , 0 , $width , $height , imagecolorallocate($image , 240 , 240 , 240));

		return $image;
	}

	public function create_image($image , $name)
	{
		imagepng($image , _PLG . "captcha/" . $this->plugin->type . "/images/" . $name . ".png");
		imagedestroy($image);
	}
}