<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	07/29/2015
	*	last edit		12/08/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

Templates::add_css(Site::$base . _ADM . 'widgets/visit/css/template.css');
Templates::add_js(Site::$base . _ADM . 'widgets/visit/js/template.js');

$width = 1000;
$height = 400;

if($setting->number > 0)
{
	require_once _INC . 'system/calendar.php';

	if(Language::$lang == "fa-ir")
		$calendar = new Calendar('shamsi');
	else
		$calendar = new Calendar();

	$date = date('Y-m-d H:i:s' , strtotime('-' . ($setting->number - 1) . ' day'));

	$db = new Database;
	$db->table('users_all_visit')->where('`date` >= "' . $date . '"')->order('date DESC')->select()->process();
	$days = $db->output();

	$db->table('users_today_visit')->select()->process();
	$today = $db->output();

	$count = array();
	$count_all = array();
	$dates_string = array();

	$count[0] = count($today);
	$count_all[0] = 0;
	$dates_string[0] = $calendar->convert(date('Y-m-d H:i:s') , 'Y / m / d');

	if(!empty($today))
		foreach ($today as $value)
			$count_all[0] += $value->count;

	for ($i = 0,$j = 0;$i < $setting->number - 1;$i++){
		$count[$i + 1] = 0;
		$count_all[$i + 1] = 0;

		if(!empty($days) && isset($days[$j]))
		{
			$time_a = strtotime(date('Y-m-d' , strtotime('-' . ($i + 1) . ' day')));
			$time_b = strtotime($days[$j]->date);
			$diff_time = $time_a - $time_b;

			if($diff_time == 0)
			{
				$count[$i + 1] = $days[$j]->count;
				$count_all[$i + 1] = $days[$j]->count_all;
				$j++;
			}
		}

		$dates_string[$i + 1] = $calendar->convert(date('Y-m-d H:i:s' , strtotime('-' . ($i + 1) . ' day')) , 'Y / m / d');
	}

	$max_count = max($count);
	$min_count = min($count);
	$max_count_all = max($count_all);
	$min_count_all = min($count_all);
	$max_all = max($max_count , $max_count_all);
	$min_all = min($min_count , $min_count_all);

	$max_base = (int)log10($max_all);
	$max_table = ceil($max_all / pow(10 , $max_base)) * pow(10 , $max_base);
	$min_table = 0;
}

// users online
$db->table('users')->where('`visit` >= "' . date('Y-m-d H:i:s' , strtotime('-5 minute')) . '"')->select()->process();
$online_users = $db->output();

$db->table('users')->select(false , 'count(*)')->process();
$all_users = $db->output('num');

