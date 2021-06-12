<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	09/02/2015
	*	last edit		12/04/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class BackModel extends Model {
	public function get_with_order($order)
	{
		$this->table('bank')->where('`order` = "' . $order . '" AND `user` = ' . User::$id)->select()->process();
		return $this->output();
	}

	public function set_accept_order_id($id)
	{
		$accept_order = 0;

		while(true){
			$accept_order = rand(1000000000 , 9999999999);
			$this->table('bank')->where('`accept_order` = ' . $accept_order)->select()->process();

			if(!$this->output())
				break;
		}

		$this->table('bank')->where('`id` = ' . $id)->update(array(array('accept_order' , $accept_order) , array('accept_orderdate' , Site::$datetime)))->process();
		return $accept_order;
	}
}