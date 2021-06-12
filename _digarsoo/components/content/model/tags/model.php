<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	07/14/2015
	*	last edit		12/04/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class TagsModel extends Model {
	public function get_all($name = "")
	{
		if(!Regex::cs($name , 'text_b'))
			$name = "";

		$this->table('tags')->where('`name` LIKE "%' . $name . '%"')->select()->process();
		$tags = $this->output();
		$tags_out = array();

		if(!empty($tags))
			foreach ($tags as $value)
				$tags_out[] = array('name' => $value->name);

		return $tags_out;
	}

	public function get_with_name($name)
	{
		$this->table('tags')->where('`name` = "' . $name . '"')->select()->process();
		return $this->output();
	}

	public function save()
	{
		$this->table('tags')->insert(array('name') , array(trim($_POST['field_input_name'])))->process();
		return $this->last_insert_id;
	}

	public function update_tags()
	{
		$this->table('tags')->update(array(array('name' , trim($_POST['field_input_name']))))->where('`id` = ' . $_GET['id']);
		return $this->process();
	}
}