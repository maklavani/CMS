<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	06/15/2015
	*	last edit		03/02/2017
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class GroupModel extends Model {
	public function get_homepage($group)
	{
		$this->table('menu')->where('`homepage` = 1 AND `group_number` = ' . $group)->select()->process();
		return $this->output();
	}

	public function get_homepage_with_languages($languages)
	{
		$this->table('menu')->where('`homepage` = 1 AND `languages` = "' . $languages . '"')->select()->process();
		return $this->output();
	}

	public function homepage($id , $value , $lang , $location)
	{
		if($value == 1)
			$this->table('menu')->where('`homepage` = 1 AND `languages` = "' . $lang . '" AND `location` = "' . $location . '"')->update(array(array('homepage' , '0')))->process();

		return $this->table('menu')->where('`id` = ' . $id)->update(array(array('homepage' , $value)))->process();
	}

	public function set_index($group)
	{
		$parent = 0;
		$index = 1;
		$this->table('menu')->where('`group_number` = ' . $group)->order('`parent` ASC , `index_number` ASC')->select()->process();

		if($menus = $this->output())
			foreach($menus as $key => $value){
				if($parent != $value->parent)
				{
					$parent = $value->parent;
					$index = 1;
				}

				if($value->index_number != $index)
					$this->table('menu')->update(array(array('index_number' , $index)))->where('`id` = ' . $value->id)->process();

				$index++;
			}
	}

	public function save()
	{
		$group = $this->get_with_id($_GET['id'] , 'menu_group');
		$this->table('menu')->where('`group_number` = ' . $group[0]->id . ' AND `parent` = ' . $_POST['field_input_parent'])->select()->process();

		if($menus = $this->output())
			foreach ($menus as $value)
				if($value->index_number >= $_POST['field_input_index'])
					$this->table('menu')->update(array(array('index_number' , $value->index_number + 1)))->where('`id` = ' . $value->id)->process();

		$setting = array('title' => $_POST['field_input_title'] , 'show_status' => $_POST['field_input_show_status'] , 'class' => $_POST['field_input_class']);

		$this->table('menu');
		$this->insert(	array(	'name' , 'status' , 'type' , 'link' , 'languages' , 'parent' , 'group_number' , 
								'permission' , 'index_number' , 'icon' , 'homepage' , 'meta_tag' , 'meta_description' , 'setting' , 
								'location') , 
						array(	trim($_POST['field_input_name']) , $_POST['field_input_status'] , $_POST['field_input_type_link'] , htmlentities($_POST['field_input_link_link'] , ENT_COMPAT | ENT_QUOTES , "UTF-8") , $_POST['field_input_languages'] , $_POST['field_input_parent'] , $group[0]->id , 
								$_POST['field_input_permission'] , $_POST['field_input_index'] , $_POST['field_input_icon'] , 0 , htmlentities($_POST['field_input_meta_tag']) , htmlentities($_POST['field_input_meta_description']) , htmlspecialchars(json_encode($setting , JSON_UNESCAPED_UNICODE)) , 
								$group[0]->location));
		$this->process();

		$id = $this->last_insert_id;
		if($_POST['field_input_homepage'] == 'show')
			$this->homepage($id , 1 , $_POST['field_input_languages'] , $group[0]->location);

		if($_POST['field_input_alias'] == "" && Configuration::$alias)
			$this->set_alias(trim($_POST['field_input_name']) , $id , $_POST['field_input_languages']);
		else if($_POST['field_input_alias'] != "")
			$this->set_alias($_POST['field_input_alias'] , $id , $_POST['field_input_languages']);

		return $id;
	}

	public function update_menu()
	{
		$group = $this->get_with_id($_GET['id'] , 'menu_group');
		$this->table('menu')->where('`group_number` = ' . $group[0]->id . ' AND `parent` = ' . $_POST['field_input_parent'])->select()->process();

		if($menus = $this->output())
			foreach ($menus as $value)
				if($value->index_numebr >= $_POST['field_input_index'])
					$this->table('menu')->update(array(array('index_number' , $value->index_number + 1)))->where('`id` = ' . $value->id)->process();

		$setting = array('title' => $_POST['field_input_title'] , 'show_status' => $_POST['field_input_show_status'] , 'class' => $_POST['field_input_class']);

		$this->table('menu');
		$this->update(	array(
							array('name' , $_POST['field_input_name']) , 
							array('type' , $_POST['field_input_type_link']) , 
							array('link' , htmlentities($_POST['field_input_link_link'] , ENT_COMPAT | ENT_QUOTES , "UTF-8")) , 
							array('languages' , $_POST['field_input_languages']) , 
							array('parent' , $_POST['field_input_parent']) , 
							array('permission' , $_POST['field_input_permission']) , 
							array('index_number' , $_POST['field_input_index']) , 
							array('icon' , $_POST['field_input_icon']) , 
							array('robots_index' , $_POST['field_input_robots_index']) , 
							array('robots_follow' , $_POST['field_input_robots_follow']) , 
							array('meta_tag' , htmlentities($_POST['field_input_meta_tag'])) , 
							array('meta_description' , htmlentities($_POST['field_input_meta_description'])) , 
							array('setting' , htmlspecialchars(json_encode($setting , JSON_UNESCAPED_UNICODE)))
						));
		$this->where('`id` = ' . $_GET['menu_id']);
		$this->process();
		$this->set_index($_GET['id']);
	}

	public function set_alias($alias , $id , $languages)
	{
		$alias = str_replace(" " , "-" , mb_strtolower(trim($alias) , 'UTF-8'));
		$number = 1;
		$alias_check = trim($alias);

		while(true){
			$this->table('menu')->where('`id` != ' . $id . ' AND `alias` = "' . $alias_check . '" AND (`languages` = "all" OR `languages` = "' . $languages . '")')->select()->process();

			if($menu = $this->output())
			{
				$number++;
				$alias_check = $alias . '-' . $number;
			}
			else
				break;
		}

		return $this->table('menu')->update(array(array('alias' , $alias_check)))->where('`id` = ' . $id)->process();
	}
}