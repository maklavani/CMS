<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	12/05/2015
	*	last edit		12/05/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class DeleteIPUsersJob Extends Jobs {
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
		return (new DateTime(date("Y-m-d 00:11:01")))->modify('+3 day')->format('Y-m-d H:i:s');
	}

	private function run_it()
	{
		$db = new Database;
		$db->table('users_ip_visit')->delete()->process();
	}
}