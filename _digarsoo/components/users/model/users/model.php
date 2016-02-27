<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/07/2015
	*	last edit		12/04/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class UsersModel extends Model {
	public function get_with_field($field , $value)
	{
		$this->table('users')->where('`' . $field . '` = "' . $value . '"')->select()->process();
		return $this->output();
	}

	public function save()
	{
		// khandane file hash baraye check kardane password
		require_once _INC . 'regex/hash.php';

		$code = "";

		while(true){
			$code = Regex::random_string(20);
			$this->table('users')->where('`code` = "' . $code . '"')->select()->process();

			if(!$this->output())
				break;
		}

		$this->create_source($code);

		// Image
		$images_check = explode(',' , $_POST['field_input_image']);
		if(!empty($images_check))
			foreach ($images_check as $key => $value)
				if($value != ""){
					$base = basename($value);
					$dot_pos = strrpos($base , '.' , -1);
					$type = substr($base , $dot_pos - strlen($base) + 1);

					$file = "uploads/component/users/" . $code . "/" . ($key + 1) . "." .  $type;
					$thumbnail = "uploads/component/users/" . $code . "/thumbnail_" . ($key + 1) . "." .  $type;

					if(_SRC_SITE . $value != _SRC_SITE . $file)
					{
						copy(_SRC_SITE . $value , _SRC_SITE . $file);
						copy(_SRC_SITE . $value , _SRC_SITE . $thumbnail);

						System::resize_image(_SRC_SITE . $file , _SRC_SITE . $thumbnail , $type , 200);
					}

					$value_check = explode("/" , $file);
					if($value_check[0] == "uploads")
						$value_check[0] = 'download';
					$images_check[$key] = implode("/" , $value_check);
				}
		$images = htmlspecialchars(json_encode($images_check , JSON_UNESCAPED_UNICODE));

		$profile = array('tel' => $_POST['field_input_tel'] , 'address' => $_POST['field_input_address'] , 'favorites' => $_POST['field_input_favorites']);

		$this->table('users');
		$this->insert(
						array(	'name' , 'family' , 'username' , 'code' , 'group' , 'email' , 
								'mobile' , 'image' , 'register' , 'status' , 'profile') , 
						array(	trim($_POST['field_input_name']) , trim($_POST['field_input_family']) , trim($_POST['field_input_username']) , $code , $_POST['field_input_group'] , $_POST['field_input_email'] , 
								$_POST['field_input_mobile'] , $images , Site::$datetime , $_POST['field_input_status'] , htmlspecialchars(json_encode($profile , JSON_UNESCAPED_UNICODE)))
					);
		$this->process();

		$last_id = $this->last_insert_id;
		$this->table('users');
		$this->update(	array(
							array('password' , Hash::create('sha512' , $_POST['field_input_password'] , 1000 + $last_id)) , 
							array('password_edit' , Site::$datetime)
							)
					);
		$this->where('`id` = ' . $last_id);
		$this->process();
		return $this->last_insert_id;
	}

	public function update_users()
	{
		$code = $_POST['field_input_code'];

		if($_POST['field_input_password'] != "")
		{
			// khandane file hash baraye check kardane password
			require_once _INC . 'regex/hash.php';

			$this->table('users');
			$this->update(	array(
								array('password' , Hash::create('sha512' , $_POST['field_input_password'] , 1000 + $_GET['id'])) , 
								array('password_edit' , Site::$datetime)
							)
					);
			$this->where('`id` = ' . $_GET['id']);
			$this->process();
		}

		$profile = array('tel' => $_POST['field_input_tel'] , 'address' => $_POST['field_input_address'] , 'favorites' => $_POST['field_input_favorites']);

		// Image
		$images_check = explode(',' , $_POST['field_input_image']);
		if(!empty($images_check))
			foreach ($images_check as $key => $value)
				if($value != ""){
					$base = basename($value);
					$dot_pos = strrpos($base , '.' , -1);
					$type = substr($base , $dot_pos - strlen($base) + 1);

					$file = "uploads/component/users/" . $code . "/" . ($key + 1) . "." .  $type;
					$thumbnail = "uploads/component/users/" . $code . "/thumbnail_" . ($key + 1) . "." .  $type;

					if(_SRC_SITE . $value != _SRC_SITE . $file)
					{
						copy(_SRC_SITE . $value , _SRC_SITE . $file);
						copy(_SRC_SITE . $value , _SRC_SITE . $thumbnail);

						System::resize_image(_SRC_SITE . $file , _SRC_SITE . $thumbnail , $type , 200);
					}

					$value_check = explode("/" , $file);
					if($value_check[0] == "uploads")
						$value_check[0] = 'download';
					$images_check[$key] = implode("/" , $value_check);
				}
		$images = htmlspecialchars(json_encode($images_check , JSON_UNESCAPED_UNICODE));

		$this->table('users');
		$this->update(	array(
							array('name' , trim($_POST['field_input_name'])),
							array('family' , trim($_POST['field_input_family'])),
							array('username' , trim($_POST['field_input_username'])),
							array('group' , $_POST['field_input_group']),
							array('email' , $_POST['field_input_email']),
							array('mobile' , $_POST['field_input_mobile']),
							array('image' , $images),
							array('status' , $_POST['field_input_status']),
							array('profile' , htmlspecialchars(json_encode($profile , JSON_UNESCAPED_UNICODE)))
						)
					);
		$this->where('`id` = ' . $_GET['id']);

		return $this->process();
	}

	public function delete_source_image($ids)
	{
		$total_id = "";
		$id = explode("," , $ids);

		foreach ($id as $value){
			if($total_id != "")
				$total_id .= " OR ";

			$total_id .= '`id` = ' . $value;
		}

		$this->table('users')->where($total_id)->select()->process();
		$advertisment = $this->output();

		foreach ($advertisment as $value)
		{
			$images = json_decode(htmlspecialchars_decode($value->image));

			if(!empty($images))
				foreach ($images as $valueb) {
					if($valueb != ""){
						$valueb_check = explode("/" , $valueb);
						if($valueb_check[0] == "download")
							$valueb_check[0] = 'uploads';

						$src = implode("/" , $valueb_check);
						System::delete_files(array(dirname($src) . '/'));
					}
				}
			
			System::delete_files(array('uploads/component/users/' . $value->code . '/'));
		}
	}

	public function create_source($code)
	{
		if(!System::has_file('uploads/component') && mkdir(_SRC_SITE . "uploads/component" , 0755 , true))
		{
			$html_file = fopen(_SRC_SITE . "uploads/component/index.html" , "w");
			fwrite($html_file , "<!DOCTYPE html><title></title>");
			fclose($html_file);
		}

		if(!System::has_file('uploads/component/users') && mkdir(_SRC_SITE . "uploads/component/users" , 0755 , true))
		{
			$html_file = fopen(_SRC_SITE . "uploads/component/users/index.html" , "w");
			fwrite($html_file , "<!DOCTYPE html><title></title>");
			fclose($html_file);
		}

		if(!System::has_file('uploads/component/users/' . $code) && mkdir(_SRC_SITE . "uploads/component/users/" . $code , 0755 , true))
		{
			$html_file = fopen(_SRC_SITE . "uploads/component/users/" . $code . "/index.html" , "w");
			fwrite($html_file , "<!DOCTYPE html><title></title>");
			fclose($html_file);
		}
	}
}