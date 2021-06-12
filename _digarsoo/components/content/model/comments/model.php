<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	02/15/2016
	*	last edit		02/18/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class CommentsModel extends Model {
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
			$this->table('comments')->where('`code` = "' . $code . '"')->select()->process();

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
				"setting_comments_info" => $_POST['field_input_setting_comments_info']
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

		$this->table('comments')
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

	public function update_comments()
	{
		$code = $_POST['field_input_code'];

		$this->table('comments')->select()->where("`code` = '" . $code . "'")->process();
		$comment = $this->output();

		if($comment[0]->status != $_POST['field_input_status'])
			if($_POST['field_input_status'] == 0)
				$this->update_article($comment[0]->id , 0 , 1 , 0);
			else
				$this->update_article($comment[0]->id , 0 , -1 , 0);

		$this->table('comments')
				->update(array(
							array("status" , $_POST['field_input_status']) , 
							array("parent" , $_POST['field_input_parent']) , 
							array("name" , $_POST['field_input_name']) , 
							array("email" , $_POST['field_input_email']) , 
							array("comment" , htmlentities($_POST['field_input_comment'])) , 
							array("publish_date" , Site::$datetime) ,
						)
				)->where('`id` = ' . $_GET['id'])->process();

		return $this->process();
	}

	public function update_article($id , $comments = 0 , $comments_verify = 0 , $comments_deleted = 0)
	{
		$ids = explode("," , $id);
		$id_out = "";

		foreach ($ids as $key => $value) {
			if($id_out)
				$id_out .= " OR ";

			$id_out .= "`id` = " . $value;
		}

		$this->table("comments")->select()->where($id_out)->process();
		$comments_output = $this->output();

		if(!empty($comments_output))
		{
			$id_out = "";

			foreach ($comments_output as $key => $value) {
				if($id_out)
					$id_out .= " OR ";

				$id_out .= "`id` = " . $value->article;
			}

			$articles = array();
			$this->table("article")->select()->where($id_out)->process();
			$articles_output = $this->output();

			if(!empty($articles_output))
			{
				foreach ($articles_output as $key => $value)
					$articles[$value->id] = $value;

				foreach ($comments_output as $key => $value)
					if(isset($articles[$value->article]))
					{
						$diff = 0;
						if($comments_deleted && !$value->status)
							$diff--;

						$this->table("article")
								->update(array(
											array("comments" , $articles[$value->article]->comments + $comments) , 
											array("comments_verify" , $articles[$value->article]->comments_verify + $comments_verify + $diff) , 
											array("comments_deleted" , $articles[$value->article]->comments_deleted + $comments_deleted)
										))->where("`id` = " . $value->article)->process();
					}
			}
		}
	}
}