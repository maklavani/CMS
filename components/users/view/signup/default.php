<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	06/15/2015
	*	last edit		10/28/2016
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
	$form->add_input('text' , Language::_('COM_USERS_SIGNUP_FORM_INPUT_4') , '' , array('placeholder' => Language::_('COM_USERS_SIGNUP_FORM_INPUT_4') , 'max' => '11'));
	$form->add_input('text' , Language::_('COM_USERS_SIGNUP_FORM_INPUT_5') , '' , array('placeholder' => Language::_('COM_USERS_SIGNUP_FORM_INPUT_5') , 'required' => 'required'));
	$form->add_input('password' , Language::_('COM_USERS_SIGNUP_FORM_INPUT_6') , '' , array('placeholder' => Language::_('COM_USERS_SIGNUP_FORM_INPUT_6') , 'required' => 'required' , 'min' => '6' , 'max' => '32'));
	$form->add_input('password' , Language::_('COM_USERS_SIGNUP_FORM_INPUT_7') , '' , array('placeholder' => Language::_('COM_USERS_SIGNUP_FORM_INPUT_7') , 'required' => 'required' , 'min' => '6' , 'max' => '32'));
	$form->add_input('captcha' , Language::_('COM_USERS_SIGNUP_FORM_INPUT_8') , '' , array('placeholder' => Language::_('COM_USERS_SIGNUP_FORM_INPUT_8') , 'required' => 'required'));

	$form->add_input('button' , '' , Language::_('COM_USERS_SIGNUP'));
?>
<div class="user-form x9 s7 m5 l3 ex05 es0 aex05 aes3 aem5 ael7">
	<h2 class="xa"><?php echo Language::_("COM_USERS_SIGNUP"); ?></h2>
	<?php echo $form->output(); ?>
</div>
<?php
}
else
{
	Messages::add_message('warning' , Language::_('COM_USERS_FIRST_SIGNOUT'));
}