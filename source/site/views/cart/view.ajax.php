<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	front-end
* @contact		support+paycart@readybytes.in
* @author		mManishTrivedi, Rimjhim Jain
*/

defined( '_JEXEC' ) or	die( 'Restricted access' );

require_once dirname(__FILE__).'/view.php';

class PaycartSiteAjaxViewCart extends PaycartSiteBaseViewCart
{
	public function display($tpl = NULL)
	{	
		parent::_assignTmplVars();
		
		$this->setTpl('products');
		$this->_renderOptions = array('domObject'=>'pc-cart-products','domProperty'=>'innerHTML');
		return true;
	}
	
	/**
	 * add product to cart
	 */
	public function addProduct()
	{
		// @PCTODO :: Convey error from controller and  handle here 

		return false;
	}
	
	public function login()
	{
		$this->_renderOptions = array('domObject'=>'pc-checkout-step-html','domProperty'=>'innerHTML');
		
		// if cart is invalid, then do nothing
		$isCartValid = $this->get('isCartValid', true);
		if(!$isCartValid){
			return true;
		}
		
		$errors = $this->get('errors', array());
		if(!empty($errors)){
			$ajax = PaycartFactory::getAjaxResponse();
			$ajax->addScriptCall('paycart.cart.login.error', $errors);
			$ajax->sendResponse();
		}
		
		$this->_setupCartVars();
		
		$this->setTpl('login');
		
		return true;
	}
	
	public function address()
	{
		$this->_renderOptions = array('domObject'=>'pc-checkout-step-html','domProperty'=>'innerHTML');
		
		// if cart is invalid, then do nothing
		$isCartValid = $this->get('isCartValid', true);
		if(!$isCartValid){
			return true;
		}
		
		$errors = $this->get('errors', array());
		if(!empty($errors)){
			$ajax = PaycartFactory::getAjaxResponse();
			$ajax->addScriptCall('paycart.cart.address.error', $errors);
			$ajax->sendResponse();
		}
		
		//contain all buyer address 
		$buyer_addresses	=	Array();
		
		//no need to get address on guest-checkout
		if(!$this->cart->isGuestcheckout()) {
			// if user is login then get buyer address
			$buyer_addresses		= PaycartFactory::getModel('buyeraddress')->loadRecords(Array('buyer_id' => $this->cart->getBuyer(), 'is_removed' => false));
		}
		
		// address on cart
		$shipping_address_id	= $this->cart->getShippingAddress();
		$billing_address_id		= $this->cart->getBillingAddress();
		
		// set default address id if exists
		$default_address_id = $this->cart->getBuyer(true)->getDefaultAddress();
		if($default_address_id && isset($buyer_addresses[$default_address_id])){
			if(empty($billing_address_id)){
				$billing_address_id = $default_address_id;
				$this->cart->setParam('billing_address', PaycartBuyeraddress::getInstance($default_address_id)->toArray());				
			}
			
			if(empty($shipping_address_id)){
				$shipping_address_id = $default_address_id;
				$this->cart->setParam('shipping_address', PaycartBuyeraddress::getInstance($default_address_id)->toArray());
			}
		}
		
		$this->_setupCartVars();
		
		$this->assign('shipping_address_id',	$shipping_address_id);
		$this->assign('billing_address_id',		$billing_address_id);
		$this->assign('buyer_addresses',		$buyer_addresses);
		
		$this->setTpl('address');
		
		return true;
	}
	
	protected function _setupCartVars()
	{			
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

		//setup basic stuff		
		$this->assign('available_steps', 		$this->_getSteps());	
		$this->assign('active_task', 			$this->getTask());	
		$this->assign('buyer', 					$buyer);
		$this->assign('billing_address', 		$billing_address);
		$this->assign('shipping_address', 		$shipping_address);
		$this->assign('billing_to_shipping', 	$billing_to_shipping);
		$this->assign('cart', 					$cart);
		$this->assign('cart_total', 			$this->cart->getTotal());
		
		return true;
	}
	
