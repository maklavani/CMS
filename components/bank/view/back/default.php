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

Templates::add_css(Site::$base . 'components/bank/css/back.css');

if($_POST['ResCode'] == 0)
{
	try
	{
		$client = new SoapClient('https://pgws.bpm.bankmellat.ir/pgwchannel/services/pgw?wsdl');
		$namespace = 'http://interfaces.core.sw.bps.com/';

		$parameters = 
			array(
				'terminalId' => '1800167' , 
				'userName' => 'gilan504' , 
				'userPassword' => '61706821' , 
				'orderId' => $params[0]->set_accept_order , 
				'saleOrderId' => $params[0]->order , 
				'saleReferenceId' => $_POST['SaleReferenceId']
			);

		$result = $client->bpVerifyRequest($parameters , $namespace);
		$result = explode(',' , $result->return);

		if($result[0] == 0)
		{
			$db = new Database;

			$db->table('bank')->where('`id` = ' . $params[0]->id)
				->update(array(
							array('status' , 0) , 
							array('reference' , $_POST['SaleReferenceId']) , 
							array('card_info' , $_POST['CardHolderInfo']) , 
							array('card_pan' , $_POST['CardHolderPan'])
						))->process();

			$db->table('estate_advertisment')->where('`code` = "' . $params[0]->code_product . '"')->update(array(array('expire' , date("Y-m-d H:i:s" , strtotime('+' . $params[0]->expire . ' month')))))->process();
			Messages::add_message('success' , Language::_('COM_BANK_VALUE_RETURN_' . $result[0]));

			?>
			<div class="back xa s9 m7 l5 es05 em15 el25 aes05 aem15 ael25">
				<h2><?php echo Language::_('COM_BANK_RECEIPT'); ?></h2>

				<div class="group x95 ex025 aex025">
					<div class="label x3"><?php echo Language::_('COM_BANK_FOLLOW_UP_NUMBER'); ?></div>
					<div class="value x7"><?php echo "2312313123"; ?></div>
				</div>

				<a class="x5 ex25 aex25" href="<?php echo Site::$base . Language::_('COM_BANK_PROFILE') . '/' . str_replace(" " , "-" , Language::_('COM_BANK_TRANSACTIONS')); ?>"><?php echo Language::_('COM_BANK_BACK_TRANSACTIONS'); ?></a>
			</div>
			<?php
		}
		else
			Messages::add_message('error' , Language::_('COM_BANK_VALUE_RETURN_' . $result[0]));
	}
	catch (Exception $e)
		Messages::add_message('error' , $e->getMessage());
}
else
	Messages::add_message('error' , Language::_('COM_BANK_VALUE_RETURN_' . $_POST['ResCode']));