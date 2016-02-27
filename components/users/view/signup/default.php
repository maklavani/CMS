<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	06/15/2015
	*	last edit		07/28/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

if(!User::$login)
{
	require_once _INC . 'output/form.php';

	$form = New Form;
	$form->name = 'signup';
	$form->action = Site::$full_link;
	$form->method = 'post';

	$form->add_input('text' , Language::_('COM_USERS_SIGNUP_FORM_INPUT_1') , '' , array('placeholder' => Language::_('COM_USERS_SIGNUP_FORM_INPUT_1') , 'required' => 'required' , 'min' => '2' , 'max' => '254'));
	$form->add_input('text' , Language::_('COM_USERS_SIGNUP_FORM_INPUT_2') , '' , array('placeholder' => Language::_('COM_USERS_SIGNUP_FORM_INPUT_2') , 'required' => 'required' , 'min' => '2' , 'max' => '254'));
	$form->add_input('text' , Language::_('COM_USERS_SIGNUP_FORM_INPUT_3') , '' , array('placeholder' => Language::_('COM_USERS_SIGNUP_FORM_INPUT_3') , 'required' => 'required' , 'min' => '5' , 'max' => '254'));
	$form->add_input('text' , Language::_('COM_USERS_SIGNUP_FORM_INPUT_4') , '' , array('placeholder' => Language::_('COM_USERS_SIGNUP_FORM_INPUT_4') , 'required' => 'required'));
	$form->add_input('password' , Language::_('COM_USERS_SIGNUP_FORM_INPUT_5') , '' , array('placeholder' => Language::_('COM_USERS_SIGNUP_FORM_INPUT_5') , 'required' => 'required' , 'min' => '6' , 'max' => '32'));
	$form->add_input('password' , Language::_('COM_USERS_SIGNUP_FORM_INPUT_6') , '' , array('placeholder' => Language::_('COM_USERS_SIGNUP_FORM_INPUT_6') , 'required' => 'required' , 'min' => '6' , 'max' => '32'));
	$form->add_input('captcha' , Language::_('COM_USERS_SIGNUP_FORM_INPUT_7') , '' , array('placeholder' => Language::_('COM_USERS_SIGNUP_FORM_INPUT_7') , 'required' => 'required'));

	$form->add_input('button' , '' , Language::_('COM_USERS_SIGNUP'));

	$form->output();
}
else
{
	Messages::add_message('warning' , Language::_('COM_USERS_FIRST_SIGNOUT'));
}