<?php

/**
* @copyright	Copyright (C) 2009 - 2015 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		Paycart
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Transaction Html View
 * @author Rimjhim Jain
 */
require_once dirname(__FILE__).'/view.php';
class PaycartAdminHtmlViewTransaction extends PaycartAdminBaseViewTransaction
{	
	protected function _adminGridToolbar()
	{
		JToolbarHelper::editList();
		//JToolbarHelper::divider();
		//JToolbarHelper::deleteList(JText::_('COM_PAYCART_ENTITY_DELETE_CONFIRMATION'));
	}
	
	protected function _adminEditToolbar()
	{	
		JToolbarHelper::cancel();
	}
	
	function display($tpl= null)
	{
		$helper		= $this->getHelper('buyer');
        $statusList = Rb_EcommerceAPI::response_get_status_list();
        
        $records    = Rb_EcommerceAPI::transaction_get_object_type_records('PaycartCart');
        $InvoiceIds	= array();
        foreach ($records as $record){
        	$InvoiceIds[] = $record->invoice_id;
        }
        
		// if total of records is more than 0
		if(count($records) > 0) {
			$filters 	= array('invoice_id' => array(array('IN', '('.implode(",", $InvoiceIds).')')), 'object_type' => 'PaycartCart');
			$invoices   = Rb_EcommerceAPI::invoice_get_records($filters);
			
			$this->assign('statusList', $statusList);
      		$this->assign('invoices',$invoices);
			return $this->_displayGrid($records);
		}

		return $this->_displayBlank();
	}
	
	function _displayGrid($records)
	{
		$this->setTpl('grid');
		
		//do processing for default display page
		$model 		= $this->getModel();
		$recordKey  = $model->getTable()->getKeyName();
		$count		= Rb_EcommerceAPI::transaction_get_object_type_record_count('PaycartCart');

		//there is no way to update the existing pagiation object with these parameters, 
 		//so create new instance
		$pagination = new JPagination($count, $model->getState('limitstart'),$model->getState('limit'));
		
		$this->assign('records', $records);
		$this->assign('record_key', $recordKey);
		$this->assign('pagination',$pagination );
		$this->assign('filter_order', $model->getState('filter_order'));
		$this->assign('filter_order_Dir', $model->getState('filter_order_Dir'));
		$this->assign('limitstart', $model->getState('limitstart'));
		$this->assign('filters', $model->getState($model->getContext()));
		return true;
	}
	
	function edit($tpl=null,$itemId = null)
	{
		//load processor to load language files
		Rb_EcommerceAPI::get_processors_list();
		
		$itemId  		= ($itemId === null) ? $this->getModel()->getState('id') : $itemId ;
		$filter			= array('transaction_id' => $itemId);
		$transaction   	= Rb_EcommerceAPI::transaction_get($filter);
		
		$invoiceData	= PaycartFactory::getHelper('invoice')->getInvoiceData($transaction['invoice_id']);
		$cart			= PaycartCart::getInstance($invoiceData['object_id']);
		$buyer			= PaycartBuyer::getInstance($transaction['buyer_id']);
		
		// Show or hide refund button
		$refundable 		= false;
		if($transaction['payment_status'] == 'payment_complete'){ 
			if($invoiceData['status'] != Rb_EcommerceInvoice::STATUS_REFUNDED){
				$processor		= Rb_EcommerceAPI::get_processor_instance($invoiceData['processor_type']);
				$refundable 	= $processor->supportForRefund();
			}
		}
		
		$this->assign('transaction',$transaction);	
		$this->assign('cart', 		$cart);	
		$this->assign('buyer', 		$buyer);	
		$this->assign('statusList',	Rb_EcommerceAPI::response_get_status_list());
		$this->assign('refundable', $refundable);
		
		return true;	
	}
}