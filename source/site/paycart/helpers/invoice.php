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



// load rb-ecommerce pkg
rb_import('ecommerce');

class PaycartHelperInvoice
{
	const STATUS_INVOICE_NONE					=	Rb_EcommerceInvoice::STATUS_NONE;			// 0
	const STATUS_INVOICE_DUE		   			= 	Rb_EcommerceInvoice::STATUS_DUE;			// 401;
	const STATUS_INVOICE_PAID 			   		= 	Rb_EcommerceInvoice::STATUS_PAID;			// 402;
	const STATUS_INVOICE_REFUNDED		   		= 	Rb_EcommerceInvoice::STATUS_REFUNDED;		// 403;
	const STATUS_INVOICE_INPROCESS				=	Rb_EcommerceInvoice::STATUS_INPROCESS;		// 404;
	const STATUS_INVOICE_EXPIRED				=	Rb_EcommerceInvoice::STATUS_EXPIRED;		// 405;
	
	const INVOICE_EXPIRATION_TYPE_FIXED			= 	RB_ECOMMERCE_EXPIRATION_TYPE_FIXED;
	const INVOICE_EXPIRATION_TYPE_FOREVER		=	RB_ECOMMERCE_EXPIRATION_TYPE_FOREVER;
	const INVOICE_EXPIRATION_TYPE_RECURRING		=	RB_ECOMMERCE_EXPIRATION_TYPE_RECURRING;
	
	const STATUS_TRANSACTION_NONE              	= 	Rb_EcommerceResponse::NONE ;				// ''
	const STATUS_TRANSACTION_PAYMENT_COMPLETE 	= 	Rb_EcommerceResponse::PAYMENT_COMPLETE;		//'payment_complete';
	const STATUS_TRANSACTION_PAYMENT_REFUND 	= 	Rb_EcommerceResponse::PAYMENT_REFUND;		//'payment_refund';
	const STATUS_TRANSACTION_PAYMENT_PENDING 	= 	Rb_EcommerceResponse::PAYMENT_PENDING;		//'payment_pending';
	const STATUS_TRANSACTION_PAYMENT_FAIL		= 	Rb_EcommerceResponse::PAYMENT_FAIL;			//'payment_fail';
	
	const STATUS_TRANSACTION_SUBSCR_START		= 	Rb_EcommerceResponse::SUBSCR_START;			//'subscr_start';
	const STATUS_TRANSACTION_SUBSCR_CANCEL		= 	Rb_EcommerceResponse::SUBSCR_CANCEL;		//'subscr_cancel';
	const STATUS_TRANSACTION_SUBSCR_END			= 	Rb_EcommerceResponse::SUBSCR_END;			//'subscr_end';
	const STATUS_TRANSACTION_SUBSCR_FAIL		= 	Rb_EcommerceResponse::SUBSCR_FAIL;			//'subscr_fail';

	const STATUS_TRANSACTION_NOTIFICATION		= 	Rb_EcommerceResponse::NOTIFICATION;			//'notification';
	const STATUS_TRANSACTION_FAIL				= 	Rb_EcommerceResponse::FAIL;					//'fail';
	

	/**
	 * 
	 * Invoke to get all rb-ecommerce processor
	 * 
	 * @return array of all available payment processor 
	 */
	public function getProecessorList() 
	{
		return Rb_EcommerceAPI::get_processors_list();
	}
	
	/**
	 * 
	 * Invoke to get Processor config file path
	 * @param $type : processor type
	 * @throws RuntimeException
	 * 
	 * @return processor config file path
	 * @since 1.0
	 */
	public function getProcessorConfigFile($type) 
	{
		if (empty($type)) {
			throw new RuntimeException('Processor Type required');
		}
		
		$processor = Rb_EcommerceAPI::get_processor_instance($type);
		$xml_file = $processor->getLocation().'/'.$processor->getName().'.xml';
		
		return $xml_file;
	}

