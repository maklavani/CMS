<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	06/28/2015
	*	last edit		12/03/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

Templates::add_css(Site::$base . _ADM . 'widgets/users/css/template.css');
Templates::add_js(Site::$base . _ADM . 'widgets/users/js/template.js');
?>
<div class="users">
	<div class="users-icon">
		<?php
			$db = new Database;
			$db->table('users')->where('`id` = ' . User::$id)->select()->process();
			$output = $db->output();
			$image = json_decode(html_entity_decode($output[0]->image));

			if($image[0] != "")
				echo "<div class=\"xa\" style=\"background-image: url(" . Site::$base . (str_replace(basename($image[0]) , "thumbnail_" . basename($image[0]) , $image[0])) . ")\"></div>";
			else
				echo "<div class=\"icon-user\"></div>";
		?>
	</div>
	<ul class="users-list">
		<li><a href="<?php echo Site::$base . _ADM . 'index.php?component=signin&amp;view=signout'; ?>"><span class="icon-signout"></span><div><?php echo Language::_('WID_USERS_SIGNOUT'); ?></div></a></li>
	</ul>
</div>