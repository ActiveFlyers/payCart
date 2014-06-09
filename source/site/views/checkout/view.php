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
	
	protected function _process()
	{
		//$ajax_response = PaycartFactory::getAjaxResponse();
		switch ($this->step_ready) 
		{
			case Paycart::CHECKOUT_STEP_ADDRESS:
				$this->prepare_step_address();
				break;
				
			case Paycart::CHECKOUT_STEP_CONFIRM :
				$this->prepare_step_confirm();
				break;
			
			default:
				;
			break;
		}
		;
	}
	
	protected function prepare_step_confirm()
	{
		// get attached address at cart
		$billing_address	= $this->cart->getBillingAddress(true);
		$shipping_address 	= $this->cart->getShippingAddress(true);
		
		// get all particular details
		$product_particular 	= $this->cart->getCartparticulars(paycart::CART_PARTICULAR_TYPE_PRODUCT);
		$shipping_particular 	= $this->cart->getCartparticulars(paycart::CART_PARTICULAR_TYPE_SHIPPING);
		$promotion_particular	= $this->cart->getCartparticulars(paycart::CART_PARTICULAR_TYPE_PROMOTION);
		$duties_particular		= $this->cart->getCartparticulars(paycart::CART_PARTICULAR_TYPE_DUTIES);
		
		$this->assign('billing_address',	$billing_address->toArray());
		$this->assign('shipping_address',	$shipping_address->toArray());
		
		
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
	
	/**
	 * (non-PHPdoc)
	 * @see /plugins/system/rbsl/rb/rb/Rb_AbstractView::_basicFormSetup()
	 */
	protected function _basicFormSetup($task)
	{
		//setup basic stuff like steps		
		$this->assign('step_ready', $this->step_ready);
		$this->assign('buyer', 		$this->cart->getBuyer(true));
		$this->assign('cart', 		(object)$this->cart->toArray());
		
		return true;
	}
}