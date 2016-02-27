<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	08/16/2015
	*	last edit		08/16/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class PluginsModel extends Model {
	public function update_plugins()
	{
		// Details
		$read_details = json_decode(file_get_contents(_SRC_SITE . 'plugins/' . $_POST['field_input_category'] .'/' . $_POST['field_input_type'] . '/details.json') , true);
		$details = $setting = array();
		$item = 0;

		if(isset($read_details['fields']))
			$details = $read_details['fields'];

		while (true) {
			if(isset($details[$item]['name']) && isset($_POST['field_input_pd_' . $details[$item]['name']]))
			{
				$setting[$details[$item]['name']] = $_POST['field_input_pd_' . $details[$item]['name']];
				$item++;
			}
			else
				break;
		}

		$setting = htmlspecialchars(json_encode($setting , JSON_UNESCAPED_UNICODE));

		$this->table('plugins');
		$this->update(array(
							array('name' , trim($_POST['field_input_name'])) , 
							array('setting' , $setting)
						));

		$this->where('`id` = ' . $_GET['id']);
		return $this->process();
	}
}