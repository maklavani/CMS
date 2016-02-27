<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/06/2015
	*	last edit		12/16/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class ArticleModel extends Model {
	public function save()
	{
		require_once _INC . 'system/calendar.php';

		if(Language::$lang == "fa-ir")
			$calendar = new Calendar('shamsi');
		else
			$calendar = new Calendar();

		$code = "";

		while(true){
			$code = Regex::random_string(1 , 'alpha') . Regex::random_string(9);
			$this->table('article')->where('`code` = "' . $code . '"')->select()->process();

			if(!$this->output())
				break;
		}

		$setting = htmlspecialchars(json_encode(
			array(
				"setting_title" => $_POST['field_input_setting_title'] , 
				"setting_heading" => $_POST['field_input_setting_heading'] , 
				"setting_author" => $_POST['field_input_setting_author'] , 
				"setting_publish_date" => $_POST['field_input_setting_publish_date'] , 
				"setting_views" => $_POST['field_input_setting_views'] , 
				"setting_tags" => $_POST['field_input_setting_tags'] , 
				"setting_likes" => $_POST['field_input_setting_likes'] , 
				"setting_likes_permission" => $_POST['field_input_setting_likes_permission'] , 
				"setting_comments" => $_POST['field_input_setting_comments'] , 
				"setting_comments_permission" => $_POST['field_input_setting_comments_permission'] , 
				"setting_comments_confirmation" => $_POST['field_input_setting_comments_confirmation'] , 
				"setting_code" => $_POST['field_input_setting_code'] , 
				"setting_article_info" => $_POST['field_input_setting_article_info']
			) , JSON_UNESCAPED_UNICODE));

		$tags = htmlspecialchars(json_encode($_POST['field_input_tags'] , JSON_UNESCAPED_UNICODE));

		if(!empty($_POST['field_input_tags']) && is_array($_POST['field_input_tags']))
			foreach ($_POST['field_input_tags'] as $value){
				$this->table('tags')->select()->where('`name` = "' . $value . '"')->process();

				if(!$this->output())
					$this->table('tags')->insert(array('name') , array($value))->process();
			}

		$image = $_POST['field_input_image'];
		$images = explode("/" , $image);
		if($images[0] == "uploads")
			$images[0] = 'download';
		$image = implode("/" , $images);

		$publish_input = explode("-" , $_POST['field_input_publish_date']);
		$publish_date = $calendar->get_gregorian($publish_input[0] , $publish_input[1] , $publish_input[2]);

		$this->table('article')
					->insert(
						array(	'title' , 'status' , 'special' , 'category' , 'permission' , 
								'content' , 'code' , 'tags' , 'image' , 'meta_tag' , 
								'meta_desc' , 'heading' , 'setting' , 'edit_date' , 'publish_date' , 
								'create_date' , 'user') , 
						array(	htmlspecialchars(trim($_POST['field_input_title'])) , $_POST['field_input_status'] , $_POST['field_input_special'] , $_POST['field_input_category'] , $_POST['field_input_permission'] , 
								htmlentities($_POST['field_input_content']) , $code , $tags , $image , $_POST['field_input_meta_tag'] , 
								$_POST['field_input_meta_desc'] , htmlspecialchars(trim($_POST['field_input_heading'])) , $setting , Site::$datetime , $publish_date->format('Y-m-d') . " 12:00:01" , 
								Site::$datetime , User::$id)
					)->process();

		return $this->last_insert_id;
	}

	public function update_article()
	{
		require_once _INC . 'system/calendar.php';

		if(Language::$lang == "fa-ir")
			$calendar = new Calendar('shamsi');
		else
			$calendar = new Calendar();

		$code = $_POST['field_input_code'];

		$setting = htmlspecialchars(json_encode(
			array(
				"setting_title" => $_POST['field_input_setting_title'] , 
				"setting_heading" => $_POST['field_input_setting_heading'] , 
				"setting_author" => $_POST['field_input_setting_author'] , 
				"setting_publish_date" => $_POST['field_input_setting_publish_date'] , 
				"setting_views" => $_POST['field_input_setting_views'] , 
				"setting_tags" => $_POST['field_input_setting_tags'] , 
				"setting_likes" => $_POST['field_input_setting_likes'] , 
				"setting_likes_permission" => $_POST['field_input_setting_likes_permission'] , 
				"setting_comments" => $_POST['field_input_setting_comments'] , 
				"setting_comments_permission" => $_POST['field_input_setting_comments_permission'] , 
				"setting_comments_confirmation" => $_POST['field_input_setting_comments_confirmation'] , 
				"setting_code" => $_POST['field_input_setting_code'] , 
				"setting_article_info" => $_POST['field_input_setting_article_info']
			) , JSON_UNESCAPED_UNICODE));

		$tags = htmlspecialchars(json_encode($_POST['field_input_tags'] , JSON_UNESCAPED_UNICODE));

		$image = $_POST['field_input_image'];
		$images = explode("/" , $image);
		if($images[0] == "uploads")
			$images[0] = 'download';
		$image = implode("/" , $images);

		$publish_input = explode("-" , $_POST['field_input_publish_date']);
		$publish_date = $calendar->get_gregorian($publish_input[0] , $publish_input[1] , $publish_input[2]);

		$this->table('article')
				->update(array(
							array("title" , htmlspecialchars(trim($_POST['field_input_title']))) , 
							array("status" , $_POST['field_input_status']) , 
							array("special" , $_POST['field_input_special']) , 
							array("category" , $_POST['field_input_category']) , 
							array("permission" , $_POST['field_input_permission']) , 
							array("content" , htmlentities($_POST['field_input_content'])) , 
							array("tags" , $tags) , 
							array("image" , $image) , 
							array("meta_tag" , $_POST['field_input_meta_tag']) , 
							array("meta_desc" , $_POST['field_input_meta_desc']) , 
							array("heading" , htmlspecialchars(trim($_POST['field_input_heading']))) , 
							array("setting" , $setting) , 
							array("publish_date" , $publish_date->format('Y-m-d') . " 12:00:01") , 
							array("edit_date" , Site::$datetime)
						)
				)->where('`id` = ' . $_GET['id'])->process();

		return $this->process();
	}

	// set kardane special
	public function set_special($ids , $table){
		$total_id = "";
		$id = explode("," , $ids);

		if(!empty($id))
			foreach ($id as $value) {
				if($total_id != "")
					$total_id .= " OR ";

				$total_id .= '`id` = ' . $value;
			}

		if($total_id != "")
		{
			$special = 0;

			if(count($id) == 1)
			{
				$this->table($table)->select()->where($total_id)->process();
				$item = $this->output();

				if (!empty($item[0]) && $item[0]->special == 0)
					$special = 1;
			}

			$this->table($table)->where($total_id)->update(array(array('special' , $special)));

			if($this->process())
			{
				if($special == 1)
					Messages::add_message('success' , Language::_('COM_CONTENT_SUCCESS_UNSPCEIAL'));
				else
					Messages::add_message('success' , Language::_('COM_CONTENT_SUCCESS_SPCEIAL'));

				return true;
			}
		}

		Messages::add_message('success' , Language::_('COM_CONTENT_ERROR_SPCEIAL'));
		return false;
	}

	// pak kardane kardane special
	public function set_unspecial($ids , $table){
		$total_id = "";
		$id = explode("," , $ids);

		if(!empty($id))
			foreach ($id as $value) {
				if($total_id != "")
					$total_id .= " OR ";

				$total_id .= '`id` = ' . $value;
			}

		if($total_id != "")
		{
			$this->table($table)->where($total_id)->update(array(array('special' , 1)));

			if($this->process())
			{
				Messages::add_message('success' , Language::_('COM_CONTENT_SUCCESS_UNSPCEIAL'));
				return true;
			}
		}

		Messages::add_message('success' , Language::_('COM_CONTENT_ERROR_UNSPCEIAL'));
		return false;
	}
}