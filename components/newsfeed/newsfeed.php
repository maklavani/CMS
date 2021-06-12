<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	07/28/2015
	*	last edit		07/29/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class NewsfeedController extends Controller {
	// khandane view
	public function view($view , $action)
	{
		$params = '';

		if(is_array($view) && !empty($view))
			if(Regex::cs($view[0] , "text_utf8"))
			{
				$_GET['name'] = $view[0];
				Controller::$view = $view = 'newsfeed';
			}

		if($view == 'newsfeed' && isset($_GET['name']) && Regex::cs($_GET['name'] , "text_utf8"))
		{
			$model = self::model('newsfeed');

			if($newsfeed = $model->has_newsfeed(str_replace("-" , " " , $_GET['name'])))
			{
				if(!$newsfeed[0]->status)
				{
					$params = array();
					$params['newsfeed'] = $newsfeed[0];
					$params['articles'] = $model->get_articles($newsfeed[0]);
					$params = json_encode($params , JSON_UNESCAPED_UNICODE);
					Controller::$view = $view = 'newsfeed';
					View::read($params);
				}
				else
				{
					Preload::$error = true;
					Messages::add_message('error' , Language::_('ERROR_INVALID_LINK'));
				}
			}
			else
			{
				Preload::$error = true;
				Messages::add_message('error' , Language::_('ERROR_INVALID_LINK'));
			}
		}
		else
		{
			Preload::$error = true;
			Messages::add_message('error' , Language::_('ERROR_INVALID_LINK'));
		}
	}
}