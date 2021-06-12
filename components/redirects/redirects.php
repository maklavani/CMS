<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	09/02/2015
	*	last edit		09/05/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

class BankController extends Controller {
	// khandane view
	public function view($view)
	{
		$params = '';

		if(is_array($view) && !empty($view))
		{
			$view_check = str_replace("-" , " " , $view[0]);

			if($view_check == Language::_('COM_BANK_PAY'))
			{
				$model = self::model('pay');

				if(isset($view[1]) && Regex::cs($view[1] , "text") && $transaction = $model->get_with_code($view[1]))
				{
					$_GET['code'] = $view[1];
					Controller::$view = $view = 'pay';					
				}
			}
			else if($view_check == Language::_('COM_BANK_BACK'))
				Controller::$view = $view = 'back';
		}

		if($view == 'pay' && Regex::cs($_GET['code'] , "text"))
		{
			$model = self::model('pay');
			$transaction = $model->get_with_code($_GET['code']);

			if($transaction && $transaction[0]->status)
			{
				$_GET['code'] = $view[1];
				$transaction[0]->order = $model->set_order_id($transaction[0]->id);
				$params = json_encode($transaction , JSON_UNESCAPED_UNICODE);
				View::read($params);
			}
			else
				Messages::add_message('error' , Language::_('ERROR_INVLAID_LINK'));
		}

		else if($view == 'back' && 
				isset($_POST['RefId']) && isset($_POST['ResCode']) && isset($_POST['SaleOrderId']) && 
				isset($_POST['SaleReferenceId']) && isset($_POST['CardHolderInfo']) && isset($_POST['CardHolderPan']) && 
				Regex::cs($_POST['RefId'] , "text") && Regex::cs($_POST['ResCode'] , "numeric") && Regex::cs($_POST['SaleOrderId'] , "numeric"))
		{
			$model = self::model('back');
			$transaction = $model->get_with_order($_POST['SaleOrderId']);

			if($transaction && $transaction[0]->status)
			{
				$transaction[0]->set_accept_order = $model->set_accept_order_id($transaction[0]->id);
				$params = json_encode($transaction , JSON_UNESCAPED_UNICODE);
				View::read($params);
			}
			else
				Messages::add_message('error' , Language::_('ERROR_INVLAID_LINK'));
		}

		else
			Messages::add_message('error' , Language::_('ERROR_INVLAID_LINK'));
	}
}