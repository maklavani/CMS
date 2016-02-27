<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	12/21/2015
	*	last edit		01/19/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class SeoJob Extends Jobs {
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
		return (new DateTime(date("Y-m-d 0:12:0")))->modify('+1 day')->format('Y-m-d H:i:s');
	}

	private function run_it()
	{
		// Sakhtane Pushe Sitemap Dar surate niyaz
		if(!System::has_file('sitemaps') && mkdir(_SRC_SITE . "sitemaps" , 0755 , true))
		{
			$html_file = fopen(_SRC_SITE . "sitemaps/index.html" , "w");
			fwrite($html_file , "<!DOCTYPE html><title></title>");
			fclose($html_file);
		}

		// Open Sitemap File
		$sitemap = fopen(_SRC_SITE . "sitemap.xml" , "w");
		fwrite($sitemap , "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<sitemapindex xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n");

		$db = new Database;
		$db->table('components')->select()->where('`location` = "site"')->process();
		$output = $db->output();

		// Check Components Seos
		if(!empty($output))
			foreach ($output as $key => $value){
				$source = 'components/' . $value->type . '/';

				if(System::has_file($source . 'details.json'))
				{
					$details = json_decode(file_get_contents(_SRC_SITE . $source . 'details.json'));

					if(isset($details->seo))
					{
						if(isset($details->language))
							foreach ($details->language as $lang)
								if($lang->name == Configuration::$seo_language && System::has_file('languages/' . $lang->name . '/' . $lang->src))
									Language::add_ini_file(_SRC_SITE . 'languages/' . $lang->name . '/' . $lang->src);


						foreach ($details->seo as $keyb => $valueb)
							if(isset($valueb->title) && isset($valueb->source) && System::has_file($source . $valueb->source))
							{
								require_once $file = _SRC_SITE . $source . $valueb->source;
								$class = ucwords(strtolower($valueb->title)) . $value->type . 'Seo';

								// agar class vujud dasht
								if(class_exists($class))
								{
									$component = new $class();
									$component->run();

									fwrite($sitemap , "\t<sitemap>\n\t\t");
									fwrite($sitemap , "<loc>" . $component->get_location() . "</loc>\n\t\t");
									fwrite($sitemap , "<lastmod>" . $component->get_last_mod() . "</lastmod>\n\t\t");
									fwrite($sitemap , "<priority>1</priority>\n\t");
									fwrite($sitemap , "</sitemap>\n");
								}
							}
					}
				}
			}

		// Close Sitemap File
		fwrite($sitemap , "</sitemapindex>");
		fclose($sitemap);
	}
}