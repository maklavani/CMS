<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	12/05/2015
	*	last edit		12/05/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class Jobs {
	private $db;
	private $jobs;

	// Khandane Jobha
	function __construct()
	{
		$this->jobs = array();

		$this->db = new Database;
		$this->db->table('jobs')->select()->where('`next_execute` < NOW() AND (`event_number` = -1 OR `event_number` > `execute_number`)')->process();
		$output = $this->db->output();

		if(!empty($output))
			foreach ($output as $key => $value)
				$this->jobs[] = $value;
	}

	public function run()
	{
		if(!empty($this->jobs))
			foreach ($this->jobs as $key => $value)
				if(System::has_file($value->source))
				{
					require_once $file = _SRC_SITE . $value->source;
					$class = ucwords(strtolower($value->title)) . 'Job';

					// agar class vujud dasht
					if(class_exists($class))
					{
						$component = new $class();

						// agar methode read vujud dasht
						if(method_exists($component , 'read'))
						{
							// set details before run
							$this->db->table('jobs')->update(array(
										array('execute_number' , ($value->execute_number + 1)) , 
										array('last_execute_status' , 1)
									))->where('`id` = ' . $value->id)->process();

							$action = call_user_func_array(array($component , 'read') , array($value));

							// set details after run
							$this->db->table('jobs')->update(array(
										array('last_execute_status' , 0) ,
										array('next_execute' , $component->get_next_time())
									))->where('`id` = ' . $value->id)->process();
						}
					}
				}
	}
}