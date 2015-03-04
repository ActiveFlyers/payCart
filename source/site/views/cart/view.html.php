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
		$cart_id 	= $this->getModel()->getId();
		$cart		= PaycartCart::getInstance($cart_id);

		// NOTE :: We are assuming status none will not available on complete task
		// take decision on payment {fail,pending,complete}
		
		// get invoice data
		$invoice = $cart->getInvoiceData();
		$buyer = $cart->getBuyer(true);		
		
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
		
		$payment_status = PaycartHelperInvoice::STATUS_TRANSACTION_PAYMENT_COMPLETE;
		if ( !empty($invoice)  && PaycartHelperInvoice::STATUS_INVOICE_PAID != $invoice['status'] ) {
			/* Case-2 : Payment Fail - When invoice have due status and transaction have fail status*/  
			if ( PaycartHelperInvoice::STATUS_INVOICE_DUE == $invoice['status']  ) {
				$payment_status = PaycartHelperInvoice::STATUS_TRANSACTION_PAYMENT_PENDING;
				// all failed transaction
				if ( !empty($transaction_details[PaycartHelperInvoice::STATUS_TRANSACTION_PAYMENT_FAIL] )) {
					$payment_status = PaycartHelperInvoice::STATUS_TRANSACTION_PAYMENT_FAIL;							
				}
			}
			
			/* Case-3 : Payment Inprocess - When invoice have in-process status and transaction have pending status  */ 
			if ( PaycartHelperInvoice::STATUS_INVOICE_INPROCESS == $invoice['status'] ) {
				$payment_status = PaycartHelperInvoice::STATUS_TRANSACTION_PAYMENT_PENDING;					
			}			
		}
	
		/* @var $cartHelper PaycartHelperCart */ 
		$cartHelper = PaycartFactory::getHelper('cart');
		$productCartParticulars = $cartHelper->getCartParticularsData($cart_id, Paycart::CART_PARTICULAR_TYPE_PRODUCT);		
		$promotionCartParticulars = $cartHelper->getCartParticularsData($cart_id, Paycart::CART_PARTICULAR_TYPE_PROMOTION);		
		$dutiesCartParticulars = $cartHelper->getCartParticularsData($cart_id, Paycart::CART_PARTICULAR_TYPE_DUTIES);
		$shippingCartParticulars = $cartHelper->getCartParticularsData($cart_id, Paycart::CART_PARTICULAR_TYPE_SHIPPING);
			
		// set cart total
		$cartObject 			= (object)$cart->toArray();
		$cartObject->total 		= $cart->getTotal();
		
		// set promotion amount
		$cartObject->promotion 	= 0;
		foreach($promotionCartParticulars as $particular){
			$cartObject->promotion += $particular->total;
		}
		
		// set duties amount
		$cartObject->duties 	= 0;
		foreach($dutiesCartParticulars as $particular){
			$cartObject->duties += $particular->total;
		}
		
		$cartObject->subtotal 	= 0;
		$products = array();
		foreach($productCartParticulars as $particular){
			$cartObject->subtotal += $particular->total;
			$products[$particular->particular_id] = PaycartProduct::getInstance($particular->particular_id);
		}
		
		// set shipping cost
		$cartObject->shipping = 0;
		foreach($shippingCartParticulars as $particular){
			$cartObject->shipping += $particular->total;
		}
		
		// get expeted delivery of complete cart, it will be used when no shipment is created for any item
		$estimatedDeliveryDate = null;	
		if($payment_status == PaycartHelperInvoice::STATUS_TRANSACTION_PAYMENT_COMPLETE || $payment_status == PaycartHelperInvoice::STATUS_TRANSACTION_PAYMENT_PENDING){			
			foreach($shippingCartParticulars as $particular){
				$params = $particular->params;
				$date = new Rb_Date($params->delivery_date);
				if(empty($estimatedDeliveryDate)){
					$estimatedDeliveryDate = $date;
					continue;
				}
				
				$estimatedDeliveryDate = ($estimatedDeliveryDate->toUnix() < $date->toUnix()) ? $date : $estimatedDeliveryDate;
			}
		}
		
		$this->assign('estimatedDeliveryDate', $estimatedDeliveryDate);
		$this->assign('track_url', $cart->getOrderUrl(false));
		$this->assign('buyer', $buyer);
		$this->assign('invoice', (object)$invoice);
		$this->assign('cart', $cartObject);			
		$this->assign('products', $products);
		$this->assign('invoiceStatusList', PaycartFactory::getHelper('invoice')->getStatusList());
		$this->assign('productCartParticulars', $productCartParticulars);
		$this->assign('shippingAddress', (object)$cart->getShippingAddress(true)->toArray());			
		$this->assign('transaction_details', $transaction_details);
		$this->assign('payment_status', $payment_status);

		$this->setTpl('complete');
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