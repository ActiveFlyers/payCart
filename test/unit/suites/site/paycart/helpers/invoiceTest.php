<?php

/**
 * @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @package 	PAYCART
 * @subpackage	paycartHelper
 * @contact		support+paycart@readybytes.in
 * @author 		Manish Trivedi
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Invoice Helper
 * @author mManishTrivedi
 */


class PaycartHelperInvoiceTest extends PayCartTestCaseDatabase
{
	/**
	 * (non-PHPdoc)
	 * @see test/unit/paycart/case/PayCartTestCaseDatabase::getDBFiles()
	 */
	protected static function getDBFiles()
	{
		$files 	= parent::getDBFiles();
		$array	= Array(RBTEST_BASE . '/_data/database/rb_package_ecommerce.sqlite.sql');
		
		return array_merge($files, $array);
	}
	
	var $test_CreateInvoice = Array('_data/dataset/rb_ecommerce/rb_ecommerce.php');
	
	/**
	 * 
	 * Enter description here ...
	 * @param PaycartCart $cart
	 * @throws RuntimeException
	 */
	public function test_CreateInvoice()
	{
		$mockConfig = $this->getMockPaycartConfig();
		$mockConfig->expects($this->any())
					->method('get')
					->will($this->returnCallback(
							function($prop, $default) 
							{
								switch($prop) {
				                	case 'invoice_serial_prefix' :
				                		return 'Rb-Invoice-';
				                	case 'currency' :
				                		return 'USD';
				                	default:
				                		return $default;	
									}
				             }
						));
								
		PayCartTestReflection::setValue('paycartfactory', '_config', $mockConfig);
		
		$data = Array (
						'cart_id'  => 122 , 	'buyer_id' => 602,
						'currency' => 'USD',	'_total'   => 500,
					 );
		
		$cart = PaycartCart::getInstance(0, $data);
		// set cart total 
		PayCartTestReflection::setValue($cart, '_total', 500);
		
		$paycartInvoice 	= PaycartFactory::getHelper('invoice');
		$invoiceId			= $paycartInvoice->createInvoice($cart);
		
		// get first invoice id
		$this->assertEquals(1, $invoiceId);
		
		// when cart is not exist
		$msg = '';
		
		try {
			$cart = PaycartCart::getInstance();
			$paycartInvoice->createInvoice($cart);
		} catch (RuntimeException $exception) {
			$msg = $exception->getMessage();
		}
		
		$this->assertEquals(Rb_Text::_('COM_PAYCART_CART_MUST_REQUIRED_FOR_INVOICE'), $msg);
		
		$tmpl_invoice 	=  array_merge(Array('invoice_id'=>0), require RBTEST_BASE.'/_data/dataset/rb_ecommerce/tmpl_invoice.php');
		
		$rows_invoice 	=  Array();
		$rows_invoice[]	=  array_replace($tmpl_invoice, 
										Array (
										    'invoice_id' => 1, 'object_id' => 122, 'object_type' => 'PaycartCart',
										    'buyer_id' => 602, 'master_invoice_id' => 0, 'currency' => 'USD', 
										    'sequence' => 1, 	 'serial' => 'Rb-Invoice-122', 'status' => PaycartHelperInvoice::STATUS_INVOICE_DUE,
										    'title' => '', 'expiration_type' => PaycartHelperInvoice::INVOICE_EXPIRATION_TYPE_FIXED, 'time_price' => '{"time":["000000000000"],"price":[500]}',
										    'recurrence_count' => '0', 'subtotal' => 500.0 , 'total' => 500.0,
										    'notes' => '', 'params' => '', 
										    'due_date' => '-0001-11-30 00:00:00',
										    'processor_config' => '{"data":{}}',
 								));
 								
 		//@PCTODO:: test date fields
 		// compare tables
 		$this->compareTables(	Array('jos_rb_ecommerce_invoice'), 
 								Array('jos_rb_ecommerce_invoice' => $rows_invoice),
 								Array('jos_rb_ecommerce_invoice' => Array(
 								 			'created_date', 'modified_date',								    
										    'issue_date', 'processor_type',
										    'processor_config'))
							);
		
	}
	
