<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @contact		support+paycart@readybytes.in
* @author 		mManishTrivedi
*/

// no direct access
defined('_JEXEC') or die( 'Restricted access' );

/** 
 * Country Ajax View
 * @author Garima Agal
 */
require_once dirname(__FILE__).'/view.php';
class PaycartAdminAjaxViewTransaction extends PaycartAdminBaseViewTransaction
{
	public function refund()
	{
		// set by controller	
		$invoice_id = $this->get('invoice_id');	
		
		if(!$this->get('confirmed')){
			$this->_confirmRefund($invoice_id);
		}
		
		$this->_refundRequest($invoice_id);	
	}
	
	// Confirm refund request
	public function _confirmRefund($invoice_id)
	{
		$this->_setAjaxWinTitle(JText::_('COM_PAYCART_ADMIN_INVOICE_REFUND_WINDOW_TITLE'));
		
		$html =  Rb_HelperTemplate::renderLayout('paycart_spinner','',PAYCART_LAYOUTS_PATH);
		$this->_setAjaxWinBody($html.JText::_('COM_PAYCART_ADMIN_INVOICE_REFUND_CONFIRM_MESSAGE'));
	
		$this->_addAjaxWinAction(JText::_('COM_PAYCART_CONFIRM'), 'paycart.admin.transaction.refund.request('.$invoice_id.');', 'btn btn-success', 'id="paycart-invoice-refund-confirm-button"');
		$this->_addAjaxWinAction(JText::_('COM_PAYCART_ADMIN_CLOSE'), 'rb.ui.dialog.close();', 'btn');
		$this->_setAjaxWinAction();		
	
		$ajax = Rb_Factory::getAjaxResponse();
		$ajax->sendResponse();
	}

	// Send request for refund payment
	public function _refundRequest($invoice_id)
	{
		$records = Rb_EcommerceAPI::invoice_get(Array('invoice_id' => $invoice_id ,'object_type' => 'PaycartCart' ));
		if($records['status'] == PaycartHelperInvoice::STATUS_INVOICE_REFUNDED){
			$this->_setAjaxWinBody(JText::_("COM_PAYCART_ADMIN_INVOICE_REFUND_NOT_SUPPORTED"));
			$this->_addAjaxWinAction('close', 'rb.ui.dialog.close(); window.location.reload();', 'btn');
			
			$ajax = Rb_Factory::getAjaxResponse();
			$ajax->sendResponse();
			return true;
		}
		
		$req_response 	= Rb_EcommerceAPI::invoice_request('refund', $records['invoice_id'], array('amount' => $records['total']));
		$response 		= Rb_EcommerceAPI::invoice_process($invoice_id, $req_response);	

		$msg = JText::_('COM_PAYCART_ADMIN_INVOICE_REFUND_COMPLETED_SUCCESSFULLY');
		if($response->get('payment_status') != 'payment_refund'){
			$msg = JText::_('COM_PAYCART_ADMIN_INVOICE_ERROR_REFUND_ERROR');						
		}
		elseif($response instanceof Exception){
			$msg  = JText::_('COM_PAYCART_ADMIN_INVOICE_ERROR_REFUND_ERROR');
			$msg .= "<br/><div class='alert alert-error'>".$response->get('message')."</div>";
		}
		
//		$cart = PaycartCart::getInstance($records['object_id']);
//		if($response->get('payment_status') == 'payment_refund'){
//			$cart->markRefunded()->save();
//		}
		
		$this->_setAjaxWinTitle(JText::_('COM_PAYCART_ADMIN_INVOICE_REFUND_WINDOW_TITLE'));
		$this->_setAjaxWinBody($msg);
		
		$this->_addAjaxWinAction('close', 'rb.ui.dialog.close(); window.location.reload();', 'btn');
		$this->_setAjaxWinAction();	
		
		$ajax = Rb_Factory::getAjaxResponse();
		$ajax->sendResponse();

	}
}