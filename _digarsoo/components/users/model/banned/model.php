<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	07/28/2015
	*	last edit		12/04/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class BannedModel extends Model {
	public function update_banned()
	{
		return $this->table('banned')->update(array(array('count' , $_POST['field_input_count'])))->where('`id` = ' . $_GET['id'])->process();
	}
}