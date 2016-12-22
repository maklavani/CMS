<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	03/28/2015
	*	last edit		12/11/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class Menus {
	public $menus;

	// ejraye constructor
	function __construct($menu_group)
	{
		$this->menus = array(0 => array('node' => null , 'child' => array() , 'class' => ''));

		$db = New Database;
		$db->table('menu')->where('`status` = 0 AND `group_number` = "' . $menu_group .'"')->order('`index_number` ASC , `parent` ASC')->select()->process();
		$menus = $db->output();

		if(_LOC == 'administrator'){
			$db->table('menu_group')->where('`location` = "site"')->select()->process();
			$menu_group = $db->output();

			$count = count($menus);

			$db->table('menu')->where('`homepage` = 1')->select()->process();
			$homepages = $db->output();

			foreach ($menu_group as $key => $value) {
				$class = "";

				if(!empty($homepages))
					foreach ($homepages as $valueb)
						if($valueb->group_number == $value->id)
							if($class != "")
								$class .= ' flag-' . $valueb->languages;
							else
								$class .= ' flag flag-' . $valueb->languages;

				$menus[$count + $key] = (object) array(	'id' => 1000 + ($key + 1) , 'name' => $value->name , 'type' => 'link' , 
														'link' => 'index.php?component=menus&amp;view=group&amp;id=' . $value->id , 
														'parent' => 8 , 'index' => $key + 1 , 'permission' => 2 , 'icon' => null , 
														'setting' => json_encode(array('title' => '' , 'show_status' => '1' , 'class' => $class) , JSON_UNESCAPED_UNICODE));
			}
		}

		$db->table('components')->select()->where('`location` = "' . _LOC . '"')->process();
		$output = $db->output();

		// Check Components Add Menu
		if(!empty($output))
			foreach ($output as $key => $value){
				$source = ($value->location == 'administrator' ? _ADM : "") . 'components/' . $value->type . '/';

				if(System::has_file($source . 'details.json'))
				{
					$details = json_decode(file_get_contents(_SRC_SITE . $source . 'details.json'));

					if(isset($details->addmenu))
					{
						// khandane file language
						if(isset($details->language))
							foreach ($details->language as $lang)
								if($lang->name == Language::$lang && System::has_file(($value->location == 'administrator' ? _ADM : "") . 'languages/' . $lang->name . '/' . $lang->src))
									Language::add_ini_file(_LANG . $lang->name . '/' . $lang->src);

						// khandane menuha
						foreach ($details->addmenu as $keyb => $valueb)
							if(	isset($valueb->title) && isset($valueb->source) && System::has_file($source . $valueb->source))
							{
								require_once $file = _SRC_SITE . $source . $valueb->source;
								$class = ucwords(strtolower($valueb->title)) . 'AddMenu';

								// agar class vujud dasht
								if(class_exists($class))
								{
									$component = new $class();
									$menus = array_merge($menus , $component->output(2001 + $key , $menu_group));
								}
							}
					}
				}
			}

		if(is_array($menus))
			$this->build_tree($menus , $this->menus[0]['child'] , 0 , 0);
	}

	// sakhtane derakht
	public function build_tree($menus , &$parent_node , $parent , $level)
	{
		$class_return = '';

		foreach ($menus as $key => $value)
			if($value->parent == $parent && User::has_permission($value->permission))
			{
				$class = '';
				$parent_node[$value->id] = array('node' => $value , 'child' => array() , 'class' => 'level-' . ($level + 1));
				$class = $this->build_tree($menus , $parent_node[$value->id]['child'] , $value->id , $level + 1);

				if(!empty($parent_node[$value->id]['child']))
					$parent_node[$value->id]['class'] .= ' parent ' . $class;

				if($class == 'active')
					$class_return = 'active';

				if($value->id == Widgets::$active_menu)
				{
					$parent_node[$value->id]['class'] .= ' active current';
					$class_return = 'active';
				}
				else if(Widgets::$active_menu == -1 && $value->link != "" && (
						($value->link == 'index.php' && Site::$full_link_text == Site::$base . $value->link) ||
						($value->link != 'index.php' && strpos(Site::$full_link_text , $value->link) > -1)))
				{
					$parent_node[$value->id]['class'] .= ' active current';
					$class_return = 'active';
					Widgets::$active_menu = $value->id;
				}
			}

		return $class_return;
	}

	// kuruji dadane menu
	public function output()
	{
		$output = "\t\t\t\t<ul class=\"menu\">";
		$output .= $this->output_child($this->menus[0]['child'] , "\n\t\t\t\t\t");
		$output .= "\n\t\t\t\t</ul>";

		return $output;
	}

	// output node ha
	private function output_child($menus , $space)
	{
		$output = '';

		if(!empty($menus))
			foreach ($menus as $key => $value){
				$setting = isset($value['node']->setting) ? json_decode($value['node']->setting) : array('title' => '' , 'show_status' => '1' , 'class' => '');

				$outputb = $this->output_child($value['child'] , $space . "\t\t");
				
				$output .= $space . "<li class=\"item-" . $value['node']->id . " " . $value['class'];
				if(isset($setting->class))
					$output .= " " . $setting->class;
				$output .= "\">";

				if($value['node']->link == "#")
					$output .= $space . "\t<a href=\"#\">";
				else if(isset($value['node']->alias) && $value['node']->alias != "")
					$output .= $space . "\t<a href=\"" . Site::$base . _ADM . $value['node']->alias . "\">";
				else
					$output .= $space . "\t<a href=\"" . Site::$base . _ADM . $value['node']->link . "\">";

				if($value['node']->icon != null && $value['node']->icon != "")
					$output .= '<div class="link-icon ' . $value['node']->icon . '"></div>';
				else
					$output .= '<div class="link-icon"></div>';

				$output .= '<div class="link-text">' . Language::_($value['node']->name) . '</div>';

				$output .= "</a>";

				if($outputb != '')
				{
					$output .= $space . "\t<ul>";
					$output .= $outputb;
					$output .= $space . "\t</ul>";
				}

				$output .= $space . "</li>";
			}

		return $output;
	}
}