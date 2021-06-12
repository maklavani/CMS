<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	06/15/2015
	*	last edit		12/04/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class MenuModel extends Model {
	public function get_menu(&$menus)
	{
		$this->table('components')->where('`location` = "administrator"')->select()->process();
		$coms = $this->output();

		foreach ($coms as $key => $value) {
			if(System::has_file(_ADM . 'components/' . $value->type . '/details.json'))
			{
				$details = json_decode(file_get_contents(_COMP . $value->type . '/details.json'));

				if(isset($details->language))
					foreach ($details->language as $lang)
						if($lang->name == Language::$lang && System::has_file(_ADM . 'languages/' . $lang->name . '/' . $lang->src))
							Language::add_ini_file(_LANG . $lang->name . '/' . $lang->src);

				if(isset($details->menus))
				{
					$menus_b = array();
					foreach ($details->menus as $menu){
						$vals = $value->type . '_' . $menu;
						$menus_b[] = array('vals' => strtolower($vals) , 'text' => Language::_(strtoupper('COM_' . $vals)));
					}

					$menus[] = array('text' => Language::_(strtoupper('COM_' . $value->type)) , 'children' => $menus_b);
				}
			}
		}
	}

	public function has_component($name)
	{
		$this->table('components')->where('`type` = "' . $name . '"')->select()->process();
		return $this->output();
	}
}