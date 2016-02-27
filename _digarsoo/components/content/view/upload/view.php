<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	06/15/2015
	*	last edit		07/10/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class UploadView extends View {
	public function base($params)
	{
		// set kardane title
		Templates::$title = Language::_('COM_CONTENT_UPLOAD');

		// $buttons = array(
		// 			array('name' => Language::_('COM_CONTENT_ARTICLE') , 'link' => 'index.php?component=content&amp;view=article') ,
		// 			array('name' => Language::_('COM_CONTENT_CATEGORY') , 'link' => 'index.php?component=content&amp;view=category') ,
		// 			array('name' => Language::_('COM_CONTENT_UPLOAD') , 'link' => 'index.php?component=content&amp;view=upload')
		// 			);

		// self::set_buttons($buttons , 2);
		self::set_toolbar(array('title' => Templates::$title));
		self::read_action($params);
	}
}