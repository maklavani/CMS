<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	06/15/2015
	*	last edit		01/31/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class System {
	// vujud dashtan file or folder
	public static function has_file($check_source , $read = false)
	{
		if(file_exists($file = _SRC . $check_source))
		{
			if($read)
				require_once $file;
			return true;
		}
		return false;
	}

	// init kardane cookie baryae file ha
	public static function init_cookie_file($name , $default)
	{
		$setting = array("source" => str_replace("/" , "_DIR_" , $default) , "view" => "grid" , "clipboard" => "" , "clipboard_action" => "");
		$cookie = Cookies::get_cookie($name);

		if($cookie)
		{
			$cookie = json_decode($cookie);
			$setting['source'] = isset($cookie->source) && Regex::cs($cookie->source , 'source') && static::has_file($cookie->source) ? $cookie->source : str_replace("/" , "_DIR_" , $default);
			$setting['view'] = isset($cookie->view) && in_array($cookie->view , array('grid' , 'list')) ? $cookie->view : 'grid';

			$clipboard = array();

			if(isset($cookie->clipboard) && !empty($cookie->clipboard))
				foreach ($cookie->clipboard as $key => $value)
					if(Regex::cs($value , 'source') && static::has_file($value))
						$clipboard[] = $value;

			if(!empty($clipboard))
			{
				$setting['clipboard'] = $clipboard;
				$setting['clipboard_action'] = isset($cookie->clipboard_action) && in_array($cookie->clipboard_action , array('cut' , 'copy')) ? $cookie->clipboard_action : 'copy';
			}
		}

		Cookies::set_cookie($name , json_encode($setting , JSON_UNESCAPED_UNICODE) , time() + 604800 , '/');

		return $setting;
	}

	public static function get_toolbar($toolbar)
	{
		$output = "<div class=\"files-toolbar xa\">";
		if (isset($toolbar['view']))
			foreach ($toolbar['view'] as $value)
				$output .= "<div class=\"x12 s1 m075 l05 files-toolbar-item icon-" . $value . "\" val=\"" . $value . "\" text=\"" . Language::_(strtoupper($value)) . "\"></div>";

		if (isset($toolbar['toolbar']))
			foreach ($toolbar['toolbar'] as $value)
				$output .= "<div class=\"x12 s1 m075 l05 files-toolbar-item icon-" . $value . "\" val=\"" . $value . "\" text=\"" . Language::_(strtoupper($value)) . "\"></div>";

		$output .= "<div class=\"x12 s1 m075 l05 files-toolbar-item icon-save edit disabled\" val=\"save\" text=\"" . Language::_('SAVE') . "\"></div>";
		$output .= "<div class=\"x12 s1 m075 l05 files-toolbar-item icon-close edit disabled\" val=\"exit\" text=\"" . Language::_('EXIT') . "\"></div>";
		$output .= "</div>";

		return $output;
	}

	// khandane file haye darune pushe
	public static function get_view($name , $source , $view , $message , $scroll , $list = false , $selective , $selective_types = array('all'))
	{
		Templates::package('cookie');
		Templates::package('popup');
		Templates::package('files');
		Templates::package('codemirror');

		$output = "";
		$size = 'xa';
		
		if($list)
			$size = 'xa s77';

		$output .= "<div class=\"files " . $size . " " . $view . "\" name=\"" . $name . "\" source=\"" . $source . "\" ajax=\"" . Site::$base . _ADM . 'index.php?component=content&amp;ajax' . "\" 
					message=\"" . $message . "\"
					scroll=\"" . $scroll . "\"
					error-more-select=\"" . Language::_('ERROR_MORE_SELECT') . "\" 
					error-one-select=\"" . Language::_('ERROR_ONE_SELECT') . "\" 
					error-archive-select=\"" . Language::_('ERROR_ARCHIVE_SELECT') . "\" 
					error-file-select=\"" . Language::_('ERROR_FILE_SELECT') . "\" 
					error-type-select=\"" . Language::_('ERROR_TYPE_SELECT') . "\" 
					error-confirm-delete=\"" . Language::_('ERROR_CONFIRM_DELETE') . "\" 
					save=\"" . Language::_('SAVE') . "\"
					names=\"" . Language::_('NAME') . "\"
					selective=\"" . $selective .  "\"
					selective_types=\"" . htmlentities(json_encode($selective_types , JSON_UNESCAPED_UNICODE)) . "\"
					theme=\"" . Configuration::$theme_editor . "\"
					></div>";

		if($list)
			$output .= "<div class=\"files-list xa s2 es025\"><h3>" . Language::_('FOLDER_LIST') . '</h3>' . static::get_list(str_replace("_DIR_" , '/' , $source)) . "</div>";

		return $output;
	}

	public static function get_list($active)
	{
		$source = 'uploads/';
		if(User::$group == 2)
			$source = '';

		$srcs = static::get_sub_folder($active , $source);
		$output = '<ul>' . static::create_list($srcs) . '</ul>';
		return $output;
	}

	public static function get_sub_folder($active , $source)
	{
		$src = array();
		$inners = scandir(_SRC . $source);

		if(!empty($inners))
		{
			foreach ($inners as $value)
				if(!in_array($value , array("." , ".." , "index.html")) && strpos($value , '.') === false)
				{
					$class = "";
					$node = static::get_sub_folder($active , $source . $value . '/');

					if(!empty($node))
					{
						$class = "parent";
						foreach ($node as $valueb)
							if($valueb['class'] == 'current showing')
								$class = "parent showing";
							else if($valueb['class'] == 'parent showing')
								$class = "parent showing";
					}

					if($value . '/' == $active)
						$class = "current showing";

					$src[] = array('source' => str_replace("/" , "_DIR_" , $source . $value . '/') , 'name' => $value , 'node' => $node , 'class' => $class);
				}
		}

		return $src;
	}

	public static function create_list($srcs)
	{
		$output = "";
		if(!empty($srcs))
			foreach ($srcs as $value) {
				$outputb = "";
				$output .= '<li class="' . $value['class'] . '">';
					$output .= '<a folder="' . $value['source'] . '">';
					if(!empty($value['node']))
					{
						$output .= '<div class="icon icon-plus"></div>';
						$outputb = '<ul>' . static::create_list($value['node']) . '</ul>';
					}
					else
						$output .= '<div class="space"></div>';
					$output .= '<div class="text">' . $value['name'] . '</div></a>' . $outputb;
				$output .= '</li>';
			}
		return $output;
	}

	public static function print_path($source)
	{
		$output = "";
		$sources = explode('/' , $source);

		// $file_types = "ai,bmp,css,csv,doc,docx,eot,gif,html,jpg,jpeg,js,json,mp3,mp4,ogg,pdf,php,png,ppt,pptx,psd,rar,svg,swf,ttf,txt,wav,webm,woff,woff2,xls,xlsx,xml,zip";

		$directory = glob(_SRC . $source . '*' , GLOB_ONLYDIR);
		$file_paths = glob(_SRC . $source . '*.*' , GLOB_BRACE | GLOB_NOSORT);

		if(User::$group == 2)
		{
			$htaccess = glob(_SRC . $source . '.htaccess' , GLOB_BRACE | GLOB_NOSORT);
			$file_paths = array_merge($file_paths , $htaccess);
		}

		if($source != "" && User::$group == 2 || ($sources[0] == "uploads" && isset($sources[1]) && $sources[1] != ""))
		{
			$last_directore = strrpos($source , "/" , -2);
			$src = substr($source , 0 , $last_directore);

			$output .= "<div class=\"file-item back\" src=\"" . $src . "\">";
			$output .= "<div class=\"file-image icon-back\"></div>";
			$output .= "<div class=\"file-name\">" . Language::_('BACK') . "</div>";
			$output .= "</div>";
		}

		if(!empty($directory) || !empty($file_paths))
		{
			$output .= "<div class=\"file-item check-all\">";
			$output .= "<input class=\"file-checked-all\" type=\"checkbox\">";
			$output .= "<div class=\"file-image\">" .Language::_('TYPE') . "</div>";
			$output .= "<div class=\"file-name\">" . Language::_('NAME') . "</div>";
			$output .= "<div class=\"file-size\">" . Language::_('SIZE') . "</div>";
			$output .= "</div>";
		}
		
		if(!empty($directory))
			foreach ($directory as $path) {
				$base = basename($path);
				$output .= "<div class=\"file-item folder\" names=\"" . $base . "\">";
				$output .= "<input class=\"file-checked\" type=\"checkbox\" value=\"" . $source . $base . "/" . "\">";
				$output .= "<div class=\"file-image icon-folder\"></div>";
				$output .= "<div class=\"file-name\">" . $base . "</div>";
				$output .= "</div>";
			}

		if(!empty($file_paths))
			foreach ($file_paths as $path) {
				$base = basename($path);

				if($base != 'index.html')
				{
					$dot_pos = strrpos($base , '.' , -1);
					$type = substr($base , $dot_pos - strlen($base) + 1);
					$name = preg_replace('/' . '.' . $type . '/' , '' , $base);
					$file_size = static::get_file_size(filesize($path));

					if(!in_array($type , array(_COPR , 'htaccess')))
					{
						$output .= "<div class=\"file-item\" type=\"" . $type . "\" size=\"" . $file_size . "\" names=\"" . $name . "\">";
						$output .= "<input class=\"file-checked\" type=\"checkbox\" value=\"" . $source . $base . "\">";
						$output .= static::get_file_type($type , $source . $base);
						$output .= "<div class=\"file-name\">" . $base . "</div>";
						$output .= "<div class=\"file-size\">" . $file_size . "</div>";
						$output .= "</div>";
					}
				}
			}

		return $output;
	}

	// aya src delete mishavand
	public static function is_deleted($srsc)
	{
		$output = false;

		foreach ($srsc as $value){
			if(System::has_file($value . '.' . _COPR))
			{
				$setting = json_decode(file_get_contents(_SRC . $value . '.' . _COPR));
				if($setting->deleted != 1)
					$output = true;
			}
		}

		return $output;
	}

	public static function delete_files($srsc)
	{
		$result = false;

		if($srsc != "" && $srsc != "/")
			foreach ($srsc as  $value) {
				if (is_dir(_SRC . $value) === true){
					$inners = array_diff(scandir(_SRC . $value) , array('.' , '..'));

					if(!empty($inners))
					{
						$inners_path = array();

						foreach ($inners as $valueb){
							if(strpos($valueb , '.') !== false)
								$inners_path[] = $value . $valueb;
							else
								$inners_path[] = $value . $valueb . '/';
						}

						$result += static::delete_files($inners_path);
					}

					if(System::has_file($value . '.' . _COPR))
						$result += !unlink(_SRC . $value . '.' . _COPR);

					$result += !rmdir(_SRC . $value);
				}

				else if (is_file(_SRC . $value) === true)
				{
					if(System::has_file($value . '.' . _COPR))
						$result += !unlink(_SRC . $value . '.' . _COPR);

					$result += !unlink(_SRC . $value);
				}
			}

		return $result;
	}

	public static function rename($new_name , $src)
	{
		$base = basename($src);
		$dir = dirname($src);

		if(is_dir(_SRC . $src) === true)
		{
			if(System::has_file($dir . '/' . $new_name))
				return false;
			else
				return rename(_SRC . $src , _SRC . $dir . '/' . $new_name);
		}
		else
		{
			$dot_pos = strrpos($base , '.' , -1);
			$type = substr($base , $dot_pos - strlen($base) + 1);
			$name = preg_replace('/' . '.' . $type . '/' , '' , $base);
			$new_base = $new_name . '.' . $type;

			if(System::has_file($dir . '/' . $new_base))
				return false;
			else
			{
				if(System::has_file($src . '.' . _COPR))
					rename(_SRC . $src . '.' . _COPR , _SRC . $dir . '/' . $new_base . '.' . _COPR);

				return rename(_SRC . $src , _SRC . $dir . '/' . $new_base);
			}
		}
	}

	public static function move($new_name , $src)
	{
		if(System::has_file($new_name))
			return false;
		else
			return rename(_SRC . $src , _SRC . $new_name);
	}

	public static function copies($address , $source)
	{
		$result = false;

		foreach ($address as $path)
			if(System::has_file($path))
			{
				$base_first = $base = basename(_SRC . $path);

				if($path != $source . $base && $path != $source . $base . '/')
				{
					$number = 1;

					while(true){
						if(System::has_file($source . $base))
						{
							if(is_dir(_SRC . $source . $base) === true){
								$number++;
								$base = $base_first . '_' . $number;
							}
							else
							{
								$number++;
								$dot_pos = strrpos($base_first , '.' , -1);
								$type = substr($base_first , $dot_pos - strlen($base_first) + 1);
								$name = preg_replace('/' . '.' . $type . '/' , '' , $base_first);
								$base = $name . '_' . $number . '.' . $type;
							}
						}
						else
							break;
					}

					if(is_dir(_SRC . $path) === true)
					{
						$inners = scandir(_SRC . $path);
						$result += !mkdir(_SRC . $source . $base , 0755 , true);

						if(!empty($inners))
						{
							$inners_path = array();

							foreach ($inners as $valueb)
								if(!in_array($valueb , array("." , "..")))
								{
									if(strpos($valueb , '.') !== false)
										$inners_path[] = $path . $valueb;
									else
										$inners_path[] = $path . $valueb . '/';
								}

							$result += static::copies($inners_path , $source . $base . '/');
						}
					}
					else if(is_file(_SRC . $path) === true)
					{
						if(System::has_file($path . '.' . _COPR) && basename($path) != '.' . _COPR && !in_array($path . '.' . _COPR , $address))
							$result += !copy(_SRC . $path . '.' . _COPR , _SRC . $source . $base . '.' . _COPR);
							
						$result += !copy(_SRC . $path , _SRC . $source . $base);
					}
				}
			}
		return $result;
	}

	public static function archive($address , $source , $name)
	{
		$result = false;

		$zip = new ZipArchive();
		$filename = $name . ".zip";
		$number = 1;

		while(true){
			if(System::has_file($source . $filename))
			{
				$number++;
				$filename = $name . '_' . $number . ".zip";
			}
			else
				break;
		}

		if($zip->open(_SRC . $source . $filename , ZipArchive::CREATE) !== false){
			foreach ($address as $path)
				if(System::has_file($path))
				{
					if(is_dir(_SRC . $path) === true){
						$inners = static::get_sub_file($path);

						if(!empty($inners))
							foreach ($inners as $valueb)
								$result += !$zip->addFile(_SRC . $valueb , str_replace(dirname(_SRC . $path) . '/' , "" , _SRC . $valueb));
					}
					else
						$result += !$zip->addFile(_SRC . $path , basename($path));
				}

			$zip->close();
		}

		return $result;
	}

	public static function extract($file , $source , $download_file = true)
	{
		$result = true;

		$zip = new ZipArchive;

		$base = basename($file);
		$dot_pos = strrpos($base , '.' , -1);
		$type = substr($base , $dot_pos - strlen($base) + 1);
		$name = preg_replace('/' . '.' . $type . '/' , '' , $base);

		$filename = $name;
		$number = 1;

		while(true){
			if(System::has_file($source . $filename))
			{
				$number++;
				$filename = $name . '_' . $number;
			}
			else
				break;
		}

		if($zip->open(_SRC . $file) === TRUE)
		{
			$result = $zip->extractTo(_SRC . $source . $filename . '/');
			$zip->close();

			if($result && $download_file)
				static::add_files($source . $filename . '/');
		}

		return $result;
	}

	public static function new_folder($folder , $source , $download_file = true)
	{
		$result = true;

		$folders = explode(" " , $folder);

		foreach ($folders as $value)
			if(!System::has_file($source . $value)){
				if(mkdir(_SRC . $source . $value , 0755 , true)){
					$html_file = fopen(_SRC . $source . $value . "/index.html" , "w");
					fwrite($html_file , "<!DOCTYPE html><title></title>");
					fclose($html_file);

					if($download_file)
					{
						$file = fopen(_SRC . $source . $value . '/.' . _COPR , "w");
						fwrite($file , json_encode(array('permission' => 5 , 'downloads' => 0 , 'deleted' => 1 , 'users' => array('-1')) , JSON_PRETTY_PRINT));
						fclose($file);
					}
				}
				else
					$result = false;
			}
			else
				$result = false;

		return $result;
	}

	public static function new_file($file , $source)
	{
		$result = true;

		if(!System::has_file($source . $file))
		{
			$new_file = fopen(_SRC . $source . $file , "w");
			fclose($new_file);

			$file = fopen(_SRC . $source . $file . '.' . _COPR , "w");
			fwrite($file , json_encode(array('permission' => 5 , 'downloads' => 0 , 'deleted' => 1 , 'users' => array('-1')) , JSON_PRETTY_PRINT));
			fclose($file);
		}
		else
			$result = false;

		return $result;
	}

	public static function get_sub_file($source)
	{
		$src = array();
		$inners = scandir(_SRC . $source);

		if(!empty($inners))
		{
			foreach ($inners as $valueb)
				if(!in_array($valueb , array("." , ".." , "index.html" , "." . _COPR)))
				{
					if(strpos($valueb , '.') !== false)
					{
						$dot_pos = strrpos($valueb , '.' , -1);
						$type = substr($valueb , $dot_pos - strlen($valueb) + 1);

						if($type != _COPR)
							$src[] = $source . $valueb;
					}
					else
						$src = array_merge($src , static::get_sub_file($source . $valueb . '/'));
				}
		}

		return $src;
	}

	public static function add_files($source)
	{
		if(is_dir(_SRC . $source) === true)
		{
			$inners = scandir(_SRC . $source);

			if(!empty($inners))
			{
				foreach ($inners as $valueb)
					if(!in_array($valueb , array("." , ".." , "." . _COPR)))
						static::add_files($source . '/' . $valueb);
			}

			if(empty($inners) || !in_array("index.html" , $inners))
			{
				$html_file = fopen(_SRC . $source . "/index.html" , "w");
				fwrite($html_file , "<!DOCTYPE html><title></title>");
				fclose($html_file);

				$file = fopen(_SRC . $source . '/.' . _COPR , "w");
				fwrite($file , json_encode(array('permission' => 5 , 'downloads' => 0 , 'deleted' => 1 , 'users' => array('-1')) , JSON_PRETTY_PRINT));
				fclose($file);
			}
		}
		else
		{
			if(System::has_file($source) && !System::has_file($source . '.' . _COPR))
			{
				$file = fopen(_SRC . $source . '.' . _COPR , "w");
				fwrite($file , json_encode(array('permission' => 5 , 'downloads' => 0 , 'deleted' => 1 , 'users' => array('-1')) , JSON_PRETTY_PRINT));
				fclose($file);
			}
		}
	}

	// mohasebeye size file
	public static function get_file_size($file_size)
	{
		if($file_size > 1073741824)
			$file_size_new = round($file_size / 1073741824 , 2) . " GB";
		else if($file_size > 1048576)
			$file_size_new = round($file_size / 1048576 , 2) . " MB";
		else if($file_size > 1024)
			$file_size_new = round($file_size / 1024 , 2) . " KB";
		else
			$file_size_new = $file_size . " B";

		return $file_size_new;
	}

	// disk total free space
	public static function free_space($source)
	{
		return disk_free_space($source);
	}

	// disk total space
	public static function total_space($source)
	{
		return disk_total_space($source);
	}

	// resize kardane image
	public static function resize_image($file , $thumbnail , $type , $width = 100){
		$size = getimagesize($file);
		$height = round($width * ($size[1] / $size[0]));

		if($type == 'jpg' || $type == 'jpeg')
			$src = imagecreatefromjpeg($file);
		else if($type == 'png')
			$src = imagecreatefrompng($file);
		else if($type == 'gif')
			$src = imagecreatefromgif($file);

		$dest = imagecreatetruecolor($width , $height);

		imagealphablending($dest , false);
		imagesavealpha($dest , true);
		$transparent = imagecolorallocatealpha($dest , 255 , 255 , 255 , 127);
		imagefilledrectangle($dest , 0 , 0 , $width , $height , $transparent);

		imagecopyresampled($dest , $src , 0 , 0 , 0 , 0 , $width , $height , $size[0] , $size[1]);

		if($type == 'jpg' || $type == 'jpeg')
			return imagejpeg($dest , $thumbnail);
		else if($type == 'png')
			return imagepng($dest , $thumbnail);
		else if($type == 'gif')
			return imagegif($dest , $thumbnail);
	}

	// bargardandane file type
	private static function get_file_type($type , $path)
	{
		$output = "<div class=\"file-image ";
		switch ($type) {
			case 'gif': case 'jpg': case 'jpeg': case 'png':
				$output .= " icon-file-picture image\" source-image=\"" . str_replace(Site::$base . 'uploads' , Site::$base . 'download' , Site::$base . $path) . "\">";
				break;
			case 'ai':
				$output .= " icon-file-illustrator\">";
				break;
			case 'bmp':
				$output .= " icon-file-picture\">";
				break;
			case 'css': case 'html': case 'js': case 'json': case 'php': case 'xml': case 'svg':
				$output .= " icon-file-code\">";
				break;
			case 'csv': case 'xls': case 'xlsx':
				$output .= " icon-file-excel\">";
				break;
			case 'doc': case 'docx':
				$output .= " icon-file-word\">";
				break;
			case 'eot': case 'ttf': case 'woff': case 'woff2':
				$output .= " icon-file-font\">";
				break;
			case 'mp3': case 'wav':
				$output .= " icon-file-music\">";
				break;
			case 'pdf':
				$output .= " icon-file-pdf\">";
				break;
			case 'ppt': case 'pptx':
				$output .= " icon-file-play\">";
				break;
			case 'psd':
				$output .= " icon-file-photoshop\">";
				break;
			case 'rar': case 'zip':
				$output .= " icon-file-zip\">";
				break;
			case 'swf': case 'mp4': case 'ogg': case 'webm':
				$output .= " icon-file-video\">";
				break;
			case 'txt':
				$output .= " icon-file-text\">";
				break;
			default:
				$output .= " icon-file\"><span>" . $type . '</span>';
		}

		$output .= "</div>";
		return $output;
	}
}