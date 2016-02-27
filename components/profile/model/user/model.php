<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	06/15/2015
	*	last edit		01/30/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class UserModel extends Model {
	public function get_with_field($field , $value)
	{
		$this->table('users')->where('`' . $field . '` = "' . $value . '"')->select()->process();
		return $this->output();
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
								array('password' , Hash::create('sha512' , $_POST['field_input_password'] , 1000 + User::$id)) , 
								array('password_edit' , Site::$datetime)
							)
					);
			$this->where('`id` = ' . User::$id);
			$this->process();
		}

		$profile = array('tel' => $_POST['field_input_tel'] , 'address' => $_POST['field_input_address'] , 'favorites' => $_POST['field_input_favorites']);

		$this->table('users');
		$this->update(	array(
							array('name' , trim($_POST['field_input_name'])),
							array('family' , trim($_POST['field_input_family'])),
							array('username' , trim($_POST['field_input_username'])),
							array('mobile' , $_POST['field_input_mobile']),
							array('profile' , htmlspecialchars(json_encode($profile , JSON_UNESCAPED_UNICODE)))
						)
					);
		$this->where('`id` = ' . User::$id);

		return $this->process();
	}

	public function update_users_image($image)
	{
		return $this->table('users')->update(array(array('image' , $image)))->where('`id` = ' . User::$id)->process();
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