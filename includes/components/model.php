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

class Model extends Database {
	public function get($name , $component , $tree = false , $where = false , $sort_defualt = 'id' , $show_found = true)
	{
		$search = '';
		$search_category = '';
		$sort = '';
		$sort_order = '';
		$number = '';
		$page = '';

		$cookie_name = $name;

		if(Controller::$view)
			$cookie_name = Controller::$view;

		$cookie = Cookies::get_cookie($component . '_' . $cookie_name);

		if($cookie)
			$cookie = json_decode($cookie , true);
		else
			$cookie = array();

		if(isset($_POST['form-search']) && Regex::cs($_POST['form-search'] , 'search'))
			$cookie['form_search'] = $search = $_POST['form-search'];
		else if(isset($_GET['form-search']) && Regex::cs($_GET['form-search'] , 'search'))
			$cookie['form_search'] = $search = $_GET['form-search'];
		else if(isset($cookie['form_search']) && Regex::cs($cookie['form_search'] , 'search'))
			$search = $cookie['form_search'];

		if(isset($_POST['form-search-category']) && Regex::cs($_POST['form-search-category'] , 'text'))
			$cookie['form_search_category'] = $search_category = $_POST['form-search-category'];
		else if(isset($_GET['form-search-category']) && Regex::cs($_GET['form-search-category'] , 'text'))
			$cookie['form_search_category'] = $search_category = $_GET['form-search-category'];
		else if(isset($cookie['form_search_category']) && Regex::cs($cookie['form_search_category'] , 'text'))
			$search_category = $cookie['form_search_category'];

		if(isset($_POST['form-sort']) && Regex::cs($_POST['form-sort'] , 'text') && $_POST['form-sort'] != '')
			$cookie['form_sort'] = $sort = $_POST['form-sort'];
		else if(isset($_GET['form-sort']) && Regex::cs($_GET['form-sort'] , 'text') && $_GET['form-sort'] != '')
			$cookie['form_sort'] = $sort = $_GET['form-sort'];
		else if(isset($cookie['form_sort']) && Regex::cs($cookie['form_sort'] , 'text') && $cookie['form_sort'] != '')
			$sort = $cookie['form_sort'];
		else
			$cookie['form_sort'] = $sort = $sort_defualt;

		if(isset($_POST['form-sort-order']) && in_array($_POST['form-sort-order'] , array('ASC' , 'DESC')))
			$cookie['form_sort_order'] = $sort_order = $_POST['form-sort-order'];
		else if(isset($_GET['form-sort-order']) && in_array($_GET['form-sort-order'] , array('ASC' , 'DESC')))
			$cookie['form_sort_order'] = $sort_order = $_GET['form-sort-order'];
		else if(isset($cookie['form_sort_order']) && in_array($cookie['form_sort_order'] , array('ASC' , 'DESC')))
			$sort_order = $cookie['form_sort_order'];
		else
			$cookie['form_sort_order'] = $sort_order = 'ASC';

		if(isset($_POST['form-number']) && in_array($_POST['form-number'] , array(5 , 10 , 15 , 20 , 25 , 30 , 50 , 75 , 100 , 'all')))
			$cookie['form_number'] = $number = $_POST['form-number'];
		else if(isset($_GET['form-number']) && in_array($_GET['form-number'] , array(5 , 10 , 15 , 20 , 25 , 30 , 50 , 75 , 100 , 'all')))
			$cookie['form_number'] = $number = $_GET['form-number'];
		else if(isset($cookie['form_number']) && in_array($cookie['form_number'] , array(5 , 10 , 15 , 20 , 25 , 30 , 50 , 75 , 100 , 'all')))
			$number = $cookie['form_number'];
		else
			$cookie['form_number'] = $number = 20;

		if(isset($_POST['form-page']) && Regex::cs($_POST['form-page'] , "numeric") && $_POST['form-page'] > 0)
			$cookie['form_page'] = $page = (int)$_POST['form-page'];
		else if(isset($_GET['form-page']) && Regex::cs($_GET['form-page'] , "numeric") && $_GET['form-page'] > 0)
			$cookie['form_page'] = $page = (int)$_GET['form-page'];
		else if(isset($cookie['form_page']) && Regex::cs($cookie['form_page'] , "numeric") && $cookie['form_page'] > 0)
			$page = (int)$cookie['form_page'];
		else
			$cookie['form_page'] = $page = 1;

		if($sort != $sort_defualt)
		{
			$this->table('information_schema.COLUMNS' , true)->where('TABLE_NAME = "' . $this->prefix . $name . '" AND COLUMN_NAME = "' . $sort . '"')->select()->process();

			if(empty($this->output()))
				$cookie['form_sort'] = $sort = $sort_defualt;
		}

		$this->table($name);

		if(!$tree && $search != '' && $search_category != '')
			if($where)
				$this->where($where . ' AND `' . $search_category . '` LIKE "%' . $search .  '%"');
			else
				$this->where('`' . $search_category . '` LIKE "%' . $search .  '%"');
		else if($where)
			$this->where($where);

		if($tree)
			$this->order('parent ASC , id ASC');
		else
			$this->order($sort . ' ' . $sort_order);

		if(!$tree && $number != 'all')
			$this->limit(($page - 1) * $number + 1 , $page * $number);

		$this->select();
		$this->process();
		$output = $this->output();

		if($output && $tree)
		{
			$tree_arr = array();
			$this->build_tree($output , $tree_arr , 0 , 0);

			$number_check = 0;
			$number_hidden = 0;

			if($tree_arr)
			{
				$tree_arr_b = $tree_arr;
				$tree_arr = array();

				$this->build_tree_sort($tree_arr_b , $tree_arr , 0 , $sort , $sort_order);
			}

			if($number != 'all')
			{
				foreach ($tree_arr as $key => $value)
					if($search != '' && $search_category != '' && stripos($value->$search_category , $search) === false)
						unset($tree_arr[$key]);

				foreach ($tree_arr as $key => $value) {
					$number_check++;

					if((($page - 1) * $number + 1) > $number_check || ($page * $number) < $number_check)
					{
						$number_hidden++;
						unset($tree_arr[$key]);
					}
				}
			}

			if($show_found)
				$cookie['found'] = count($tree_arr) + $number_hidden;
			else
				unset($cookie['found']);

			Cookies::set_cookie($component . '_' . $cookie_name , json_encode($cookie , JSON_UNESCAPED_UNICODE) , time() + 604800);

			return $tree_arr;
		}

		if($show_found)
			$cookie['found'] = $this->get_found_all();
		else
			unset($cookie['found']);

		Cookies::set_cookie($component . '_' . $cookie_name , json_encode($cookie , JSON_UNESCAPED_UNICODE) , time() + 604800);
		return $output;
	}

