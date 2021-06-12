<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	05/17/2017
	*	last edit		05/17/2017
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

if(User::$id != -1):
	require_once _INC . 'output/form.php';

	$form = New Form;
	$form->name = 'authentication';
	$form->action = Site::$full_link;
	$form->method = 'post';

	$form->add_input('text' , Language::_('COM_USERS_AUTHENTICATION_FORM_INPUT_1') , '' , array('placeholder' => Language::_('COM_USERS_AUTHENTICATION_FORM_INPUT_1') , 'required'));
	$form->add_input('captcha' , Language::_('COM_USERS_AUTHENTICATION_FORM_INPUT_2') , '' , array('placeholder' => Language::_('COM_USERS_AUTHENTICATION_FORM_INPUT_2') , 'required' => 'required'));
	$form->add_input('button' , '' , Language::_('COM_USERS_AUTHENTICATION'));
?>
<div class="user-form x9 s7 m5 l3 ex05 es0 aex05 aes3 aem5 ael7">
	<h2 class="xa"><?php echo Language::_("COM_USERS_AUTHENTICATION"); ?></h2>
	<?php echo $form->output(); ?>
</div>
<?php endif;