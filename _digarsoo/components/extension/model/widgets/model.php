<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	06/15/2015
	*	last edit		01/24/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class WidgetsModel extends Model {
	public function save()
	{
		$this->table('extension')->where('`id` = ' . $_GET['widget'])->select()->process();
		$widget = $this->output();

		$this->table('widgets')->select(false , 'MAX(id)')->where('`location` = "' . $widget[0]->location . '"')->process();
		$output = $this->output('assoc');
		$max_id = $output[0]['MAX(id)'] + 1;

		if($widget[0]->location == "administrator" && $max_id < 51)
			$max_id = 51;
		else if($widget[0]->location == "site" && $max_id < 101)
			$max_id = 101;

		// Details
		$read_details = json_decode(file_get_contents(_SRC_SITE . 'widgets/' . $widget[0]->name . '/details.json') , true);
		$details = $setting = array();
		$item = 0;

		if(isset($read_details['fields']))
			$details = $read_details['fields'];

		while (true) {
			if(isset($details[$item]['name']) && isset($_POST['field_input_wd_' . $details[$item]['name']]))
			{
				$val = $_POST['field_input_wd_' . $details[$item]['name']];

				if($details[$item]['type'] == 'image')
				{
					$images = explode(',' , $val);

					foreach ($images as $key => $value) {
						$address = explode("/" , $value);
						if($address[0] == "uploads")
							$address[0] = 'download';
						$images[$key] = implode("/" , $address);
					}

					$val = implode(',' , $images);
				}

				$setting[$details[$item]['name']] = str_replace('"' , '&quot;' , $val);
				$item++;
			}
			else
				break;
		}

		$setting = htmlspecialchars(json_encode($setting , JSON_UNESCAPED_UNICODE));

		$this->table('widgets');
		$this->insert(	array(	'id' , 'name' , 'status' , 'show_name' , 'type' , 'permission' , 'position' , 
								'setting' , 'menus' , 'menu_type' , 'location' , 'languages') , 
						array(	$max_id , trim($_POST['field_input_name']) , $_POST['field_input_status'] , $_POST['field_input_show_name'] , $_POST['field_input_type'] , $_POST['field_input_permission'] , $_POST['field_input_position'] , 
								$setting , htmlspecialchars($_POST['field_input_menus']) , $_POST['field_input_menu_type'] , $widget[0]->location , $_POST['field_input_languages'])
					);
		$this->process();
		return $this->last_insert_id;
	}

	public function update_widgets()
	{
		// Details
		$read_details = json_decode(file_get_contents(_SRC_SITE . 'widgets/' . $_POST['field_input_type'] . '/details.json') , true);
		$details = $setting = array();
		$item = 0;

		if(isset($read_details['fields']))
			$details = $read_details['fields'];

		while (true) {
			if(isset($details[$item]['name']) && isset($_POST['field_input_wd_' . $details[$item]['name']]))
			{
				$val = $_POST['field_input_wd_' . $details[$item]['name']];

				if($details[$item]['type'] == 'image')
				{
					$images = explode(',' , $val);

					foreach ($images as $key => $value) {
						$address = explode("/" , $value);
						if($address[0] == "uploads")
							$address[0] = 'download';
						$images[$key] = implode("/" , $address);
					}

					$val = implode(',' , $images);
				}

				$setting[$details[$item]['name']] = str_replace('"' , '&quot;' , $val);
				$item++;
			}
			else
				break;
		}

		$setting = htmlspecialchars(json_encode($setting , JSON_UNESCAPED_UNICODE));

		$this->table('widgets');
		$this->update(array(
							array('name' , trim($_POST['field_input_name'])) , 
							array('status' , $_POST['field_input_status']) , 
							array('show_name' , $_POST['field_input_show_name']) , 
							array('type' , $_POST['field_input_type']) , 
							array('permission' , $_POST['field_input_permission']) , 
							array('position' , $_POST['field_input_position']) , 
							array('setting' , $setting) , 
							array('menus' , htmlspecialchars($_POST['field_input_menus'])) , 
							array('menu_type' , $_POST['field_input_menu_type']) , 
							array('languages' , $_POST['field_input_languages'])
						));

		$this->where('`id` = ' . $_GET['id']);
		return $this->process();
	}
}