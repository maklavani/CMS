<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	03/28/2015
	*	last edit		08/21/2015
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
			// User::add_visit();

			$setting = Templates::$setting;
			ob_start();
			require _TEMP . Templates::$name . '/' . $page . '.php';
			$buffer = ob_get_clean();

			// jaygozini component
			$buffer = preg_replace('@<digarsoo\s*type="component"\s*\/?>@iu' , Components::$output_buffer , $buffer);

			preg_match_all('@<digarsoo\s*type="widget"\s*name="([^"]+)"\s*\/?>@iU' , $buffer , $widgets);

			if(isset($widgets[1]) && is_array($widgets[1]) && !empty($widgets[1]))
				foreach ($widgets[1] as $value){
					Widgets::add_files_read($value);
					$buffer = preg_replace('@<digarsoo\s*type="widget"\s*name="' . $value . '"\s*\/?>@iU' , Widgets::get($value) , $buffer);
				}

			// khandane file plugins
			require _INC . 'plugins/plugins.php';
			Plugins::read(Components::$output_buffer , 'components');

			// jaygozini head
			$buffer = preg_replace('@<digarsoo\s*type="head"\s*\/?>@iu' , Templates::output() , $buffer);
			// jaygozini message
			$buffer = preg_replace('@<digarsoo\s*type="message"\s*\/?>@iu' , Messages::output() , $buffer);

			Plugins::read($buffer);

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