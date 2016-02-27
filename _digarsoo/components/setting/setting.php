<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	06/15/2015
	*	last edit		12/21/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class SettingController extends Controller {
	// khandane view
	public function view($view)
	{
		if($view == 'setting')
		{
			$model = self::model('setting');
			View::read();
		}
		else if($view == 'component' && isset($_GET['id']) && Regex::cs($_GET['id'] , 'number'))
		{
			$model = self::model('setting');
			View::read($model->read_details($_GET['id']));
		}
	}

	//check kardane form
	public function action($view , $action)
	{
		if(	$view == 'component' && $action == 'default' && 
			isset($_GET['id']) && Regex::cs($_GET['id'] , 'number') && 
			isset($_POST['form-button']) && $_POST['form-button'] == 'save')
		{
			$model = self::model('setting');
			$component = $model->get_component($_GET['id']);

			if($component)
			{
				$model->save_setting($component[0]);
				Messages::add_message('success' , Language::_('COM_SETTING_SUCCESS'));
				return Site::$full_link;
			}
			else
			{
				Messages::add_message('error' , Language::_('COM_SETTING_ERROR_NOT_FOUND_COMPONENT'));
				return false;
			}
		}
		else if($view == 'setting' && $action == 'default' && isset($_POST['form-button']) && $_POST['form-button'] == 'save')
		{
			$file = file_get_contents(_CONF . 'configuration.php');

			if(isset($_POST['field_input_sitename']))
			{
				preg_match('/\$sitename\s=\s(.*);/' , $file , $result);
				$file = str_replace("\$sitename = " . $result[1] , "\$sitename = '" . $_POST['field_input_sitename'] . "'" , $file);
			}

			if(isset($_POST['field_input_offline']) && in_array($_POST['field_input_offline'] , array('on' , 'off')))
			{
				preg_match('/\$offline\s=\s(.*);/' , $file , $result);
				$file = str_replace("\$offline = " . $result[1] , "\$offline = " . ($_POST['field_input_offline'] == 'on' ? 1 : 0) , $file);
			}

			if(isset($_POST['field_input_languages']) && in_array($_POST['field_input_languages'] , array('on' , 'off')))
			{
				preg_match('/\$languages\s=\s(.*);/' , $file , $result);
				$file = str_replace("\$languages = " . $result[1] , "\$languages = " . ($_POST['field_input_languages'] == 'on' ? 1 : 0) , $file);
			}

			if(isset($_POST['field_input_alias']) && in_array($_POST['field_input_alias'] , array('on' , 'off')))
			{
				preg_match('/\$alias\s=\s(.*);/' , $file , $result);
				$file = str_replace("\$alias = " . $result[1] , "\$alias = " . ($_POST['field_input_alias'] == 'on' ? 1 : 0) , $file);
			}

			if(isset($_POST['field_input_captcha']))
			{
				preg_match('/\$captcha\s=\s(.*);/' , $file , $result);
				$file = str_replace("\$captcha = " . $result[1] , "\$captcha = '" . $_POST['field_input_captcha'] . "'" , $file);
			}

			if(isset($_POST['field_input_theme_editor']))
			{
				preg_match('/\$theme_editor\s=\s(.*);/' , $file , $result);
				$file = str_replace("\$theme_editor = " . $result[1] , "\$theme_editor = '" . $_POST['field_input_theme_editor'] . "'" , $file);
			}

			if(isset($_POST['field_input_file_upload_size']))
			{
				preg_match('/\$file_upload_size\s=\s(.*);/' , $file , $result);
				$file = str_replace("\$file_upload_size = " . $result[1] , "\$file_upload_size = " . $_POST['field_input_file_upload_size'] , $file);
			}

			if(isset($_POST['field_input_busy']) && in_array($_POST['field_input_busy'] , array('on' , 'off')))
			{
				preg_match('/\$busy\s=\s(.*);/' , $file , $result);
				$file = str_replace("\$busy = " . $result[1] , "\$busy = " . ($_POST['field_input_busy'] == 'on' ? 1 : 0) , $file);
			}

			if(isset($_POST['field_input_validity']) && in_array($_POST['field_input_validity'] , array('email' , 'sms')))
			{
				preg_match('/\$validity\s=\s(.*);/' , $file , $result);
				$file = str_replace("\$validity = " . $result[1] , "\$validity = '" . $_POST['field_input_validity'] . "'" , $file);
			}

			if(isset($_POST['field_input_sms']))
			{
				preg_match('/\$sms\s=\s(.*);/' , $file , $result);
				$file = str_replace("\$sms = " . $result[1] , "\$sms = '" . $_POST['field_input_sms'] . "'" , $file);
			}

			if(isset($_POST['field_input_sms_password']))
			{
				preg_match('/\$sms_password\s=\s(.*);/' , $file , $result);
				$file = str_replace("\$sms_password = " . $result[1] , "\$sms_password = '" . $_POST['field_input_sms_password'] . "'" , $file);
			}

			if(isset($_POST['field_input_email_function']) && in_array($_POST['field_input_email_function'] , array('mail' , 'smtp')))
			{
				preg_match('/\$email_function\s=\s(.*);/' , $file , $result);
				$file = str_replace("\$email_function = " . $result[1] , "\$email_function = '" . $_POST['field_input_email_function'] . "'" , $file);
			}

			if(isset($_POST['field_input_email']))
			{
				preg_match('/\$email\s=\s(.*);/' , $file , $result);
				$file = str_replace("\$email = " . $result[1] , "\$email = '" . $_POST['field_input_email'] . "'" , $file);
			}

			if(isset($_POST['field_input_email_password']))
			{
				preg_match('/\$email_password\s=\s(.*);/' , $file , $result);
				$file = str_replace("\$email_password = " . $result[1] , "\$email_password = '" . $_POST['field_input_email_password'] . "'" , $file);
			}

			if(isset($_POST['field_input_port']))
			{
				preg_match('/\$port\s=\s(.*);/' , $file , $result);
				$file = str_replace("\$port = " . $result[1] , "\$port = " . $_POST['field_input_port'] , $file);
			}

			if(isset($_POST['field_input_host_email']))
			{
				preg_match('/\$host_email\s=\s(.*);/' , $file , $result);
				$file = str_replace("\$host_email = " . $result[1] , "\$host_email = '" . $_POST['field_input_host_email'] . "'" , $file);
			}

			if(isset($_POST['field_input_smtp_authentication']) && in_array($_POST['field_input_smtp_authentication'] , array('on' , 'off')))
			{
				preg_match('/\$smtp_authentication\s=\s(.*);/' , $file , $result);
				$file = str_replace("\$smtp_authentication = " . $result[1] , "\$smtp_authentication = " . ($_POST['field_input_smtp_authentication'] == 'on' ? 1 : 0) , $file);
			}

			if(isset($_POST['field_input_latitude']))
			{
				preg_match('/\$latitude\s=\s(.*);/' , $file , $result);
				$file = str_replace("\$latitude = " . $result[1] , "\$latitude = " . $_POST['field_input_latitude'] , $file);
			}

			if(isset($_POST['field_input_longitude']))
			{
				preg_match('/\$longitude\s=\s(.*);/' , $file , $result);
				$file = str_replace("\$longitude = " . $result[1] , "\$longitude = " . $_POST['field_input_longitude'] , $file);
			}

			if(isset($_POST['field_input_seo']) && in_array($_POST['field_input_seo'] , array('on' , 'off')))
			{
				preg_match('/\$seo\s=\s(.*);/' , $file , $result);
				$file = str_replace("\$seo = " . $result[1] , "\$seo = " . ($_POST['field_input_seo'] == 'on' ? 1 : 0) , $file);
			}

			if(isset($_POST['field_input_minify']) && in_array($_POST['field_input_minify'] , array('on' , 'off')))
			{
				preg_match('/\$minify\s=\s(.*);/' , $file , $result);
				$file = str_replace("\$minify = " . $result[1] , "\$minify = " . ($_POST['field_input_minify'] == 'on' ? 1 : 0) , $file);
			}

			if(isset($_POST['field_input_webmaster']))
			{
				preg_match('/\$webmaster\s=\s(.*);/' , $file , $result);
				$file = str_replace("\$webmaster = " . $result[1] , "\$webmaster = '" . $_POST['field_input_webmaster'] . "'" , $file);
			}

			if(isset($_POST['field_input_alexa']))
			{
				preg_match('/\$alexa\s=\s(.*);/' , $file , $result);
				$file = str_replace("\$alexa = " . $result[1] , "\$alexa = '" . $_POST['field_input_alexa'] . "'" , $file);
			}

			if(isset($_POST['field_input_analytics']))
			{
				preg_match('/\$analytics\s=\s(.*);/' , $file , $result);
				$file = str_replace("\$analytics = " . $result[1] , "\$analytics = '" . $_POST['field_input_analytics'] . "'" , $file);
			}

			if(isset($_POST['field_input_tag_manager']))
			{
				preg_match('/\$tag_manager\s=\s(.*);/' , $file , $result);
				$file = str_replace("\$tag_manager = " . $result[1] , "\$tag_manager = '" . $_POST['field_input_tag_manager'] . "'" , $file);
			}

			if(isset($_POST['field_input_seo_language']))
			{
				preg_match('/\$seo_language\s=\s(.*);/' , $file , $result);
				$file = str_replace("\$seo_language = " . $result[1] , "\$seo_language = '" . $_POST['field_input_seo_language'] . "'" , $file);
			}

			$configuration_file = fopen(_CONF . "configuration.php" , "w");
			fwrite($configuration_file , $file);
			fclose($configuration_file);

			Messages::add_message('success' , Language::_('COM_SETTING_SUCCESS'));
			return Site::$full_link;
		}

		return false;
	}
}