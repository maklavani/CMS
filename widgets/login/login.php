<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/19/2015
	*	last edit		08/29/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

Templates::add_css(Site::base() . 'widgets/login/css/template.css');
?>
<div class="login xa">
	<div class="login-in xa">
<?php if(User::$login): ?>
		<div class="login-text xa">
			<small><?php echo Language::_('WID_LOGIN_HELLO'); ?></small>
			<?php echo mb_substr(User::$name , 0 , 20) . (mb_strlen(User::$name) > 20 ? " ..." : ""); ?>
		</div>

		<input type="checkbox" class="login-checkbox xa">

		<div class="login-icon xa after-float">
			<span class="icon-cogs xa"></span>
		</div>

		<ul class="login-list xa">
			<li class="xa">
				<a href="index.php?component=profile&amp;view=user" class="xa">
					<span class="icon-user"></span>
					<?php echo Language::_('WID_LOGIN_USER'); ?>
				</a>
			</li>

			<li class="xa">
				<a href="index.php?component=users&amp;view=signout" class="xa">
					<span class="icon-signout"></span>
					<?php echo Language::_('WID_LOGIN_LOGOUT'); ?>
				</a>
			</li>
		</ul>
<?php else: ?>
		<div class="login-buttons xa">
			<a class="login-button xa after-float" href="index.php?component=users&amp;view=signin">
				<span class="icon-users"></span>
				<?php echo Language::_('WID_LOGIN_SIGNIN'); ?>
			</a>

			<a class="login-button xa after-float" href="index.php?component=users&amp;view=signup">
				<span class="icon-plus"></span>
				<?php echo Language::_('WID_LOGIN_SIGNUP'); ?>
			</a>
		</div>
<?php endif; ?>
	</div>
</div>