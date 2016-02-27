<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/08/2015
	*	last edit		12/04/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class GroupModel extends Model {
	public function save()
	{
		$this->table('group')->insert(array('name' , 'parent') , array(trim($_POST['field_input_name']) , $_POST['field_input_parent']))->process();
		return $this->last_insert_id;
	}

	public function update_permission()
	{
		return $this->table('group')->update(array(array('name' , trim($_POST['field_input_name'])) , array('parent' , $_POST['field_input_parent'])))->where('`id` = ' . $_GET['id'])->process();
	}

	public function get_lock_group_id()
	{
		$this->table('group')->where('`lock` = 1')->select()->process();
		$ids = $this->output();
		
		$id = array();
		foreach ($ids as $value)
			$id[$value->id] = $value->id;

		return $id;
	}
}