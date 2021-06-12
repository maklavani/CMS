<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	07/14/2015
	*	last edit		01/19/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

foreach ($articles as $key => $value):
?>
				<item>
					<title><?php echo $value->title; ?></title>
					<description><?php
						$content = html_entity_decode($value->content);
						$pos = strpos($content , '<hr />');

						if($pos > -1)
							$content = preg_replace("/&#?[a-z0-9]+;/i" , "" , strip_tags(substr($content , 0 , $pos)));
						else
							$content = preg_replace("/&#?[a-z0-9]+;/i" , "" , strip_tags($content));

						if(strlen($content) > $newsfeed->countdesc)
							$content = mb_substr($content , 0 , $newsfeed->countdesc) . ' ...';

						echo $content;
					?></description>
					<link><?php echo Site::get_address_site() . (Language::$abbreviation != "" ? Language::$abbreviation . '/' : "") . Language::_('COM_NEWSFEED_CONTENT') . '/' . Language::_('COM_NEWSFEED_ARTICLE') . '/' . $value->code . '/' . str_replace(" " , "-" , $value->title); ?></link>
					<pubDate><?php echo $value->publish_date; ?></pubDate>
				</item>
<?php endforeach; ?>