	/**
	 * 
	 * create new invoice
	 * @param PaycartCart $cart, invoice create on $cart bases
	 * @param Array $processorData
	 * @throws RuntimeException if cart is not exist or invoice creation fail
	 * 
	 * @return new created invoice id
	 */
	public function createInvoice(PaycartCart $cart, $processorData = Array())
	{
		$data  		= $this->buildInvoiceData($cart, $processorData);
		
		// we are not supporting recurring so always created invoice must be master invoice
		$is_master	= true;
				
		// get created invocie_id from RB-ecommerce API
		$invoiceId = Rb_EcommerceAPI::invoice_create($data, $is_master);
		
		if (!$invoiceId) {
			throw new RuntimeException(Rb_Text::_('COM_PAYCART_INVOICE_CREATION_FAIL'));
		}
		
		return $invoiceId;
	}
	
	/**
	 * 
	 * Build data according to invoice lib
	 * @param Paycart $cart : data getch from cart
	 * @param Array $processor, Payment Processor data
	 * 				Array (	'processor_type' 		=> _PAYMENT_PROCESSOR_NAME_,
	 * 						'processor_config'		=> _PAYMENT_PROCESSOR_CONFIG_,
	 * 						'processor_data'		=> _PAYMENT_PROCESSOR_DATA_
	 * 					  )
	 * @throws RuntimeException if cart is not exist
	 * 
	 * @return stdClass object 
	 */
	public function buildInvoiceData(PaycartCart $cart, $processor = Array())
	{
		$invoiceId = $cart->getInvoiceId();
		
		$invoiceData = Array();
		if ($invoiceId) {
			$invoiceData = $this->getInvoiceData($invoiceId);
		}
		
		$data  = new stdClass();
		
		//Auto set Property
//		$data->invoice_id 			= 0;
//		$data->master_invoice_id 	= 0;
//		$data->created_date 		= new Rb_Date();
//		$data->modified_date 		= new Rb_Date();

		$cartId = $cart->getId();
		
		if (!$cartId) {
			throw new RuntimeException(Rb_Text::_('COM_PAYCART_CART_MUST_REQUIRED_FOR_INVOICE'));
		}
		
		// Core-Property 
		// creating new entry
		if(empty($invoiceData)) {
			$data->object_id 			= $cartId;
			$data->object_type 			= get_class($cart);
			$data->status 				= self::STATUS_INVOICE_DUE;

			// @PCTODO:: get default value from global-config
			$prefix = PaycartFactory::getConfig()->get('invoice_serial_prefix', 'Paycart-');
			$suffix = $cartId;
			
			//@PCTODO :: Stuff
			$data->title 				= $prefix.$cartId;
			$data->serial 				= $prefix.$cartId;		// like Paycart-Invocie-524
			
//			$data->paid_date 			= $cart->getPaymentDate();		
//			$data->refund_date			= Rb_Date::getInstance('0000-00-00 00:00:00');
			$data->issue_date 			= Rb_Date::getInstance();
			//@NOTE: due date must be greater than created date 
			// And if have to "0000-00-00 00:00:00" then due-date means "infinite"   
			// @PCTODO :: issue#84
			$data->due_date 			= Rb_Date::getInstance('0000-00-00 00:00:00');
		}
		
		$data->buyer_id 			= $cart->getBuyer();
		$data->currency 			= $cart->getCurrency();	
		$data->subtotal 			= $cart->getTotal();
		$data->total 				= $cart->getTotal();
		$data->time_price 			= Array('time' => array('000000000000'), 'price' => Array($cart->getTotal()));		
		$data->notes 				= '';
		$data->params 				= '';
		
		// if cart is reversing
//		if ($cart->getReversalFor()) {
//			$data->refund_date		= $cart->getPaymentDate();
//		}
		
		// Hard-Coded Property
		$data->sequence 			= 0;		// Invoice-Counter
		$data->expiration_type 		= self::INVOICE_EXPIRATION_TYPE_FIXED;
		$data->recurrence_count 	= 0;
				
		$processorType = isset($invoiceData['processor_type'])? $invoiceData['processor_type'] : '';
		if ( isset($processor['processor_type'])) {
			$processorType 	= $processor['processor_type'];	
		}
		
		$processorConfig	= isset($invoiceData['processor_config'])? $invoiceData['processor_config'] : '';
		if ( isset($processor['processor_config'])) {
			$processorConfig 	= $processor['processor_config'];	
		}
		
//		$processorData		= isset($invoiceData['processor_data'])? $invoiceData['processor_data'] : '';
//		if ( isset($processor['processor_data']) ) {
//			$processorData 	= $processor['processor_data'];	
//		}
		
		$data->processor_type 		= $processorType;
		$data->processor_config		= $processorConfig;
//		$data->processor_data		= new Rb_Registry($processorData);
		
		return $data;
	}
	
