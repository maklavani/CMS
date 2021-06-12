<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	07/04/2015
	*	last edit		01/16/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class CategoryModel extends Model {
	public function save()
	{
		$code = "";

		while(true){
			$code = Regex::random_string(1 , 'alpha') . Regex::random_string(4);
			$this->table('category')->where('`code` = "' . $code . '"')->select()->process();

			if(!$this->output())
				break;
		}

		$setting = htmlspecialchars(json_encode(
			array(
				"setting_special" => $_POST['field_input_setting_special'] , 
				"setting_special_related" => $_POST['field_input_setting_special_related'] , 
				"setting_special_limit" => $_POST['field_input_setting_special_limit'] , 
				"setting_limit" => $_POST['field_input_setting_limit'] , 
				"setting_countdesc" => $_POST['field_input_setting_countdesc'] , 
				"setting_pagination" => $_POST['field_input_setting_pagination'] , 
				"setting_article_heading" => $_POST['field_input_setting_article_heading'] , 
				"setting_newsfeed" => $_POST['field_input_setting_newsfeed'] , 
				"setting_sort" => $_POST['field_input_setting_sort']
			) , JSON_UNESCAPED_UNICODE));

		$this->table('category')->
			insert(
					array(	'title' , 'parent' , 'code' , 'permission' , 'setting' , 
							'edit_date' , 'create_date' , 'user') , 
					array(	trim($_POST['field_input_title']) , $_POST['field_input_category'] , $code , $_POST['field_input_permission'] , $setting , 
							Site::$datetime , Site::$datetime , User::$id)
				)->process();

		return $this->last_insert_id;
	}

	public function update_category()
	{
		$code = $_POST['field_input_code'];

		$setting = htmlspecialchars(json_encode(
			array(
				"setting_special" => $_POST['field_input_setting_special'] , 
				"setting_special_related" => $_POST['field_input_setting_special_related'] , 
				"setting_special_limit" => $_POST['field_input_setting_special_limit'] , 
				"setting_limit" => $_POST['field_input_setting_limit'] , 
				"setting_countdesc" => $_POST['field_input_setting_countdesc'] , 
				"setting_pagination" => $_POST['field_input_setting_pagination'] , 
				"setting_article_heading" => $_POST['field_input_setting_article_heading'] , 
				"setting_newsfeed" => $_POST['field_input_setting_newsfeed'] , 
				"setting_sort" => $_POST['field_input_setting_sort']
			) , JSON_UNESCAPED_UNICODE));

		$this->table('category')
			->update(array(
						array('title' , trim($_POST['field_input_title'])) , 
						array('parent' , $_POST['field_input_category']) , 
						array('permission' , $_POST['field_input_permission']) , 
						array('setting' , $setting) , 
						array('edit_date' , Site::$datetime)
						)
			)->where('`id` = ' . $_GET['id']);

		return $this->process();
	}

	public function has_newsfeed($id)
	{
		$id_out = "";
		$ids = explode("," , $id);

		if(!empty($ids))
		{
			foreach ($ids as $value) {
				if($id_out != "")
					$id_out .= " OR ";

				$id_out .= "`category` LIKE \"%&quot;" . $value . "&quot;%\"";
			}
			
			$this->table('newsfeed')->select()->where($id_out)->process();
			return $this->output();
		}

		return false;
	}
}