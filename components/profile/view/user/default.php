<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	08/24/2015
	*	last edit		01/31/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

require_once _INC . 'output/fields.php';

Templates::add_css(Site::$base . 'components/profile/css/user.css');
Templates::add_js(Site::$base . 'components/profile/js/user.js');

$fields = New Fields;
$fields->name = 'COM_PROFILE_USER_FIELD_INPUT';
$fields->action = Site::$full_link_text;
$fields->method = 'post';
$pages = $user =  array();

$profile_default = json_decode(htmlspecialchars_decode($params[0]->profile));

$user = array(
				0 => array('type' => 'text' , 'name' => 'name' , 'default' => $params[0]->name),
				1 => array('type' => 'text' , 'name' => 'family' , 'default' => $params[0]->family),
				2 => array('type' => 'text' , 'name' => 'username' , 'default' => $params[0]->username),
				3 => array('type' => 'password' , 'name' => 'password'),
				4 => array('type' => 'password' , 'name' => 'repassword'),
				5 => array('type' => 'text' , 'name' => 'mobile' , 'default' => $params[0]->mobile),
				6 => array('type' => 'text' , 'name' => 'tel' , 'default' => $profile_default->tel , 'children' => array('warning' => true)),
				7 => array('type' => 'textarea' , 'name' => 'address' , 'default' => $profile_default->address),
				8 => array('type' => 'textarea' , 'name' => 'favorites' , 'default' => $profile_default->favorites),
				9 => array('type' => 'hidden' , 'name' => 'code' , 'default' => $params[0]->code)
			  );

$pages['user'] = $user;
$fields->pages = $pages;

$image = json_decode(htmlspecialchars_decode($params[0]->image));
if(!empty($image[0]))
	$image_out = $image[0];
else
	$image_out = "components/profile/images/user.png";
?>
<div class="user xa">
	<div class="user-image xa s3 m4 l25">
		<div class="progress xa"><div class="progress-in xa"></div></div>

		<div class="image xa" style="background-image: url(<?php echo Site::$base . $image_out; ?>)"></div>

		<form class="xa files" enctype="multipart/form-data" action="<?php echo Site::$base . 'index.php?component=profile&ajax=profile'; ?>" save="<?php echo Language::_('SAVE'); ?>" cancel="<?php echo Language::_('CANCEL'); ?>" window="<?php echo Site::$full_link; ?>">
			<input class="file-input xa" name="file" type="file">
			<div class="xa"><?php echo Language::_('COM_PROFILE_EDIT'); ?></div>
		</form>
	</div>

	<div class="user-profile xa s67 m57 l72 es025">
		<?php echo $fields->output(); ?>
	</div>
</div>