	/**
	 * (non-PHPdoc)
	 * @see test/unit/paycart/case/PayCartTestCaseDatabase::getDataSetFile()
	 */
	protected function getDataSetFile() 
	{
		$testcase = $this->getName();
		
		// Test case (test_processPayment) is using dataprovider so test case name will be {test_processPayment with data set #0}  
		if(strpos($testcase, 'test_processPayment') === 0 || strpos($testcase, 'test_processNotification') === 0) {
			return Array(
							'_data/dataset/rb_ecommerce/rb_ecommerce-1.php' ,
							'_data/dataset/rb_ecommerce/extensions-1.php'
						);
		}
		
		return parent::getDataSetFile();
	}
	
	/**
	 * 
	 * Test Payment Processing
	 * @param $invoiceId		 	: Collect payment on this invoice  
	 * @param $paymentData 	: post data by Payment form
	 * @param $processorData: Data-Array of Payment-Processor 
	 *		 				  Array('processor_type' => _PAYMENT_PROCESSOR_NAME, 'processor_config' => _PAYMENT_PROCESSOR_CONFIG_)
	 * @param $auData		: After payment compare tables	
	 * @param $excludeColumns 
	 * 
	 * 
	 * @dataProvider provider_test_processPayment
	 */
	public function test_processPayment($invoiceId, $paymentData, $processorData, $auData, $excludeColumns) 
	{
		
		// Mock Dependancy
		//$session = PaycartFactory::$session;
		$options = Array(
						'get.user.id' 		=>  490,
						'get.user.name'		=> '_MANISH_TRIVEDI_',
						'get.user.username' => 'mManishTrivedi',
						'get.user.guest'	=>	0,
						'get.user.email'	=>	'support+paycart@readybytes.in'
						);
						
		// MockSession and set 490 user id in session
		PaycartFactory::$session = $this->getMockSession($options);
		
		$paycartInvoice 	= PaycartFactory::getHelper('invoice');
		
		/** 
		 * @var PaycartHelperInvoice 
		 */
		// set processor data on invoice
		PayCartTestReflection::invoke($paycartInvoice, '_updateInvoice', $invoiceId, $processorData);


		//process payment
		$response = PayCartTestReflection::invoke($paycartInvoice, '_processPayment', $invoiceId, $paymentData);

		$this->compareTables(array_keys($auData), $auData, $excludeColumns);

 		//@PCTODO:: test date fields
		
	}
	
	public function provider_test_processPayment()
	{
		//make sure stripe exist in system
		if(!JFile::exists(JPATH_ROOT.'/plugins/rb_ecommerceprocessor/stripe/stripe.php')) {
			throw new RuntimeException("Stripe Payment Processor is not exist");
		}
		
		$invoiceId = 6;
		
		$stripe_processorData = Array(	'processor_type' => 'stripe', 
										'processor_config' => '{"api_key":"sk_test_X13tGn9VbhcWDhfruzd8SLMN"}');
		$stripe_paymentData 	= Array(
										'card_number'		=> 	'4242424242424242',	
										'expiration_month'	=> 	'01',	
										'expiration_year'	=>	'2031',	
										'card_code'			=>	'123'	
								);
						
		$auData 	  = $this->get_auData_Stripe();

		$excludeColumns = Array(
							'jos_rb_ecommerce_invoice' 		=> Array( 'created_date', 'modified_date', 'issue_date', 'paid_date', 'processor_data'),
							'jos_rb_ecommerce_transaction' 	=> Array( 'gateway_txn_id', 'gateway_parent_txn', 'gateway_subscr_id', 'created_date', 'params')
							);
						
		return Array(
					// live testing with stripe
					Array($invoiceId, $stripe_paymentData, $stripe_processorData, $auData, $excludeColumns )
					);
	}	
	
