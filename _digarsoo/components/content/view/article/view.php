<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	06/29/2015
	*	last edit		01/16/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class ArticleView extends View {
	public function base($params)
	{
		if(Controller::$action == 'default')
		{
			Templates::$title = Language::_('COM_CONTENT_ARTICLE');
			self::set_toolbar(array('title' => Templates::$title , 'buttons' => array('icon-star-empty' => 'unspecial' , 'icon-star' => 'special' , 'unblock' , 'block' , 'delete' , 'edit' , 'new')));
			self::set_search(array('item' => array('title' , 'views') , 'sort' => array('title' , 'status' , 'category' , 'publish_date' , 'views' , 'id')));
		}
		else if(Controller::$action == 'new')
		{
			Templates::$title = Language::_('COM_CONTENT_ARTICLE') . ' - ' . Language::_('NEW');
			self::set_toolbar(array('title' => Templates::$title , 'buttons' => array('close' , 'saveclose' , 'savenew' , 'save')));
		}
		else if(Controller::$action == 'edit')
		{
			Templates::$title = Language::_('COM_CONTENT_ARTICLE') . ' - ' . Language::_('EDIT');
			self::set_toolbar(array('title' => Templates::$title , 'buttons' => array('close' , 'saveclose' , 'save')));
		}

		$buttons = array(
					array('name' => Language::_('COM_CONTENT_ARTICLE') , 'link' => 'index.php?component=content&amp;view=article') ,
					array('name' => Language::_('COM_CONTENT_CATEGORY') , 'link' => 'index.php?component=content&amp;view=category') ,
					array('name' => Language::_('COM_CONTENT_UPLOAD') , 'link' => 'index.php?component=content&amp;view=upload') , 
					array('name' => Language::_('COM_CONTENT_TAGS') , 'link' => 'index.php?component=content&amp;view=tags') , 
					array('name' => Language::_('COM_CONTENT_COMMENTS') , 'link' => 'index.php?component=content&amp;view=comments')
					);

		self::set_buttons($buttons , 0);
		self::read_action($params);
	}
}