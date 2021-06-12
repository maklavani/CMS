<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	07/28/2015
	*	last edit		09/22/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class IdentificationModel extends Model {
	public function has_identification($str)
	{
		$this->table('identification')->where('`code` = "' . $str . '"')->select()->process();
		return $this->output();
	}

	public function delete_identification($id)
	{
		return $this->table('identification')->where('`id` = ' . $id)->delete()->process();
	}

	public function change_password($identification , $password)
	{
		$this->table('users')->where('`email` = "' . $identification->val . '"')->select()->process();
		$user = $this->output();

		if(!empty($user))
		{
			require_once _INC . 'regex/hash.php';
			$this->table('users');
			$this->update(	array(
								array('password' , Hash::create('sha512' , $password , 1000 + $user[0]->id)) , 
								array('password_edit' , Site::$datetime)
							)
					);
			$this->where('`id` = ' . $user[0]->id);
			$this->process();
		}

		$this->table('identification')->where('`id` = ' . $identification->id)->delete()->process();
	}
}