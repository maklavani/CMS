<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/28/2015
	*	last edit		09/28/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

$db = new Database();
$identification = $params;
$expire = strtotime($identification->expire);
$now = strtotime(Site::$datetime);

if($expire - $now > 0)
{
	if($identification->section == "signup")
	{
		$db = new Database;
		$db->table('users')->where('`id` = ' . $identification->val)->update(array(array('status' , 0)))->process();
		Messages::add_message('success' , Language::_('COM_USERS_SIGNUP_COMPELETE'));
		Site::goto_link(Site::$base);

		$db->table('identification')->where('`id` = ' . $identification->id)->delete()->process();
	}
	else if($identification->section == "forget")
	{
		Templates::$title = Language::_("COM_USERS_FORGET");
		require_once _INC . 'output/form.php';

		$form = New Form;
		$form->name = 'identification';
		$form->action = Site::$full_link;
		$form->method = 'post';

		$form->add_input('password' , Language::_('COM_USERS_IDENTIFICATION_FORM_INPUT_1') , '' , array('placeholder' => Language::_('COM_USERS_IDENTIFICATION_FORM_INPUT_1') , 'required' => 'required' , 'min' => '2' , 'max' => '254'));
		$form->add_input('password' , Language::_('COM_USERS_IDENTIFICATION_FORM_INPUT_2') , '' , array('placeholder' => Language::_('COM_USERS_IDENTIFICATION_FORM_INPUT_2') , 'required' => 'required' , 'min' => '2' , 'max' => '254'));
		$form->add_input('captcha' , Language::_('COM_USERS_IDENTIFICATION_FORM_INPUT_3') , '' , array('placeholder' => Language::_('COM_USERS_IDENTIFICATION_FORM_INPUT_3') , 'required' => 'required'));

		$form->add_input('hidden' , '' , 'forget');
		$form->add_input('button' , '' , Language::_('COM_USERS_SAVE_PASSWORD'));
?>
	<div class="user-form x9 s7 m5 l3 ex05 es0 aex05 aes3 aem5 ael7">
		<h2 class="xa"><?php echo Language::_("COM_USERS_FORGET"); ?></h2>
		<?php echo $form->output(); ?>
	</div>
<?php
	}
}
else
{
	Messages::add_message('error' , Language::_('ERROR_EXPIRE_IDENTIFICATION'));
	$db->table('identification')->where('`id` = ' . $identification->id)->delete()->process();
	Site::goto_link(Site::$base);
}