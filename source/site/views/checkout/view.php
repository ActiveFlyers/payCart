<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	front-end
* @contact		support+paycart@readybytes.in
* @author		mManishTrivedi
*/

defined( '_JEXEC' ) or	die( 'Restricted access' );
/**
 * 
 * Checkout Base View
 * @author Manish
 */
class PaycartSiteBaseViewCheckout extends PaycartView
{
	protected $step_ready = Paycart::CHECKOUT_STEP_LOGIN;
	
	/**
	 * @var PaycartCart
	 */
	protected $cart;
	
	
	/**
	 * 
	 * Enter description here ...
	 */
	public function init()
	{
		if($this->step_ready == Paycart::CHECKOUT_STEP_ADDRESS) {
			$this->prepare_step_address();
		}
		
		return true;
	}
	
	/**
	 * 
	 * Enter description here ...
	 */
	public function process()
	{
		switch ($this->step_ready) 
		{
			case Paycart::CHECKOUT_STEP_ADDRESS:
				$this->prepare_step_address();
			;
			break;
			
			default:
				;
			break;
		}
		
		
		$this->setTpl($this->step_ready);
		return true;
	}
	
	/**
	 * Prepare address step
	 */
	protected function prepare_step_address()
	{
		
		//no need to get address on guest-checkout
		if(!$this->cart->getIsGuestCheckout()) {
			// if user is login then get buyer address
			$buyer_addresses		= PaycartFactory::getModel('buyeraddress')->loadRecords(Array('buyer_id' => $this->cart->getBuyer()));
		}
		
		// address on cart
		$shipping_address_id	= $this->cart->getShippingAddress();
		$billing_address_id		= $this->cart->getBillingAddress();
		
		$this->assign('shipping_address_id',	$shipping_address_id);
		$this->assign('billing_address_id',		$billing_address_id);
		$this->assign('buyer_addresses',		$buyer_addresses);
		
	}
	
	
	protected function _basicFormSetup($task)
	{
		//setup basic stuff like steps		
		$this->assign('step_ready', $this->step_ready);
		 
		return true;
	}

}