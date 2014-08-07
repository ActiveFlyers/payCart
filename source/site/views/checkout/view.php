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
	protected $message 		= '';
	protected $message_type = '';
	
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
		// message type set by controller
		if ($this->message_type ) {
			$this->setTpl('error');
			return true;
		}
		
		if($this->step_ready == Paycart::CHECKOUT_STEP_ADDRESS) {
			$this->prepare_step_address();
		}
		
		return true;
	}
	
	/**
	 * 
	 * Invoke to prepare confirm step data for template
	 */
	protected function prepare_step_confirm()
	{
		
		$product_particular	=	Array();
		$product_total	 	= 0;
		$product_quantity	= 0;
		$product_media		=	Array();
		foreach ($this->cart->getCartparticulars(paycart::CART_PARTICULAR_TYPE_PRODUCT) as  $key => $particular) {
			/* @var $particular Paycartcartparticular */
			$product_particular[] = $particular->toObject();
			$product_total 	 +=	$particular->getTotal(true);
			$product_quantity 	+=	$particular->getQuantity();
			
			// get product media
			$product_id = $particular->getParticularId();
			
			$product_media[$product_id]	=	PaycartProduct::getInstance($product_id)->getCoverMedia();
		}
		
		$shipping_particular	=	Array();
		$shipping_total			=	0;
		foreach ($this->cart->getCartparticulars(paycart::CART_PARTICULAR_TYPE_SHIPPING) as  $key => $particular) {
			/* @var $particular Paycartcartparticular */
			$shipping_particular[] = $particular->toObject();
			$shipping_total		  += $particular->getTotal(true);
		}
		
		$promotion_particular	=	Array();
		$promotion_total		=	0;
		foreach ($this->cart->getCartparticulars(paycart::CART_PARTICULAR_TYPE_PROMOTION) as  $key => $particular) {
			/* @var $particular Paycartcartparticular */
			$promotion_particular[] = $particular->toObject();
			$promotion_total		  += $particular->getTotal(true);
		}
		
		$duties_particular	=	Array();
		$duties_total		=	0;
		foreach ($this->cart->getCartparticulars(paycart::CART_PARTICULAR_TYPE_DUTIES) as  $key => $particular) {
			/* @var $particular Paycartcartparticular */
			$duties_particular[] = $particular->toObject();
			$duties_total  	    += $particular->getTotal(true);
		}
		
		// set all particular details
		$this->assign('product_total',			$product_total);
		$this->assign('product_quantity',		$product_quantity);
		$this->assign('shipping_total',			$shipping_total);
		$this->assign('promotion_total',		$promotion_total);
		$this->assign('duties_total',			$duties_total);
		
		$this->assign('product_media',			$product_media);
		$this->assign('product_particular',		$product_particular);
		$this->assign('shipping_particular',	$shipping_particular);
		$this->assign('promotion_particular',	$promotion_particular);
		$this->assign('duties_particular',		$duties_particular);
	}
	
	/**
	 * Prepare address step
	 */
	protected function prepare_step_address()
	{
		//contain all buyer address 
		$buyer_addresses	=	Array();
		
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
	 * 
	 * Setup Payment Collection page
	 */
	protected function prepare_step_payment() 
	{
		//available payment gateway
		$payment_gateway	=	PaycartFactory::getModel('paymentgateway')->loadRecords(Array('published' => 1 ));
		
		// @PCFIXME :: get default payment gateway then get gateway html and assign here
		$payment_gateway_html	=	'';
		
		$this->assign('payment_gateway', $payment_gateway);
		$this->assign('payment_gateway_html', $payment_gateway_html);
	}
	
	
	/**
	 * (non-PHPdoc)
	 * @see /plugins/system/rbsl/rb/rb/Rb_AbstractView::_basicFormSetup()
	 */
	protected function _basicFormSetup($task)
	{
		$this->assign('message', 		$this->message);
		
		// message type set by controller
		if ($this->message_type ) {
			$this->assign('message_type', 	$this->message_type);
			return true;
		}
			
		// get std class object for cart
		$cart = (object)$this->cart->toArray();
		
		// initialization
		$buyer = $billing_address = $shipping_address = new stdClass();
		
		if ( isset($cart->params['buyer'])) {
			$buyer = (object)$cart->params['buyer'];
		}
		
		if ( isset($cart->params['billing_address'])) {
			$billing_address	=	(object)$cart->params['billing_address'];
		}
			
		if ( isset($cart->params['shipping_address'])) {
			$shipping_address	=	(object)$cart->params['shipping_address'];
		}
		
		//default value 
		$billing_to_shipping	=	true;
		if ( isset($cart->params['billing_to_shipping'])) {
			$billing_to_shipping	=	(bool)$cart->params['billing_to_shipping'];
		}
		
		$this->assign('currency_id', $this->cart->getCurrency());
		
		$this->assign('is_platform_mobile', PaycartFactory::getApplication()->client->mobile);

		//setup basic stuff like steps
		$this->assign('available_steps', 		$this->getSteps());		
		$this->assign('step_ready',				$this->step_ready);
		$this->assign('buyer', 					$buyer);
		$this->assign('billing_address', 		$billing_address);
		$this->assign('shipping_address', 		$shipping_address);
		$this->assign('billing_to_shipping', 	$billing_to_shipping);
		$this->assign('cart', 					$cart);
		$this->assign('cart_total', 			$this->cart->getTotal());
		
		return true;
	}
	
	/**
	 * 
	 * Invoke to get all available steps
	 * 
	 * @return all available steps
	 */
	protected  function getSteps() 
	{
		$steps 				=  Array();
		
		//Step :: Address
		$step  = new stdClass();
		$step->icon		= 'fa-user';
		$step->class	= 'pc-checkout-step-'.Paycart::CHECKOUT_STEP_LOGIN;
		$step->title	=  JText::_('COM_PAYCART_CHECKOUT_STEP_LOGIN');
		$step->onclick	= ($this->cart->getIsGuestCheckout()) ? 'paycart.checkout.confirm.edit.email();' : false;
		$step->isActive	= ($this->step_ready == Paycart::CHECKOUT_STEP_LOGIN)? 1 : 0;
		$steps[0]= $step;
		
		//Step :: Address
		$step  = new stdClass();
		$step->icon = 'fa-truck';
		$step->class = 'pc-checkout-step-'.Paycart::CHECKOUT_STEP_ADDRESS;
		$step->title	=  JText::_('COM_PAYCART_CHECKOUT_STEP_ADDRESS');
		$step->onclick	= 'paycart.checkout.confirm.edit.address()';
		$step->isActive	= ($this->step_ready == Paycart::CHECKOUT_STEP_ADDRESS)? 1 : 0;
		$steps[1]= $step;

		//Step :: Order-Confirm
		$step  = new stdClass();
		$step->icon = 'fa-thumbs-up';
		$step->class = 'pc-checkout-step-'.Paycart::CHECKOUT_STEP_CONFIRM;
		$step->title	=  JText::_('COM_PAYCART_CHECKOUT_STEP_CONFIRM');
		$step->onclick	= ' paycart.checkout.confirm.edit.confirm()';
		$step->isActive	= ($this->step_ready == Paycart::CHECKOUT_STEP_CONFIRM)? 1 : 0;
		$steps[2]= $step;
		
		//Step :: Payment
		$step  = new stdClass();
		$step->icon = 'fa-credit-card';
		$step->class = 'pc-checkout-step-'.Paycart::CHECKOUT_STEP_PAYMENT;
		$step->title	=  JText::_('COM_PAYCART_CHECKOUT_STEP_PAYMENT');
		$step->onclick	= false;
		$step->isActive	= ($this->step_ready == Paycart::CHECKOUT_STEP_PAYMENT)? 1 : 0;
		$steps[3]= $step;
		
		return $steps;
	}
}