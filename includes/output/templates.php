<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	06/17/2015
	*	last edit		02/11/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class Templates {
	private static $instance;

	public static $title;
	public static $meta;
	public static $css;
	public static $js;

	public static $name;
	public static $setting;
	public static $details;

	// ejraye constructor
	function __construct()
	{
		self::$title = Configuration::$sitename;
		self::$meta = array();
		self::$css = array();
		self::$js = array();

		$db = new Database;
		$db->table('templates')->where('`location` = "' . _LOC . '" AND `showing` = "1"')->select()->process();
		$tem = $db->output();

		self::$name = $tem[0]->type;
		self::$setting = json_decode(htmlspecialchars_decode($tem[0]->setting));

		if(System::has_file('templates/' . static::$name . '/details.json'))
		{
			self::$details = json_decode(file_get_contents(_TEMP . static::$name . '/details.json'));

			if(isset(self::$details->language))
				foreach (self::$details->language as $lang)
					if($lang->name == Language::$lang && System::has_file('languages/' . $lang->name . '/' . $lang->src))
						Language::add_ini_file(_LANG . $lang->name . '/' . $lang->src);
		}
		else
		{
			self::$details = false;
		}

		// add kardane meta haye default
		self::add_meta('http-equiv' , 'Content-Type' , 'content' , 'text/html;charset=utf-8');
		self::add_meta('http-equiv' , 'encoding' , 'content' , 'utf-8');
		self::add_meta('name' , 'viewport' , 'content' , 'width = device-width , initial-scale = 1.0');
		self::add_meta('name' , 'generator' , 'content' , Language::_('SITE_COPYRIGHT'));

		// SEO
		if(Configuration::$seo)
		{
			if(Configuration::$webmaster != "")
				self::add_meta('name' , 'google-site-verification' , 'content' , Configuration::$webmaster);
			if(Configuration::$alexa != "")
				self::add_meta('name' , 'alexaVerifyID' , 'content' , Configuration::$alexa);
		}

		// add kardane font
		self::add_font('icondigarsoo');

		// add kardane css va js haye default
		self::package('normalize');
		self::package('direction');
		self::package('jquery');
		self::package('digarsoo');
		self::package('cookie');
	}

	// sakhtane instance baraye estefade az tavabe static
	public static function get_instance() 
	{
		if (!isset(self::$instance)) {
			$c = __CLASS__;
			$instance = new $c;
		}
		return self::$instance;
	}

	// ezafe kardane file css
	public static function add_css($src , $code = false)
	{
		if($code)
			self::$css[] = array('code' => $src);
		else if(!isset(self::$css[$src]))
			self::$css[$src] = array('src' => $src);
	}

	// ezafe kardane file js
	public static function add_js($src , $code = false)
	{
		if($code)
			self::$js[] = array('code' => $src);
		else if(!isset(self::$js[$src]))
			self::$js[$src] = array('src' => $src);
	}

	// ezafe kardane file js
	public static function add_meta($var_a , $val_a , $var_b , $val_b)
	{
		self::$meta[$var_a . $val_a] = array($var_a , $val_a , $var_b , $val_b);
	}

	// ezafe kardane file js
	public static function add_font($name)
	{
		switch ($name)
		{
			case 'icondigarsoo':
				self::add_css(Site::$base . 'media/css/fonts/icondigarsoo.css');
				break;
			case 'a_google':
				self::add_css(Site::$base . 'media/css/fonts/a_google.css');
				break;
			case 'fira':
				self::add_css(Site::$base . 'media/css/fonts/fira.css');
				break;
			case 'iranian_sans':
				self::add_css(Site::$base . 'media/css/fonts/iranian_sans.css');
				break;
			case 'mitra':
				self::add_css(Site::$base . 'media/css/fonts/mitra.css');
				break;
			case 'nasim':
				self::add_css(Site::$base . 'media/css/fonts/nasim.css');
				break;
			case 'neirizi':
				self::add_css(Site::$base . 'media/css/fonts/neirizi.css');
				break;
			case 'poiret_one':
				self::add_css(Site::$base . 'media/css/fonts/poiret_one.css');
				break;
			case 'roboto':
				self::add_css(Site::$base . 'media/css/fonts/roboto.css');
				break;
			case 'yekan':
				self::add_css(Site::$base . 'media/css/fonts/yekan.css');
				break;
		}
	}

	// ezafe kardane file haye package
	public static function package($name)
	{
		switch ($name)
		{
			case 'adddetails':
				self::add_css(Site::$base . 'media/css/setting/adddetails.css');
				self::add_js(Site::$base . 'media/js/setting/adddetails.js');
				break;
			case 'article_tinymce':
				self::add_css(Site::$base . 'media/css/setting/article_tinymce.css');
				self::add_js(Site::$base . 'media/js/setting/article_tinymce.js');
				break;
			case 'codemirror':
				self::add_css(Site::$base . 'media/css/library/codemirror/codemirror.min.css');
				self::add_css(Site::$base . 'media/css/library/codemirror/theme/' . Configuration::$theme_editor . '.css');
				self::add_js(Site::$base . 'media/js/library/codemirror/codemirror.min.js');
				self::add_js(Site::$base . 'media/js/library/codemirror/mode/css.min.js');
				self::add_js(Site::$base . 'media/js/library/codemirror/mode/htmlembedded.min.js');
				self::add_js(Site::$base . 'media/js/library/codemirror/mode/htmlmixed.min.js');
				self::add_js(Site::$base . 'media/js/library/codemirror/mode/javascript.min.js');
				self::add_js(Site::$base . 'media/js/library/codemirror/mode/php.min.js');
				self::add_js(Site::$base . 'media/js/library/codemirror/mode/sass.min.js');
				self::add_js(Site::$base . 'media/js/library/codemirror/mode/xml.min.js');
				break;
			case 'color':
				self::add_css(Site::$base . 'media/css/library/colorpicker.css');
				self::add_js(Site::$base . 'media/js/library/colorpicker.js');
				self::add_js(Site::$base . 'media/js/setting/color.js');
				break;
			case 'cookie':
				self::add_js(Site::$base . 'media/js/site/cookie.js');
				break;
			case 'creator':
				self::add_css(Site::$base . 'media/css/setting/creator.css');
				self::add_js(Site::$base . 'media/js/setting/creator.js');
				break;
			case 'date':
				self::add_css(Site::$base . 'media/css/setting/date.css');
				self::add_js(Site::$base . 'media/js/setting/date.js');
				break;
			case 'digarsoo':
				self::add_css(Site::$base . 'media/css/library/digarsoo_' . Language::$direction . '.min.css');
				self::add_js(Site::$base . 'media/js/library/digarsoo.min.js');
				break;
			case 'direction':
				self::add_css(Site::$base . 'media/css/default/direction_' . Language::$direction . '.min.css');
				break;
			case 'flag':
				self::add_css(Site::$base . 'media/css/library/flag.css');
				break;
			case 'fields':
				self::add_css(Site::$base . 'media/css/setting/fields.css');
				self::add_js(Site::$base . 'media/js/setting/fields.js');
				break;
			case 'files':
				self::add_css(Site::$base . 'media/css/system/files.css');
				self::add_js(Site::$base . 'media/js/system/files.js');
				break;
			case 'forms_creator':
				self::add_css(Site::$base . 'media/css/setting/forms_creator.css');
				self::add_js(Site::$base . 'media/js/setting/forms_creator.js');
				break;
			case 'icon':
				self::add_js(Site::$base . 'media/js/setting/icon.js');
			case 'image':
				self::add_js(Site::$base . 'media/js/setting/image.js');
				break;
			case 'image_tinymce':
				self::add_css(Site::$base . 'media/css/setting/image_tinymce.css');
				self::add_js(Site::$base . 'media/js/setting/image_tinymce.js');
				break;
			case 'jquery':
				self::add_js(Site::$base . 'media/js/library/jquery.min.js');
				break;
			case 'listajax':
				self::add_js(Site::$base . 'media/js/setting/listajax.js');
				break;
			case 'lists':
				self::add_css(Site::$base . 'media/css/setting/lists.css');
				self::add_js(Site::$base . 'media/js/setting/lists.js');
				break;
			case 'map':
				self::add_js(Site::$base . 'media/js/setting/map.js');
				break;
			case 'menu':
				self::add_js(Site::$base . 'media/js/setting/menu.js');
				break;
			case 'menu_position':
				self::add_css(Site::$base . 'media/css/setting/menu_position.css');
				self::add_js(Site::$base . 'media/js/setting/menu_position.js');
				break;
			case 'menu_select':
				self::add_css(Site::$base . 'media/css/setting/menu_select.css');
				self::add_js(Site::$base . 'media/js/setting/menu_select.js');
				break;
			case 'mousewheel':
				self::add_js(Site::$base . 'media/js/library/mousewheel.min.js');
				break;
			case 'normalize':
				self::add_css(Site::$base . 'media/css/default/normalize.min.css');
				break;
			case 'price':
				self::add_js(Site::$base . 'media/js/library/price.js');
				break;
			case 'popup':
				self::add_css(Site::$base . 'media/css/setting/popup.css');
				self::add_js(Site::$base . 'media/js/setting/popup.js');
				break;
			case 'roll':
				self::add_css(Site::$base . 'media/css/library/roll.css');
				self::add_js(Site::$base . 'media/js/library/roll.js');
				break;
			case 'select2':
				self::add_css(Site::$base . 'media/css/library/select2.min.css');
				self::add_js(Site::$base . 'media/js/library/select2.min.js');
				if(System::has_file('media/js/library/select2_language/' . Language::$lang . '.js'))
					self::add_js(Site::$base . 'media/js/library/select2_language/' . Language::$lang . '.js');
				break;
			case 'table':
				self::add_css(Site::$base . 'media/css/library/table.css');
				self::add_js(Site::$base . 'media/js/library/table.js');
				break;
			case 'tel':
				self::add_css(Site::$base . 'media/css/setting/tel.css');
				self::add_js(Site::$base . 'media/js/setting/tel.js');
				break;
			case 'tinymce':
				self::add_js(Site::$base . 'media/js/library/tinymce/tinymce.min.js');
				self::add_js(Site::$base . 'media/js/library/tinymce/langs/fa.js');
				break;
			case 'toolbar':
				self::add_css(Site::$base . 'media/css/setting/toolbar.css');
				self::add_js(Site::$base . 'media/js/setting/toolbar.js');
				break;
			case 'view_buttons':
				self::add_css(Site::$base . 'media/css/setting/view_buttons.css');
				break;
		}
	}

	// khuruji dadane
	public static function output()
	{
		$output = '';

		if(!empty(self::$meta))
			foreach (self::$meta as $value)
				$output .= "<meta {$value[0]}=\"{$value[1]}\" {$value[2]}=\"{$value[3]}\" />\n\t\t";

		$output .= "\n\t\t<title>" . self::$title . "</title>\n";

		if(System::has_file('templates/' . self::$name . '/favicon.png'))
			$output .= "\n\t\t<link rel=\"shortcut icon\" type=\"image/png\" href=\"" . Site::$base . 'templates/' . self::$name . '/favicon.png' . "\" />\n";

		if(Configuration::$minify)
		{
			$source = array();

			if(!System::has_file('minify') && mkdir(_SRC_SITE . "minify" , 0755 , true))
			{
				$html_file = fopen(_SRC_SITE . "minify/index.html" , "w");
				fwrite($html_file , "<!DOCTYPE html><title></title>");
				fclose($html_file);
			}

			if(!System::has_file('minify/css') && mkdir(_SRC_SITE . "minify/css" , 0755 , true))
			{
				$html_file = fopen(_SRC_SITE . "minify/css/index.html" , "w");
				fwrite($html_file , "<!DOCTYPE html><title></title>");
				fclose($html_file);
			}

			if(!System::has_file('minify/js') && mkdir(_SRC_SITE . "minify/js" , 0755 , true))
			{
				$html_file = fopen(_SRC_SITE . "minify/js/index.html" , "w");
				fwrite($html_file , "<!DOCTYPE html><title></title>");
				fclose($html_file);
			}

			if(!System::has_file('minify/.htaccess'))
			{
				$htaccess_file = fopen(_SRC_SITE . "minify/.htaccess" , "w");
				fwrite($htaccess_file , "# --------------------------------------------------------------------------\n#\tauthor\t\t\tHossein Mohammadi Maklavani\n#\tcopyright\t\tCopyright (C) 2014 - 2016 Digarsoo. All rights reserved.\n#\tcreation date\t12/12/2015\n# --------------------------------------------------------------------------\n\nRewriteRule .*.json.*$ index.html [L]");
				fclose($htaccess_file);
			}

			if(!System::has_file('minify/source.json'))
			{				
				$source_file = fopen(_SRC_SITE . "minify/source.json" , "w");
				fwrite($source_file , json_encode($source , JSON_PRETTY_PRINT));
				fclose($source_file);
			}
			else if(System::has_file("minify/source.json"))
				$source = json_decode(file_get_contents(_SRC_SITE . "minify/source.json") , true);

			// CSS
			$css_str = "";
			$css_hex = "";
			if(!empty(self::$css))
			{
				$css_str = json_encode(self::$css , JSON_UNESCAPED_UNICODE);
				for ($i = 0;$i < strlen($css_str); $i++)
					$css_hex .= dechex(ord($css_str[$i]));
			}

			// JS
			$js_str = "";
			$js_hex = "";
			if(!empty(self::$js))
			{
				$js_str = json_encode(self::$js , JSON_UNESCAPED_UNICODE);
				for ($i = 0;$i < strlen($js_str); $i++)
					$js_hex .= dechex(ord($js_str[$i]));
			}

			// Find Hex
			$hex_find = $css_hex . $js_hex;
			$found = false;

			if($hex_find != "")
			{
				if(!empty($source))
					foreach ($source as $key => $value)
						if($value == $hex_find)
							$found = $key;

				if(!$found)
				{
					$css_minify = "";
					if(!empty(self::$css))
					{
						require_once _INC . 'output/minify/cssmin.php';
						foreach (self::$css as $value)
						{
							$css = "";
							if(isset($value['src']))
							{
								if(Site::$base != "/")
									$file = str_replace(Site::$base , "" , $value['src']);
								else
									$file = $value['src'];

								if(System::has_file($file))
									$css = file_get_contents(_SRC_SITE . $file);
								$css = self::url_change($css , $value['src']);
							}
							else if(isset($value['code']))
								$css = $value['code'];

							$css_minify .= CSSMin::minify($css);
						}
					}

					$js_minify = "";
					if(!empty(self::$js))
					{
						require_once _INC . 'output/minify/jsmin.php';
						foreach (self::$js as $value)
						{
							$js = "";
							if(isset($value['src']))
							{
								if(Site::$base != "/")
									$file = str_replace(Site::$base , "" , $value['src']);
								else
									$file = $value['src'];

								if(System::has_file($file))
									$js = file_get_contents(_SRC_SITE . $file);
							}
							else if(isset($value['code']))
								$js = $value['code'];

							$js_minify .= JSMin::minify($js);
						}
					}

					if($css_minify != "" || $js_minify != "")
					{
						$found = Regex::random_string(10);

						if($css_minify != "")
						{
							$css_file = fopen(_SRC_SITE . "minify/css/" . $found . ".min.css" , "w");
							fwrite($css_file , $css_minify);
							fclose($css_file);
						}

						if($js_minify != "")
						{
							$js_file = fopen(_SRC_SITE . "minify/js/" . $found . ".min.js" , "w");
							fwrite($js_file , $js_minify);
							fclose($js_file);
						}

						$source[$found] = $hex_find;
						$source_file = fopen(_SRC_SITE . "minify/source.json" , "w");
						fwrite($source_file , json_encode($source , JSON_PRETTY_PRINT));
						fclose($source_file);
					}
				}

				if($found)
				{
					$output .= "\n\t\t<link rel=\"stylesheet\" type=\"text/css\" href=\"" . Site::$base . "minify/css/" . $found . ".min.css" . "\" />";
					$output .= "\n\t\t<script type=\"text/javascript\" src=\"" . Site::$base . "minify/js/" . $found . ".min.js" . "\"></script>";
				}
			}
		}
		else
		{
			if(System::has_file("minify"))
				System::delete_files(array('minify/'));

			if(!empty(self::$css))
				foreach (self::$css as $value){
					if(isset($value['src']))
						$output .= "\n\t\t<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $value['src'] . "\" />";
					else if(isset($value['code']))
						$output .= "\n\t\t<style rel=\"stylesheet\" type=\"text/css\">\n\t\t\t" . $value['code'] . "\n\t\t</style>";
				}

			if(!empty(self::$js))
			{
				$output .= "\n";

				foreach (self::$js as $value){
					if(isset($value['src']))
						$output .= "\n\t\t<script type=\"text/javascript\" src=\"" . $value['src'] . "\"></script>";
					else if(isset($value['code']))
						$output .= "\n\t\t<script type=\"text/javascript\">\n\t\t\t" . $value['code'] . "\n\t\t</script>";
				}
			}
		}
		return $output; 
	}

	public static function url_change($content , $address)
	{
		$found_all_url = preg_match_all('@url\(\'?"?.+"?\'?\)@iU' , $content , $urls);

		if($found_all_url)
		{
			if(empty(!$urls[0]))
				foreach ($urls[0] as $value) {
					$number = substr_count($value , '../');

					if($number)
					{
						$srcs = explode('/' , $address);
						$src_out = "";

						for ($i = 0;$i < count($srcs) - $number - 1; $i++)
							$src_out .= $srcs[$i] . '/';

						$new_address = str_replace(str_repeat('../' , $number) , $src_out , $value);
						$content = str_replace($value , $new_address , $content);
					}
				}
		}

		return $content;
	}
}

$templates = Templates::get_instance();