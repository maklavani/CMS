<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	12/04/2015
	*	last edit		12/04/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class UploadModel extends Model {
	public function get_all($name = "")
	{
		if(!Regex::cs($name , 'text_b'))
			$name = "";

		$this->table('users')->where('`code` LIKE "%' . $name . '%"')->select()->process();
		$users = $this->output();
		$users_out = array();

		if(!empty($users))
			foreach ($users as $value)
				$users_out[] = array('name' => $value->code);

		return $users_out;
	}
}