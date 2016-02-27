<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	02/15/2016
	*	last edit		02/15/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class CommentsView extends View {
	public function base($params)
	{
		if(Controller::$action == 'default')
		{
			Templates::$title = Language::_('COM_CONTENT_COMMENTS');
			self::set_toolbar(array('title' => Templates::$title , 'buttons' => array('unblock' , 'block' , 'delete' , 'edit' , 'new')));
			self::set_search(array('item' => array('name' , 'comment') , 'sort' => array('status' , 'publish_date' , 'id')));
		}
		else if(Controller::$action == 'new')
		{
			Templates::$title = Language::_('COM_CONTENT_COMMENTS') . ' - ' . Language::_('NEW');
			self::set_toolbar(array('title' => Templates::$title , 'buttons' => array('close' , 'saveclose' , 'savenew' , 'save')));
		}
		else if(Controller::$action == 'edit')
		{
			Templates::$title = Language::_('COM_CONTENT_COMMENTS') . ' - ' . Language::_('EDIT');
			self::set_toolbar(array('title' => Templates::$title , 'buttons' => array('close' , 'saveclose' , 'save')));
		}
		else if(Controller::$action == 'select_article')
		{
			Templates::$title = Language::_('COM_CONTENT_COMMENTS') . ' - ' . Language::_('SELECT');
			self::set_toolbar(array('title' => Templates::$title , 'buttons' => array('close' , 'save')));
		}

		$buttons = array(
					array('name' => Language::_('COM_CONTENT_ARTICLE') , 'link' => 'index.php?component=content&amp;view=article') ,
					array('name' => Language::_('COM_CONTENT_CATEGORY') , 'link' => 'index.php?component=content&amp;view=category') ,
					array('name' => Language::_('COM_CONTENT_UPLOAD') , 'link' => 'index.php?component=content&amp;view=upload') , 
					array('name' => Language::_('COM_CONTENT_TAGS') , 'link' => 'index.php?component=content&amp;view=tags') , 
					array('name' => Language::_('COM_CONTENT_COMMENTS') , 'link' => 'index.php?component=content&amp;view=comments')
					);

		self::set_buttons($buttons , 4);
		self::read_action($params);
	}
}