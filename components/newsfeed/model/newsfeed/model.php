<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/28/2015
	*	last edit		01/16/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class NewsfeedModel extends Model {
	public function has_newsfeed($name)
	{
		$this->table('newsfeed')->where('`name` = "' . $name . '"')->select()->process();
		return $this->output();
	}

	public function get_articles($newsfeed)
	{
		$categories = json_decode(html_entity_decode($newsfeed->category));
		$order_check = $newsfeed->sort;

		if($order_check == 0) { $sort = 'publish_date'; $sort_order = 'DESC'; }
		else if($order_check == 1) { $sort = 'publish_date'; $sort_order = 'ASC'; }
		else if($order_check == 2) { $sort = 'title';  $sort_order = 'ASC'; }
		else if($order_check == 3) { $sort = 'title';  $sort_order = 'DESC'; }
		else if($order_check == 4) { $sort = 'views';  $sort_order = 'DESC'; }
		else if($order_check == 5) { $sort = 'views';  $sort_order = 'ASC'; }
		else if($order_check == 6) { $sort = 'id';  $sort_order = 'DESC'; }
		else if($order_check == 7) { $sort = 'id';  $sort_order = 'ASC'; }

		$where = "";
		foreach ($categories as $key => $value) {
			if($key)
				$where .= ' OR ';

			$where .= '`category` = ' . $value;
		}

		$this->table('article')->select()->where('`status` = 0 AND ' . $where)->order('id DESC , ' . $sort . ' ' . $sort_order)->limit($newsfeed->count)->process();
		return $this->output();
	}
}