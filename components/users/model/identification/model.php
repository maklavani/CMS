<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/28/2015
	*	last edit		12/04/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class IdentificationModel extends Model {
	public function has_identification($str)
	{
		$this->table('identification')->where('`code` = "' . $str . '" AND `component` = "users"')->select()->process();
		return $this->output();
	}

	public function delete_identification($id)
	{
		return $this->table('identification')->where('`id` = ' . $id)->delete()->process();
	}
}