	/**
	 * 
	 * Get invoice records
	 * @param Integer $invoiceId
	 * 
	 * @return Array('_COLUMN_NAME_' => '_COLUMN_VALUE_')
	 */
	public function getInvoiceData($invoiceId)
	{
		$records = Rb_EcommerceAPI::invoice_get(Array('invoice_id' => $invoiceId ));
		
		return $records;
	}
	
	/**
	 * 
	 * Get Transaction records
	 * @param Integer $invoiceId
	 * 
	 * @return Array(Object(COLUMN_NAME->COLUMN_VALUE))
	 */
	public function getTransactionData($invoiceId)
	{
		$records = Rb_EcommerceAPI::transaction_get_records(Array('invoice_id' => $invoiceId ));
		
		return $records;
	}
	
	/**
	 * 
	 * Delete existing invoice
	 * @param Integer $invoiceid :which will delete
	 *  
	 */
	public function deleteInvoice($invoiceId)
	{
		return Rb_EcommerceAPI::invoice_delete($invoiceId);
	}
	
	/**
	 * 
	 * Update invoice data
	 * @param Integer $invoiceId	: need to update this invoice
	 * @param Array $data			: updated data
	 * @param bool $refresh	 		: refresh invoice object
	 *
	 * @return int updated invoice's id
	 */
	private function _updateInvoice($invoiceId, $data, $refresh = false)
	{
		$invoiceId = Rb_EcommerceAPI::invoice_update($invoiceId, $data, $refresh);
		
		return $invoiceId;
	}
	

	/**
	 * 
	 * Update invoice
	 * @param PaycartCart $cart
	 * @param $processorData	=	Array['processor_type' => _GATEWAY_TYPE_ , 'processor_config' => _CONFIG_IN_STRING_]
	 * @param  $refresh
	 * @return int updated invoice's id 
	 */
	public function updateInvoice(PaycartCart $cart,  $processorData = Array(), $refresh = false)
	{
		// build invoice data
		$data = $this->buildInvoiceData($cart, $processorData);
		
		// update invoice data
		$invoiceId = $this->_updateInvoice($cart->getInvoiceId(), $data, $refresh);
		
		return $invoiceId;
	}
	
