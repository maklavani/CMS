<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	12/30/2015
	*	last edit		10/03/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class SettingModel extends Model {
	// Install
	public function install()
	{
		if(isset($_FILES['field_input_file']['name']) && $_FILES['field_input_file']['name'] != "")
		{
			$file = $_FILES['field_input_file'];

			if(in_array($file['type'] , array("application/zip" , "application/x-zip-compressed")))
			{
				$free = System::free_space(_SRC_SITE);

				if($file['size'] <= $free)
				{
					$new_address = 'temp/' . $file['name'];

					if(move_uploaded_file($file["tmp_name"] , _SRC_SITE . $new_address))
					{
						$new_name = Regex::random_string(10);
						System::rename($new_name , $new_address);
						System::extract('temp/' . $new_name . '.zip' , 'temp/' , false);
						System::delete_files(array('temp/' . $new_name . '.zip'));

						if(System::has_file('temp/' . $new_name . '/details.json'))
						{
							$details = json_decode(file_get_contents(_SRC_SITE . 'temp/' . $new_name . '/details.json'));

							if(isset($details->type) && in_array($details->type , array('component' , 'widget' , 'plugin' , 'template' , 'language')))
							{
								if(	isset($details->location) && (
											in_array($details->location , array('site' , 'administrator')) || 
											(in_array($details->type , array('plugin' , 'language')) && $details->location == 'all')
										))
								{
									if(isset($details->name))
									{
										$name = $details->name;
										$this->table('extension')->select()->where('`type` = "' . $details->type . '" AND `location` = "' . $details->location . '" AND `name` = "' . $name . '"')->process();
										$output = $this->output();

										if(!empty($output) && !(isset($details->method) && $details->method == 'upgrade'))
											Messages::add_message('error' ,  sprintf(Language::_('COM_EXTENSION_ERROR_EXIST_NAME') , $details->type));
										else
										{
											// Delete Exist Extension
											if(!empty($output))
												$this->delete_details_extension($details->name , $details->location , $details->type , isset($details->label) ? $details->label : "" , isset($details->category) ? $details->category : "");

											// Install Extension
											if($details->type == 'component')
												$this->install_component('temp/' . $new_name . '/' , $details , $output);
											else if($details->type == 'widget')
												$this->install_widget('temp/' . $new_name . '/' , $details , $output);
											else if($details->type == 'plugin')
												$this->install_plugin('temp/' . $new_name . '/' , $details , $output);
											else if($details->type == 'template')
												$this->install_template('temp/' . $new_name . '/' , $details , $output);
											else if($details->type == 'language')
												$this->install_language('temp/' . $new_name . '/' , $details , $output);
										}
									}
									else
										Messages::add_message('error' ,  Language::_('COM_EXTENSION_ERROR_NOT_FOUND_NAME'));
								}
								else
									Messages::add_message('error' ,  Language::_('COM_EXTENSION_ERROR_LOCATION_INCORRECT'));
							}
							else
								Messages::add_message('error' ,  Language::_('COM_EXTENSION_ERROR_TYPE_INCORRECT'));
						}
						else
							Messages::add_message('error' , sprintf(Language::_('COM_EXTENSION_ERROR_NOT_FOUND_FILE') , 'details.json'));
					}
					else
						Messages::add_message('error' , sprintf(Language::_('COM_EXTENSION_ERROR_MOVE_FILE') , $file['name']));
				}
				else
					Messages::add_message('error' , sprintf(Language::_('COM_EXTENSION_ERROR_SELECT_SIZE_FILE') , System::get_file_size($free)));
			}
			else
				Messages::add_message('error' , Language::_('COM_EXTENSION_ERROR_SELECT_ZIP_FILE'));
		}
		else
			Messages::add_message('warning' , Language::_('COM_EXTENSION_ERROR_SELECT_FILE'));
	}

	// Install Widget
	private function install_widget($source , $details , $update)
	{
		// Get Max ID
		$max_id = $this->get_max_id($details->type , $details->location , 3051 , 3151);

		// Exist Files and Folders
		$exist_files = $this->exist_files_in_details($source , $details);
		$exist_folders = $this->exist_folders_in_details($source , $details);

		if($exist_files && $exist_folders)
		{
			// New Name and New Source
			$new_source = ($details->location == "administrator" ? _ADM : "") . 'widgets/';
			$folder_name = str_replace(" " , "_" , $details->name) . '/';
			
			// Copy Files and Folders
			$this->copy_files_folders($details , $folder_name , $new_source , $source);

			// Read Language File
			if(isset($details->language))
				foreach ($details->language as $lang)
					if($lang->name == Language::$lang && System::has_file(($details->location == 'administrator' ? _ADM : '') . 'languages/' . $lang->name . '/' . $lang->src))
						Language::add_ini_file(_SRC_SITE . ($details->location == 'administrator' ? _ADM : '') . 'languages/' . $lang->name . '/' . $lang->src);

			// Update Server
			if(!empty($update))
				$this->table('extension')->update(array(array('update_date' , Site::$datetime)))->where('`id` = ' . $update[0]->id)->process();
			else
				$this->table('extension')->insert(	array('id' , 'name' , 'type' , 'update_date' , 'create_date' , 'location') , 
													array($max_id , $details->name , $details->type , Site::$datetime , Site::$datetime , $details->location)
											)->process();

			Messages::add_message('success' , sprintf(Language::_('COM_EXTENSION_SUCCESS_INSTALL') , $details->name));

			if(isset($details->description))
				Messages::add_message('' , Language::_($details->description));

			// Script
			if(System::has_file($source . 'script.php'))
				require_once _SRC_SITE . $source . 'script.php';
		}
	}

	// Install Template
	private function install_template($source , $details , $update)
	{
		// Get Max ID
		$max_id = $this->get_max_id($details->type , $details->location , 3051 , 3151);

		// Exist Files and Folders
		$exist_files = $this->exist_files_in_details($source , $details);
		$exist_folders = $this->exist_folders_in_details($source , $details);

		if($exist_files && $exist_folders)
		{
			// New Name and New Source
			$new_source = ($details->location == "administrator" ? _ADM : "") . 'templates/';
			$folder_name = str_replace(" " , "_" , $details->name) . '/';

			// Copy Files and Folders
			$this->copy_files_folders($details , $folder_name , $new_source , $source);

			// Read Language File
			if(isset($details->language))
				foreach ($details->language as $lang)
					if($lang->name == Language::$lang && System::has_file(($details->location == 'administrator' ? _ADM : '') . 'languages/' . $lang->name . '/' . $lang->src))
						Language::add_ini_file(_SRC_SITE . ($details->location == 'administrator' ? _ADM : '') . 'languages/' . $lang->name . '/' . $lang->src);

			// Update Server
			if(!empty($update))
				$this->table('extension')->update(array(array('update_date' , Site::$datetime)))->where('`id` = ' . $update[0]->id)->process();
			else
			{
				$this->table('extension')->insert(	array('id' , 'name' , 'type' , 'update_date' , 'create_date' , 'location') , 
													array($max_id , $details->name , $details->type , Site::$datetime , Site::$datetime , $details->location)
											)->process();

				// Setting
				$setting = array();

				if(isset($details->fields))
					foreach ($details->fields as $key => $value)
						if(isset($value->name))
							$setting[$value->name] = isset($value->default) ? $value->default : "";

				$this->table('templates')->insert(	array('id' , 'name' , 'type' , 'setting' , 'location') , 
													array($max_id - 3000 , Language::_('TEMP_' . strtoupper($details->name)) , $details->name , htmlspecialchars(json_encode($setting , JSON_UNESCAPED_UNICODE)) , $details->location)
											)->process();
			}

			Messages::add_message('success' , sprintf(Language::_('COM_EXTENSION_SUCCESS_INSTALL') , $details->name));

			if(isset($details->description))
				Messages::add_message('' , Language::_($details->description));

			// Script
			if(System::has_file($source . 'script.php'))
				require_once _SRC_SITE . $source . 'script.php';
		}
	}

	// Install Template
	private function install_component($source , $details , $update)
	{
		// Get Max ID
		$max_id = $this->get_max_id($details->type , $details->location , 51 , 151);

		// Exist Files and Folders
		$exist_files = $this->exist_files_in_details($source , $details);
		$exist_folders = $this->exist_folders_in_details($source , $details);

		if($exist_files && $exist_folders)
		{
			// New Name and New Source
			$new_source = ($details->location == "administrator" ? _ADM : "") . 'components/';
			$folder_name = str_replace(" " , "_" , $details->name) . '/';

			// Copy Files and Folders
			$this->copy_files_folders($details , $folder_name , $new_source , $source);

			// Read Language File
			if(isset($details->language))
				foreach ($details->language as $lang)
					if($lang->name == Language::$lang && System::has_file(($details->location == 'administrator' ? _ADM : '') . 'languages/' . $lang->name . '/' . $lang->src))
						Language::add_ini_file(_SRC_SITE . ($details->location == 'administrator' ? _ADM : '') . 'languages/' . $lang->name . '/' . $lang->src);

			// Update Server
			if(!empty($update))
				$this->table('extension')->update(array(array('update_date' , Site::$datetime)))->where('`id` = ' . $update[0]->id)->process();
			else
			{
				$this->table('extension')->insert(	array('id' , 'name' , 'type' , 'update_date' , 'create_date' , 'location') , 
													array($max_id , $details->name , $details->type , Site::$datetime , Site::$datetime , $details->location)
											)->process();

				// Setting
				$setting = array();

				if(isset($details->fields))
					foreach ($details->fields as $key => $value)
					{
						$setting[$key] = array();
						foreach ($value as $keyb => $valueb)
							if(isset($valueb->name))
								$setting[$key][$valueb->name] = isset($valueb->default) ? $valueb->default : "";
					}

				$this->table('components')->insert(	array(	'id' , 'name' , 'status' , 'type' , 
															'permission' , 'setting' , 'location') , 
													array(	$max_id , Language::_('COM_' . strtoupper($details->name)) , 0 , $details->name , 
															isset($details->permission) ? $details->permission : ($details->location == 'administrator' ? 3 : 4) , htmlspecialchars(json_encode($setting , JSON_UNESCAPED_UNICODE)) , $details->location)
											)->process();
			}

			Messages::add_message('success' , sprintf(Language::_('COM_EXTENSION_SUCCESS_INSTALL') , $details->name));

			if(isset($details->description))
				Messages::add_message('' , Language::_($details->description));

			// Script
			if(System::has_file($source . 'script.php'))
				require_once _SRC_SITE . $source . 'script.php';
		}
	}

	// Install Template
	private function install_plugin($source , $details , $update)
	{
		// Get Max ID
		$max_id = $this->get_max_id($details->type , $details->location);

		// Exist Files and Folders
		$exist_files = $this->exist_files_in_details($source , $details);
		$exist_folders = $this->exist_folders_in_details($source , $details);

		if($exist_files && $exist_folders)
		{
			// New Name and New Source
			$new_source = 'plugins/' . $details->category . '/';
			$folder_name = str_replace(" " , "_" , $details->name) . '/';

			// Copy Files and Folders
			$this->copy_files_folders($details , $folder_name , $new_source , $source);

			// Read Language File
			if(isset($details->language))
				foreach ($details->language as $lang)
					if($lang->name == Language::$lang && System::has_file('languages/' . $lang->name . '/' . $lang->src))
						Language::add_ini_file(_SRC_SITE . 'languages/' . $lang->name . '/' . $lang->src);

			// Update Server
			if(!empty($update))
				$this->table('extension')->update(array(array('update_date' , Site::$datetime)))->where('`id` = ' . $update[0]->id)->process();
			else
			{
				$this->table('extension')->insert(	array('id' , 'name' , 'type' , 'update_date' , 'create_date' , 'location') , 
													array($max_id , $details->name , $details->type , Site::$datetime , Site::$datetime , $details->location)
											)->process();

				// Setting
				$setting = array();

				if(isset($details->fields))
					foreach ($details->fields as $key => $value)
						if(isset($value->name))
							$setting[$value->name] = isset($value->default) ? $value->default : "";

				$this->table('plugins')->insert(	array(	'id' , 'name' , 'type' , 'category' , 
															'setting' , 'location') , 
													array(	$max_id - 1000 , Language::_('PLG_' . strtoupper($details->category) . '_' . strtoupper($details->name)) , $details->name , $details->category , 
															htmlspecialchars(json_encode($setting , JSON_UNESCAPED_UNICODE)) , $details->location)
											)->process();
			}

			Messages::add_message('success' , sprintf(Language::_('COM_EXTENSION_SUCCESS_INSTALL') , $details->name));

			if(isset($details->description))
				Messages::add_message('' , Language::_($details->description));

			// Script
			if(System::has_file($source . 'script.php'))
				require_once _SRC_SITE . $source . 'script.php';
		}
	}

	// Install Template
	private function install_language($source , $details , $update)
	{
		// Get Max ID
		$max_id = $this->get_max_id($details->type , $details->location);

		// Exist Files and Folders
		$exist_files = $this->exist_files_in_details($source , $details);
		$exist_folders = $this->exist_folders_in_details($source , $details);

		if($exist_files && $exist_folders)
		{
			// New Name and New Source
			$new_source = 'languages/';
			$folder_name = str_replace(" " , "_" , $details->label) . '/';

			// Copy Files and Folders
			$this->copy_files_folders($details , $folder_name , $new_source , $source);

			// Read Language File
			Language::add_ini_file(_SRC_SITE . 'languages/' . $details->label . '/' . $details->label . '.json');

			// Update Server
			if(!empty($update))
				$this->table('extension')->update(array(array('update_date' , Site::$datetime)))->where('`id` = ' . $update[0]->id)->process();
			else
			{
				$this->table('extension')->insert(	array('id' , 'name' , 'type' , 'update_date' , 'create_date' , 'location') , 
													array($max_id , $details->name , $details->type , Site::$datetime , Site::$datetime , $details->location)
											)->process();

				$this->table('languages')->insert(	array(	'id' , 'name' , 'type' , 'label' , 'abbreviation') , 
													array(	$max_id - 2000 , Language::_('LANG_' . strtoupper($details->name)) , $details->name , $details->label , $details->abbreviation)
											)->process();
			}

			Messages::add_message('success' , sprintf(Language::_('COM_EXTENSION_SUCCESS_INSTALL') , $details->name));

			if(isset($details->description))
				Messages::add_message('' , Language::_($details->description));

			// Script
			if(System::has_file($source . 'script.php'))
				require_once _SRC_SITE . $source . 'script.php';
		}
	}

	// Get Max ID
	private function get_max_id($type , $location , $min_administrator = 0 , $min_site = 0)
	{
		$this->table('extension')->select(false , 'MAX(id)')->where('`type` = "' . $type . '" AND `location` = "' . $location . '"')->process();
		$output = $this->output('assoc');
		$max_id = $output[0]['MAX(id)'] + 1;

		if($location == "administrator" && $max_id < $min_administrator)
			$max_id = $min_administrator;
		else if($location == "site" && $max_id < $min_site)
			$max_id = $min_site;

		return $max_id;
	}

	// Exist Files in Details
	private function exist_files_in_details($source , $details)
	{
		$exist_files = true;

		if(isset($details->files))
			foreach ($details->files as $value)
				if($details->type != "plugin" && !System::has_file($source . $value))
				{
					$exist_files = false;
					Messages::add_message('error' , sprintf(Language::_('COM_EXTENSION_ERROR_NOT_FOUND_FILE') , $value));
				}

		return $exist_files;
	}

	// Exist Folders in Details
	private function exist_folders_in_details($source , $details)
	{
		$exist_folders = true;

		if(isset($details->folders))
			foreach ($details->folders as $value)
				if(!System::has_file($source . $value))
				{
					$exist_folders = false;
					Messages::add_message('error' , sprintf(Language::_('COM_EXTENSION_ERROR_NOT_FOUND_FOLDER') , $value));
				}

		return $exist_folders;
	}

	// Copy Files and Folders in Details
	private function copy_files_folders($details , $folder_name , $new_source , $source)
	{
		if($details->type != "language")
		{
			$folder = $new_source . $folder_name;
			System::new_folder($folder_name , $new_source , false);

			if(isset($details->files))
				foreach ($details->files as $value)
					if(System::has_file($source . $value))
						System::move($folder . $value , $source . $value);

			if(isset($details->folders))
				foreach ($details->folders as $value)
					if(System::has_file($source . $value))
						System::move($folder . $value , $source . $value);

			if(isset($details->language))
				foreach ($details->language as $value)
				{
					if(System::has_file($source . 'language/' . $value->name . '/' . $value->src))
					{
						if(System::has_file($details->location == "administrator" ? _ADM : "") . 'languages/' . $value->name)
							System::move(($details->location == "administrator" ? _ADM : "") . 'languages/' . $value->name . '/' . $value->src , $source . 'language/' . $value->name . '/' . $value->src);
					}
					else
						Messages::add_message('warning' , sprintf(Language::_('COM_EXTENSION_ERROR_NOT_FOUND_FILE') , $value->name));
				}
		}
		else
		{
			$folder = $new_source . $folder_name;
			System::new_folder($folder_name , $new_source , false);
			System::new_folder($folder_name , _ADM . $new_source , false);

			if(isset($details->files))
				foreach ($details->files as $value)
					if(System::has_file($source . $value))
					{
						System::copies(array($source . $value) , $folder);
						System::move(_ADM . $folder . $value , $source . $value);
					}
		}
	}

	// delete Details Extension
	private function delete_details_extension($name , $location , $type , $label = "" , $category = "")
	{
		if(in_array($type , array('component' , 'widget' , 'template' , 'plugin')))
		{
			if($type != "plugin")
			{
				$location = ($location == 'administrator' ? _ADM : "");
				$old_source = $location . $type . 's/' . str_replace(" " , "_" , $name) . '/';
			}
			else
			{
				$location = "";
				$old_source = $location . 'plugins/' . $category . '/' . str_replace(" " , "_" , $name) . '/';
			}

			// Read Old Details
			$old_details = json_decode(file_get_contents(_SRC_SITE . $old_source . 'details.json'));

			if(isset($old_details->files))
				foreach ($old_details->files as $value)
					if(System::has_file($old_source . $value))
						System::delete_files(array($old_source . $value));

			if(isset($old_details->folders))
				foreach ($old_details->folders as $value)
					if(System::has_file($old_source . $value))
						System::delete_files(array($old_source . $value . '/'));

			if(isset($old_details->language))
				foreach ($old_details->language as $value)
					if(System::has_file($location . 'languages/' . $value->name . '/' . $value->src))
						System::delete_files(array($location . 'languages/' . $value->name . '/' . $value->src));

			System::delete_files(array($old_source));
		}
		else if($type == 'language' && $label != "")
		{
			// Read Old Details
			$old_details = json_decode(file_get_contents(_SRC_SITE . _ADM . 'languages/' . $label . '/' . 'details.json'));

			if(isset($old_details->files))
				foreach ($old_details->files as $value)
				{
					if(System::has_file(_ADM . 'languages/' . $label . '/' . $value))
						System::delete_files(array(_ADM . 'languages/' . $label . '/' . $value));

					if(System::has_file('languages/' . $label . '/' . $value))
						System::delete_files(array('languages/' . $label . '/' . $value));
				}

		}
	}

	// Delete Extension
	public function delete_extension($ids)
	{
		$total_id = "";
		$id = explode("," , $ids);

		if(!empty($id))
			foreach ($id as $key => $value) {
				if($key)
					$total_id .= " OR ";

				$total_id .= '`id` = ' . $value;
			}

		$this->table('extension')->select()->where($total_id)->process();
		$output = $this->output();

		$has_lock = false;
		$items = array();

		if(!empty($output))
		{
			foreach ($output as $key => $value)
			{
				if($value->type == 'component')
					$this->table('components')->select()->where('`type` = "' . $value->name . '" AND `location` = "' . $value->location . '"')->process();
				else if($value->type == 'widget')
					$this->table('widgets')->select()->where('`type` = "' . $value->name . '" AND `location` = "' . $value->location . '"')->process();
				else if($value->type == 'template')
					$this->table('templates')->select()->where('`type` = "' . $value->name . '" AND `location` = "' . $value->location . '"')->process();
				else if($value->type == 'plugin')
					$this->table('plugins')->select()->where('`type` = "' . $value->name . '" AND `location` = "' . $value->location . '"')->process();
				else if($value->type == 'language')
					$this->table('languages')->select()->where('`type` = "' . $value->name . '"')->process();

				$items[$key] = $this->output();

				if($value->lock_key)
					$has_lock = true;
			}

			if($has_lock)
				Messages::add_message('error' , Language::_('COM_EXTENSION_ERROR_CONTAINS_LOCK'));
			else
			{
				$status = true;

				foreach ($output as $key => $value)
				{
					if($value->type == 'language' && ($items[$key][0]->default_administrator || $items[$key][0]->default_site))
					{
						$status = false;
						Messages::add_message('error' , sprintf(Language::_('COM_EXTENSION_ERROR_DEFAULT') , $value->type));
					}
					else if($value->type == 'template' && $items[$key][0]->showing)
					{
						$status = false;
						Messages::add_message('error' , sprintf(Language::_('COM_EXTENSION_ERROR_DEFAULT') , $value->type));
					}
					else if($value->type == 'plugin' && Configuration::$captcha == $value->name)
					{
						$status = false;
						Messages::add_message('error' , sprintf(Language::_('COM_EXTENSION_ERROR_DEFAULT') , $value->type));
					}
				}

				if($status)
				{
					foreach ($output as $key => $value)
						foreach ($items[$key] as $valueb)
							$this->table($value->type . "s")->delete()->where('`id` = ' . $valueb->id)->process();

					foreach ($output as $key => $value)
					{
						if($value->type == "language")
							$this->delete_details_extension($value->name , $value->location , $value->type , $items[$key]->label);
						else
							$this->delete_details_extension($value->name , $value->location , $value->type);
					}

					$this->delete_items($ids , 'extension');
				}
			}
		}
	}
}