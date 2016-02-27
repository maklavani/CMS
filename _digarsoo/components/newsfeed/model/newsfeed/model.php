<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	07/20/2015
	*	last edit		07/20/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class NewsfeedModel extends Model {
	public function get_with_name($name)
	{
		$this->table('newsfeed')->where('`name` = "' . $name . '"')->select()->process();
		return $this->output();
	}

	public function save()
	{

		$this->table('newsfeed');
		$this->insert(
						array(	'name' , 'status' , 'category' , 'count' , 
								'countdesc' , 'sort' , 'image' , 'description') , 
						array(	$_POST['field_input_name'] , $_POST['field_input_status'] , htmlentities(json_encode($_POST['field_input_category'] , JSON_UNESCAPED_UNICODE)) , $_POST['field_input_count'] , 
								$_POST['field_input_countdesc'] , $_POST['field_input_sort'] , $_POST['field_input_image'] , $_POST['field_input_description'])
					);
		$this->process();
		return $this->last_insert_id;
	}

	public function update_newsfeed()
	{
		$this->table('newsfeed');
		$this->update(	array(
							array('name' , $_POST['field_input_name']) , 
							array('status' , $_POST['field_input_status']) , 
							array('category' , htmlentities(json_encode($_POST['field_input_category'] , JSON_UNESCAPED_UNICODE))) , 
							array('count' , $_POST['field_input_count']) , 
							array('countdesc' , $_POST['field_input_countdesc']) , 
							array('sort' , $_POST['field_input_sort']) , 
							array('image' , $_POST['field_input_image']) , 
							array('description' , $_POST['field_input_description'])
							)
					);
		$this->where('`id` = ' . $_GET['id']);
		$this->process();
		return $this->process();
	}
}