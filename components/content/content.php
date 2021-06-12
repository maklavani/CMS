<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	07/13/2015
	*	last edit		02/18/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class ContentController extends Controller {
	public function ajax(){
		if(isset($_GET['ajax']) && strlen($_GET['ajax']) == 10 && Regex::cs($_GET['ajax'] , "ansi") && isset($_GET['type']) && in_array($_GET['type'] , array("like" , "dislike" , "comment" , "likecomment" , "dislikecomment")) && isset($_GET['message']))
		{
			$code = $_GET['ajax'];
			$type = $_GET['type'];
			$message = $_GET['message'];
			$model = self::model("article");
			$article = $model->get_article($code);

			if(!empty($article))
			{
				// Setting
				$as = json_decode(htmlspecialchars_decode($article[0]->setting));
				$setting = Components::$setting->article;
				$setting_likes_permission = $as->setting_likes_permission == 2 ? $setting->setting_likes_permission : $as->setting_likes_permission;
				$setting_comments_confirmation = $as->setting_comments_confirmation == 2 ? $setting->setting_comments_confirmation : $as->setting_comments_confirmation;

				// Article
				$likes_number = $article[0]->likes;
				$dislikes_number = $article[0]->dislikes;
				$comments_number = $article[0]->comments;
				$likes_id = json_decode(htmlspecialchars_decode($article[0]->likes_id));
				$dislikes_id = json_decode(htmlspecialchars_decode($article[0]->dislikes_id));

				if($type == "like")
				{
					if(User::$login)
					{
						$found = false;

						if(!empty($likes_id))
							foreach ($likes_id as $key => $value)
								if(isset($value->user) && $value->user == User::$id)
								{
									$found = true;
									break;
								}

						if(!$found)
						{
							if(!empty($dislikes_id))
								foreach ($dislikes_id as $key => $value)
									if(isset($value->user) && $value->user == User::$id)
									{
										$dislikes_number--;
										unset($dislikes_id[$key]);
										break;
									}

							if(!is_array($likes_id))
								$likes_id = array();

							$likes_id[] = array("user" => User::$id , "datetime" => Site::$datetime);
							$likes_number++;

							$model->article_update($likes_number , $dislikes_number , $comments_number , $likes_id , $dislikes_id , $article[0]->id);
							echo json_encode(array("status" => true , "information" => array("likes" => number_format($likes_number) , "dislikes" => number_format($dislikes_number))) , JSON_UNESCAPED_UNICODE);
						}
						else
							echo json_encode(array("status" => false , "message" => Language::_("COM_CONTENT_ERROR_COMMENT")) , JSON_UNESCAPED_UNICODE);
					}
					else if(!$setting_likes_permission)
					{
						$likes_session = json_decode(htmlspecialchars_decode(Sessions::get_session('articles_like')) , true);
						$likes_cookie = json_decode(htmlspecialchars_decode(Cookies::get_cookie('articles_like')) , true);

						$dislikes_session = json_decode(htmlspecialchars_decode(Sessions::get_session('articles_dislike')) , true);
						$dislikes_cookie = json_decode(htmlspecialchars_decode(Cookies::get_cookie('articles_dislike')) , true);

						if(!isset($likes_session[$code]) && !isset($likes_cookie[$code]))
						{
							if(isset($dislikes_session[$code]) || isset($dislikes_cookie[$code]))
							{
								if(isset($dislikes_session[$code]))
									unset($dislikes_session[$code]);

								if(isset($dislikes_cookie[$code]))
									unset($dislikes_cookie[$code]);

								$dislikes_number--;
								Sessions::set_session("articles_dislike" , htmlspecialchars(json_encode($dislikes_session , JSON_UNESCAPED_UNICODE)));
								Cookies::set_cookie("articles_dislike" , htmlspecialchars(json_encode($dislikes_session , JSON_UNESCAPED_UNICODE)) , time() + 2592000);
							}

							if(!is_array($likes_session))
								$likes_session = array();
							$likes_session[$code] = array("user" => User::$id , "datetime" => Site::$datetime);

							if(!is_array($likes_cookie))
								$likes_cookie = array();
							$likes_cookie[$code] = array("user" => User::$id , "datetime" => Site::$datetime);

							$likes_number++;

							Sessions::set_session("articles_like" , htmlspecialchars(json_encode($likes_session , JSON_UNESCAPED_UNICODE)));
							Cookies::set_cookie("articles_like" , htmlspecialchars(json_encode($likes_cookie , JSON_UNESCAPED_UNICODE)) , time() + 2592000);

							$model->article_update($likes_number , $dislikes_number , $comments_number , $likes_id , $dislikes_id , $article[0]->id);
							echo json_encode(array("status" => true , "information" => array("likes" => number_format($likes_number) , "dislikes" => number_format($dislikes_number))) , JSON_UNESCAPED_UNICODE);
						}
						else
							echo json_encode(array("status" => false , "message" => Language::_("COM_CONTENT_ERROR_COMMENT")) , JSON_UNESCAPED_UNICODE);
					}
					else
						echo json_encode(array("status" => false , "message" => Language::_("COM_CONTENT_ERROR_FIRST_LOGIN")) , JSON_UNESCAPED_UNICODE);
				}
				else if($type == "dislike")
				{
					if(User::$login)
					{
						$found = false;

						if(!empty($dislikes_id))
							foreach ($dislikes_id as $key => $value)
								if(isset($value->user) && $value->user == User::$id)
								{
									$found = true;
									break;
								}

						if(!$found)
						{
							if(!empty($likes_id))
								foreach ($likes_id as $key => $value)
									if(isset($value->user) && $value->user == User::$id)
									{
										$likes_number--;
										unset($likes_id[$key]);
										break;
									}

							if(!is_array($dislikes_id))
								$dislikes_id = array();

							$dislikes_id[] = array("user" => User::$id , "datetime" => Site::$datetime);
							$dislikes_number++;

							$model->article_update($likes_number , $dislikes_number , $comments_number , $likes_id , $dislikes_id , $article[0]->id);
							echo json_encode(array("status" => true , "information" => array("likes" => number_format($likes_number) , "dislikes" => number_format($dislikes_number))) , JSON_UNESCAPED_UNICODE);
						}
						else
							echo json_encode(array("status" => false , "message" => Language::_("COM_CONTENT_ERROR_COMMENT")) , JSON_UNESCAPED_UNICODE);
					}
					else if(!$setting_likes_permission)
					{
						$likes_session = json_decode(htmlspecialchars_decode(Sessions::get_session('articles_like')) , true);
						$likes_cookie = json_decode(htmlspecialchars_decode(Cookies::get_cookie('articles_like')) , true);

						$dislikes_session = json_decode(htmlspecialchars_decode(Sessions::get_session('articles_dislike')) , true);
						$dislikes_cookie = json_decode(htmlspecialchars_decode(Cookies::get_cookie('articles_dislike')) , true);

						if(!isset($dislikes_session[$code]) && !isset($dislikes_cookie[$code]))
						{
							if(isset($likes_session[$code]) || isset($likes_cookie[$code]))
							{
								if(isset($likes_session[$code]))
									unset($likes_session[$code]);

								if(isset($likes_cookie[$code]))
									unset($likes_cookie[$code]);

								$likes_number--;
								Sessions::set_session("articles_like" , htmlspecialchars(json_encode($likes_session , JSON_UNESCAPED_UNICODE)));
								Cookies::set_cookie("articles_like" , htmlspecialchars(json_encode($likes_cookie , JSON_UNESCAPED_UNICODE)) , time() + 2592000);
							}

							if(!is_array($dislikes_session))
								$dislikes_session = array();
							$dislikes_session[$code] = array("user" => User::$id , "datetime" => Site::$datetime);

							if(!is_array($dislikes_cookie))
								$dislikes_cookie = array();
							$dislikes_cookie[$code] = array("user" => User::$id , "datetime" => Site::$datetime);

							$dislikes_number++;

							Sessions::set_session("articles_dislike" , htmlspecialchars(json_encode($dislikes_session , JSON_UNESCAPED_UNICODE)));
							Cookies::set_cookie("articles_dislike" , htmlspecialchars(json_encode($dislikes_cookie , JSON_UNESCAPED_UNICODE)) , time() + 2592000);
							$model->article_update($likes_number , $dislikes_number , $comments_number , $likes_id , $dislikes_id , $article[0]->id);
							echo json_encode(array("status" => true , "information" => array("likes" => number_format($likes_number) , "dislikes" => number_format($dislikes_number))) , JSON_UNESCAPED_UNICODE);
						}
						else
							echo json_encode(array("status" => false , "message" => Language::_("COM_CONTENT_ERROR_COMMENT")) , JSON_UNESCAPED_UNICODE);
					}
					else
						echo json_encode(array("status" => false , "message" => Language::_("COM_CONTENT_ERROR_FIRST_LOGIN")) , JSON_UNESCAPED_UNICODE);
				}
				else if($type == "comment")
				{
					$name_input = "";
					$email_input = "";
					$comment_input = "";
					$captcha_input = "";

					// Set Inputs
					$messages = explode("&" , urldecode($message));
					if(count($messages) > 3)
						foreach ($messages as $key => $value){
							$result = explode("=" , $value);

							if(isset($result[0]) && isset($result[1]))
							{
								if($key == 0)
									$name_input = $result[1];
								else if($key == 1)
									$email_input = $result[1];
								else if($key == 2)
									$comment_input = $result[1];
								else if($key == 3)
									$captcha_input = $result[1];
							}
						}

					// Check Captcha
					$captcha = Sessions::get_session("captcha");
					if($captcha == $captcha_input)
					{
						// Check Comment Empty
						$comment_input = urldecode($comment_input);

						if($comment_input != "")
						{
							// Check Comment
							if(Regex::cs($comment_input))
							{
								if(!Regex::cs($name_input , "text_with_space_utf8"))
									$name_input = "";

								if(!Regex::cs($email_input , "email"))
									$email_input = "";

								$escapers = array("\\" , "/" , "\"" , "\n" , "\r" , "\t" , "\x08" , "\x0c");
								$replacements = array("\\\\" , "\\/" , "\\\"" , "\\n" , "\\r" , "\\t" , "\\f" , "\\b");
								$comment_input = str_replace($escapers , $replacements , $comment_input);

								// Comment Parent
								$comment_parent = array();
								if(isset($_GET['parent']) && $_GET['parent'] != "" && Regex::cs($_GET['parent'] , "ansi"))
									$comment_parent = $model->get_comment($_GET['parent']);

								$parent = 0;
								if(!empty($comment_parent) && !$comment_parent[0]->parent)
									$parent = $comment_parent[0]->id;

								$model->comment_insert($setting_comments_confirmation , $article[0]->id , $parent , $name_input , $email_input , $comment_input);
								echo json_encode(array("status" => true , "reload" => true) , JSON_UNESCAPED_UNICODE);
							}
							else
								echo json_encode(array("status" => false , "message" => Language::_("COM_CONTENT_ERROR_COMMENT_INPUT_FORMAT")) , JSON_UNESCAPED_UNICODE);
						}
						else
							echo json_encode(array("status" => false , "message" => Language::_("COM_CONTENT_ERROR_COMMENT_INPUT")) , JSON_UNESCAPED_UNICODE);
					}
					else
						echo json_encode(array("status" => false , "message" => Language::_("COM_CONTENT_ERROR_CAPTCHA")) , JSON_UNESCAPED_UNICODE);

					Sessions::set_session("captcha" , Regex::random_string(30));
				}
				else if($type == "likecomment")
				{
					if(strlen($message) == 15 && Regex::cs($message , "ansi") && $comment = $model->get_comment($message))
					{
						$comment_code = $comment[0]->code;
						$comment_likes_number = $comment[0]->likes;
						$comment_dislikes_number = $comment[0]->dislikes;
						$comment_likes_id = json_decode(htmlspecialchars_decode($comment[0]->likes_id));
						$comment_dislikes_id = json_decode(htmlspecialchars_decode($comment[0]->dislikes_id));

						if(User::$login)
						{
							$found = false;

							if(!empty($comment_likes_id))
								foreach ($comment_likes_id as $key => $value)
									if(isset($value->user) && $value->user == User::$id)
									{
										$found = true;
										break;
									}

							if(!$found)
							{
								if(!empty($comment_dislikes_id))
									foreach ($comment_dislikes_id as $key => $value)
										if(isset($value->user) && $value->user == User::$id)
										{
											$comment_dislikes_number--;
											unset($comment_dislikes_id[$key]);
											break;
										}

								if(!is_array($comment_likes_id))
									$comment_likes_id = array();

								$comment_likes_id[] = array("user" => User::$id , "datetime" => Site::$datetime);
								$comment_likes_number++;

								$model->comment_update($comment_likes_number , $comment_dislikes_number , $comment_likes_id , $comment_dislikes_id , $comment[0]->id);
								echo json_encode(array("status" => true , "information" => array("likes" => number_format($comment_likes_number) , "dislikes" => number_format($comment_dislikes_number))) , JSON_UNESCAPED_UNICODE);
							}
							else
								echo json_encode(array("status" => false , "message" => Language::_("COM_CONTENT_ERROR_COMMENT")) , JSON_UNESCAPED_UNICODE);
						}
						else if(!$setting_likes_permission)
						{
							$comment_likes_session = json_decode(htmlspecialchars_decode(Sessions::get_session('comments_like')) , true);
							$comment_likes_cookie = json_decode(htmlspecialchars_decode(Cookies::get_cookie('comments_like')) , true);

							$comment_dislikes_session = json_decode(htmlspecialchars_decode(Sessions::get_session('comments_dislike')) , true);
							$comment_dislikes_cookie = json_decode(htmlspecialchars_decode(Cookies::get_cookie('comments_dislike')) , true);

							if(!isset($comment_likes_session[$comment_code]) && !isset($comment_likes_cookie[$comment_code]))
							{
								if(isset($comment_dislikes_session[$comment_code]) || isset($comment_dislikes_cookie[$comment_code]))
								{
									if(isset($comment_dislikes_session[$comment_code]))
										unset($comment_dislikes_session[$comment_code]);

									if(isset($comment_dislikes_cookie[$comment_code]))
										unset($comment_dislikes_cookie[$comment_code]);

									$comment_dislikes_number--;
									Sessions::set_session("comments_dislike" , htmlspecialchars(json_encode($comment_dislikes_session , JSON_UNESCAPED_UNICODE)));
									Cookies::set_cookie("comments_dislike" , htmlspecialchars(json_encode($comment_dislikes_session , JSON_UNESCAPED_UNICODE)) , time() + 2592000);
								}

								if(!is_array($comment_likes_session))
									$comment_likes_session = array();
								$comment_likes_session[$comment_code] = array("user" => User::$id , "datetime" => Site::$datetime);

								if(!is_array($comment_likes_cookie))
									$comment_likes_cookie = array();
								$comment_likes_cookie[$comment_code] = array("user" => User::$id , "datetime" => Site::$datetime);

								$comment_likes_number++;

								Sessions::set_session("comments_like" , htmlspecialchars(json_encode($comment_likes_session , JSON_UNESCAPED_UNICODE)));
								Cookies::set_cookie("comments_like" , htmlspecialchars(json_encode($comment_likes_cookie , JSON_UNESCAPED_UNICODE)) , time() + 2592000);

								$model->comment_update($comment_likes_number , $comment_dislikes_number , $comment_likes_id , $comment_dislikes_id , $comment[0]->id);
								echo json_encode(array("status" => true , "information" => array("likes" => number_format($comment_likes_number) , "dislikes" => number_format($comment_dislikes_number))) , JSON_UNESCAPED_UNICODE);
							}
							else
								echo json_encode(array("status" => false , "message" => Language::_("COM_CONTENT_ERROR_COMMENT")) , JSON_UNESCAPED_UNICODE);
						}
						else
							echo json_encode(array("status" => false , "message" => Language::_("COM_CONTENT_ERROR_FIRST_LOGIN")) , JSON_UNESCAPED_UNICODE);
					}
					else
						echo json_encode(array("status" => false , "message" => Language::_("COM_CONTENT_ERROR_COMMENT_FOUND")) , JSON_UNESCAPED_UNICODE);
				}
				else if($type == "dislikecomment")
				{
					if(strlen($message) == 15 && Regex::cs($message , "ansi") && $comment = $model->get_comment($message))
					{
						$comment_code = $comment[0]->code;
						$comment_likes_number = $comment[0]->likes;
						$comment_dislikes_number = $comment[0]->dislikes;
						$comment_likes_id = json_decode(htmlspecialchars_decode($comment[0]->likes_id));
						$comment_dislikes_id = json_decode(htmlspecialchars_decode($comment[0]->dislikes_id));

						if(User::$login)
						{
							$found = false;

							if(!empty($comment_dislikes_id))
								foreach ($comment_dislikes_id as $key => $value)
									if(isset($value->user) && $value->user == User::$id)
									{
										$found = true;
										break;
									}

							if(!$found)
							{
								if(!empty($comment_likes_id))
									foreach ($comment_likes_id as $key => $value)
										if(isset($value->user) && $value->user == User::$id)
										{
											$comment_likes_number--;
											unset($comment_likes_id[$key]);
											break;
										}

								if(!is_array($comment_dislikes_id))
									$comment_dislikes_id = array();

								$comment_dislikes_id[] = array("user" => User::$id , "datetime" => Site::$datetime);
								$comment_dislikes_number++;

								$model->comment_update($comment_likes_number , $comment_dislikes_number , $comment_likes_id , $comment_dislikes_id , $comment[0]->id);
								echo json_encode(array("status" => true , "information" => array("likes" => number_format($comment_likes_number) , "dislikes" => number_format($comment_dislikes_number))) , JSON_UNESCAPED_UNICODE);
							}
							else
								echo json_encode(array("status" => false , "message" => Language::_("COM_CONTENT_ERROR_COMMENT")) , JSON_UNESCAPED_UNICODE);
						}
						else if(!$setting_likes_permission)
						{
							$comment_likes_session = json_decode(htmlspecialchars_decode(Sessions::get_session('comments_like')) , true);
							$comment_likes_cookie = json_decode(htmlspecialchars_decode(Cookies::get_cookie('comments_like')) , true);

							$comment_dislikes_session = json_decode(htmlspecialchars_decode(Sessions::get_session('comments_dislike')) , true);
							$comment_dislikes_cookie = json_decode(htmlspecialchars_decode(Cookies::get_cookie('comments_dislike')) , true);

							if(!isset($comment_dislikes_session[$comment_code]) && !isset($comment_dislikes_cookie[$comment_code]))
							{
								if(isset($comment_likes_session[$comment_code]) || isset($comment_likes_cookie[$comment_code]))
								{
									if(isset($comment_likes_session[$comment_code]))
										unset($comment_likes_session[$comment_code]);

									if(isset($comment_likes_cookie[$comment_code]))
										unset($comment_likes_cookie[$comment_code]);

									$comment_likes_number--;
									Sessions::set_session("comments_like" , htmlspecialchars(json_encode($comment_likes_session , JSON_UNESCAPED_UNICODE)));
									Cookies::set_cookie("comments_like" , htmlspecialchars(json_encode($comment_likes_cookie , JSON_UNESCAPED_UNICODE)) , time() + 2592000);
								}

								if(!is_array($comment_dislikes_session))
									$comment_dislikes_session = array();
								$comment_dislikes_session[$comment_code] = array("user" => User::$id , "datetime" => Site::$datetime);

								if(!is_array($comment_dislikes_cookie))
									$comment_dislikes_cookie = array();
								$comment_dislikes_cookie[$comment_code] = array("user" => User::$id , "datetime" => Site::$datetime);

								$comment_dislikes_number++;

								Sessions::set_session("comments_dislike" , htmlspecialchars(json_encode($comment_dislikes_session , JSON_UNESCAPED_UNICODE)));
								Cookies::set_cookie("comments_dislike" , htmlspecialchars(json_encode($comment_dislikes_cookie , JSON_UNESCAPED_UNICODE)) , time() + 2592000);
								$model->comment_update($comment_likes_number , $comment_dislikes_number , $comment_likes_id , $comment_dislikes_id , $comment[0]->id);
								echo json_encode(array("status" => true , "information" => array("likes" => number_format($comment_likes_number) , "dislikes" => number_format($comment_dislikes_number))) , JSON_UNESCAPED_UNICODE);
							}
							else
								echo json_encode(array("status" => false , "message" => Language::_("COM_CONTENT_ERROR_COMMENT")) , JSON_UNESCAPED_UNICODE);
						}
						else
							echo json_encode(array("status" => false , "message" => Language::_("COM_CONTENT_ERROR_FIRST_LOGIN")) , JSON_UNESCAPED_UNICODE);
					}
					else
						echo json_encode(array("status" => false , "message" => Language::_("COM_CONTENT_ERROR_COMMENT_FOUND")) , JSON_UNESCAPED_UNICODE);
				}
			}
		}
	}

	public function view($view)
	{
		$params = array();

		if(is_array($view) && !empty($view))
		{
			if(str_replace("-" , " " , $view[0]) == Language::_('COM_CONTENT_ARTICLE'))
			{
				if(isset($view[1]) && Regex::cs($view[1] , "text_utf8"))
					$_GET['id'] = $view[1];

				Controller::$view = $view = 'article';
			}
			else if(str_replace("-" , " " , $view[0]) == Language::_('COM_CONTENT_CATEGORY'))
			{
				if(isset($view[1]) && Regex::cs($view[1] , "text_utf8"))
					$_GET['id'] = $view[1];

				Controller::$view = $view = 'category';
			}
		}

		if($view == 'article') 
		{
			if(isset($_GET['id']) && Regex::cs($_GET['id'] , "text"))
			{
				$model = self::model('article');
				$article = $model->get_article($_GET['id']);

				if($article) 
				{
					if (!$article[0]->status) 
					{
						// set kardane menue ke category in article ra darad
						$model->set_category_menu($article[0]->category);
						$model->inc_views($article[0]->id , $article[0]->views);

						$params['article'] = $article[0];
						$params['comments'] = $model->get_comments($article[0]->id);

						View::read(json_encode($params) , JSON_UNESCAPED_UNICODE);
					}
					else
						Messages::add_message('error' , Language::_('ERROR_NOT_EXIST_ARTICLE'));
				}
				else
					Messages::add_message('error' , Language::_('ERROR_NOT_EXIST_ARTICLE'));
			}
			else
				Messages::add_message('error' , Language::_('ERROR_NOT_EXIST_ARTICLE'));
		}
		else if($view == 'category')
		{
			if(isset($_GET['id']) && Regex::cs($_GET['id'] , "text"))
			{
				$model = self::model('category');
				$category = $model->get_category($_GET['id']);

				if($category) 
				{
					$params = $model->get_articles($category[0]->id , $category[0]->setting);
					$params['category'] = $category[0];
					$params = json_encode($params , JSON_UNESCAPED_UNICODE);
					View::read($params);
				}
				else
					Messages::add_message('error' , Language::_('ERROR_NOT_EXIST_CATEGORY'));
			}
			else
				Messages::add_message('error' , Language::_('ERROR_NOT_EXIST_CATEGORY'));
		}
	}
}