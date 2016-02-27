<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	06/15/2015
	*	last edit		01/05/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class ComponentView extends View {
	public function base($params)
	{
		$paramsb = json_decode($params);

		// set kardane title
		require_once _SRC . 'components/setting/parameters/setting.php';
		$params_setting = New ParametersSetting;

		Templates::$title = Language::_('SETTING') . ' ' . Language::_('COM_' . strtoupper($paramsb->name));

		self::set_buttons($params_setting->buttons , $_GET['id']);
		self::set_toolbar(array('title' => Templates::$title , 'buttons' => array('save')));
		self::read_action($params);
	}
}