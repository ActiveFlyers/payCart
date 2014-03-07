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


// require_once  only rb_ecommerce api. Avoid to load rb-ecmmoerce whole Package
$file = JPATH_ROOT."/plugins/system/rbsl/rb/pkg/ecommerce/api.php";
if (!JFile::exists($file)) {
	// fire exception
	throw new RuntimeException(Rb_Text::sprintf('COM_PAYCART_PACKAGE_NOT_EXIST', 'ECOMMERCE'));
}
include_once $file;

//rb_import('ecommerce');

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
	
	
	const STATUS_TRANSACTION_NONE              	= 	Rb_EcommerceResponse::NONE ;					// ''
	const STATUS_TRANSACTION_PAYMENT_COMPLETE 	= 	Rb_EcommerceResponse::PAYMENT_COMPLETE;		//'payment_complete';
	const STATUS_TRANSACTION_PAYMENT_REFUND 	= 	Rb_EcommerceResponse::PAYMENT_REFUND;			//'payment_refund';
	const STATUS_TRANSACTION_PAYMENT_PENDING 	= 	Rb_EcommerceResponse::PAYMENT_PENDING;		//'payment_pending';
	const STATUS_TRANSACTION_PAYMENT_FAIL		= 	Rb_EcommerceResponse::PAYMENT_FAIL;			//'payment_fail';
	
	const STATUS_TRANSACTION_SUBSCR_START		= 	Rb_EcommerceResponse::SUBSCR_START;			//'subscr_start';
	const STATUS_TRANSACTION_SUBSCR_CANCEL		= 	Rb_EcommerceResponse::SUBSCR_CANCEL;			//'subscr_cancel';
	const STATUS_TRANSACTION_SUBSCR_END			= 	Rb_EcommerceResponse::SUBSCR_END;				//'subscr_end';
	const STATUS_TRANSACTION_SUBSCR_FAIL		= 	Rb_EcommerceResponse::SUBSCR_FAIL;			//'subscr_fail';
	
	const STATUS_TRANSACTION_NOTIFICATION		= 	Rb_EcommerceResponse::NOTIFICATION;			//'notification';
	const STATUS_TRANSACTION_FAIL				= 	Rb_EcommerceResponse::FAIL;					//'fail';
	


	/**
	 * 
	 * Enter description here ...
	 * @param PaycartCart $cart
	 * @throws RuntimeException
	 */
	public function createInvoice(PaycartCart $cart)
	{
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
		$data->object_id 			= $cartId;
		$data->object_type 			= get_class($cart);
		$data->buyer_id 			= $cart->getBuyer();
		$data->currency 			= $cart->getCurrency();	
		$data->status 				= self::STATUS_INVOICE_DUE;
		$data->subtotal 			= $cart->getTotal();
		$data->total 				= $cart->getTotal();
		$data->time_price 			= Array('time' => array('000000000000'), 'price' => Array($cart->getTotal()));
		
		// @PCTODO:: get it from global-config
		$prefix = PaycartFactory::getConfig()->get('invoice_serial_prefix','');
		$suffix = $cartId;
		
		//@PCTODO :: Stuff
		$data->title 				= '';
		$data->serial 				= $prefix.$cartId;		// like Rb-Invocie-524
		
		$data->notes 				= '';
		$data->params 				= '';
		$data->paid_date 			= $cart->getPaymentDate();
		
		$data->refund_date			= Rb_Date::getInstance('0000-00-00 00:00:00');
		
		// if cart is reversing
		if ($cart->getReversalFor()) {
			$data->refund_date		= $cart->getPaymentDate();
		}
		
		$data->issue_date 			= Rb_Date::getInstance();
		//@NOTE: due date must be greater than created date And if have to "0000-00-00 00:00:00" then due-date means "infinite"   
		$data->due_date 			= Rb_Date::getInstance('0000-00-00 00:00:00');
		
		// Hard-Coded Property
		$data->sequence 			= 0;		// Invoice-Counter
		$data->expiration_type 		= self::INVOICE_EXPIRATION_TYPE_FIXED;
		$is_master					= true;
		$data->recurrence_count 	= 0;
				
		//@PCTODO:: Processor stuff
		$data->processor_type 		= '';
		$data->processor_config		= new Rb_Registry();
		$data->processor_data		= new Rb_Registry();
		
		// get created invocie_id from RB-ecommerce API
		$invoiceId = Rb_EcommerceAPI::invoice_create($data, $is_master);
		
		if (!$invoiceId) {
			throw new RuntimeException(Rb_Text::_('COM_PAYCART_INVOICE_CREATION_FAIL'), $code, $previous);
		}
		
		return $invoiceId;
	}
	
	
	/**
	 * 
	 * Enter description here ...
	 * @param $invoiceid
	 */
	public function deleteInvoice($invoiceId)
	{
		return Rb_EcommerceAPI::invoice_delete($invoiceId);
	}
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $invoiceId
	 * @param unknown_type $data
	 * @param unknown_type $refresh
	 */
	public function updateInvoice($invoiceId, $data, $refresh = false)
	{
		$invoiceId = Rb_EcommerceAPI::invoice_update($invoiceId, $data, $refresh);
		
		return $invoiceId;
	}
	
	
	/**
	 * 
	 * Enter description here ...
	 * @param unknown_type $invoiceId
	 * @param unknown_type $paymentData
	 */
	public function processPayment($invoiceId, $paymentData) 
	{
		// request function suffix
		$requestName = 'payment';
		
		while (true) {
			// Payment Start : request for payment    
			$paymentResponseData 	= Rb_EcommerceApi::invoice_request($requestName, $invoiceId, $paymentData);
			// Process Payement : After request need to Process payament data 
			$processResponseData	= Rb_EcommerceApi::invoice_process($invoiceId, $paymentResponseData);
						
			// if you still need to process like first you need to create user profile at payment-gateway then process for payment
			if($processResponseData->get('next_request', false) == false){
				break;
			}

			// our default moto is get payment {status : payment_complete}. next_request_name will set by payment-processor
			$requestName = $processResponseData->get('next_request_name', 'payment');
		}
		
		//@PCTODO: Create new response and set required cart's stuff. 
		// Like Payment is successfully then "payment_date" required , Set status according to ecommerce pkg
		$response = new stdClass();
		$response->processorResponse = $processResponseData;
		
		return $response;
	}
	
}
