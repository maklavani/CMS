<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/14/2015
	*	last edit		12/03/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

if($as->author == 0 || ($as->author == 2 && $setting->article->author == 0))
{
	$db = new Database;
	$db->table('users')->where('`id` = "' . $params->user . '"')->select()->process();
	$user = $db->output();
}
?>
<?php if(	($as->visit == 0 || ($as->visit == 2 && $setting->article->visit == 0)) || 
			($as->publish_date == 0 || ($as->publish_date == 2 && $setting->article->publish_date == 0)) ||
			($as->author == 0 || ($as->author == 2 && $setting->article->author == 0))
		): ?>
<div class="article-info xa">
	<?php if($as->visit == 0 || ($as->visit == 2 && $setting->article->visit == 0)): ?>
	<div class="article-view article-info-item"><span class="field-title"><?php echo Language::_('COM_CONTENT_ARTICLE_VIEW'); ?></span>&nbsp;<span class="field-value"><?php echo $params->views + 1; ?></span></div>
	<?php endif; ?>
	<?php if($as->publish_date == 0 || ($as->publish_date == 2 && $setting->article->publish_date == 0)): ?>
	<div class="article-publish article-info-item"><span class="field-title"><?php echo Language::_('COM_CONTENT_ARTICLE_PUBLISH_DATE'); ?></span>&nbsp;<span class="field-value"><?php echo $params->publish; ?></span></div>
	<?php endif; ?>
	<?php if($as->author == 0 || ($as->author == 2 && $setting->article->author == 0)): ?>
	<div class="article-user article-info-item"><span class="field-title"><?php echo Language::_('COM_CONTENT_ARTICLE_USER'); ?></span>&nbsp;<span class="field-value"><?php echo $user[0]->name . ' ' . $user[0]->family; ?></span></div>
	<?php endif; ?>
</div>
<?php endif; ?>