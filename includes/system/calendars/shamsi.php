<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/21/2015
	*	last edit		01/09/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class ShamsiCalendar {
	public $year;
	public $month;
	public $day;

	public $month_name;
	public $day_in_month; //tedade roozhaye mah
	public $day_of_month;
	public $day_of_month_name;
	public $day_name;
	public $day_of_week;
	public $day_of_year;

	public $week_of_year;

	public $leap;
	public $leap_array = array(	79 , 444 , 810 , 1175 , 1540 , 1905 , 2271 , 2636 , 3001 , 3366 , 3732 , 4097 , 4462 , 4827 , 5193 , 
							5558 , 5923 , 6288 , 6654 , 7019 , 7384 , 7749 , 8115 , 8480 , 8845 , 9210 , 9576 , 9941 , 10306 , 10671 , 
							11037 , 11402 , 11767 , 12132 , 12497 , 12863 , 13228 , 13593 , 13958 , 14324 , 14689 , 15054 , 15419 , 15785 , 16150 , 
							16515 , 16880 , 17246 , 17611 , 17976 , 18341 , 18707 , 19072 , 19437 , 19802 , 20168 , 20533 , 20898 , 21263 , 21629 , 
							21994 , 22359 , 22724 , 23090 , 23455 , 23820 , 24185 , 24550 , 24916 , 25281 , 25646 , 26011 , 26377 , 26742 , 27107 , 
							27472 , 27838 , 28203 , 28568 , 28933 , 29299 , 29664 , 30029 , 30394 , 30760 , 31125 , 31490 , 31855 , 32221 , 32586 , 
							32951 , 33316 , 33682 , 34047 , 34412 , 34777 , 35142 , 35508 , 35873 , 36238 , 36603 , 36969 , 37334 , 37699 , 38064 , 
							38430 , 38795 , 39160 , 39525 , 39891 , 40256 , 40621 , 40986 , 41352 , 41717 , 42082 , 42447 , 42813 , 43178 , 43543 , 
							43908 , 44274 , 44639 , 45004 , 45369 , 45735 , 46100 , 46465 , 46830 , 47195 , 47561 , 47926 , 48291 , 48656 , 49022 , 
							49387 , 49752 , 50117 , 50483 , 50848 , 51213 , 51578 , 51944 , 52309 , 52674 , 53039 , 53405 , 53770 , 54135 , 54500 , 
							54866 , 55231 , 55596 , 55961 , 56327 , 56692 , 57057 , 57422 , 57788 , 58153 , 58518 , 58883 , 59248 , 59614 , 59979 , 
							60344 , 60709 , 61075 , 61440 , 61805 , 62170 , 62536 , 62901 , 63266 , 63631 , 63997 , 64362 , 64727 , 65092 , 65458 , 
							65823 , 66188 , 66553 , 66919 , 67284 , 67649 , 68014 , 68380 , 68745 , 69110 , 69475 , 69841 , 70206 , 70571 , 70936 , 
							71301 , 71667 , 72032 , 72397 , 72762 , 73128 , 73493 , 73858 , 74223 , 74589 , 74954 , 75319 , 75684 , 76050 , 76415 , 
							76780 , 77145 , 77511 , 77876 , 78241 , 78606 , 78972 , 79337 , 79702 , 80067 , 80433 , 80798 , 81163 , 81528);

	public function get($date , $format)
	{
		$current_date = date_timezone_set($date , timezone_open('Asia/Tehran'));
		$this->convert($current_date);
		return $this->format($current_date , $format);
	}

	public function convert($date)
	{
		$start = new DateTime('1900-01-01');
		$start = date_timezone_set($start , timezone_open('Asia/Tehran'));
		date_time_set($start , 0 , 0);
		$interval = date_diff($start , $date);
		$dates = $interval->format('%a');

		$first_year = 1278;
		$eyd = $dates;
		// 1278 - 1500

		// handle error
		if($dates > 82000)
		{
			$interval = date_diff($start , new DateTime());
			$dates = $interval->format('%a');
		}

		$year = 0;
		foreach($this->leap_array as $value){
			if($dates < $value)
				break;
			$eyd = $value;
			$year++;
		}

		$this->year = $first_year + $year;
		$this->day_of_year = $dates - $eyd + 1;

		if($this->day_of_year > 186)
		{
			$this->day = ($this->day_of_year - 1 - 186) % 30 + 1;
			$this->month = (int)(($this->day_of_year - 1 - 186) / 30) + 1 + 6;
			$this->day_of_month = $this->day_of_year - ($this->month - 7) * 30 - 186;
			$this->day_in_month = 30;
		}
		else
		{
			$this->day = ($this->day_of_year - 1) % 31 + 1;
			$this->month = (int)(($this->day_of_year - 1) / 31) + 1;
			$this->day_of_month = $this->day_of_year - ($this->month - 1) * 31;
			$this->day_in_month = 31;
		}


		$day_of_month_name = array('یکم' , 'دوم' , 'سوم' , 'چهارم' , 'پنجم' , 'ششم' , 'هفتم' , 'هشتم' , 'نهم' , 'دهم' , 'یازدهم' , 'دوازدهم' , 'سیزدهم' , 'چهاردهم' , 'پانزدهم' , 'شانزدهم' , 'هفدهم' , 'هجدهم' , 'نوزدهم' , 'بیستم' , 'بیست و یکم' , 'بیست و دوم' , 'بیست و سوم' , 'بیست و چهارم' , 'بیست و پنمجم' , 'بیست و ششم' , 'بیست و هفتم' , 'بیست و هشتم' , 'بیست و نهم' , 'سی ام' , 'سی و یکم');
		$this->day_of_month_name = $day_of_month_name[$this->day_of_month - 1];

		$day_of_week_gregorian = (new DateTime('1900-01-01'))->modify('+' . $eyd . ' days')->format('w');
		$this->week_of_year = (int)(($this->day_of_year - 1) / 7) + 1;
		$this->day_of_week = ($this->day_of_year - 1 + (($day_of_week_gregorian + 2) % 7) - 1) % 7 + 1;

		$day_name = array('شنبه' , 'یکشنبه' , 'دوشنبه' , 'سه شنبه' , 'چهار شنبه' , 'پنج شنبه' , 'جمعه');
		$this->day_name = $day_name[$this->day_of_week - 1];

		$month_name = array('فروردین' , 'اردیبهشت' , 'خرداد' , 'تیر' , 'مرداد' , 'شهریور' , 'مهر' , 'آبان' , 'آذر' , 'دی' , 'بهمن' , 'اسفند');
		$this->month_name = $month_name[$this->month - 1];

		if(in_array($eyd + 366 , $this->leap_array))
			$this->leap = 1;
		else
		{
			$this->leap = 0;

			if($this->month == 12)
				$this->day_in_month--;
		}
	}

	public function format($date , $format)
	{
		$format = str_replace("c" , "Y-m-dTH:i:sP" , $format);
		$format = str_replace("r" , "l, d F Y H:i:s O" , $format);
		$format = str_replace("d" , str_pad($this->day_of_month , 2 , '0' , STR_PAD_LEFT) , $format);
		$format = str_replace("D" , $this->day_name , $format);
		$format = str_replace("j" , $this->day_of_month , $format);
		$format = str_replace("l" , $this->day_name , $format);
		$format = str_replace("'L'" , $this->day_name , $format);
		$format = str_replace("N" , $this->day_of_week, $format);
		$format = str_replace("S" , $this->day_of_month_name , $format);
		$format = str_replace("w" , ($this->day_of_week % 7) , $format);
		$format = str_replace("z" , str_pad($this->day_of_year , 3 , '0' , STR_PAD_LEFT) , $format);
		$format = str_replace("W" , $this->week_of_year , $format);
		$format = str_replace("F" , $this->month_name , $format);
		$format = str_replace("m" , str_pad($this->month , 2 , '0' , STR_PAD_LEFT) , $format);
		$format = str_replace("M" , $this->month_name , $format);
		$format = str_replace("n" , $this->month , $format);
		$format = str_replace("t" , $this->day_in_month , $format);
		$format = str_replace("L" , $this->leap , $format);
		$format = str_replace("o" , $this->year , $format);
		$format = str_replace("Y" , $this->year , $format);
		$format = str_replace("y" , substr($this->year , -2) , $format);
		$format = $date->format($format);
		$format = str_replace("pm" , 'بعد از ظهر' , $format);
		$format = str_replace("am" , 'قبل از ظهر' , $format);
		$format = str_replace("PM" , 'بعد از ظهر' , $format);
		$format = str_replace("AM" , 'قبل از ظهر' , $format);

		return $format;
	}

	public function get_gregorian($year , $month , $day)
	{
		$days = 0;

		$date = array('year' => $year , 'month' => $month , 'day' => $day);

		if($year > 1278)
			$days += $this->leap_array[$year - 1279];

		if((int)($month) > 6)
			$days += 186 + ($month - 7) * 30 + $day;
		else
			$days += ($month - 1) * 31 + $day;

		$new_date = new DateTime('1900-01-01 +' . $days . 'days');
		$new_date = date_timezone_set($new_date , timezone_open('Europe/London'));
		date_time_set($new_date , date("H") , date("i"));

		return $new_date;
	}
}