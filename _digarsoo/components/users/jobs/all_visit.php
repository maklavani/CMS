<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	12/05/2015
	*	last edit		10/03/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class AllVisitUsersJob Extends Jobs {
	public $event_number;
	public $execute_number;
	public $last_execute_status;
	public $next_execute;

	function __construct()
	{
		$this->event_number = -1;
		$this->next_execute = $this->get_next_time();
	}

	public function read($value)
	{
		// init kardane moteghaterhaye avaliye
		$this->event_number = $value->event_number;
		$this->execute_number = $value->execute_number;
		$this->last_execute_status = $value->last_execute_status;
		$this->run_it();
	}

	public function get_next_time()
	{
		return (new DateTime(date("Y-m-d 00:01:01")))->modify('+1 day')->format('Y-m-d H:i:s');
	}

	private function run_it()
	{
		$db = new Database;
		$db->table('users_today_visit')->select()->where('`last_seen` < CURDATE()')->process();
		$visits = $db->output();

		if(!empty($visits))
		{
			$days = array();

			foreach ($visits as $key => $value){
				$date = new Datetime($value->last_seen);
				$ds = $date->format('Y-m-d');

				if(!isset($days[$ds]))
					$days[$ds] = array('count' => 0 , 'count_all' => 0);

				$days[$ds]['count']++;
				$days[$ds]['count_all'] += $value->count;
			}

			$db->table('users_all_visit')->edit_table('ALTER' , 'auto_increment = 1')->process();

			foreach ($days as $key => $value){
				$db->table('users_all_visit')->select("COUNT(*)" , false)->where('`date` = "' . $key . '"')->process();
				$count = $db->output("asooc");

				if($count[0]["COUNT(*)"] == 0)
					$db->table('users_all_visit')->insert(array('date' , 'count' , 'count_all') , array($key , $value['count'] , $value['count_all']))->process();
			}

			$db->table('users_today_visit')->delete()->process();
		}
	}
}