$db->table('users_today_visit')->where('`last_seen` >= "' . date('Y-m-d H:i:s' , strtotime('-5 minute')) . '"')->select()->process();
$visitor_online = $db->output();
?>
<div id="visit" class="box xa">
	<h2><?php echo Language::_('WID_VISIT'); ?></h2>
	<div id="visit-status" class="xa s3">
		<div class="status-item status-users-online xa">
			<h3><?php echo Language::_('WID_VISIT_USERS_ONLINE'); ?></h3>
			<div class="xa status-line" val="<?php echo count($online_users); ?>" all="<?php echo $all_users[0][0]; ?>"></div>
		</div>
		<div class="status-item status-users-online xa">
			<h3><?php echo Language::_('WID_VISIT_VISITOR_ONLINE'); ?></h3>
			<div class="xa status-line" val="<?php echo count($visitor_online); ?>" all="<?php echo $count[0]; ?>"></div>
		</div>
		
	</div>
	<div id="visit-svg" class="xa s7">
		<div class="details xa">
			<div class="detail count"><span class="icon-line"></span><div class="text"><?php echo Language::_('WID_VISIT_COUNT'); ?></div></div>
			<div class="detail count-all"><span class="icon-line"></span><div class="text"><?php echo Language::_('WID_VISIT_COUNT_ALL'); ?></div></div>
		</div>
		<svg id="visit-graph" class="xa" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="<?php echo $width; ?>" viewBox="0 0 <?php echo $width; ?> <?php echo $height; ?>" enable-background="new 0 0 <?php echo $width; ?> <?php echo $height; ?>" xml:space="preserve">
		<?php
			if($setting->number > 0 && $max_table > 0)
			{
				$width_shift = 50;
				$height_shift = 50;
				$width_space = 100;
				$height_space = 50;

				$number_of_horizontal_lines = 10;

				$width_in = $width - ($width_space + $width_shift);
				$height_in = $height - ($height_space + $height_shift);
				$p_w = $width_in / ($setting->number + 2);
				$p_h = $height_in / $max_table;

				// Vertical Lines
				for ($i = 0;$i < $setting->number;$i++){ 
					$x = (int)(($setting->number + 2 - $i) * $p_w + $width_shift);
					echo "<line class=\"vertical-line\" x1=\"" . $x . "\" x2=\"" . $x . "\" y1=\"" . (int)($height_space / 2) . "\" y2=\"" . ($height_in + $height_shift) . "\" />";
				}

				// Horizontal Lines
				$p_h_l = $height_in / $number_of_horizontal_lines;
				for ($i = 0;$i < $number_of_horizontal_lines + 1;$i++){ 
					$y = $height_in - (int)($i * $p_h_l) + (int)($height_space / 2);
					echo "<line class=\"horizontal-line\" x1=\"" . (int)($width_space / 2) . "\" x2=\"" . ($width - (int)($width_space / 2)) . "\" y1=\"" . $y . "\" y2=\"" . $y . "\" />";
				}

				// Numbres
				$n_h_l = $max_table / $number_of_horizontal_lines;
				for ($i = 0;$i < $number_of_horizontal_lines + 1;$i++){ 
					$y = $height_in - (int)($i * $p_h_l) + (int)($height_space / 2);
					$number = (int)($i * $n_h_l);
					echo "<text class=\"number-text\" x=\"" . (int)($width_space / 2) . "\" y=\"" . $y . "\">" . number_format($number , 0 , '.' , ',') . "</text>";
				}

				// dates
				for ($i = 0;$i < $setting->number;$i++){ 
					$x = (int)(($setting->number + 2 - $i) * $p_w + $width_shift);
					$y = $height_in + (int)($height_space / 2);
					$line_height = 30;
					$text_height = 90;
					echo "<text class=\"dates-text\" x=\"" . $x . "\" y=\"" . ($y - ($text_height + (int)($line_height / 2))) . "\" transform=\"rotate(-90 , " . ($x + $text_height) . " , " . ($y - (int)($line_height / 2)) . ")\">" . $dates_string[$i] . "</text>";
				}

				// count all
				$count_all_points = ($width - (int)($width_space / 2)) . " , " . ($height_in + (int)($height_shift / 2));
				foreach ($count_all as $key => $value){
					$wp = (int)(($setting->number + 2 - $key) * $p_w + $width_shift);
					$hp = (int)($height_in - $value * $p_h + (int)($height_shift / 2));

					$count_all_points .= " " . $wp . " , " . $hp;
				}
				$count_all_points .= " " . ($width_shift + (int)($width_space / 2)) . " , " . ($height_in + (int)($height_shift / 2)) . " " . ($width - (int)($width_space / 2)) . " , " . ($height_in + (int)($height_shift / 2));
				echo "<polygon class=\"polygon-count-all\" points=\"" . $count_all_points . "\" />";

				// count
				$count_points = ($width - (int)($width_space / 2)) . " , " . ($height_in + (int)($height_shift / 2));
				foreach ($count as $key => $value){
					$wp = (int)(($setting->number + 2 - $key) * $p_w + $width_shift);
					$hp = (int)($height_in - $value * $p_h + (int)($height_shift / 2));

					$count_points .= " " . $wp . " , " . $hp;
				}
				$count_points .= " " . ($width_shift + (int)($width_space / 2)) . " , " . ($height_in + (int)($height_shift / 2)) . " " . ($width - (int)($width_space / 2)) . " , " . ($height_in + (int)($height_shift / 2));
				echo "<polygon class=\"polygon-count\" points=\"" . $count_points . "\" />";

				// cirrcle count all
				foreach ($count_all as $key => $value){
					$x = (int)(($setting->number + 2 - $key) * $p_w + $width_shift);
					$y = (int)($height_in - $value * $p_h + (int)($height_shift / 2));

					echo "<circle class=\"circle-count-all visit-graph-circle\" cx=\"" . $x . "\" cy=\"" . $y . "\" r=\"12\" views=\"" . $value . "\" dates=\"" . $dates_string[$key] . "\" />";
				}

				// cirrcle count
				foreach ($count as $key => $value){
					$x = (int)(($setting->number + 2 - $key) * $p_w + $width_shift);
					$y = (int)($height_in - $value * $p_h + (int)($height_shift / 2));

					echo "<circle class=\"circle-count visit-graph-circle\" cx=\"" . $x . "\" cy=\"" . $y . "\" r=\"12\" views=\"" . $value . "\" dates=\"" . $dates_string[$key] . "\" />";
				}
			}
		?>
		</svg>
	</div>
</div>