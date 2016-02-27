<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/13/2015
	*	last edit		02/18/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

$article = $params->article;
$comments = $params->comments;

$db = new Database();
$as = json_decode(htmlspecialchars_decode($article->setting));
$setting = $setting->article;

Templates::$title = $article->title;

if(Configuration::$seo)
{
	if($article->meta_tag != "")
		Templates::add_meta('name' , 'keywords' , 'content' , $article->meta_tag);
	else
		Templates::add_meta('name' , 'keywords' , 'content' , str_replace(" " , "," , $article->title));

	if($article->meta_desc != "")
		Templates::add_meta('name' , 'description' , 'content' , $article->meta_desc);
	else
		Templates::add_meta('name' , 'description' , 'content' , $article->title);

	Templates::add_meta('name' , 'robots' , 'content' , 'index, follow');
}

Templates::add_css(Site::$base . 'components/content/css/article.css');
Templates::add_js(Site::$base . 'components/content/js/article.js');

$setting_title = $as->setting_title == 2 ? $setting->setting_title : $as->setting_title;
$setting_heading = $as->setting_heading == 2 ? $setting->setting_heading : $as->setting_heading;
$setting_author = $as->setting_author == 2 ? $setting->setting_author : $as->setting_author;
$setting_publish_date = $as->setting_publish_date == 2 ? $setting->setting_publish_date : $as->setting_publish_date;
$setting_views = $as->setting_views == 2 ? $setting->setting_views : $as->setting_views;
$setting_tags = $as->setting_tags == 2 ? $setting->setting_tags : $as->setting_tags;
$setting_likes = $as->setting_likes == 2 ? $setting->setting_likes : $as->setting_likes;
$setting_likes_permission = $as->setting_likes_permission == 2 ? $setting->setting_likes_permission : $as->setting_likes_permission;
$setting_comments = $as->setting_comments == 2 ? $setting->setting_comments : $as->setting_comments;
$setting_comments_permission = $as->setting_comments_permission == 2 ? $setting->setting_comments_permission : $as->setting_comments_permission;
$setting_comments_confirmation = $as->setting_comments_confirmation == 2 ? $setting->setting_comments_confirmation : $as->setting_comments_confirmation;
$setting_code = $as->setting_code == 2 ? $setting->setting_code : $as->setting_code;
$setting_article_info = $as->setting_article_info == 2 ? $setting->setting_article_info : $as->setting_article_info;

echo "\n<div class=\"article xa\" ajax=\"index.php?component=content&amp;ajax=" . $article->code . "\">";
	if(!$setting_heading)
		echo "\n\t<h3 class=\"article-heading xa\">\n\t\t" . $article->heading . "\n\t</h3>";

	if(!$setting_title)
		echo "\n\t<h2 class=\"article-title xa\">\n\t\t" . $article->title . "\n\t</h2>";

	// Read Information
	if(!$setting_article_info)
		require _COMP . 'content/view/article/information.php';

	// Read Article
	require _COMP . 'content/view/article/article.php';

	// Read Information
	if($setting_article_info)
		require _COMP . 'content/view/article/information.php';
echo "\n</div>";