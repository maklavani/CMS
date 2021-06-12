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

foreach ($special as $key => $value):
?>
<div class="special xa">
	<?php
		$size = 'xa';

		if($value->image != ""){
			echo "<div class=\"special-pic xa s5 m45 l4\"><img title=\"" . $value->title . "\" alt=\"" . $value->title . "\" src=\"" . Site::$base . $value->image . "\"></div>";
			$size = "xa s47 m52 l57 es025";
		}
	?> 
	<div class="special-article <?php echo $size; ?>">
		<?php
			if(!$heading && $value->heading != "")
				echo "<span class=\"special-heading xa\">" . $value->heading . "</span>";
		?>
		<a class="xa" href="<?php echo 'index.php?component=content&amp;view=article&amp;id=' . $value->code . '&title=' . str_replace(" " , "-" , $value->title); ?>"><h3 class="article-title xa"><?php echo $value->title; ?></h3></a>
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
	<?php
		if(isset($value->related))
		{
			echo "<ul class=\"special-related xa\">";
			foreach ($value->related as $keyb => $valueb)
				echo "<li><a href=\"index.php?component=content&amp;view=article&amp;id=" . $valueb->code . "&title=" . str_replace(" " , "-" , $valueb->title) . "\">" . $valueb->title . "</a></li>";

			echo "</ul>";
		}
	?>
</div>
<?php endforeach; ?>