<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	06/15/2015
	*	last edit		12/04/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class GroupsModel extends Model {
	public function get_menu_group()
	{
		$this->table('menu_group')->where('`location` = "site"')->select()->process();
		return $this->output();
	}

	public function save()
	{
		$this->table('menu_group')->insert(array('name' , 'location') , array(trim($_POST['field_input_name']) , 'site'))->process();
		return $this->last_insert_id;
	}

	public function update_meun_group()
	{
		$this->table('menu_group')->update(array(array('name' , trim($_POST['field_input_name']))))->where('`id` = ' . $_GET['id'])->process();
		return $this->last_insert_id;
	}
}