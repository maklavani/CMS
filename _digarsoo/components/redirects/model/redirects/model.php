<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	10/03/2016
	*	last edit		10/03/2016
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class RedirectsModel extends Model {
	public function get_with_url($url)
	{
		$this->table('redirects')->where('`url` = "' . $url . '"')->select()->process();
		return $this->output();
	}

	public function save()
	{

		$this->table('redirects');
		$this->insert(
						array(	'url' , 'status' , 'redirect_to' , 'update_date' , 'create_date') , 
						array(	$_POST['field_input_url'] , $_POST['field_input_status'] , $_POST['field_input_redirect_to'] , Site::$datetime , Site::$datetime)
					);
		$this->process();
		return $this->last_insert_id;
	}

	public function update_redirects()
	{
		$this->table('redirects');
		$this->update(	array(
							array('status' , $_POST['field_input_status']) , 
							array('redirect_to' , $_POST['field_input_redirect_to']) , 
							array('update_date' , Site::$datetime)
							)
					);
		$this->where('`id` = ' . $_GET['id']);
		$this->process();
		return $this->process();
	}
}