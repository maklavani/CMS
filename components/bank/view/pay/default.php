<?php
/**
	* --------------------------------------------------------------------------
	*	@author			Hossein Mohammadi Maklavani
	*	@copyright		Copyright (C) 2014 - 2017 Digarsoo. All rights reserved.
	*	creation date	09/02/2015
	*	last edit		12/03/2015
	* --------------------------------------------------------------------------
*/

defined('_ALLOW') or die("access denied!");

require_once _COMP . $params[0]->component . '/bank/pay.php';

Templates::add_css(Site::$base . 'components/bank/css/pay.css');

$payment = new Payment();
$payment->init($params[0]->code_product , $params[0]->expire);

$name = $payment->get_name();
$price = $payment->get_price();

try
{
	$RefId = 0;
	$client = new SoapClient('https://pgws.bpm.bankmellat.ir/pgwchannel/services/pgw?wsdl');
	$namespace = 'http://interfaces.core.sw.bps.com/';

	$date = date("ymd");
	$time = date("his");

	// $price = 1000;
	$additional = Language::_('COM_BANK_PAY_BY') . User::$name . ' ' . User::$family;

	$parameters = 
		array(
			'terminalId' => '1800167' , 
			'userName' => 'gilan504' , 
			'userPassword' => '61706821' , 
			'orderId' => $params[0]->order , 
			'amount' => $price * 10 , 
			'localDate' => $date , 
			'localTime' => $time , 
			'additionalData' => $additional , 
			'callBackUrl' => Site::$domain_name . Language::_('COM_BANK') . '/' . Language::_('COM_BANK_BACK') , 
			'payerId' => 0
		);

	$result = $client->bpPayRequest($parameters , $namespace);
	$result = explode(',' , $result->return);

	if($result[0] == 0)
	{
		$db = new Database;
		$db->table('bank')->where('`id` = ' . $params[0]->id)->update(array(array('price' , $price)))->process();
		?>
			<form
				class="pay xa s9 m7 l5 es05 em15 el25 aes05 aem15 ael25"
				method="post"
				action="https://bpm.shaparak.ir/pgwchannel/startpay.mellat"
				target="_self"
			>
				<h2><?php echo Language::_('COM_BANK_PAY_REQUEST'); ?></h2>

				<div class="group x95 ex025 aex025">
					<div class="label x3"><?php echo Language::_('COM_BANK_NAME'); ?></div>
					<div class="value x7"><?php echo $name; ?></div>
				</div>

				<div class="group x95 ex025 aex025">
					<div class="label x3"><?php echo Language::_('COM_BANK_PRICE'); ?></div>
					<div class="value x7"><?php echo number_format($price , 0 , '.' , ',') . '&nbsp;<span>' . Language::_('COM_BANK_TOMAN') . '</span>'; ?></div>
				</div>

				<div class="group x95 ex025 aex025">
					<div class="label x3"><?php echo Language::_('COM_BANK_GATEWAY'); ?></div>
					<div class="value x7"><?php echo Language::_('COM_BANK_MELLAT'); ?><div class="logo"></div></div>
				</div>

				<div class="group x95 ex025 aex025">
					<div class="label x3"><?php echo Language::_('COM_BANK_PAYABLE'); ?></div>
					<div class="value x7"><small><?php echo Language::_('COM_BANK_CARTS_SHETAB'); ?></small></div>
				</div>

				<input type="hidden" name="orderId" value="<?php echo $params[0]->order; ?>">
				<input type="hidden" name="amount" value="<?php echo $price * 10; ?>">
				<input type="hidden" name="localDate" value="<?php echo $date; ?>">
				<input type="hidden" name="localTime" value="<?php echo $time; ?>">
				<input type="hidden" name="additionalData" value="<?php echo $additional; ?>">
				<input type="hidden" name="callBackUrl" value="<?php echo Site::$domain_name . Language::_('COM_BANK') . '/' . Language::_('COM_BANK_BACK'); ?>">
				<input type="hidden" name="payerId" value="<?php echo 0; ?>">
				<input type="hidden" name="RefId" value="<?php echo $result[1]; ?>">

				<button class="x5 ex25 aex25"><?php echo Language::_('COM_BANK_PAY'); ?></button>
			</form>
		<?php
	}
	else
		Messages::add_message('warning' , Language::_('COM_BANK_VALUE_RETURN_' . $result[0]));
}
catch (Exception $e)
	Messages::add_message('error' , $e->getMessage());