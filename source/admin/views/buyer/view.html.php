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

/** 
 * Buyer Html View
 */
require_once dirname(__FILE__).'/view.php';
class PaycartAdminViewBuyer extends PaycartAdminBaseViewBuyer
{	
	protected function _adminEditToolbar()
	{
		Rb_HelperToolbar::cancel();
	}
	
	protected function _adminGridToolbar()
	{
		// no need any toolbar button
	}
	
	/**
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_View::edit()
	 */
	public function edit($tpl=null) {
		
		$buyer_id	=  $this->getModel()->getState('id');
		$buyer		=  PaycartBuyer::getInstance($buyer_id);

		$filter = Array('buyer_id' => $buyer_id);

		$addresses = PaycartFactory::getModel('buyeraddress')->loadRecords($filter);
		
		$this->assign('form',  $buyer->getModelform()->getForm($buyer));
		$this->assign('billing_address_id',		$buyer->getBillingAddress());
		$this->assign('shipping_address_id',	$buyer->getShippingAddress());
		$this->assign('addresses', $addresses);
		
		return parent::edit($tpl);
	}
	
}
