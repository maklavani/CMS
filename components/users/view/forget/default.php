<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	06/15/2015
	*	last edit		06/15/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

require_once _INC . 'output/form.php';

$form = New Form;
$form->name = 'forget';
$form->action = Site::$full_link;
$form->method = 'post';

$form->add_input('text' , Language::_('COM_USERS_FORGET_FORM_INPUT_1') , '' , array('placeholder' => Language::_('COM_USERS_FORGET_FORM_INPUT_1') , 'required' => 'required' , 'min' => '2' , 'max' => '254'));
$form->add_input('text' , Language::_('COM_USERS_FORGET_FORM_INPUT_2') , '' , array('placeholder' => Language::_('COM_USERS_FORGET_FORM_INPUT_2') , 'required' => 'required' , 'min' => '2' , 'max' => '254'));

$form->add_input('button' , '' , Language::_('COM_USERS_SEND_PASSWORD'));

$form->output();