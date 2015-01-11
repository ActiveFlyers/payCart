<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author		Puneet Singhal, rimjhim
*/

// no direct access
defined( '_JEXEC' ) or	die( 'Restricted access' );

class PaycartAdminControllerCart extends PaycartController 
{
	protected	$_defaultOrderingDirection = 'DESC';
	
	/**
	 * Json task : save new/existing shipment from the current cart
	 */
	public function saveShipment()
	{
		return true;
	}

	/**
	 * Json task : remove shipment from the current cart
	 */
	public function removeShipment()
	{
		return true;
	}
	
	/**
	 * 
	 * Task to approve cart
	 */
	public function approve() 
	{
		$cart_id = $this->input->get('cart_id', 0);
		
		if (!$cart_id) {
			$this->setredirect(
					'index.php?option=com_paycart&view=cart',
					JText::_('COM_PAYCART_ADMIN_WARNING_CART_ID_NOT_EXIST'),
					'warning'
					);
            return false;
        }
        
        $cart = PaycartCart::getInstance($cart_id);
        
        $cart->markApproved()->save();
        
        $this->setredirect(
					'index.php?option=com_paycart&view=cart&task=edit&id='.$cart_id,
					JText::_('COM_PAYCART_ADMIN_SUCCESS_CART_APPROVED')
					);
					
		return false;
	}
	
	/**
	 * 
	 * Task : Pay cart
	 * 
	 * task will perform according to action
	 * 	if action is pay_by_transaction-id
	 * 		- It means Payment successfully transfer by remotely
	 * 	
	 * 	if action is pay by any mean
	 * 		- Check if procesoor allow to process then do it.
	 * 		- Oherwise delete previous invoice and create new invoice with manual pay processor
	 * 
	 */
	public function pay()
	{
		$cart_id 	= $this->input->get('cart_id',	0);
		
		//cart_id must be reqired for pay task
		if (!$cart_id) {
			$this->setredirect(
					'index.php?option=com_paycart&view=cart',
					JText::_('COM_PAYCART_ADMIN_WARNING_CART_ID_NOT_EXIST'),
					'warning'
					);
            return false;
        }
		
        // get action
        $action		= $this->input->get('action', '');
		
		if (!$action) {
			$this->setredirect(
					'index.php?option=com_paycart&view=cart&task=edit&id='.$cart_id,
					JText::_('COM_PAYCART_ADMIN_WARNING_INVALID_ACTION'),
					'warning'
					);
            return false;
		}
        
		$cart = PaycartCart::getInstance($cart_id);
		
		//Case 1 : pay by transaction id
		if ('pay_by_transaction_id' == $action) {
			
			$gatewaytransaction_id		= $this->input->get('gatewaytransaction_id',	0);
		
		
			// if transaction id is not exits
			if (!$gatewaytransaction_id) {
				$this->setredirect(
						'index.php?option=com_paycart&view=cart&task=edit&id='.$cart_id,
						JText::_('COM_PAYCART_ADMIN_WARNING_CART_GATEWAYTRANSACTION_ID_NOT_EXIT'),
						'warning'
						);
	            return false;
	        }
	        
	        return $this->_payByTransactionId($cart_id, $gatewaytransaction_id);
		} 
		
		//Case 2 : pay by anymean
		if ('pay_by_anymean' == $action) {
			$note		= $this->input->get('notes',	0, 'RAW');
			
			if (!$note) {
				$this->setredirect(
						'index.php?option=com_paycart&view=cart&task=edit&id='.$cart_id,
						JText::_('COM_PAYCART_ADMIN_WARNING_CART_NOTES_NOT_EXIT'),
						'warning'
						);
	            return false;
	        }
			
	        return $this->_payByAnymean($cart_id, $note);
		}

		$this->setredirect(
					'index.php?option=com_paycart&view=cart&task=edit&id='.$cart_id,
					JText::_('COM_PAYCART_ADMIN_WARNING_INVALID_ACTION')
					);
					
		return false;
	}
	
	/**
	 * 
	 * Invoke to pay cart by any-mean.
	 * Check if procesoor allowe to process then do it.
	 * Oherwise delete previous invoice and create new invoice without processor
	 */
	private function _payByAnymean($cart_id, $note)
	{	
        $cart = PaycartCart::getInstance($cart_id);
        
        //set note on cart
        $cart->setNote($note);
        
        // invoice is not exist, it means cart total is zero then process cart
		$invoice_data = $cart->getInvoiceData();

        // invoice is not exist then process cart
        if ( empty($invoice_data) ) { 
        	return $cart->markPaid();
        }

        /**
         * If invoice exist then we will work on following assuption for processing 
         * 1#. We can't process it without processor permit.
         * 2#. If processing require without processor permission 
         * 		then 
         * 			Change delete previous invoice.
         * 		 	Create new invoice 
         * 			Attach hard-coded processor (ManualPay-Processor)
         * 
         */
        	
        $processor_config = $invoice_data['processor_config'];
        
        // Case 1# Processor allow to process invoice
        if ( isset($processor_config['require_admin_approval']) && $processor_config['require_admin_approval'] ) {
        	$cart->requestPayment(Array());
        } else {
        	// Caes 2# Process invoice with system will manually carete new invoice, transaction and pay it. 
        	// Previous invoice will be deleted 
        	$cart->markPaid_withManualPay();
        }
        
        //@PCTODO : make sure process successfully done otherwise raise error
        $cart->save();
        
        $this->setredirect(
					'index.php?option=com_paycart&view=cart&task=edit&id='.$cart_id,
					JText::_('COM_PAYCART_ADMIN_SUCCESS_CART_PAID')
					);
					
		return false;
	}

	/**
	 * 
	 * Invoke to paid cart by transaction id.
	 * 	- Payment successfully transfer by remotely.	 
	 * 
	 */
	private function _payByTransactionId($cart_id, $gatewaytransaction_id)
	{
		$cart = PaycartCart::getInstance($cart_id);
		
		$invoice_data = $cart->getInvoiceData();

        // invoice is not exist, it means cart total is zero then process cart
        if ( empty($invoice_data) ) { 
        	return $cart->markPaid();
        }
        
        
		//@PCTODO : make sure process successfully done otherwise raise error
       	$cart->markPaid_withGatewayTransaction($gatewaytransaction_id)->save();
        
        $this->setredirect(
					'index.php?option=com_paycart&view=cart&task=edit&id='.$cart_id,
					JText::_('COM_PAYCART_ADMIN_SUCCESS_CART_PAID')
					);
					
		return false;
	}
	
}