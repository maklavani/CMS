<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	07/09/2015
	*	last edit		10/03/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class PermissionsModel extends Model {
	public function save()
	{		
		$groups = '1';

		if(!empty($_POST['field_input_groups']))
			foreach ($_POST['field_input_groups'] as $value)
				$groups .= ',' . $value;

		$this->table('permissions');
		$this->insert(
						array('name' , 'groups') , 
						array(trim($_POST['field_input_name']) , $groups)
					);
		$this->process();
		return $this->last_insert_id;
	}

	public function update_permission()
	{		
		$groups = '1,2';

		if(!empty($_POST['field_input_groups']))
			foreach ($_POST['field_input_groups'] as $value)
				$groups .= ',' . $value;

		$this->table('permissions');
		$this->update(array(array('name' , trim($_POST['field_input_name'])) , array('groups' , $groups)));
		$this->where('`id` = ' . $_GET['id']);
		$this->process();
	}

	public function get_lock_permissions_id()
	{
		$this->table('permissions')->where('`lock_key` = 1')->select()->process();
		$ids = $this->output();
		
		$id = array();
		foreach ($ids as $value)
			$id[$value->id] = $value->id;

		return $id;
	}
}