	public function confirm()
	{	
		$this->_renderOptions = array('domObject'=>'pc-checkout-step-html','domProperty'=>'innerHTML');
		
		// if cart is invalid, then do nothing
		$isCartValid = $this->get('isCartValid', true);
		if(!$isCartValid){
			return true;
		}
		
		$errors = $this->get('errors', array());
		if(!empty($errors)){
			$ajax = PaycartFactory::getAjaxResponse();
			$ajax->addScriptCall('paycart.cart.confirm.error', $errors);
			$ajax->sendResponse();
		}
		
		$this->_setupCartVars();
		
		$product_particular	  = Array();
		$shipping_particular  = Array();
		$promotion_particular = Array();
		$duties_particular	  = Array();
		$shipping_total		  = 0;
		$promotion_total	  = 0;
		$duties_total         = 0;
		$product_total	 	  = 0;
		$product_quantity	  = 0;
		$product_media		  = Array();
		$usageDetails		  = Array();
		
		// collect all particular details
		$carparticulars['product'] 		= $this->cart->getCartparticulars(Paycart::CART_PARTICULAR_TYPE_PRODUCT);
		$carparticulars['promotion'] 	= $this->cart->getCartparticulars(Paycart::CART_PARTICULAR_TYPE_PROMOTION);
		$carparticulars['duties'] 		= $this->cart->getCartparticulars(Paycart::CART_PARTICULAR_TYPE_DUTIES);
		$carparticulars['shipping']  	= $this->cart->getCartparticulars(Paycart::CART_PARTICULAR_TYPE_SHIPPING);
		
		$product_quantity = 0;
		foreach ($carparticulars as $name => $particulars){
			$tmppraticulars = array();
			$type_total = 0;
			
			foreach($particulars as $particular){
				$usage = $particular->getUsage();
				
				// IMP :: This is patch for promotion and duties type processors
				// as we need to pass true to get actual total
				$total = $particular->getTotal(true);
				$particular = $particular->toObject();
				$particular->total = $total;
				
				//build media and total quantity when type of particular is product
				if($particular->type == Paycart::CART_PARTICULAR_TYPE_PRODUCT){
					$product_media[$particular->particular_id]	=	PaycartProduct::getInstance($particular->particular_id)->getCoverMedia();
					$product_quantity += $particular->quantity;
				}
				
				foreach($usage as $use){
					$key = $particular->type.'-'.$particular->particular_id;
					if(!isset($usageDetails[$key])){
						$usageDetails[$key] 		=  array();
					}					
					if(!isset($usageDetails[$key][$use->rule_type])){
						$usageDetails[$key][$use->rule_type] = array();
					}
					$usageDetails[$key][$use->rule_type][] = $use->message;
				}
				
				$tmppraticulars[$particular->particular_id] = $particular;
				$type_total += $total;
			}
			${$name.'_total'} = $type_total;
			${$name.'_particular'} = $tmppraticulars;
		}
		
		// applied promotion code
		$promotions = $this->cart->getParam('promotions', Array());
		
		$applied_promotion_code = PaycartFactory::getHelper('cart')->getAppliedPromotionCode(array_keys($promotion_particular), $promotions);	
		
		//get available shipping options
		$shippingOptions  = $this->_getShippingOptions();
		$default_shipping = $this->cart->getParam('shipping');
		
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
		
		$this->assign('usageDetails', 	$usageDetails);
		$this->assign('shipping_options', $shippingOptions);
		$this->assign('default_shipping', $default_shipping);
		
		//set click action on proceed to payment button
		$shipping  = $this->cart->getParam('shipping',null);
		$condition = ( empty($shipping) || empty($shippingOptions) || !array_key_exists($shipping, $shippingOptions));
		$this->assign('clickActionOnProceed',$condition?'onClick="return false"':'onClick="return paycart.cart.confirm.do();"');
		$this->assign('isDisabled',$condition?'disabled':'');
		
		// applied promotion code
		$this->assign('applied_promotion_code', $applied_promotion_code);
		
		$this->setTpl('confirm');		
		return true;
	}
	
