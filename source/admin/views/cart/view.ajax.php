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
	public function cancelCart()
		{
			$cart_id	=  $this->getModel()->getState('id');
			$this->assign('cart_id', $cart_id);
			$this->setTpl('cancel_confirmation');
			$this->_renderOptions = array('domObject'=>'pc-cancel-confirmation','domProperty'=>'innerHTML');
			return true;
		}
		
		
	public function initiateCancel()
	{
		$cart_id		= $this->input->get('cart_id',0);
		$cart 			= PaycartCart::getInstance($cart_id);
		$invoice_id		= $cart->getInvoiceId();
		
		if($cart->getStatus() != Paycart::STATUS_CART_CANCELLED){
			$cart->markCancel()->save();
		}

		$filter			= array('invoice_id' => $cart->getInvoiceId());
		$transaction   	= Rb_EcommerceAPI::transaction_get($filter);
		
		$msg 			= JText::sprintf("COM_PAYCART_CART_CANCEL_SUCCESSFULLY", $transaction['transaction_id']);
		//$msg = "Cancelled done successfully. You need to go to transaction to refund the amount. Go to:- <a href='index.php?option=com_paycart&view=transaction&task=edit&id=".$transaction['transaction_id']."'>Transaction</a>";
		$this->assign('msg', $msg);
		$this->setTpl('refund_done');
		$this->_renderOptions = array('domObject'=>'pc-cancel-confirmation','domProperty'=>'innerHTML');
		return true;
		
		$ajax = Rb_Factory::getAjaxResponse();
		$ajax->sendResponse();
	}
}