<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/14/2015
	*	last edit		07/17/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

$size = 'xa';
?>
<div class="essential xa">
	<a href="<?php echo 'index.php?component=content&amp;view=article&amp;id=' . $article->code . '&title=' . str_replace(" " , "-" , $article->title); ?>"><h2 class="article-title xa"><?php echo $article->title; ?></h2></a>
	<?php
		$image = $article->image;
		$images = explode("/" , $image);
		if($images[0] == "download")
			$images[0] = 'uploads';
		$image = implode("/" , $images);

		if($article->image != "" && System::has_file($image)){
			echo "<div class=\"essential-pic xa s3 m2\"><img src=\"" . Site::$base . $article->image . "\"></div>";
			$size = "xa s65 m75 es05 em025";
		}
	?> 
	<div class="essential-article <?php echo $size; ?>">
		<div class="article-content xa">
			<?php
				$content = html_entity_decode($article->content);
				$pos = strpos($content , '<hr />');

				if($pos > -1)
					$content = strip_tags(substr($content , 0 , $pos));
				else
				{
					$content = strip_tags($content);

					if(strlen($content) > $setting->template->countdesc)
						$content = mb_substr($content , 0 , $setting->template->countdesc) . ' ...';
				}

				echo $content;
			?>
		</div>
	</div>
</div>