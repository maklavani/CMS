<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/07/2015
	*	last edit		12/03/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

require_once _COMP . 'content/model/category/model.php';
require_once _INC . 'output/lists.php';

$model = new CategoryModel();
$params = $model->get('category' , 'content');

require_once _INC . 'output/lists.php';

$search = array('item' => array('title') , 'sort' => array('title' , 'id'));

$cookie = Cookies::get_cookie('content_category');

if($cookie)
	$cookie = json_decode($cookie , true);
else
	$cookie = array();

if(isset($search['item']) && !empty($search['item'])){
	$found_search = false;
	echo "\n\t\t\t\t\t\t\t<div class=\"toolbar-div toolbar-search-input-parent\"><input name=\"toolbar-search-input\" type=\"text\" placeholder=\"" . Language::_('SEARCH') . "\" value=\"";

		if(isset($_POST['form-search']))
			echo $found_search = $_POST['form-search'];
		else if(isset($_GET['form-search']))
			echo $found_search = $_GET['form-search'];
		else if(isset($cookie['form_search']))
			echo $found_search = $cookie['form_search'];

	echo "\" val=\"";
	if($found_search)
		echo $found_search;
	echo "\"></div>";
	echo "\n\t\t\t\t\t\t\t<div class=\"toolbar-div toolbar-item-parent\">\n\t\t\t\t\t\t\t\t<select class=\"toolbar-item\">";

	foreach ($search['item'] as $value) {
		echo "\n\t\t\t\t\t\t\t\t\t<option ";

		if(isset($_POST['form-search-category']) && $_POST['form-search-category'] == $value)
			echo "selected";
		else if(isset($_GET['form-search-category']) && $_GET['form-search-category'] == $value)
			echo "selected";
		else if(isset($cookie['form_search_category']) && $cookie['form_search_category'] == $value)
			echo "selected";

		echo " value=\"" . $value . "\">" . Language::_('COM_CONTENT_' . strtoupper($value)) . "</option>";
	}

	echo "\n\t\t\t\t\t\t\t\t</select>\n\t\t\t\t\t\t\t</div>";
}

if(isset($search['sort']) && !empty($search['sort'])){
	echo "\n\t\t\t\t\t\t\t<div class=\"toolbar-div toolbar-sort-parent\">\n\t\t\t\t\t\t\t\t<select class=\"toolbar-sort\">";

	foreach ($search['sort'] as $value) {
		echo "\n\t\t\t\t\t\t\t\t\t<option ";

		if(isset($_POST['form-sort']) && isset($_POST['form-sort-order']) && $_POST['form-sort'] == $value && $_POST['form-sort-order'] == "ASC")
			echo "selected";
		else if(isset($_GET['form-sort']) && isset($_GET['form-sort-order']) && $_GET['form-sort'] == $value && $_GET['form-sort-order'] == "ASC")
			echo "selected";
		else if(isset($cookie['form_sort']) && isset($cookie['form_sort_order']) && $cookie['form_sort'] == $value && $cookie['form_sort_order'] == "ASC")
			echo "selected";
		else if((!isset($_POST['form-sort']) || $_POST['form-sort'] == "") && 
				(!isset($_POST['form-sort-order']) || $_POST['form-sort-order'] == "") && 
				(!isset($_GET['form-sort']) || $_GET['form-sort'] == "") && 
				(!isset($_GET['form-sort-order']) || $_GET['form-sort-order'] == "") && 
				!isset($cookie['form_sort']) && !isset($cookie['form_sort_order']) &&
				$value == 'id')
			echo "selected";

		echo " value=\"" . $value . ".ASC\">" . Language::_('COM_CONTENT_' . strtoupper($value)) . ' ' . Language::_('ASC') . "</option>";

		echo "\n\t\t\t\t\t\t\t\t\t<option ";

		if(isset($_POST['form-sort']) && isset($_POST['form-sort-order']) && $_POST['form-sort'] == $value && $_POST['form-sort-order'] == "DESC")
			echo "selected";
		else if(isset($_GET['form-sort']) && isset($_GET['form-sort-order']) && $_GET['form-sort'] == $value && $_GET['form-sort-order'] == "DESC")
			echo "selected";
		else if(isset($cookie['form_sort']) && isset($cookie['form_sort_order']) && $cookie['form_sort'] == $value && $cookie['form_sort_order'] == "DESC")
			echo "selected";

		echo " value=\"" . $value . ".DESC\">" . Language::_('COM_CONTENT_' . strtoupper($value)) . ' ' . Language::_('DESC') . "</option>";
	}

	echo "\n\t\t\t\t\t\t\t\t</select>\n\t\t\t\t\t\t\t</div>";
}

echo "\n\t\t\t\t\t\t\t<div class=\"toolbar-div toolbar-number-parent\">\n\t\t\t\t\t\t\t\t<select class=\"toolbar-number\">";
foreach (array(5 , 10 , 15 , 20 , 25 , 30 , 50 , 75 , 100 , 'all') as $value) {
	echo "\n\t\t\t\t\t\t\t\t<option ";

	if(isset($_POST['form-number']) && $_POST['form-number'] == $value)
		echo "selected";
	else if(isset($_GET['form-number']) && $_GET['form-number'] == $value)
		echo "selected";
	else if(isset($cookie['form_number']) && $cookie['form_number'] == $value)
		echo "selected";
	else if((!isset($_POST['form-number']) || $_POST['form-number'] == "") && 
			(!isset($_GET['form-number']) || $_GET['form-number'] == "") && 
			!isset($cookie['form_number']) &&
			$value == 20)
		echo "selected";

	echo " value=\"" . $value . "\">" . Language::_(strtoupper($value)) . "</option>";
}
echo "\n\t\t\t\t\t\t\t\t</select>\n\t\t\t\t\t\t\t</div>";

echo "\n\t\t\t\t\t\t\t<div class=\"toolbar-div toolbar-clean\">" . Language::_('CLEAN') . "</div>";

$list = new Lists;
$list->name = 'category';
$list->component = 'content';
$list->method = 'get';
$list->head = array(
				'title' => array('type' => 'button' , 'size' => '90') , 
				'id' => array('type' => 'button' , 'size' => '10'));

if(!empty($params))
{
	$category = array();

	foreach ($params as $key => $value) {
		$category[] = 	array(
							'title' => array('type' => 'text' , 'value' => '<a class="selective" vals="index.php?component=content&view=category&id=' . $value->code . '&title=' . str_replace(" " , "-" , $value->title) . '">' . $value->title . '</a>') , 
							'id' => array('type' => 'text' , 'value' => $value->id)
						);
	}

	$list->body = $category;
}

$list->output();