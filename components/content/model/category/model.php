<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	07/14/2015
	*	last edit		01/16/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class CategoryModel extends Model {
	public $found_all;

	public function get_category($code)
	{
		$this->table('category')->where('`code` = "' . $code . '"')->select()->process();
		return $this->output();
	}

	public function get_articles($cid , $setting)
	{
		// Setting
		$c_setting = Components::$setting->category;
		$setting = json_decode(htmlspecialchars_decode($setting));
		$ids_found = array();
		$ids_out = "";

		$pagination = $setting->setting_pagination > 1 ? $c_setting->setting_pagination : $setting->setting_pagination;

		// Oder Check
		$order_check = $setting->setting_sort;
		if($order_check == 8)
			$order_check = $c_setting->setting_sort;

		if($order_check == 0) { $sort = 'publish_date'; $sort_order = 'DESC'; }
		else if($order_check == 1) { $sort = 'publish_date'; $sort_order = 'ASC'; }
		else if($order_check == 2) { $sort = 'title';  $sort_order = 'ASC'; }
		else if($order_check == 3) { $sort = 'title';  $sort_order = 'DESC'; }
		else if($order_check == 4) { $sort = 'views';  $sort_order = 'DESC'; }
		else if($order_check == 5) { $sort = 'views';  $sort_order = 'ASC'; }
		else if($order_check == 6) { $sort = 'id';  $sort_order = 'DESC'; }
		else if($order_check == 7) { $sort = 'id';  $sort_order = 'ASC'; }

		// Number and Page
		$page = 1;
		if(!$pagination && isset($_GET['page']) && Regex::cs($_GET['page'] , 'numeric') && $_GET['page'] > 0)
			$page = (int)$_GET['page'];

		$number = Regex::cs($setting->setting_limit , 'numeric') ? $setting->setting_limit : (Regex::cs($c_setting->setting_limit , 'numeric') ? $c_setting->setting_limit : 0);
		if(!$pagination && isset($_GET['number']) && Regex::cs($_GET['number'] , 'numeric') && in_array($_GET['number'] , array(5 , 10 , 20 , 50 , 100)))
			$number = (int)$_GET['number'];

		// Special
		$special = $setting->setting_special > 1 ? $c_setting->setting_special : $setting->setting_special;
		$special_related = $setting->setting_special_related > 1 ? $c_setting->setting_special_related : $setting->setting_special_related;
		$special_limit = $setting->setting_special_limit;

		$special_items = array();
		$related_ids = array();

		if(!$special && Regex::cs($special_limit , 'numeric') && $special_limit > 0)
		{
			$this->table('article')->select()->where('`status` = 0 AND `category` = ' . $cid . ' AND `special` = 0')->order('id  DESC , ' . $sort . ' ' . $sort_order)->limit($special_limit)->process();
			$special_items = $this->output();

			if(!$special_related && !empty($special_items))
			{
				foreach ($special_items as $key => $value)
					if(!in_array($value->id , $related_ids))
					{
						$ids_found[] = $value->id;

						$tags_out = "";
						$tags = json_decode(htmlspecialchars_decode($value->tags));

						if(!empty($tags))
							foreach ($tags as $keyc => $valuec) {
								if($tags_out != "")
									$tags_out .= " AND ";
								else if($tags_out == "")
									$tags_out .= "(";

								$tags_out .= "`tags` LIKE \"%" . $valuec . "%\"";

								if(count($tags) == $keyc + 1)
									$tags_out .= ")";
							}

						if($tags_out != "")
						{
							$this->table('article')->select()->where('`id` != ' . $value->id . ' AND `status` = 0 AND `category` = ' . $cid . ' AND ' . $tags_out)->order('id  DESC , ' . $sort . ' ' . $sort_order)->process();
							$related = $this->output();

							if(!empty($related))
							{
								$special_items[$key]->related = array();

								foreach ($related as $keyb => $valueb) {
									$ids_found[] = $related_ids[] = $valueb->id;
									$special_items[$key]->related[] = $valueb;
								}
							}
						}
					}
					else
						unset($special_items[$key]);
			}
			else
				foreach ($special_items as $key => $value)
					$ids_found[] = $value->id;

		}

		// baraye safahate dakheli special pak shavad
		if($page > 1)
			$special_items = array();

		// News Feed
		$newsfeed = $setting->setting_newsfeed > 1 ? $c_setting->setting_newsfeed : $setting->setting_newsfeed;
		$newsfeed_items = array();

		if(!$newsfeed)
		{
			$this->table('newsfeed')->select()->where('`status` = 0 AND `category` = "[&quot;' . $cid . '&quot;]"')->process();
			$newsfeed_items = $this->output();
		}

		// Articles
		if(!empty($ids_found))
		{
			$ids_out = " AND ";

			foreach ($ids_found as $key => $value) {
				if($key)
					$ids_out .= " AND ";

				$ids_out .= "`id` != " . $value;
			}
		}

		$this->table('article')->select()->where('`status` = 0 AND `category` = ' . $cid . $ids_out)->order('id  DESC , ' . $sort . ' ' . $sort_order)->limit(($page - 1) * $number + 1 , $page * $number)->process();
		$output = $this->output();
		$this->found_all = $this->get_found_all();

		// Pagination
		$pagination_output = "";

		if(!$pagination)
			$pagination_output = $this->get_pagination($this->found_all , $page , $number);

		return array("special" => $special_items , "articles" => $output , "newsfeed" => $newsfeed_items , "pagination" => $pagination_output , "found_all" => $this->found_all);
	}

	// sakhtane pagination
	private function get_pagination($total , $page , $number)
	{
		$output = "";

		if($page && $number > 0 && $number <= 100 && !($number == 1 && $page == 1)){
			if($total > $number){
				$total_field = $total / $number;
				$total_field = (int)($total_field) < $total_field ? (int)($total_field) + 1 : $total_field;
				
				$output .= "<div class=\"pagination-item item-prev\" page=\"" . (($page > 1) ? $page - 1 : $total_field) . "\">" . Language::_('COM_CONTENT_PREV') . "</div>";

				$k = 0;
				for ($i = 0;$i < $total_field;$i++) {
					if($i + 1 == $page){
						$output .= "<div class=\"pagination-item item-current\">" . ($i + 1) . "</div>";
						$k = 0;
					} else if(!$i || $i == ($total_field - 1) || ($i + 4) > $page && ($i - 2) < $page) {
						$output .= "<div class=\"pagination-item\" page=\"" . ($i + 1) . "\">" . ($i + 1) . "</div>";
						$k = 0;
					} else if(!$k) {
						$output .= "<div class=\"pagination-item item-false\">...</div>";
						$k = 1;
					}
				}

				$output .= "<div class=\"pagination-item item-next\" page=\"" . (($page < $total_field) ? $page + 1 : 1) . "\">" . Language::_('COM_CONTENT_NEXT') . "</div>";
			}
		}

		return $output;
	}
}