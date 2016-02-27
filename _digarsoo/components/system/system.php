<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/30/2015
	*	last edit		08/01/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class SystemController extends Controller {
	public function ajax()
	{
		if(isset($_GET['ajax']) && $_GET['ajax'] == 'cpu')
			echo Site::$sla;
		else if(isset($_GET['ajax']) && $_GET['ajax'] == 'datetime')
		{
			require_once _INC . 'system/calendar.php';

			if(Language::$lang == "fa-ir")
				$calendar = new Calendar('shamsi');
			else
				$calendar = new Calendar();

			echo $calendar->convert(date('Y-m-d H:i:s') , 'd M Y - H:i:s');
		}
		else if(isset($_GET['ajax']) && $_GET['ajax'] == 'creator')
		{
			$fields = array('text' , 'radio' , 'list' , 'tinymce' , 'textarea' , 'image' , 'tel' , 'date' , 'price');

			if(isset($_GET['field']) && in_array($_GET['field'] , $fields))
			{
				require_once _INC . 'output/fields/' . $_GET['field'] . '.php';
				$class_name = ucwords($_GET['field']) . 'Field';

				if(class_exists($class_name) && method_exists($class_name , 'properties'))
				{
					$class = new $class_name();
					echo call_user_func_array(array($class , 'properties') , array());
				}
				else
					echo Language::_('NONE');
			}
			else
			{
				echo "<div class=\"xa\">";
					echo "<div class=\"x2\">" . Language::_('NAME') . "</div>";
					echo "<div class=\"x8\">";
						echo "<input class=\"fields-name xa\" type=\"text\" placeholder=\"" . Language::_('NAME') . "\">";
					echo "</div>";
				echo "</div>";

				echo "<div class=\"xa\">";
					echo "<div class=\"x2\">" . Language::_('TYPE') . "</div>";
					echo "<div class=\"x8\">";
						echo "<select class=\"fields-items\">";
						foreach ($fields as $value)
							echo "<option value=\"" . $value . "\">" . $value . "</option>";
						echo "</select>";
					echo "</div>";
				echo "</div>";
					
				echo "<div class=\"xa\">";
					echo "<div class=\"x2\">" . Language::_('VALUE') . "</div>";
					echo "<div class=\"x8\">";
						echo "<div class=\"field-properties xa\">" . Language::_('NONE') . "</div>";
					echo "</div>";
				echo "</div>";
			}

		}
	}
}