	/**
	 * 
	 * 
	 * @param PaycartCart $cart			
	 * @param Integer $processorId : _PROCESSOR_ID_
	 * 
	 */
	public function getBuildForm(PaycartCart $cart, $build_data = Array())
	{
		$invoice_data = $cart->getInvoiceData();
		
		$url_string 	= JUri::root()."index.php?option=com_paycart&view=cart&processor={$invoice_data['processor_type']}";
		
		$build_data['build_type'] = 'html';
		$build_data['notify_url'] = $url_string.'&task=notify';
		$build_data['cancel_url'] = $url_string.'&task=cancel';
		$build_data['return_url'] = $url_string.'&task=complete';
		
		
        $shippingAddress = $cart->getShippingAddress(true);
        $billingAddress  = $cart->getBillingAddress(true);
        
        $formatter  = PaycartFactory::getHelper('format');
        
        
 		if(!empty($billingAddress)){
		        $build_data['billing_address']['to'] 						= $billingAddress->getTo();
		        $build_data['billing_address']['phone_number'] 				= $billingAddress->getPhone();
		        $build_data['billing_address']['address'] 					= $billingAddress->getAddress();
		        $build_data['billing_address']['zipcode']					= $billingAddress->getZipcode();
		        $build_data['billing_address']['state'] 					= $formatter->state($billingAddress->getStateId());
		        
			$build_data['billing_address']['country']['isocode2'] ="";
			$build_data['billing_address']['country']['isocode3'] = "";

		        $billingCountryId = $billingAddress->getCountryId();
		        if(!empty($billingCountryId)){
			        $build_data['billing_address']['country']['isocode2'] 	= $formatter->country($billingCountryId,'isocode2');
			        $build_data['billing_address']['country']['isocode3'] 	= $billingCountryId;
		        }
		                
		        $build_data['billing_address']['city'] 						= $billingAddress->getCity();
		        $build_data['billing_address']['vat_number']				= $billingAddress->getVatnumber();
 		}
        
 		if(!empty($shippingAddress)){
	        $build_data['shipping_address']['to'] 						= $shippingAddress->getTo();
	        $build_data['shipping_address']['phone_number'] 			= $shippingAddress->getPhone();
	        $build_data['shipping_address']['address'] 					= $shippingAddress->getAddress();
	        $build_data['shipping_address']['zipcode'] 					= $shippingAddress->getZipcode();
	        $build_data['shipping_address']['state'] 					= $formatter->state($shippingAddress->getStateId());
	        
	        $shippingCountryId = $shippingAddress->getCountryId();
		$build_data['shipping_address']['country']['isocode2'] = "";
		$build_data['shipping_address']['country']['isocode3'] = "";
	        if(!empty($shippingCountryId)){
		        $build_data['shipping_address']['country']['isocode2'] 	= $formatter->country($shippingCountryId,'isocode2');
		        $build_data['shipping_address']['country']['isocode3'] 	= $shippingCountryId;
	        }
	        $build_data['shipping_address']['city']						= $shippingAddress->getCity();
	        $build_data['shipping_address']['vat_number'] 				= $shippingAddress->getVatnumber();
 		}
        
      
		
		$processResponseData = Rb_EcommerceApi::invoice_request('build', $cart->getInvoiceId(), $build_data);
		
		//Create new response and set required cart's stuff. 
		$response = new stdClass();
		$response->post_url	=	$processResponseData->data->post_url;
		$response->html		=	$processResponseData->data->form;
		$response->processorResponse = $processResponseData;

		return $response;
	}
	
	/**
	 * 
	 * Process payment on invoice
	 * @param $invoiceId	: Collect payment on this invoice id
	 * @param $paymentData	: Post data from payment form
	 * 
	 * @return stdClass 
	 */
	public function processPayment($invoiceId, $paymentData) 
	{
		// request function suffix
		$requestName = 'payment';
		
		$response = new stdClass();
		$response->is_error = false;
		$response->error_message = false;
		
		try {
			while (true) {
				
				$paymentResponseData = '';
				
				// Payment Start : request for payment    
				$paymentResponseData 	= Rb_EcommerceApi::invoice_request($requestName, $invoiceId, $paymentData);
				
				// Process Payement : After request need to Process payament data 
				$processResponseData	= Rb_EcommerceApi::invoice_process($invoiceId, $paymentResponseData);
	
				//PaycartFactory::getHelper('log')->add($processResponseData);
				
				// if you still need to process like fist you need to create user profile at payment-gatway then process for payment
				if($processResponseData->get('next_request', false) == false){
					break;
				}
	
				// our default moto is get payment, next_request_name will set by payment-processor
				$requestName = $processResponseData->get('next_request_name', 'payment');
			}
		} catch (Exception $e) {
			$response->is_error = true;
			$response->error_message = $e->get('message');
		}
		
		$reponse_status = $processResponseData->get('payment_status', Rb_EcommerceResponse::FAIL);
		
		// if dont have any error/exception then check transaction status
		if ( !($response->is_error) &&
			  ( self::STATUS_TRANSACTION_PAYMENT_FAIL == $reponse_status ||
				self::STATUS_TRANSACTION_SUBSCR_FAIL == $reponse_status ||
				self::STATUS_TRANSACTION_FAIL == $reponse_status
			  )
			) {
				$response->is_error = true;
				$response->error_message = $processResponseData->get('message');
			}
		
		
		//Create new response and set required cart's stuff. 
		$response->processorResponse = $processResponseData;

		return $response;
	}
	