	public function get_auData_Stripe()
	{
		$tmpl_invoice 	=  array_merge(Array('invoice_id'=>0), require RBTEST_BASE.'/_data/dataset/rb_ecommerce/tmpl_invoice.php');
		
		$rows_invoice 	=  Array();
		// Piad invoice
		$rows_invoice[]	=  array_replace($tmpl_invoice, 
										Array (
										    'invoice_id' => 6,	'object_id' => 6, 'object_type' => 'paycartcart',
										    'buyer_id' => 490,	'master_invoice_id' => 0,	'currency' => 'USD',
											'sequence' => '1',	'serial' => 'Inv-01-01',	'status' => PaycartHelperInvoice::STATUS_INVOICE_PAID,	
											'title' => 'Invoice-1', 'expiration_type' => PaycartHelperInvoice::INVOICE_EXPIRATION_TYPE_FIXED, 
											'time_price' => '{"time":["000000000000"],"price":["50.00000"]}',
											'recurrence_count' => 1, 'subtotal' => 50.0, 'total' => 50.0,
											'notes' => '', 'params' => '', 'refund_date' => 'NULL', 'due_date' => '-0001-11-30 00:00:00',
											'processor_type' => 'stripe', 'processor_config' => '{"api_key":"sk_test_X13tGn9VbhcWDhfruzd8SLMN"}',
											//    'created_date' => '2014-03-06 12:48:05',
											//    'modified_date' => '2014-03-06 12:48:10',
											//    'paid_date' => '2014-03-06 12:48:10',
											//    'issue_date' => '2014-03-06 12:48:05',
											//    'processor_data' => '{"profileId":"cus_3cFP0Nxq76gaY6"}',
 								));
 		
		$tmpl_transaction 	=	array_merge(Array('transaction_id'=>0),require RBTEST_BASE.'/_data/dataset/rb_ecommerce/tmpl_transaction.php');
		$rows_transaction 	=	Array();
		$rows_transaction[]	=  	array_replace($tmpl_transaction, 
										Array(  
											 	'transaction_id' => 1, 'buyer_id' => 490,
											    'invoice_id' => 6, 	'processor_type' => 'stripe',
											    'payment_status' => PaycartHelperInvoice::STATUS_TRANSACTION_SUBSCR_START,
											    'message' => 'PLG_RB_ECOMMERCEPROCESSOR_STRIPE_TRANSACTION_STRIPE_PROFILE_CREATED',
												'amount' => 0.0,
												'signature' => '',		
//											    'gateway_txn_id' => 'cus_3cFP0Nxq76gaY6',
//											    'gateway_parent_txn' => '0',
//											    'gateway_subscr_id' => 'cus_3cFP0Nxq76gaY6',
//											    'created_date' => '2014-03-06 12:48:07',
//											    'params' => '{"id":"cus_3cFP0Nxq76gaY6","object":"customer","created":1394110087,"livemode":false,"description":" at 2014-03-06 12:48:05","email":null,"delinquent":false,"metadata":[],"subscriptions":{"object":"list","count":0,"url":"\\/v1\\/customers\\/cus_3cFP0Nxq76gaY6\\/subscriptions","data":[]},"discount":null,"account_balance":0,"currency":null,"cards":{"object":"list","count":1,"url":"\\/v1\\/customers\\/cus_3cFP0Nxq76gaY6\\/cards","data":[{"id":"card_103cFP2aIfD4Vi0EqUQ4uZmf","object":"card","last4":"4242","type":"Visa","exp_month":1,"exp_year":2031,"fingerprint":"ia38PDwPUagffsPc","customer":"cus_3cFP0Nxq76gaY6","country":"US","name":null,"address_line1":null,"address_line2":null,"address_city":null,"address_state":null,"address_zip":null,"address_country":null,"cvc_check":"pass","address_line1_check":null,"address_zip_check":null}]},"default_card":"card_103cFP2aIfD4Vi0EqUQ4uZmf"}'	    
											));
											
	$rows_transaction[]	=  	array_replace($tmpl_transaction, 
										Array(  
											 'transaction_id' => 2,
											    'buyer_id' => 490,
											    'invoice_id' => 6,
											    'processor_type' => 'stripe',
											    'amount' => 50.0,
											    'payment_status' => PaycartHelperInvoice::STATUS_TRANSACTION_PAYMENT_COMPLETE ,
											    'message' => 'PLG_RB_ECOMMERCEPROCESSOR_STRIPE_TRANSACTION_STRIPE_PAYMENT_COMPLETED',
											    'signature' => '',
//											    'gateway_txn_id' => 'ch_103cFX2aIfD4Vi0EFSDLodJz',
//											    'gateway_parent_txn' => '0',
//											    'gateway_subscr_id' => 'cus_3cFX1Zyp0PfdKP',
//											    'created_date' => '2014-03-06 12:55:43',
//											    'params' => '{"id":"ch_103cFX2aIfD4Vi0EFSDLodJz","object":"charge","created":1394110542,"livemode":false,"paid":true,"amount":5000,"currency":"usd","refunded":false,"card":{"id":"card_103cFX2aIfD4Vi0EDLTHYReK","object":"card","last4":"4242","type":"Visa","exp_month":1,"exp_year":2031,"fingerprint":"ia38PDwPUagffsPc","customer":"cus_3cFX1Zyp0PfdKP","country":"US","name":null,"address_line1":null,"address_line2":null,"address_city":null,"address_state":null,"address_zip":null,"address_country":null,"cvc_check":"pass","address_line1_check":null,"address_zip_check":null},"captured":true,"refunds":[],"balance_transaction":"txn_103cFX2aIfD4Vi0ErqLvWG4e","failure_message":null,"failure_code":null,"amount_refunded":0,"customer":"cus_3cFX1Zyp0PfdKP","invoice":null,"description":null,"dispute":null,"metadata":[]}'
											));											
											
											
	
											
 								
		$auData 	  = Array(
							'jos_rb_ecommerce_invoice' 		=> $rows_invoice,
							'jos_rb_ecommerce_transaction' 	=> $rows_transaction
						);
		return $auData;
	}
	
	
	
	
	/**
	 * 
	 * Test Notification Processing
	 * @param $invoiceId		: Collect payment on this invoice  
	 * @param $responseData		: notification data by Payment-gatway
	 * @param $processorData	: Data-Array of Payment-Processor 
	 *		 				  		Array('processor_type' => _PAYMENT_PROCESSOR_NAME, 'processor_config' => _PAYMENT_PROCESSOR_CONFIG_)
	 * @param $auData			: After payment compare tables	
	 * @param $excludeColumns	:
	 * 
	 * @dataProvider provider_test_processNotification
	 */
	public function test_processNotification($invoiceId, $responseData, $processorData, $auData, $excludeColumns) 
	{

		// Mock Dependancy
		//$session = PaycartFactory::$session;
		$options = Array(
						'get.user.id' 		=>  490,
						'get.user.name'		=> '_MANISH_TRIVEDI_',
						'get.user.username' => 'mManishTrivedi',
						'get.user.guest'	=>	0,
						'get.user.email'	=>	'support+paycart@readybytes.in'
						);
						
		// MockSession and set 490 user id in session
		PaycartFactory::$session = $this->getMockSession($options);
		
		$paycartInvoice 	= PaycartFactory::getHelper('invoice');
		
		/** 
		 * @var PaycartHelperInvoice 
		 */
		// set processor data on invoice
		PayCartTestReflection::invoke($paycartInvoice, '_updateInvoice', $invoiceId, $processorData);
		
		// get invoice id from response
		$invoiceId = $paycartInvoice->getNotificationInvoiceId($responseData);
		//process payment
		$response = PayCartTestReflection::invoke($paycartInvoice, '_processNotification', $invoiceId, $responseData);

		$this->compareTables(array_keys($auData), $auData, $excludeColumns);

 		//@PCTODO:: test date fields
		
	}
	
