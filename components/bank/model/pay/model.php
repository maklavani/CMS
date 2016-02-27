<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2016 Digarsoo. All rights reserved.
	*	creation date	09/02/2015
	*	last edit		12/04/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class PayModel extends Model {
	public function get_with_code($code)
	{
		$this->table('bank')->where('`code` = "' . $code . '" AND `user` = ' . User::$id)->select()->process();
		return $this->output();
	}

	public function set_order_id($id)
	{
		$order = 0;

		while(true){
			$order = rand(1000000000 , 9999999999);
			$this->table('bank')->where('`order` = ' . $order)->select()->process();

			if(!$this->output())
				break;
		}

		$this->table('bank')->update(array(array('order' , $order) , array('orderdate' , Site::$datetime)))->where('`id` = ' . $id)->process();
		return $order;
	}
}