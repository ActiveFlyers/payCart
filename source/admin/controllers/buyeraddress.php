<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support_paycart@readybytes.in
* @author		mManish Trivedi
*/

// no direct access
defined( '_JEXEC' ) or	die( 'Restricted access' );

class PaycartAdminControllerBuyeraddress extends PaycartController 
{

	/**
	 * 
	 * Ajax Call create new attribute
	 */
	public function add() 
	{
		$buyerAddress = parent::save();
		return true;
	}
	
	public function edit() 
	{
		// Id required in View
		$this->getModel()->setState('buyer_id', JFactory::getApplication()->input->get('buyer_id', 0));
	}
	
	
	
}