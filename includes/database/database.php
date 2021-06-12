<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	03/29/2015
	*	last edit		06/01/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class Database extends Configuration {
	// status for connection
	private $connect;
	private $result;
	private $error;

	// events
	public $select;
	public $update;
	public $insert;
	public $delete;
	public $edit_table;
	public $query;

	// conditions
	public $table;
	public $where;
	public $order;
	public $limit;

	// status for select
	public $found;
	public $found_all;

	// status for insert
	public $last_insert_id;

	// ejraye constructor
	function __construct()
	{
		try
		{
			$this->connect = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db . ';charset=utf8' , $this->user , $this->password);
			// set the PDO error mode to exception
			$this->connect->setAttribute(PDO::ATTR_ERRMODE , PDO::ERRMODE_EXCEPTION);

			$this->error = false;
		}

		catch(PDOException $e)
		{
			// set kardane peyghame error
			Preload::$error = true;
			Messages::add_message('error' , $e->getMessage());

			$this->error = true;
		}
	}

	// set kardane select
	public function select($items = false , $func = false)
	{
		if($func)
			$this->select = "SELECT " . $func . " FROM " . $this->table . " ";
		else
		{
			$this->select = "SELECT SQL_CALC_FOUND_ROWS ";

			if(is_array($items))
				foreach ($items as $key => $item)
				{
					if($key)
						$this->select .= ' , ';
					else if($func)
						$this->select .= ' AS ';

					$this->select .= ' `' . $item . '` ';

					if(count($items) == $key + 1)
						$this->select .= " FROM " . $this->table . " ";
				}
			else
				$this->select .= " * FROM " . $this->table . " ";
		}

		$this->update = false;
		$this->insert = false;
		$this->delete = false;
		$this->edit_table = false;
		$this->query = false;

		return $this;
	}

	// update kardane table
	public function update($items = false , $quotes_values = true)
	{
		$this->update = 'UPDATE ' . $this->table . ' ';
		if(is_array($items))
			foreach ($items as $key => $item)
			{
				if($key)
					$this->update .= ' , ';
				else
					$this->update .= ' SET ';

				if($quotes_values)
					$this->update .= ' `' . $item[0] . '` = "' . $item[1] . '"';
				else
					$this->update .= ' `' . $item[0] . '` = ' . $item[1];
			}

		$this->select = false;
		$this->insert = false;
		$this->delete = false;
		$this->edit_table = false;
		$this->query = false;

		return $this;
	}

	// insert kardane be table
	public function insert($varaibles = array() , $values = array() , $quotes_values = true)
	{
		$this->insert = 'INSERT INTO ' . $this->table . ' ';

		if(!empty($varaibles) && is_array($varaibles))
			foreach ($varaibles as $key => $item)
			{	
				if($key)
					$this->insert .= ' , ';
				else
					$this->insert .= ' ( ';

				$this->insert .= ' `' . $item . '` ';

				if(count($varaibles) == $key + 1)
					$this->insert .= ' ) ';
			}
		else
			$this->insert .= $varaibles;

		if(!empty($values) && is_array($values))
			foreach ($values as $key => $item)
			{
				if($key)
					$this->insert .= ' , ';
				else
					$this->insert .= ' VALUES ( ';

				if($quotes_values)
					$this->insert .= ' "' . $item . '" ';
				else
					$this->insert .= ' ' . $item . ' ';

				if(count($values) == $key + 1)
					$this->insert .= ' ) ';
			}
		else
			$this->insert .= $values;

		$this->select = false;
		$this->update = false;
		$this->delete = false;
		$this->edit_table = false;
		$this->query = false;

		return $this;
	}

	// delete kardane be table
	public function delete()
	{
		$this->delete = 'DELETE FROM ' . $this->table . " ";

		$this->select = false;
		$this->update = false;
		$this->insert = false;
		$this->edit_table = false;
		$this->query = false;

		return $this;
	}

	// edit kardane table
	public function edit_table($type , $str)
	{
		$this->edit_table = $type . ' TABLE ' . $this->table . ' ' . $str;

		$this->select = false;
		$this->update = false;
		$this->insert = false;
		$this->delete = false;
		$this->query = false;

		return $this;
	}

	// ferestadane yek query
	public function query($query = "")
	{
		$this->query = str_replace("`#__" , "`" . $this->prefix , $query);

		$this->select = false;
		$this->update = false;
		$this->insert = false;
		$this->delete = false;
		$this->edit_table = false;

		return $this;
	}

	// set kardane table
	public function table($table , $not_table = false)
	{
		if(!$not_table)
			$this->table = '`' . $this->prefix . $table . '`';
		else
			$this->table = $table;

		$this->where = false;
		$this->order = false;
		$this->limit = false;

		return $this;
	}

	// tabe dorost kardane where
	public function where($str)
	{
		$this->where = " WHERE " . $str;

		return $this;
	}

	// tabe dorost kardane order
	public function order($str)
	{
		$this->order = " ORDER BY " . $str;

		return $this;
	}

	// tabe dorost kardane order
	public function limit($first , $end = false)
	{
		if($end)
			$this->limit = " LIMIT " . ($first - 1) . " , " . ($end - $first + 1);
		else
			$this->limit = " LIMIT " . $first;

		return $this;
	}

	// function e tashkhise amaliate update , insert ya delete va anjame amaliat baed az tashkhis
	public function process()
	{
		$output = '';

		if($this->select != "")
			$output = $this->select . $this->where . $this->order . $this->limit;

		else if($this->update != "")
			$output = $this->update . $this->where;

		else if($this->insert != "")
			$output = $this->insert;

		else if($this->delete != "")
			$output = $this->delete . $this->where . "; ALTER TABLE " . $this->table . " AUTO_INCREMENT = 1;";

		else if($this->edit_table != "")
			$output = $this->edit_table;

		else if($this->query != "")
			$output = $this->query;

		if(!$this->error)
			try
			{
				$this->result = $this->connect->query($output);

				if($this->select)
				{
					$this->found = $this->result->rowCount();
				}

				if($this->insert)
				{
					$this->last_insert_id = $this->connect->lastInsertId();
				}

				return true;
			}

			catch(PDOException $e)
			{
				// set kardane peyghame error
				Preload::$error = true;
				Messages::add_message('error' , $e->getMessage());

				return false;
			}
	}

	// output dadan
	public function output($type = 'obj')
	{
		if($type == 'obj')
			return $this->result->fetchAll(PDO::FETCH_OBJ);
		else if($type == 'assoc')
			return $this->result->fetchAll(PDO::FETCH_ASSOC);
		else if($type == 'num')
			return $this->result->fetchAll(PDO::FETCH_NUM);
		else if($type == 'name')
			return $this->result->fetchAll(PDO::FETCH_NAMED);
		else if($type == 'both')
			return $this->result->fetchAll(PDO::FETCH_BOTH);
		else if($type == 'bound')
			return $this->result->fetchAll(PDO::FETCH_BOUND);
	}

	// peyda kardane tamame tedad peyda
	public function get_found_all()
	{
		$this->found_all = $this->connect->query('SELECT FOUND_ROWS();')->fetchColumn();
		return $this->found_all;
	}

	// exist column in table
	public function exist_column($column , $table)
	{
		return $this->connect->query("SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = '" . $this->prefix . $table . "' AND COLUMN_NAME = 'parent'")->fetchAll(PDO::FETCH_OBJ);
	}

	// bargardandane prefix
	public function get_prefix()
	{
		return $this->prefix;
	}

	// ejraye destructor
	function __destruct()
	{
		$this->connect = null;
	}
}