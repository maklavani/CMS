<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	01/20/2016
	*	last edit		02/18/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

if(!$setting_author || !$setting_publish_date || !$setting_views || !$setting_tags || !$setting_likes):
?>
<div class="article-information xa">
	<?php if(!$setting_author || !$setting_publish_date || !$setting_views || !$setting_likes): ?>
	<div class="article-information-in xa">
		<?php if(!$setting_publish_date): ?>
		<div class="article-publish article-section x5 s3">
			<small class="icon-advertisement xa"></small>
			<span class="xa">
			<?php
				require_once _INC . 'system/calendar.php';

				if(Language::$lang == "fa-ir")
					$calendar = new Calendar('shamsi');
				else
					$calendar = new Calendar();

				echo $calendar->convert($article->publish_date , 'H:i - Y/m/d');
			?>
			</span>
		</div>
		<?php endif; ?>

		<?php if(!$setting_author): ?>
		<div class="article-author article-section x5 s3">
			<small class="icon-user xa"></small>
			<span class="xa">
			<?php
				$db->table('users')->select(array("name" , "family"))->where("`id` = " . $article->user)->process();
				$user = $db->output();

				if(empty(!$user))
					echo $user[0]->name . " " . $user[0]->family;
			?>
			</span>
		</div>
		<?php endif; ?>

		<?php if(!$setting_views): ?>
		<div class="article-views article-section x5 s15"><small class="icon-eye"></small><span><?php echo number_format($article->views); ?></span></div>
		<?php endif; ?>

		<?php if(!$setting_likes): ?>
		<div class="article-likes article-section x5 s25<?php echo !$setting_likes_permission || User::$login ? ' access' : ''; ?>">
			<p class="x5">
				<small class="icon-like xa"></small>
				<span class="xa"><?php echo number_format($article->likes); ?></span>
			</p>

			<p class="x5">
				<small class="icon-dislike xa"></small>
				<span class="xa"><?php echo number_format($article->dislikes); ?></span>
			</p>
		</div>
		<?php endif; ?>
	</div>
	<?php endif; ?>

	<?php if(!$setting_tags): ?>
	<div class="article-tags xa">
		<?php
			foreach (json_decode(htmlspecialchars_decode($article->tags)) as $value)
				echo "<a class=\"xa\" href=\"" . Site::$base . Language::_('COM_CONTENT_SEARCH') . "/?tags=" . str_replace(" " , "-" , $value) . "\">" . $value . "</a>";
		?>
	</div>
	<?php endif; ?>

	<?php if(!$setting_comments): ?>
		<?php require_once _COMP . 'content/view/article/comments.php'; ?>
	<?php endif; ?>

	<?php if(!$setting_comments_permission || User::$login): ?>
		<?php require_once _COMP . 'content/view/article/comment_form.php'; ?>
	<?php endif; ?>
</div>
<?php endif; ?>