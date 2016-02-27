<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/14/2015
	*	last edit		01/16/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

// Items
$category = $params->category;
$special = $params->special;
$articles = $params->articles;
$newsfeed = $params->newsfeed;
$pagination_output = $params->pagination;
$found_all = $params->found_all;

// Setting
$c_setting = $setting->category;
$setting = json_decode(htmlspecialchars_decode($category->setting));

$countdesc = is_numeric($setting->setting_countdesc) ? $setting->setting_countdesc : (Regex::cs($c_setting->setting_countdesc , 'numeric') ? $c_setting->setting_countdesc : 150);
$heading = $setting->setting_article_heading > 1 ? $c_setting->setting_article_heading : $setting->setting_article_heading;
$pagination = $setting->setting_pagination > 1 ? $c_setting->setting_pagination : $setting->setting_pagination;

Templates::$title = $category->title;

if(Configuration::$seo)
{
	Templates::add_meta('name' , 'keywords' , 'content' , $category->title);
	Templates::add_meta('name' , 'description' , 'content' , $category->title);
	Templates::add_meta('name' , 'robots' , 'content' , 'index, nofollow');
}

Templates::add_css(Site::$base . 'components/content/css/category.css');

if(!empty($special) || !empty($articles))
{
	echo "<div class=\"articles xa\" address=\"" . Site::$full_link_text .  "\">";

		if(!empty($newsfeed))
			echo "<a class=\"newsfeed\" href=\"index.php?component=newsfeed&title=" . str_replace(" " , "-" , $newsfeed[0]->name) . "\">" . Language::_('COM_CONTENT_NEWSFEED') . " <span class=\"icon-feed\"></span></a>";

		if(!empty($special))
			require_once _COMP . 'content/view/category/special.php';

		if(!empty($articles))
			require_once _COMP . 'content/view/category/article.php';

		if(!$pagination && $pagination_output != "")
		{
			Templates::add_js(Site::$base . 'components/content/js/category.js');

			echo "<div id=\"pagination\" class=\"xa\">";
				echo $pagination_output;
			echo "</div>";
		}

	echo "</div>";
}
else
	Messages::add_message('error' , Language::_('ERROR_NOT_EMPTY_CATEGORY'));