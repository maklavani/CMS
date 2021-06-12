<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	09/07/2015
	*	last edit		01/28/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

require_once _INC . 'system/calendar.php';
$calendar = new Calendar('shamsi');

Templates::add_css(Site::$base . 'widgets/articles/css/template.css');

// Setting
$limit = is_numeric($setting->setting_limit) ? $setting->setting_limit : 10;
$countdesc = is_numeric($setting->setting_countdesc) ? $setting->setting_countdesc : 150;
$heading = $setting->setting_article_heading;

// Oder Check
$order_check = $setting->setting_sort;

if($order_check == 0) { $sort = 'publish_date'; $sort_order = 'DESC'; }
else if($order_check == 1) { $sort = 'publish_date'; $sort_order = 'ASC'; }
else if($order_check == 2) { $sort = 'title';  $sort_order = 'ASC'; }
else if($order_check == 3) { $sort = 'title';  $sort_order = 'DESC'; }
else if($order_check == 4) { $sort = 'views';  $sort_order = 'DESC'; }
else if($order_check == 5) { $sort = 'views';  $sort_order = 'ASC'; }
else if($order_check == 6) { $sort = 'id';  $sort_order = 'DESC'; }
else if($order_check == 7) { $sort = 'id';  $sort_order = 'ASC'; }

// Categories
$category_out = "";
if(!empty($setting->categories))
	foreach($setting->categories as $value){
		if($category_out != "")
			$category_out .= " OR ";
		$category_out .= "`category` = " . $value;
	}


// Articles
$articles = array();

if($category_out != "")
{
	$db = new Database;
	$db->table('article')->select()->where('`status` = 0 AND (' . $category_out . ')')->order($sort . ' ' . $sort_order)->limit($limit)->process();
	$articles = $db->output();
}
?>
<div class="articles xa">
	<div class="articles-in xa">
		<?php
			foreach ($articles as $key => $value) {
				$content = html_entity_decode($value->content);
				$pos = strpos($content , '<hr />');

				if($pos > -1)
					$content = preg_replace("/&#?[a-z0-9]+;/i" , "" , strip_tags(substr($content , 0 , $pos)));
				else
					$content = preg_replace("/&#?[a-z0-9]+;/i" , "" , strip_tags($content));

				if(strlen($content) > $countdesc)
					$content = mb_substr($content , 0 , $countdesc) . ' ...';

				echo "<a class=\"articles-items xa\" href=\"index.php?component=content&amp;view=article&amp;id=" . $value->code . '&amp;title=' . str_replace(" " , "-" , $value->title) . "\">";
					echo "<div class=\"xa\">" . $value->title . "</div>";
					echo "<span class=\"xa\">" . $content . "</span>";
					echo "<small>" . $calendar->convert($value->publish_date , 'd M Y') . "</small>";
				echo "</a>";
			}
		?>
		<?php
			if(count($setting->categories) == 1)
			{
				$db->table('category')->where('`id` = ' . $setting->categories[0])->select()->process();
				$category = $db->output();

				echo "<a class=\"articles-link x97 ex015 aex015\" href=\"index.php?component=content&amp;view=category&amp;id=" . $category[0]->code . '&amp;title=' . str_replace(" " , "-" , $category[0]->title) . "\">" . Language::_('WID_ARTICLES_ALL') . "</a>";
			}
		?>
	</div>
</div>