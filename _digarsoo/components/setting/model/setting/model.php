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

class SettingModel extends Model {
	public function read_details($id)
	{
		$details = false;
		$output = $this->get_component($id);

		if($output && System::has_file(_ADM . 'components/' . $output[0]->type . '/details.json'))
			$details = file_get_contents(_COMP . $output[0]->type . '/details.json');

		return $details;
	}

	public function get_component($id)
	{
		$permission = '';
		foreach (User::$permissions as $key => $value) {
			if($key)
				$permission .= ' OR ';	
			$permission .= "`permission` = " . $value;
		}

		$this->table('components')->where('`id` = ' . $id . ' AND `status` = 0 AND `location` = "administrator" AND (' . $permission . ')')->select()->process();
		return $this->output();
	}

	public function save_setting($component)
	{
		$details = false;

		if(System::has_file(_ADM . 'components/' . $component->type . '/details.json'))
			$details = json_decode(file_get_contents(_COMP . $component->type . '/details.json'));

		if(isset($details->fields))
		{
			$setting = array();

			foreach ($details->fields as $key => $value){
				$settingb = array();

				foreach ($value as $keyb => $valueb)
					if(isset($_POST['field_input_' . $valueb->name])){
						$settingb[$valueb->name] = $_POST['field_input_' . $valueb->name];
					}

				if(!empty($settingb))
					$setting[$key] = $settingb;
			}

			if(!empty($setting))
				$this->table('components')->update(array(array('setting' , htmlspecialchars(json_encode($setting , JSON_UNESCAPED_UNICODE)))))->where('`id` = ' . $component->id . ' OR (`type` = "' . $component->type . '" AND `location` = "site")')->process();
		}
	}
}