	protected function _getShippingOptions()
	{
		$shipping_options = array();
		
		foreach ($this->cart->getShippingOptionList() as $option => $data){
			foreach ($data as $key => $detail){
				$temp_title    = '';
				$is_best_price = false;
				$title		   = '';
				
				//calculate estimated delivery date
				foreach ($detail['shippingrule_list'] as $shippingrule_id => $parameters){
					$instance	   = PaycartShippingrule::getInstance($shippingrule_id);
					$title    	   = $instance->getTitle();
					$date          = new Rb_Date();
					$date->add(new DateInterval('P'.$instance->getDeliveryMaxDays().'D'));
					$detail['shippingrule_list'][$shippingrule_id]['delivery_date'] = $date;
				}
				
				//if shipping option is unique + cheap + fastest
				if($detail['unique_shippingrule']){	
					$temp_title = $title;
									
					if($detail['is_best_price'] && !$detail['is_best_grade']){
						$temp_title = $title.' & '.JText::_('COM_PAYCART_SHIPPING_OPTION_BEST_IN_PRICE');
					}
					
					if($detail['is_best_grade'] && !$detail['is_best_price']){
						$temp_title = $title.' & '.JText::_('COM_PAYCART_SHIPPING_OPTION_BEST_IN_DELIVERY_GRADE');
					}
				}
				
				elseif($detail['is_best_price']){
					$temp_title = JText::_('COM_PAYCART_SHIPPING_OPTION_BEST_IN_PRICE');
				}
				elseif($detail['is_best_grade']){
					$temp_title = JText::_('COM_PAYCART_SHIPPING_OPTION_BEST_IN_DELIVERY_GRADE');
				}
				
				$shipping_options[$key] = array('title' => $temp_title,'value'=> $key,'details' => $detail['shippingrule_list']);
			}
		}
		return $shipping_options;
	}
	
	/**	
	 * 
	 * Setup Payment Collection page
	 */
	public function gatewayselection() 
	{
		$this->_renderOptions = array('domObject'=>'pc-checkout-step-html','domProperty'=>'innerHTML');
		
		// if cart is invalid, then do nothing
		$isCartValid = $this->get('isCartValid', true);
		if(!$isCartValid){
			return true;
		}
		
		$errors = $this->get('errors', array());
		if(!empty($errors)){
			$ajax = PaycartFactory::getAjaxResponse();
			$ajax->addScriptCall('paycart.cart.gatewaySelection.error', $errors);
			$ajax->sendResponse();
		}
		
		$this->_setupCartVars();
		
		//available payment gateway
		$payment_gateway	=	PaycartFactory::getModel('paymentgateway')->loadRecords(Array('published' => 1 ));
		
		// @PCFIXME :: get default payment gateway then get gateway html and assign here
		$payment_gateway_html	=	'';
		
		$this->assign('payment_gateway', $payment_gateway);
		$this->assign('payment_gateway_html', $payment_gateway_html);

		$this->setTpl('gateway_selection');
	
		return true;	
	}
	
	/**
	 * 
	 * Invoke to get all available steps
	 * 
	 * @return all available steps
	 */
	protected  function _getSteps() 
	{
		$steps 				=  Array();
		
		//Step :: Address
		$step  = new stdClass();
		$step->icon		= 'fa-user';
		$step->class	= 'pc-checkout-step-'.Paycart::CHECKOUT_STEP_LOGIN;
		$step->title	=  JText::_('COM_PAYCART_CART_STEP_LOGIN');
		$step->onclick	= ($this->cart->isGuestcheckout()) ? 'paycart.cart.login.get();' : false;		
		$steps['login'] = $step;
		
		//Step :: Address
		$step  = new stdClass();
		$step->icon = 'fa-truck';
		$step->class = 'pc-checkout-step-'.Paycart::CHECKOUT_STEP_ADDRESS;
		$step->title	=  JText::_('COM_PAYCART_CART_STEP_ADDRESS');
		$step->onclick	= 'paycart.cart.address.get()';
		$steps['address']= $step;

		//Step :: Order-Confirm
		$step  = new stdClass();
		$step->icon = 'fa-thumbs-up';
		$step->class = 'pc-checkout-step-'.Paycart::CHECKOUT_STEP_CONFIRM;
		$step->title	=  JText::_('COM_PAYCART_CART_STEP_CONFIRM');
		$step->onclick	= ' paycart.cart.confirm.get()';
		$steps['confirm']= $step;
		
		//Step :: Payment
		$step  = new stdClass();
		$step->icon = 'fa-credit-card';
		$step->class = 'pc-checkout-step-'.Paycart::CHECKOUT_STEP_PAYMENT;
		$step->title	=  JText::_('COM_PAYCART_CART_STEP_PAYMENT');
		$step->onclick	= false;
		$steps['gatewayselection']= $step;
		
		return $steps;
	}
}