	/**
	 * 
	 * Processe Notification 
	 * @param $invoiceId	: Processe Notification on this invoice id
	 * @param $responseData	: Post/Get data from notification
	 * 				$responseData->__get	: var {Get} data
	 * 				$responseData->__post	: var {post} data
	 * 				$responseData->data		: Contain actual data. (merge of {get and post} data)
	 * 
	 * @return stdClass 
	 */
	public function processNotification($invoiceId, $responseData) 
	{
		PaycartFactory::getHelper('log')->add($responseData);
		
		$processResponseData	= Rb_EcommerceApi::invoice_process($invoiceId, $responseData);
		
		PaycartFactory::getHelper('log')->add($processResponseData);
		
		//Create new response and set required cart's stuff. 
		$response = new stdClass();
		$response->processorResponse = $processResponseData;

		return $response;
	}
	
	/**
	 * 
	 * Get invoice id from notification
	 * @param stdClass $responseData :Post/Get data from notification
	 * 				$responseData->__get	: var {Get} data
	 * 				$responseData->__post	: var {post} data
	 * 				$responseData->data		: Contain actual data. (merge of {get and post} data)
	 * 
	 * @return integer invoice-id
	 */
	public function getNotificationInvoiceId($responseData) 
	{
		$invoiceId	= Rb_EcommerceAPI::invoice_get_from_response($responseData->data['processor'], $responseData);
		
		return $invoiceId;
	}	
	
	/**
	 * 
	 * Get invoice id from invoice number
	 * @param numeric $invoice_number :
	 * 
	 * @return integer invoice-id
	 */
	public function getInvoiceId($invoice_number) 
	{
		$invoiceId	= Rb_EcommerceAPI::invoice_get_id_from_number($invoice_number);
		
		return $invoiceId;
	}	
	
	/**
	 * return either the record of the given currencyId or all the existing records
	 */
	public function getCurrency($currencyId = null)
	{
		static $currencies = null;
		
		if(empty($currencies)){
			$currencies = Rb_EcommerceAPI::currency_get_records();
		}
		
		if(!empty($currencyId)){
			return $currencies[$currencyId];  
		}
		
		return $currencies;
	}
	
	/**
	 * 
	 * Invoke to manually process specific invoice 
	 * 
	 * @param unknown_type $invoice_id
	 * @param unknown_type $data
	 * 
	 * @return stdClass object with Rb_EcommerceResponse 
	 */
	public function processDirectPay($invoice_id, $data)
	{
		PaycartFactory::getHelper('log')->add($data);
		
		$processResponseData	= Rb_EcommerceAPI::invoice_directPay($invoice_id, $data);
		
		PaycartFactory::getHelper('log')->add($processResponseData);
		
		//Create new response and set required cart's stuff. 
		$response = new stdClass();
		$response->processorResponse = $processResponseData;

		return $response;
		
	}
	
	public function getStatusList()
	{
		return array(
            self::STATUS_INVOICE_NONE		=> JText::_('COM_PAYCART_INVOICE_STATUS_NONE'),
			self::STATUS_INVOICE_DUE 		=> JText::_('COM_PAYCART_INVOICE_STATUS_DUE'),
			self::STATUS_INVOICE_PAID		=> JText::_('COM_PAYCART_INVOICE_STATUS_PAID'),
			self::STATUS_INVOICE_REFUNDED	=> JText::_('COM_PAYCART_INVOICE_STATUS_REFUNDED'),
			self::STATUS_INVOICE_INPROCESS	=> JText::_('COM_PAYCART_INVOICE_STATUS_INPROCESS'),
			self::STATUS_INVOICE_EXPIRED	=> JText::_('COM_PAYCART_INVOICE_STATUS_EXPIRED')		
		);
	}
	
	static $xmlData = null;
	static public function getXml($processor_type = null)
	{	
		if(self::$xmlData === null)
		{
			$processors = Rb_EcommerceAPI::get_processors_list();
				
			foreach($processors as $key => $value){	
				$xml = dirname($value['location']).'/'.$key. '.xml'; ;
				if (file_exists($xml)) {
					$xmlContent = simplexml_load_file($xml);
				}
				else {
					$xmlContent = null;
				}
				foreach ($xmlContent as $element=> $value){
					self::$xmlData[$key][$element] = (string) $value;
				}
			}
		}
		
		if($processor_type !== null){
			return self::$xmlData[$processor_type];
		}
		
		return self::$xmlData;		
	}
}