	public function provider_test_processNotification()
	{
		//make sure Payfast exist in system
		if(!JFile::exists(JPATH_ROOT.'/plugins/rb_ecommerceprocessor/payfast/payfast.php')) {
			throw new RuntimeException("PayFast Payment Processor is not exist");
		}
		
		$invoiceId = 6;
		
		//change it if required
		$baseUrl = 'http://5.kappa.readybytes.in/paycart/paycart4813/index.php';
		  
		$payfast_processorData = Array(
						'currency' => 'ZAR',				// PayFast supports only South-African-Rand(ZAR) currency
						'processor_type' 	=> 'payfast', 
						'processor_config' 	=> Array(
									'merchant_id' => '10000103', 						// test account details. Provided by PayFast
									'merchant_key' => '479f49451e829', 
									'sandbox' => '1', 
									'proxy_server' => '0', 
									'return_url' => $baseUrl.'?option=com_paycart&view=cart&task=complete&processor=payfast', 
									'cancel_url' => $baseUrl.'?option=com_paycart&view=cart&task=cancel&processor=payfast', 
									'notify_url' => $baseUrl.'?option=com_paycart&view=cart&task=notify&processor=payfast' 
									)
								);
	
		// dummy notification for check payment system is working properly
		$get =	Array (	
						'option' => 'com_paycart',	'view' => 'cart','task' => 'notify',
						'processor' => 'payfast','m_payment_id' => '6771', 'payment_status' => 'COMPLETE',		
						'pf_payment_id' => '99923',
						'item_name' 	=> 'PayFast', 'item_description' => '',	'amount_gross' => '50.00',	'amount_fee' => '-0.57',
						'amount_net' 	=> '49.43', 'custom_str1' => '', 'custom_str2' => '', 'custom_str3' => '',
						'custom_str4' 	=> '','custom_str5' => '','custom_int1' => '','custom_int2' => '','custom_int3' => '',
						'custom_int4' => '','custom_int5' => '',
						'name_first' => 'Test',
						'name_last' => 'User 01',
						'email_address' => 'sbtu01@payfast.co.za',
						'merchant_id' => '10000103',
						'signature' => 'd9390f0bd3168b6791fa956f03a66568'	// md5-data
					);
		
		// create signature
		$array	= Array('merchant_id', 'merchant_key', 'return_url', 'cancel_url', 'notify_url', 'm_payment_id', 'amount', 'item_name');
		$string = Array();
		
		foreach($get as $key => $val ) 
        {
            if($key == 'm_payment_id') $returnString = '';
            if(! isset($returnString)) continue;
            if($key == 'signature') continue;
            $returnString[] = $key . '=' . urlencode($val);
        }
        
        $string = implode('&', $returnString);
        $md5	=	md5($string);
        
        $get['signature']	= $md5;
        
        $payfast_notifyData = new stdClass();
		$payfast_notifyData->__get 	= $get;
		$payfast_notifyData->__post	= $get;
		$payfast_notifyData->data	= array_merge($payfast_notifyData->__get, $payfast_notifyData->__post);
        
        $auData 	  = $this->get_auData_PayFast();

		$excludeColumns = Array(
							'jos_rb_ecommerce_invoice' 		=> Array( 'created_date', 'modified_date', 'issue_date', 'paid_date', 'processor_data'),
							'jos_rb_ecommerce_transaction' 	=> Array( 'gateway_txn_id', 'gateway_parent_txn', 'gateway_subscr_id', 'created_date', 'params')
							);
						
		return Array(
					Array($invoiceId, $payfast_notifyData, $payfast_processorData, $auData, $excludeColumns )
					);
	}	
	
