<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	08/13/2015
	*	last edit		12/04/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class ComponentsModel extends Model {
	public function update_components()
	{
		$this->table('components')->update(array(array('name' , trim($_POST['field_input_name'])) , array('permission' , $_POST['field_input_permission'])))->where('`id` = ' . $_GET['id'])->process();
		return $this->process();
	}
}