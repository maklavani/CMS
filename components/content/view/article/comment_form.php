<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	02/13/2016
	*	last edit		02/18/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

require_once _INC . 'output/form.php';

$form = New Form;
$form->name = 'form';
$form->action = "index.php?component=content&amp;ajax=" . $article->code;
$form->method = 'post';

$form->add_input("text" , Language::_("COM_CONTENT_NAME") , '' , array('placeholder' => Language::_("COM_CONTENT_NAME")));
$form->add_input("text" , Language::_("COM_CONTENT_EMAIL") , '' , array('placeholder' => Language::_("COM_CONTENT_EMAIL")));
$form->add_input("textarea" , Language::_("COM_CONTENT_COMMENT") , '' , array('placeholder' => Language::_("COM_CONTENT_COMMENT")));
$form->add_input("captcha" , Language::_("COM_CONTENT_CAPTCHA") , '');
$form->add_input('button' , Language::_('COM_CONTENT_SEND') , Language::_('COM_CONTENT_SEND'));
?>
<div class="article-comment-form xa">
	<h3><?php echo Language::_("COM_CONTENT_SEND_COMMENT"); ?></h3>
	<?php $form->output(); ?>
</div>