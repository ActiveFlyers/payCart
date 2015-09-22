<?php

/**
* @copyright        Copyright (C) 2009 - 2015 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license          GNU/GPL, see LICENSE.php
* @package          PAYCART
* @subpackage       Back-end
* @contact          support+paycart@readybytes.in 
*/
defined('_JEXEC') or die( 'Restricted access' );

/**
 * Transaction Admin Controller
 * 
 * @since 1.0.0
 *  
 * @author Rimjhim Jain
 */
class PaycartAdminControllerTransaction extends PaycartController
{	
	protected	$_defaultOrderingDirection = 'DESC';
	
	public function getModel($name = '', $prefix = '', $config = array())
	{
        return Rb_EcommerceAPI::transaction_get_model();
	}
	
	function _remove($itemId=null, $userId=null)
	{
		return Rb_EcommerceAPI::transaction_delete_record($itemId);	
	}
	
	public function refund()
	{
		$invoiceId	= $this->input->get('invoice_id');
		$confirmed 	= $this->input->getBool('confirmed', 0);
		$this->getView()->assign('confirmed', $confirmed);
		$this->getView()->assign('invoice_id', $invoiceId);
		
		return true;
	}
}