<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	02/18/2016
	*	last edit		02/20/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

require_once _INC . 'system/calendar.php';

if(Language::$lang == "fa-ir")
	$calendar = new Calendar('shamsi');
else
	$calendar = new Calendar();

if(!empty($comments))
{
	?>
	<div class="article-status xa">
		<p class="x25 aex25">
			<small class="x5"><?php echo Language::_('COM_CONTENT_COMMMENTS'); ?></small>
			<span class="x5"><?php echo number_format($article->comments); ?></span>
		</p>

		<p class="x25">
			<small class="x5"><?php echo Language::_('COM_CONTENT_COMMMENTS_VERIFY'); ?></small>
			<span class="x5"><?php echo number_format($article->comments_verify); ?></span>
		</p>

		<p class="x25">
			<small class="x5"><?php echo Language::_('COM_CONTENT_COMMMENTS_DELETED'); ?></small>
			<span class="x5"><?php echo number_format($article->comments_deleted); ?></span>
		</p>
	</div>

	<div class="comments xa">
		<?php comments_print($comments , $calendar , $setting_likes_permission , $setting_comments_permission); ?>
	</div>
	<?php
}

function comments_print($comments , $calendar , $setting_likes_permission , $setting_comments_permission , $parent = 0 , $level = 0)
{
	foreach ($comments as $value)
		if($value->parent == $parent && !$value->status)
		{
			?>
			<div class="comment xa">
				<div class="comment-in xa" code="<?php echo $value->code; ?>">
					<div class="comment-toolbar xa">
						<span><?php echo $value->name != "" ? $value->name : Language::_('COM_CONTENT_UNKNOWN'); ?></span>
						<span><?php echo $calendar->convert($value->publish_date , 'l d M Y H:i:s'); ?></span>
						<span class="comment-likes x3 s25 m3 l25<?php echo !$setting_likes_permission || User::$login ? ' access' : ''; ?>">
							<p class="x5">
								<small class="icon-like xa"></small>
								<span class="xa"><?php echo number_format($value->likes); ?></span>
							</p>

							<p class="x5">
								<small class="icon-dislike xa"></small>
								<span class="xa"><?php echo number_format($value->dislikes); ?></span>
							</p>
						</span>
						<?php if(!$value->parent && (!$setting_comments_permission || User::$login)): ?>
						<span class="answer-to-comment"><?php echo Language::_('COM_CONTENT_COMMMENTS_ANSWER'); ?></span>
						<?php endif; ?>
					</div>

					<div class="comment-comment xa">
						<?php echo htmlspecialchars_decode($value->comment); ?>
						<?php comments_print($comments , $calendar , $setting_likes_permission , $setting_comments_permission , $value->id , $level + 1); ?>
					</div>
				</div>
			</div>
			<?php
		}
}