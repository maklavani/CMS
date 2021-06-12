<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	07/14/2015
	*	last edit		01/23/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

foreach ($articles as $key => $value):
?>
<div class="article xa<?php echo !$key ? ' article-first' : ''; ?>">
	<?php
		$size = 'xa';

		if($value->image != ""){
			echo "<div class=\"article-pic x3 s2 m1\"><img src=\"" . Site::$base . $value->image . "\"></div>";
			$size = "x67 s77 m87 ex025";
		}
	?> 
	<div class="article-article <?php echo $size; ?>">
		<?php
			if(!$heading && $value->heading != "")
				echo "<span class=\"special-heading xa\">" . $value->heading . "</span>";
		?>
		<a class="xa" href="<?php echo 'index.php?component=content&amp;view=article&amp;id=' . $value->code . '&title=' . str_replace(" " , "-" , $value->title); ?>"><h4 class="article-title xa"><?php echo $value->title; ?></h4></a>
		<div class="article-content xa">
			<?php
				$content = html_entity_decode($value->content);
				$pos = strpos($content , '<hr />');

				if($pos > -1)
					$content = preg_replace("/&#?[a-z0-9]+;/i" , "" , strip_tags(substr($content , 0 , $pos)));
				else
					$content = preg_replace("/&#?[a-z0-9]+;/i" , "" , strip_tags($content));

				if(strlen($content) > $countdesc)
					$content = mb_substr($content , 0 , $countdesc) . ' ...';

				echo $content;
			?>
		</div>
	</div>
</div>
<?php endforeach; ?>