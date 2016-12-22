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

class RootJob Extends Jobs {
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
		return (new DateTime(date("Y-m-d 0:0:0")))->modify('+1 day')->format('Y-m-d H:i:s');
	}

	private function run_it()
	{
		$titles = array();
		$db = new Database;

		$db->table('components')->select()->process();
		$output = $db->output();

		$db->table('jobs')->select(array('title'))->process();
		$jobs = $db->output();

		if(!empty($jobs))
			foreach ($jobs as $key => $value)
				$titles[] = $value->title;

		// Check Components Jobs
		if(!empty($output))
			foreach ($output as $key => $value){
				if($value->location == 'administrator')
					$source = _ADM . 'components/' . $value->type . '/';
				else
					$source = 'components/' . $value->type . '/';

				if(System::has_file($source . 'details.json'))
				{
					$details = json_decode(file_get_contents(_SRC_SITE . $source . 'details.json'));

					if(isset($details->jobs))
						foreach ($details->jobs as $keyb => $valueb)
							if(	isset($valueb->title) && !in_array($valueb->title , $titles) && 
								isset($valueb->source) && System::has_file($source . $valueb->source))
							{
								require_once $file = _SRC_SITE . $source . $valueb->source;
								$class = ucwords(strtolower($valueb->title)) . 'Job';

								// agar class vujud dasht
								if(class_exists($class))
								{
									$component = new $class();

									$db->table('jobs')->insert(
											array('title' , 'event_number' , 'next_execute' , 'source') , 
											array($valueb->title , $component->event_number , $component->next_execute , $source . $valueb->source)
										)->process();
								}
							}
				}
			}

		// Update Bad Jobs
		$db->table('jobs')->update(array(array("event_number" , -1) , array("last_execute_status" , 0)))->process();

		// Check Seo Job
		if(Configuration::$seo)
		{
			if(!in_array('Seo' , $titles))
			{
				$source = 'includes/jobs/seo.php';
				require_once $file = _SRC_SITE . $source;
				$class = 'SeoJob';

				// agar class vujud dasht
				if(class_exists($class))
				{
					$component = new $class();

					$db->table('jobs')->insert(
							array('title' , 'event_number' , 'next_execute' , 'source') , 
							array('Seo' , $component->event_number , $component->next_execute , $source)
						)->process();
				}
			}
		}
		else
			System::delete_files(array('sitemap.xml' , 'sitemaps/'));

		// Remove Identification
		$db->table('identification')->delete()->where("`expire` < NOW()")->process();
		// Remove Banned
		$db->table('banned')->delete()->where("`expire` < NOW()")->process();
	}
}