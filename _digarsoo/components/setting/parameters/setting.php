<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	06/15/2015
	*	last edit		12/03/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class ParametersSetting {
	public $buttons;

	function __construct()
	{
		$this->buttons = array();

		$permission = '';
		foreach (User::$permissions as $key => $value) {
			if($key)
				$permission .= ' OR ';	
			$permission .= "`permission` = " . $value;
		}

		$db = new Database;
		$db->table('components')->where('`status` = 0 AND `location` = "administrator" AND (' . $permission . ')')->select()->process();
		$output = $db->output();

		$this->buttons[] = array('name' => Language::_('COM_SETTING_ALL') , 'link' => 'index.php?component=setting');

		if($output)
			foreach ($output as $key => $value) {
				// khandane language ha
				if(System::has_file(_ADM . 'components/' . $value->type . '/details.json'))
				{
					$details = json_decode(file_get_contents(_COMP . $value->type . '/details.json'));

					if(isset($details->language))
						foreach ($details->language as $lang)
							if($lang->name == Language::$lang && System::has_file(_ADM . 'languages/' . $lang->name . '/' . $lang->src))
								Language::add_ini_file(_LANG . $lang->name . '/' . $lang->src);
				}

				if(isset($details->fields))
					$this->buttons[$value->id] = array('name' => Language::_('COM_' . strtoupper($value->type)) , 'link' => 'index.php?component=setting&amp;view=component&amp;id=' . $value->id);
			}
	}
}