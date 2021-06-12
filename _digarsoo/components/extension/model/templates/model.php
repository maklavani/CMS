<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	08/13/2015
	*	last edit		12/04/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class TemplatesModel extends Model {
	public function set_showing($id , $location)
	{
		Messages::add_message('success' , Language::_('COM_EXTENSION_SHOWING_SET'));
		$this->table('templates')->update(array(array('showing' , 0)))->where('`showing` = 1 AND `location` = "' . $location . '"')->process();
		return $this->table('templates')->update(array(array('showing' , 1)))->where('`id` = ' . $id)->process();
	}

	public function update_templates()
	{
		$template = $this->get_with_id($_GET['id'] , 'templates');
		
		if($_POST['field_input_showing'] == 'show' && !$template[0]->showing)
			$this->set_showing($_GET['id'] , $_POST['field_input_location']);

		// Details
		if($_POST['field_input_location'] == 'site')
			$read_details = json_decode(file_get_contents(_SRC_SITE . 'templates/' . $_POST['field_input_type'] . '/details.json') , true);
		else
			$read_details = json_decode(file_get_contents(_SRC . 'templates/' . $_POST['field_input_type'] . '/details.json') , true);

		$details = $setting = array();
		$item = 0;

		if(isset($read_details['fields']))
			$details = $read_details['fields'];

		while (true) {
			if(isset($details[$item]['name']) && isset($_POST['field_input_tp_' . $details[$item]['name']]))
			{
				$setting[$details[$item]['name']] = $_POST['field_input_tp_' . $details[$item]['name']];
				$item++;
			}
			else
				break;
		}

		$setting = htmlspecialchars(json_encode($setting , JSON_UNESCAPED_UNICODE));
		return $this->table('templates')->update(array(array('name' , $_POST['field_input_name']) , array('setting' , $setting)))->where('`id` = ' . $_GET['id'])->process();
	}
}