	// build tree
	private function build_tree($items , &$tree , $parent , $level)
	{
		foreach ($items as $key => $value)
			if($value->parent == $parent || !$level)
			{
				if(!isset($tree[$value->id]))
				{
					$value->class = 'level-' . ($level + 1);
					$tree[$value->id] = $value;
				}

				$this->build_tree($items , $tree , $value->id , ($level + 1));
			}
	}

	private function build_tree_sort($items , &$tree , $parent , $sort , $sort_order){
		$arr = array();

		foreach ($items as $key => $value)
			if($value->parent == $parent)
				$arr[$key] = $value;

		if($arr)
		{
			$sort_arr = array();

			foreach ($arr as $key => $value)
				$sort_arr[$key] = $value->$sort;
			
			if ($sort_order == 'ASC')
				array_multisort($sort_arr , SORT_ASC  , $arr);
			else
				array_multisort($sort_arr , SORT_DESC  , $arr);


			if(empty($tree))
			{
				foreach ($arr as $key => $value)
					$tree[$value->id] = $value;
			}
			else
			{
				$tree_out = array();

				foreach($tree as $value)
					if($value->id == $parent)
					{
						$tree_out[$value->id] = $value;

						foreach ($arr as $valueb)
							$tree_out[$valueb->id] = $valueb;
					}
					else
						$tree_out[$value->id] = $value;

				$tree = $tree_out;
			}
		}

		if($tree)
			foreach ($tree as $value)
				foreach ($items as $valueb)
					if($valueb->parent == $value->id && !isset($tree[$valueb->id]))
					{
						$this->build_tree_sort($items , $tree , $value->id , $sort , $sort_order);
						break;
					}
	}

