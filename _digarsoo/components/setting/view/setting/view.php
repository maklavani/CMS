<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	06/15/2015
	*	last edit		07/27/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class SettingView extends View {
	public function base($params)
	{
		// set kardane title
		Templates::$title = Language::_('COM_SETTING_ALL');
		require_once _SRC . 'components/setting/parameters/setting.php';
		$params_setting = New ParametersSetting;

		self::set_buttons($params_setting->buttons , 0);
		self::set_toolbar(array('title' => Templates::$title , 'buttons' => array('save')));
		self::read_action($params);
	}
}