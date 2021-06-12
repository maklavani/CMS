<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	03/28/2015
	*	last edit		10/30/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class Start extends Preload {
	// tabe sikhtane safhe site
	private static function get_page($page){
		if(in_array(Preload::$status , array('ajax' , 'notheme')))
			echo Components::$output_buffer;
		else
		{
			// khandane file widget
			require _INC . 'widgets/widgets.php';
			// khandane details
			if(isset(Templates::$details->positions))
				Widgets::read(Templates::$details->positions);

			// ezafe shodane amar bazdid
			User::add_visit();

			$setting = Templates::$setting;
			ob_start();
			require _TEMP . Templates::$name . '/' . $page . '.php';
			$buffer = ob_get_clean();

			// jaygozini component
			$buffer = preg_replace('@<digarsoo\s*type="component"\s*\/?>@i' , Components::$output_buffer , $buffer);

			preg_match_all('@<digarsoo\s*type="widget"\s*name="([^"]+)"\s*\/?>@i' , $buffer , $widgets);

			if(isset($widgets[1]) && is_array($widgets[1]) && !empty($widgets[1]))
				foreach ($widgets[1] as $value){
					Widgets::add_files_read($value);
					$buffer = preg_replace('@<digarsoo\s*type="widget"\s*name="' . $value . '"\s*\/?>@i' , Widgets::get($value) , $buffer);
				}

			// khandane file plugins
			require _INC . 'plugins/plugins.php';
			Plugins::read($buffer , 'components');

			// jaygozini head
			$buffer = preg_replace('@<digarsoo\s*type="head"\s*\/?>@i' , Templates::output() , $buffer);
			// jaygozini message
			$buffer = preg_replace('@<digarsoo\s*type="message"\s*\/?>@i' , Messages::output() , $buffer);

			Plugins::read($buffer);

			require _INC . 'output/seo.php';
			// jaygozini seo
			$buffer = preg_replace('@<digarsoo\s*type="seo"\s*\/?>@i' , SEO::output() , $buffer);

			// khuruji dadan site
			echo $buffer;
		}
	}

	// tabe sakhtane kole site
	public static function get_site(){
		if(static::$login){
			self::get_page('login');
		} else if(static::$error){
			self::get_page('error');
		} else {
			self::get_page('index');
		}
	}
}