<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	12/21/2015
	*	last edit		10/22/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class ArticleContentSeo {
	private $last_modified;
	private $location;

	function __construct()
	{
		$this->location = Site::get_address_site() . 'sitemaps/content_article.xml';
		$now_time = new DateTime();
		$this->last_modified = $now_time->format("Y-m-d\TH:i:s+00:00");
	}

	public function get_location()
	{
		return $this->location;
	}

	public function run()
	{
		$db = new Database();
		$db->table('article')->select()->where('`status` = 0 AND `publish_date` <= NOW()')->order('`create_date` DESC')->process();
		$articles = $db->output();

		$lastmod = "";		
		if(!empty($articles))
		{
			// Open Sitemap File
			$sitemap = fopen(_SRC_SITE . "sitemaps/content_article.xml" , "w");
			fwrite($sitemap , "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n");

			foreach ($articles as $key => $value){
				if($lastmod == "" || strtotime($value->edit_date) > strtotime($lastmod))
					$lastmod = $value->edit_date;

				$edit_time = new DateTime($value->edit_date);

				fwrite($sitemap , "\t<url>\n\t\t<loc>" . Site::get_address_site() . Language::_('COM_CONTENT') . "/" . Language::_('COM_CONTENT_ARTICLE') . "/" . $value->code . "/" . str_replace(" " , "-" , $value->title) . "</loc>\n\t\t<lastmod>" . $edit_time->format("Y-m-d\TH:i:s+00:00") . "</lastmod>\n\t\t<changefreq>monthly</changefreq>\n\t\t<priority>0.9</priority>\n\t</url>\n");
			}

			if($lastmod != "")
			{
				$lastmod_time = new DateTime($lastmod);
				$this->last_modified = $lastmod_time->format("Y-m-d\TH:i:s+00:00");
			}

			// Close Sitemap File
			fwrite($sitemap , "</urlset>");
			fclose($sitemap);
		}
	}

	public function get_last_mod()
	{
		return $this->last_modified;
	}
}