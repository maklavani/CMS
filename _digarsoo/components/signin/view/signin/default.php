<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	06/15/2015
	*	last edit		06/29/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

$banned = Site::has_banned();

if(!$banned){
	if(!User::$login)
	{
		require_once _INC . 'output/form.php';

		$form = New Form;
		$form->name = 'signin';
		$form->action = Site::$full_link_text;
		$form->method = 'post';

		$form->add_input('text' , Language::_('COM_SIGNIN_SIGNIN_FORM_INPUT_1') , '' , array('placeholder' => Language::_('COM_SIGNIN_EMAIL_USERNAME') , 'required' , 'min' => '5' , 'max' => '254'));
		$form->add_input('password' , Language::_('COM_SIGNIN_SIGNIN_FORM_INPUT_2') , '' , array('placeholder' => Language::_('COM_SIGNIN_SIGNIN_FORM_INPUT_2') , 'required' , 'min' => '6' , 'max' => '32'));
		$form->add_input('captcha' , Language::_('COM_SIGNIN_SIGNIN_FORM_INPUT_4') , '' , array('placeholder' => Language::_('COM_SIGNIN_SIGNIN_FORM_INPUT_4')));
		$form->add_input('checkbox' , Language::_('COM_SIGNIN_SIGNIN_FORM_INPUT_3') , '' , array('placeholder' => Language::_('COM_SIGNIN_SIGNIN_FORM_INPUT_3')));
		$form->add_input('button' , '<div class="icon-lock"></div>' , Language::_('COM_SIGNIN_CHECK') , array('class' => 'x3 s25 m3 l25'));

		$form->output();
	}
	else
	{
		Messages::add_message('success' , sprintf(Language::_('COM_SIGNIN_WELCOME') , User::$name));
	}
}
else
{
	if($banned > 10)
		$time = 3600 - Site::$banned_time;
	else
		$time = 900 - Site::$banned_time;

	Messages::add_message('error' , sprintf(Language::_('COM_SIGNIN_ERROR_BANNED_IP') , (int)($time / 60) . ":" . ($time % 60)));
}