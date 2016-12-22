<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/12/2015
	*	last edit		11/05/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class Url_friendlySystem extends SystemPlugins {
	public function get($buffer)
	{
		// href
		preg_match_all('@href="([^"]+)"@iU' , $buffer , $urls);

		if(isset($urls[1]) && is_array($urls[1]) && !empty($urls[1]))
		{
			$db = new Database;
			$component = array();

			$db->table('menu')->where('`status` = 0 AND `location` = "site" AND (`languages` = "all" OR `languages` = "' . Language::$lang . '")')->select()->process();
			$menu = $db->output();

			$allmenu = array();
			if($menu)
				foreach ($menu as $key => $value)
					$allmenu[$value->link] = $value;

			foreach ($urls[1] as $value)
			{
				$abbreviation = false;

				if(Language::$abbreviation != "" && mb_strpos($value , Language::$abbreviation . '/') !== false && mb_strpos($value , Language::$abbreviation . '/') == 0)
				{
					$abbreviation = true;
					$value = str_replace(Language::$abbreviation . '/' , "" , $value);
				}

				if(mb_strpos($value , 'index.php') > -1 && mb_strpos($value , "/") === false && Regex::cs($value , 'text_d'))
				{
					$value_test = str_replace("&amp;" , "&" , $value);
					$ent_value_test = htmlentities($value_test , ENT_COMPAT | ENT_QUOTES , "UTF-8");

					if(isset($allmenu[$ent_value_test]) && $allmenu[$ent_value_test]->homepage)
						$link_out = Site::$base . (Language::$abbreviation ? Language::$abbreviation . '/' : "");
					else if(isset($allmenu[$ent_value_test]) && 
							isset($allmenu[$ent_value_test]->alias) && 
							$allmenu[$ent_value_test]->alias != "")
						$link_out = Site::$base . (Language::$abbreviation ? Language::$abbreviation . '/' : "") . $allmenu[$ent_value_test]->alias;
					else
					{
						$link = str_replace("index.php?" , "" , $value_test);
						$gets = explode("&" , $link);

						$link_out = Site::$base . (Language::$abbreviation ? Language::$abbreviation . '/' : "");
						$link_total = array();

						if(!empty($gets))
							foreach ($gets as $valueb) {
								$get = explode("=" , $valueb);
								if(isset($get[0]) && isset($get[1]))
									$link_total[$get[0]] = $get[1];
							}

						$link_component = "";

						if(isset($link_total['component']) && !isset($component[$link_total['component']]) && Regex::cs($link_total['component'] , 'text'))
						{
							$db->table('components')->where('`type` = "' . $link_total['component'] . '" AND `location` = "site"')->select()->process();
							$component = $db->output();

							if(isset($component[0]) && User::has_permission($component[0]->permission))
							{
								if(System::has_file('languages/' . Language::$lang . '/com_' . strtolower($component[0]->type) . '_url.json'))
								{
									$component[$component[0]->type] = array();
									$languages = json_decode(file_get_contents(_LANG . Language::$lang . '/com_' . strtolower($component[0]->type) . '_url.json'));

									foreach ($languages as $keyb => $valueb)
										$component[$component[0]->type][$keyb] = $valueb;
								}
							}
						}

						if(isset($component[$link_total['component']]) && !empty($link_total)){
							$com = $link_total['component'];

							$item = 0;
							foreach ($link_total as $keyb => $valueb){
								$keyc = 'COM_' . strtoupper($com . '_' . $keyb . '_' . $valueb);

								if($item)
									$link_component .= '/';

								if(isset($component[$com][$keyc]))
									$link_component .= str_replace(' ' , '-' , $component[$com][$keyc]);
								else
									$link_component .= $valueb;

								$item++;
							}
						}

						if($link_component == "")
							$link_out = $link_out . implode('/' , $link_total);
						else
							$link_out = $link_out . $link_component;
					}

					if($abbreviation)
						$buffer = str_replace('href="' . Language::$abbreviation . '/' . $value . '"' , 'href="' . $link_out . '"' , $buffer);
					else
						$buffer = str_replace('href="' . $value . '"' , 'href="' . $link_out . '"' , $buffer);
				}
			}
		}

		// src
		preg_match_all('@src="([^"]+)"@iU' , $buffer , $srcs);

		if(isset($srcs[1]) && is_array($srcs[1]) && !empty($srcs[1]))
		{
			foreach ($srcs[1] as $value)
			{
				if(((Site::$base != "/" && mb_strpos($value , Site::$base) === false) || (Site::$base == "/" && mb_strpos($value , Site::$base) > 0)) && mb_strpos($value , 'http://') === false && mb_strpos($value , 'https://') === false && mb_strpos($value , 'data:image/') === false && mb_strpos($value , "download/") == 0)
				{
					$src = Site::$base . $value;
					$buffer = str_replace('src="' . $value . '"' , 'src="' . $src . '"' , $buffer);
				}
			}
		}

		return $buffer;
	}
}