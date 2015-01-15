<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	front-end
* @contact		support+paycart@readybytes.in
* @author		mManishTrivedi, Rimjhim Jain
*/

defined( '_JEXEC' ) or	die( 'Restricted access' );
/**
 * 
 * Cart Html View
 * @author Manish
 *
 */
require_once dirname(__FILE__).'/view.php';

class PaycartsiteHtmlViewcart extends PaycartSiteBaseViewcart
{
	function complete()
	{
		$cart_id 	= $this->input->get('cart_id', 0);		
		$cart		= PaycartCart::getInstance($cart_id);

		// NOTE :: We are assuming status none will not available on complete task
		// take decision on payment {fail,pending,complete}
		
		// get invoice data
		$invoice = $cart->getInvoiceData();
		
		/**
		 * Case-1 : Payment Successfully Complete on Cart 
		 * 		- When we dont have invoice. On zero cart-total, may be you will not create any invoice
		 * 		- When invoice have paid status 
		 */  
		
		$message				= 	'COM_PAYCART_CART_PAYMENT_COMPLETE';
		$transaction_detail 	=	Array();
		
		if ( !empty($invoice)  && PaycartHelperInvoice::STATUS_INVOICE_PAID != $invoice['status'] ) {
			$transaction_data = $cart->getTransactionData();
			//	Collect transaction details
			$transaction_details 	=	Array();
			foreach ($transaction_data as $data ) {
				switch($data->payment_status) {
					case PaycartHelperInvoice::STATUS_TRANSACTION_PAYMENT_FAIL :
					case PaycartHelperInvoice::STATUS_TRANSACTION_SUBSCR_FAIL :
					case PaycartHelperInvoice::STATUS_TRANSACTION_FAIL:
						//collect all fail transaction  
						$transaction_details[PaycartHelperInvoice::STATUS_TRANSACTION_PAYMENT_FAIL][] = $data;
						break;
					case PaycartHelperInvoice::STATUS_TRANSACTION_PAYMENT_PENDING :
					case PaycartHelperInvoice::STATUS_TRANSACTION_SUBSCR_START :
						// Collect all pending
						$transaction_details[PaycartHelperInvoice::STATUS_TRANSACTION_PAYMENT_PENDING][] = $data;
						break;
					default:
						// Collect other status as usual
						$transaction_details[$data->payment_status][] = $data;
						break;
				}
			}
			
			/**
			 * Case-2 : Payment Fail 
			 * 		- When invoice have due status and transaction have fail status
			 */  
			if ( PaycartHelperInvoice::STATUS_INVOICE_DUE == $invoice['status']  ) {
				$message = 'COM_PAYCART_CART_PAYMENT_DUE';
				// all failed transaction
				if ( !empty($transaction_details[PaycartHelperInvoice::STATUS_TRANSACTION_PAYMENT_FAIL] )) {
					$message = 'COM_PAYCART_CART_PAYMENT_FAIL';
					$transaction_detail = $transaction_details[PaycartHelperInvoice::STATUS_TRANSACTION_PAYMENT_FAIL];
				}
			}
			
			/**
			 * Case-3 : Payment Inprocess 
			 * 		- When invoice have in-process status and transaction have pending status  
			 */ 
			if ( PaycartHelperInvoice::STATUS_INVOICE_INPROCESS == $invoice['status'] ) {
				$message = 'COM_PAYCART_CART_PAYMENT_INPROCESS';
				//Pending payment with transactions
				if ( !empty($transaction_details[PaycartHelperInvoice::STATUS_TRANSACTION_PAYMENT_PENDING] ) ) {
					$transaction_detail = $transaction_details[PaycartHelperInvoice::STATUS_TRANSACTION_PAYMENT_PENDING];
				}
			}			
		}

		$this->setTpl('complete');
		$this->assign('message', $message);
		$this->assign('transaction_detail', $transaction_detail);
		$this->assign('cart_id', $cart_id);
		
		return true;
	}
	
	/**
	 * Display cart details
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_View::display()
	 */
	public function display($tpl = NULL)
	{	
		return parent::_assignTmplVars();
	}
	
	public function checkout()
	{
		$this->assign('is_platform_mobile', PaycartFactory::getApplication()->client->mobile);
		$this->assign('cart', isset($this->cart) ? $this->cart : '');
		return true;
	}
}