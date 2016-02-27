<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/28/2015
	*	last edit		01/16/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

$newsfeed = $params->newsfeed;
$articles = $params->articles;

Preload::$status = 'notheme';
header('Content-Type: application/xml');

echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>";
echo "\r\n\t<rss version=\"2.0\">";
	echo "\r\n\t\t<channel>";
		echo "\r\n\t\t\t<title>" . $newsfeed->name . "</title>";
		echo "\r\n\t\t\t<link>" . Site::$full_link_text . "</link>";
		echo "\r\n\t\t\t<description>" . ($newsfeed->description != "" ? $newsfeed->description : Language::_('COM_NEWSFEED_DESCRIPTION') . " " . $newsfeed->name) . "</description>";
		echo "\r\n\t\t\t<language>" . Language::$lang . "</language>";

		$image = $newsfeed->image;
		$images = explode("/" , $image);
		if($images[0] == "uploads")
			$images[0] = 'download';
		$image = implode("/" , $images);

		if($newsfeed->image != "" && System::has_file($newsfeed->image))
		{
			list($width , $height) = getimagesize(_SRC . $newsfeed->image);
			echo "\r\n\t\t\t<image>";
				echo "\r\n\t\t\t\t<url>" . Site::$base . $image . "</url>";
				echo "\r\n\t\t\t\t<title>" . $newsfeed->name . "</title>";
				echo "\r\n\t\t\t\t<link>" . Site::$base . $image  . "</link>";
				echo "\r\n\t\t\t\t<width>" . $width . "</width>";
				echo "\r\n\t\t\t\t<height>" . $height . "</height>";
				echo "\r\n\t\t\t\t<description>" . Language::_('COM_NEWSFEED_DESCRIPTION') . " " . $newsfeed->name . "</description>";
			echo "\r\n\t\t\t</image>";
		}

		echo "\r\n\t\t\t<copyright>Copyright " . date('Y') . " , Digarsoo.com</copyright>";
		echo "\r\n\t\t\t<lastBuildDate>" . date('r') . "</lastBuildDate>\n";
		if(count($articles))
			require_once _COMP . 'newsfeed/view/newsfeed/article.php';
	echo "\t\t</channel>";
echo "\r\n\t</rss>";