	// get with id
	public function get_with_id($id , $name)
	{
		$this->table($name)->select()->where('`id` = ' . $id)->process();
		return $this->output();
	}

	// aya item mad nazar vujud darad
	public function has_item($ids , $table , $column = 'id' , $total = true){
		$total_id = "";
		$id = explode("," , $ids);

		foreach ($id as $value)
			if($value != "" && (int)$value > 0)
			{
				if($total_id != "")
					$total_id .= " OR ";

				$total_id .= '`' . $column . '` = ' . $value;
			}

		if($total_id != "")
		{
			$this->table($table)->where($total_id)->select()->process();

			if($total && $this->found == count($id))
				return true;
			else if($this->found > 0)
				return true;
		}

		return false;
	}

	// set kardane block
	public function set_block($ids , $table)
	{
		$total_id = "";
		$id = explode("," , $ids);
		$parent = $this->exist_column('parent' , $table);

		while(!empty($id)){
			$value = array_pop($id);

			if($value != "" && (int)$value > 0)
			{
				if($total_id != "")
					$total_id .= " OR ";

				$total_id .= '`id` = ' . $value;

				if($parent)
				{
					$this->table($table)->where('`parent` = ' . $value)->select()->process();

					if($child = $this->output())
						foreach ($child as $value)
							if(!in_array($value->id , $id))
								array_push($id , $value->id);
				}
			}
		}

		if($total_id != "")
		{
			$this->table($table)->where($total_id)->update(array(array('status' , 1)));

			if($this->process())
			{
				Messages::add_message('success' , Language::_('SUCCESS_BLOCKED'));
				return true;
			}
		}

		Messages::add_message('success' , Language::_('ERROR_BLOCKED'));
		return false;
	}

	// set kardane unblock
	public function set_unblock($ids , $table){
		$total_id = "";
		$id = explode("," , $ids);
		$parent = $this->exist_column('parent' , $table);

		while(!empty($id)){
			$value = array_pop($id);

			if($value != "" && (int)$value > 0)
			{
				if($total_id != "")
					$total_id .= " OR ";

				$total_id .= '`id` = ' . $value;

				if($parent)
				{
					$this->table($table)->where('`parent` = ' . $value)->select()->process();

					if($child = $this->output())
						foreach ($child as $value)
							if(!in_array($value->id , $id))
								array_push($id , $value->id);
				}
			}
		}

		if($total_id != "")
		{
			$this->table($table)->where($total_id)->update(array(array('status' , 0)));

			if($this->process())
			{
				Messages::add_message('success' , Language::_('SUCCESS_UNBLOCKED'));
				return true;
			}
		}

		Messages::add_message('success' , Language::_('ERROR_UNBLOCKED'));
		return false;
	}

	// set kardane unblock
	public function delete_items($ids , $table){
		$total_id = "";
		$id = explode("," , $ids);
		$parent = $this->exist_column('parent' , $table);

		while(!empty($id)){
			$value = array_pop($id);

			if($value != "" && (int)$value > 0)
			{
				if($total_id != "")
					$total_id .= " OR ";

				$total_id .= '`id` = ' . $value;

				if($parent)
				{
					$this->table($table)->where('`parent` = ' . $value)->select()->process();

					if($child = $this->output())
						foreach ($child as $value)
							if(!in_array($value->id , $id))
								array_push($id , $value->id);
				}
			}
		}

		if($total_id != "")
		{
			$this->table($table)->where($total_id)->delete();

			if($this->process())
			{
				Messages::add_message('success' , Language::_('SUCCESS_DELETED'));
				return true;
			}
		}

		Messages::add_message('success' , Language::_('ERROR_DELETED'));
		return false;
	}
}