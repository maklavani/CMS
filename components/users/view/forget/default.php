<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	06/15/2015
	*	last edit		10/03/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

if(!User::$login)
{
	require_once _INC . 'output/form.php';

	$form = New Form;
	$form->name = 'forget';
	$form->action = Site::$full_link;
	$form->method = 'post';

	$form->add_input('text' , Language::_('COM_USERS_FORGET_FORM_INPUT_1') , '' , array('placeholder' => Language::_('COM_USERS_FORGET_FORM_INPUT_1') , 'required' => 'required' , 'min' => '2' , 'max' => '254'));
	$form->add_input('captcha' , Language::_('COM_USERS_FORGET_FORM_INPUT_2') , '' , array('placeholder' => Language::_('COM_USERS_FORGET_FORM_INPUT_2') , 'required' => 'required'));

	$form->add_input('button' , '' , Language::_('COM_USERS_SEND_EMAIL'));
?>
<div class="user-form x9 s7 m5 l3 ex05 es0 aex05 aes3 aem5 ael7">
	<h2 class="xa"><?php echo Language::_("COM_USERS_FORGET"); ?></h2>
	<?php echo $form->output(); ?>
</div>
<?php
}
else
{
	Messages::add_message('warning' , Language::_('COM_USERS_FIRST_SIGNOUT_FORGET'));
	Site::goto_link(Site::$base . Language::_('COM_USERS_PROFILE') . "/" . str_replace(" " , "-" , Language::_('COM_USERS_PROFILE_USER')));
}