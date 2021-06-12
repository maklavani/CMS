<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	08/13/2015
	*	last edit		12/04/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class LanguagesModel extends Model {
	public function set_default_administrator($id)
	{
		Messages::add_message('success' , Language::_('COM_EXTENSION_DEFAULT_ADMINISTRATOR_SET'));
		$this->table('languages')->update(array(array('default_administrator' , 0)))->where('`default_administrator` = 1')->process();
		return $this->table('languages')->update(array(array('default_administrator' , 1)))->where('`id` = ' . $id)->process();
	}

	public function set_default_site($id)
	{
		Messages::add_message('success' , Language::_('COM_EXTENSION_DEFAULT_SITE_SET'));
		$this->table('languages')->update(array(array('default_site' , 0)))->where('`default_site` = 1')->process();
		return $this->table('languages')->update(array(array('default_site' , 1)))->where('`id` = ' . $id)->process();
	}

	public function update_languages()
	{
		$template = $this->get_with_id($_GET['id'] , 'languages');
		
		if($_POST['field_input_default_administrator'] == 'show' && !$template[0]->default_administrator)
			$this->set_default_administrator($_GET['id']);

		if($_POST['field_input_default_site'] == 'show' && !$template[0]->default_site)
			$this->set_default_site($_GET['id']);

		return $this->table('languages')->update(array(array('name' , $_POST['field_input_name']) , array('label' , $_POST['field_input_label']) , array('abbreviation' , $_POST['field_input_abbreviation'])))->where('`id` = ' . $_GET['id'])->process();
	}
}