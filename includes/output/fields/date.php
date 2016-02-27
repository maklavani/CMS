<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Makalvani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/30/2015
	*	last edit		08/04/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class DateField {
	public static function output($name , $tabindex , $default , $children , $attributes , $fields_name , $placeholder)
	{
		Templates::package('date');
		Templates::package('mousewheel');

		require_once _INC . 'system/calendar.php';

		if($default == "")
			$default = date('Y-m-d');

		if(Language::$lang == 'fa-ir')
		{
			$calendar = new Calendar('shamsi');
			$month_day = array(
								"1" => 31 , "2" => 31 , "3" => 31 , 
								"4" => 31 , "5" => 31 , "6" => 31 , 
								"7" => 30 , "8" => 30 , "9" => 30 , 
								"10" => 30 , "11" => 30 , "12" => 29
								);
			$leap_month = 12;
			$min_year = 1278;
			$max_year = 1500;
			$leap_year = array(	1280 , 1284 , 1288 , 1292 , 1296 , 1300 , 1304 , 1308 , 
								1313 , 1317 , 1321 , 1325 , 1329 , 1333 , 1337 , 1341 , 
								1346 , 1350 , 1354 , 1358 , 1362 , 1366 , 1370 , 
								1375 , 1379 , 1383 , 1387 , 1391 , 1395 , 1399 , 1403 , 
								1408 , 1412 , 1416 , 1420 , 1424 , 1428 , 1432 , 1436 , 
								1441 , 1445 , 1449 , 1453 , 1457 , 1461 , 1465 , 1469 , 
								1474 , 1478 , 1482 , 1486 , 1490 , 1494 , 1498
							);
		}
		else
		{
			$calendar = new Calendar();
			$month_day = array(
								"1" => 31 , "2" => 28 , "3" => 31 , 
								"4" => 30 , "5" => 31 , "6" => 30 , 
								"7" => 31 , "8" => 31 , "9" => 30 , 
								"10" => 31 , "11" => 30 , "12" => 31
								);
			$leap_month = 2;
			$min_year = 1900;
			$max_year = 2100;
			$leap_year = array(	1904 , 1908 , 1912 , 1916 , 1920 , 1924 , 1928 , 1932 , 
								1936 , 1940 , 1944 , 1948 , 1952 , 1956 , 1960 , 1964 , 
								1968 , 1972 , 1976 , 1980 , 1984 , 1988 , 1992 , 1996 , 
								2000 , 2004 , 2008 , 2012 , 2016 , 2020 , 2024 , 2028 , 
								2032 , 2036 , 2040 , 2044 , 2048 , 2052 , 2056 , 2060 , 
								2064 , 2068 , 2072 , 2076 , 2080 , 2084 , 2088 , 2092 , 
								2096 , 2104
							);
		}

		$date_text = explode("-" , $default);
		$year = $date_text[0];
		$month = $date_text[1];
		$day = $date_text[2];

		$output = "<input class=\"field_input_" . $name . "\" type=\"hidden\" name=\"field_input_" . $name . "\" value=\"" . $year . "-" . $month . "-" . $day . "\"
						year=\"" . $year . "\" 
						month=\"" . $month . "\" 
						day=\"" . $day . "\"
						month_day=\"" . htmlspecialchars(json_encode($month_day , JSON_UNESCAPED_UNICODE)) . "\"
						leap_month=\"" . $leap_month . "\"
						min_year=\"" . $min_year . "\"
						max_year=\"" . $max_year . "\"
						leap_year=\"" . htmlspecialchars(json_encode($leap_year , JSON_UNESCAPED_UNICODE)) . "\"
					>";

		$output .= "<div class=\"date\" inp=\".field_input_" . $name . "\">";
			$output .= "<div class=\"date-item date-year\"></div>";
			$output .= "<div class=\"date-item date-month\"></div>";
			$output .= "<div class=\"date-item date-day\"></div>";
		$output .= "</div>";
		
		return $output;
	}
}