	public function get_auData_PayFast()
	{
		$tmpl_invoice 	=  array_merge(Array('invoice_id'=>0), require RBTEST_BASE.'/_data/dataset/rb_ecommerce/tmpl_invoice.php');
		
		$rows_invoice 	=  Array();
		// Piad invoice
		$rows_invoice[]	=  array_replace($tmpl_invoice, 
										Array (
										    'invoice_id' => 6,	'object_id' => 6, 'object_type' => 'paycartcart',
										    'buyer_id' => 490,	'master_invoice_id' => 0,	'currency' => 'ZAR',
											'sequence' => '1',	'serial' => 'Inv-01-01',	'status' => PaycartHelperInvoice::STATUS_INVOICE_PAID,	
											'title' => 'Invoice-1', 'expiration_type' => PaycartHelperInvoice::INVOICE_EXPIRATION_TYPE_FIXED, 
											'time_price' => '{"time":["000000000000"],"price":["50.00000"]}',
											'recurrence_count' => 1, 'subtotal' => 50.0, 'total' => 50.0,
											'notes' => '', 'params' => '', 'refund_date' => 'NULL', 'due_date' => '-0001-11-30 00:00:00',
											'processor_type' => 'payfast',
  											'processor_config' => '{"merchant_id":"10000103","merchant_key":"479f49451e829","sandbox":"1","proxy_server":"0","return_url":"http:\\/\\/5.kappa.readybytes.in\\/paycart\\/paycart4813\\/index.php?option=com_paycart&view=cart&task=complete&processor=payfast","cancel_url":"http:\\/\\/5.kappa.readybytes.in\\/paycart\\/paycart4813\\/index.php?option=com_paycart&view=cart&task=cancel&processor=payfast","notify_url":"http:\\/\\/5.kappa.readybytes.in\\/paycart\\/paycart4813\\/index.php?option=com_paycart&view=cart&task=notify&processor=payfast"}',
  											'processor_data' => '{}',
											//    'created_date' => '2014-03-06 12:48:05',
											//    'modified_date' => '2014-03-06 12:48:10',
											//    'paid_date' => '2014-03-06 12:48:10',
											//    'issue_date' => '2014-03-06 12:48:05',
											//    'processor_data' => '{"profileId":"cus_3cFP0Nxq76gaY6"}',
 								));
 		
		$tmpl_transaction 	=	array_merge(Array('transaction_id'=>0),require RBTEST_BASE.'/_data/dataset/rb_ecommerce/tmpl_transaction.php');
		$rows_transaction 	=	Array();
											
		$rows_transaction[]	=  	array_replace($tmpl_transaction, 
										Array(  
											 'transaction_id' => 1,
											    'buyer_id' => 490,
											    'invoice_id' => 6,
											    'processor_type' => 'payfast',
											    'amount' => 50.0,
											    'payment_status' => PaycartHelperInvoice::STATUS_TRANSACTION_PAYMENT_COMPLETE ,
											    'message' => 'PLG_RB_ECOMMERCEPROCESSOR_PAYFAST_TRANSACTION_PAYFAST_PAYMENT_COMPLETED',
											    'signature' => '',
												'gateway_txn_id' => '99923',
//											    'gateway_txn_id' => 'ch_103cFX2aIfD4Vi0EFSDLodJz',
//											    'gateway_parent_txn' => '0',
//											    'gateway_subscr_id' => 'cus_3cFX1Zyp0PfdKP',
//											    'created_date' => '2014-03-06 12:55:43',
//											    'params' => '{"option":"com_paycart","view":"cart","task":"notify","processor":"payfast","m_payment_id":"6771","payment_status":"COMPLETE","pf_payment_id":"99923","item_name":"PayFast","item_description":"","amount_gross":"25.00","amount_fee":"-0.57","amount_net":"24.43","custom_str1":"","custom_str2":"","custom_str3":"","custom_str4":"","custom_str5":"","custom_int1":"","custom_int2":"","custom_int3":"","custom_int4":"","custom_int5":"","name_first":"Test","name_last":"User 01","email_address":"sbtu01@payfast.co.za","merchant_id":"10000103","signature":"58d17d484a8879dba38c68772b000776"}',
											));											
											
											
	
											
 								
		$auData 	  = Array(
							'jos_rb_ecommerce_invoice' 		=> $rows_invoice,
							'jos_rb_ecommerce_transaction' 	=> $rows_transaction
						);
		return $auData;
	}
	
}
