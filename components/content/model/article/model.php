<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	07/13/2015
	*	last edit		10/07/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class ArticleModel extends Model {
	public function get_article($code)
	{
		$this->table('article')->where('`code` = "' . $code . '"')->select()->process();
		return $this->output();
	}

	public function inc_views($id , $views)
	{
		return $this->table('article')->update(array(array('views' , ($views + 1))))->where('`id` = ' . $id)->process();
	}

	public function set_category_menu($category)
	{
		if(Preload::$active_menu_group_homepage == -1 && Preload::$active_menu_homepage == -1)
		{
			$this->table('category')->select()->where('`id` = ' . $category)->process();
			$category = $this->output();
			$link = "index.php?component=content&amp;view=category&amp;id=" . $category[0]->code . "&amp;title=" . str_replace(" " , "-" , $category[0]->title);
			$this->table('menu')->select()->where('`link` = "' . $link . '"')->process();

			if($menu = $this->output())
			{
				Preload::$active_menu_group_homepage = $menu[0]->group_number;
				Preload::$active_menu_homepage = $menu[0]->id;
			}
		}
	}

	public function article_update($likes , $dislikes , $comments , $likes_id , $dislikes_id , $id)
	{
		return $this->table('article')
					->update(
								array(
									array('likes' , $likes) , 
									array('likes_id' , htmlspecialchars(json_encode($likes_id , JSON_UNESCAPED_UNICODE))) , 
									array('dislikes' , $dislikes) , 
									array('dislikes_id' , htmlspecialchars(json_encode($dislikes_id , JSON_UNESCAPED_UNICODE))) , 
									array('comments' , $comments)
								)
					)->where('`id` = ' . $id)->process();
	}

	public function get_comment($code)
	{
		$this->table('comments')->where('`code` = "' . $code . '"')->select()->process();
		return $this->output();
	}

	public function get_comments($article)
	{
		$this->table('comments')->where('`article` = ' . $article)->select()->order("create_date ASC")->process();
		return $this->output();
	}

	public function comment_insert($status , $article , $parent , $name , $email , $comment)
	{
		$code = "";

		while(true){
			$code = Regex::random_string(1 , 'alpha') . Regex::random_string(14);
			$this->table('comments')->where('`code` = "' . $code . '"')->select()->process();

			if(!$this->output())
				break;
		}

		$this->table("article")->select()->where("`id` = " . $article)->process();
		$article_output = $this->output();

		$this->table("article")
				->update(array(
							array("comments" , $article_output[0]->comments + 1) , 
							array("comments_verify" , $article_output[0]->comments_verify + !$status)
						))->where("`id` = " . $article_output[0]->id)->process();

		return $this->table('comments')
				->insert(
					array(	'status' , 'code' , 'article' , 'parent' , 
							'name' , 'email' , 'comment' , 
							'publish_date' , 'create_date' , 'user') , 
					array(	$status , $code , $article , $parent , 
							$name , $email , htmlspecialchars($comment) , 
							Site::$datetime , Site::$datetime , User::$id)
				)->process();
	}

	public function comment_update($likes , $dislikes , $likes_id , $dislikes_id , $id)
	{
		return $this->table('comments')
					->update(
								array(
									array('likes' , $likes) , 
									array('likes_id' , htmlspecialchars(json_encode($likes_id , JSON_UNESCAPED_UNICODE))) , 
									array('dislikes' , $dislikes) , 
									array('dislikes_id' , htmlspecialchars(json_encode($dislikes_id , JSON_UNESCAPED_UNICODE)))
								)
					)->where('`id` = ' . $id)->process();
	}	
}