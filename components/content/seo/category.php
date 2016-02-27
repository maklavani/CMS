<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	12/21/2015
	*	last edit		01/20/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class CategoryContentSeo {
	private $last_modified;
	private $location;

	function __construct()
	{
		$this->location = Site::get_address_site() . 'sitemaps/content_category.xml';
		$this->last_modified = Site::$datetime;
	}

	public function get_location()
	{
		return $this->location;
	}

	public function run()
	{
		$db = new Database();
		$db->table('category')->select()->order('`create_date` DESC')->process();
		$categories = $db->output();

		$lastmod = "";
		if(!empty($categories))
		{
			// Open Sitemap File
			$sitemap = fopen(_SRC_SITE . "sitemaps/content_category.xml" , "w");
			fwrite($sitemap , "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n");

			foreach ($categories as $key => $value){
				if($lastmod == "" || strtotime($value->edit_date) > strtotime($lastmod))
					$lastmod = $value->edit_date;

				fwrite($sitemap , "\t<url>\n\t\t<loc>" . Site::get_address_site() . Language::_('COM_CONTENT') . "/" . Language::_('COM_CONTENT_CATEGORY') . "/" . $value->code . "/" . str_replace(" " , "-" , $value->title) . "</loc>\n\t\t<lastmod>" . $value->edit_date . "</lastmod>\n\t\t<changefreq>daily</changefreq>\n\t\t<priority>0.9</priority>\n\t</url>\n");
			}

			if($lastmod != "")
				$this->last_modified = $lastmod;

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