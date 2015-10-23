<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @contact		team@readybytes.in
* @author		Puneet Singhal
*/

// no direct access
defined('_JEXEC') or die( 'Restricted access' );

require_once dirname(__FILE__).'/view.php';
class PaycartAdminAjaxViewCart extends PaycartAdminBaseViewCart
{	
	public function cancel()
	{
		// set by controller	
		$cart_id = $this->get('cart_id');	
		
		if(!$this->get('confirmed')){
			$this->_confirmCancel($cart_id);
		}
		$this->_requestCancel($cart_id);	
	}
			
	
	public function _confirmCancel($cart_id){
			$this->assign('cart_id', $cart_id);
			$this->setTpl('cancel_confirmation');
			$this->_renderOptions = array('domObject'=>'pc-cancel-confirmation','domProperty'=>'innerHTML');
			return true;
	}
		
		
	public function initiateCancel($cart_id)
	{
		$cart 			= PaycartCart::getInstance($cart_id);
		$invoice_id		= $cart->getInvoiceId();
		
		if($cart->getStatus() != Paycart::STATUS_CART_CANCELLED){
			$cart->markCancel()->save();
		}
		
		if(!empty($invoice_id) && floatval($cart->getTotal()) !=  floatval(0)){		
			$filter			= array('invoice_id' => $cart->getInvoiceId());
			$transaction   	= Rb_EcommerceAPI::transaction_get($filter);
		}
		$msg 			= JText::sprintf("COM_PAYCART_CART_CANCEL_SUCCESSFULLY", $transaction['transaction_id']);
		$this->assign('msg', $msg);
		$this->setTpl('refund_done');
		$this->_renderOptions = array('domObject'=>'pc-cancel-confirmation','domProperty'=>'innerHTML');
		return true;
	}
}