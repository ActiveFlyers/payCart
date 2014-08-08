<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author 		Puneet Singhal , mManishTrivedi
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * 
 * Cart Lib
 * @author manish
 *
 */
class PaycartCart extends PaycartLib
{
	// Table fields : 
	protected $cart_id;
	protected $buyer_id;
	protected $status;
	protected $currency;
	protected $ip_address;
	protected $billing_address_id;				// for invoicing
	protected $shipping_address_id;	
	protected $invoice_id;

	/**
	 * 
	 * stop cart-recalculation
	 * @var Boolean
	 */
	protected $is_locked;						 		
	
	protected $locked_date;					// Checkout/refund  Request date (Request for Payment)
	protected $paid_date;					// Payment Completion date
	protected $completed_date;					// Cart deliver-date (Fill by manually) 
	
	protected $reversal_for;					// when reverse/return cart build. 
												// Set here cart_id which is reversed 
	
	protected $cancelled_date;				// Before delivery, if cancel existing cart  
	
	protected $created_date;
	protected $modified_date;
	
	protected $session_id;
	
	protected $params;
	protected $is_guestcheckout;
	
	
	// Related Table Fields: Array of cart-particulars
	protected $_cartparticulars;
	
	// Cart-total
	protected $_total;
	
	/** 
	 * 
	 * @var PaycartHelperCart
	 */
	protected $_helper;
	
	public function __construct($config = Array())
	{
		//@Note:: we would not reset _helper in reset
		$this->_helper = PaycartFactory::getHelper('cart');
		
		return parent::__construct($config);
	}
	
	/**
	 * 
	 * PaycartCart Instance
	 * @param  $id, existing Productcategory id
	 * @param  $data, required data to bind on return instance	
	 * @param  $dummy1, Just follow code-standards
	 * @param  $dummy2, Just follow code-standards
	 * 
	 * @return PaycartCart lib instance
	 */
	public static function getInstance($id = 0, $bindData = null, $dummy1 = null, $dummy2 = null)
	{
		return parent::getInstance('cart', $id, $bindData);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_Lib::reset()
	 */
	public function reset() 
	{	
		// Table fields	
		$this->cart_id	 			= 0; 
		$this->buyer_id 		 	= 0;
		$this->invoice_id			= 0;
		
		$this->status				= Paycart::STATUS_CART_DRAFTED;
		$this->currency				= PaycartFactory::getConfig()->get('localization_currency');
		$this->ip_address			= PaycartFactory::getHelper('buyer')->getClientIP();
		$this->billing_address_id	= 0;
		$this->shipping_address_id	= 0;	
		$this->is_locked			= 0;			
		
		$this->locked_date			= Rb_Date::getInstance('0000-00-00 00:00:00');
		$this->paid_date			= Rb_Date::getInstance('0000-00-00 00:00:00');
		$this->completed_date		= Rb_Date::getInstance('0000-00-00 00:00:00'); 
		
		$this->reversal_for			= 0; 
		$this->cancelled_date		= Rb_Date::getInstance('0000-00-00 00:00:00');  
		
		$this->created_date			= Rb_Date::getInstance();
		$this->modified_date		= Rb_Date::getInstance();
		
		$this->session_id			= 	'';
		$this->is_guestcheckout		=	false;
		
		// Related Table Fields: cart particulars libs instance
		$this->clearCartParticulars();
		
		$this->params	=	new Rb_Registry();
		
		return $this;
	}
	
	/**
	 * 
	 * Return Buyer-Lib instance Or buyer-Id
	 * @param Boolean $instance_required : true, if you need buyer-lib instance. default buyer-Id 
	 * 
	 * @return Buyer-Lib instance Or buyer-Id
	 */
	public function getBuyer($instance_required = false)
	{
		if (!$instance_required) {
			return $this->buyer_id;
		}

		$buyer_instance = PaycartBuyer::getInstance($this->buyer_id);
		
		//if cart is locked then no need to following processing
		if (! $this->is_locked) {
			
			// get buyer detail form cart param 
			$buyer 	= 	$this->getParam('buyer', new stdclass());
			$buyer_instance->bind($buyer);
		}

		return $buyer_instance;
	}


	public function getTotal()
	{
		$this->updateTotal();
		return $this->_total;
	}
	
	public function getInvoiceId()
	{
		return $this->invoice_id;
	}
	
	public function getCurrency()
	{
		return $this->currency;
	}

	public function getPaymentDate()
	{
		return $this->payment_date;
	}
	
	public function getReversalFor()
	{
		return $this->reversal_for;
	}
	
	public function getIsGuestCheckout() 
	{
		return (bool)$this->is_guestcheckout;
	}
	
	public function setIsGuestCheckout($isGuestCheckout =false)
	{
		$this->is_guestcheckout = $isGuestCheckout; 
	}
	
	public function setShippingAddressId($id)
	{
		$this->shipping_address_id = $id; 
	}
	
	public function setBillingAddressId($id)
	{
		$this->billing_address_id =	$id; 
	}
	
	public function setBuyer($id)
	{
		$this->buyer_id	=	$id; 
	}
	
	public function setSessionId($id)
	{
		$this->session_id	=	$id;
	}
	
	/**
	 * 
	 * Return buyeraddress-Lib instance Or buyeraddress-Id
	 * @param Boolean $instance_required : true, if you need buyeraddress-lib instance. default buyeraddress-Id 
	 * 
	 * @return buyeraddress-Lib instance Or buyeraddress-Id
	 */
	public function getShippingAddress($instance_required = false)
	{
		if(!$instance_required) {
			return $this->shipping_address_id;
		}

		$shipping_address_instance = PaycartBuyeraddress::getInstance($this->shipping_address_id);
		
		//if address availble in cart param then bind it
		$shipping_address	=	$this->getParam('shipping_address', Array());
		if (!empty($shipping_address)) {
			// get buyer detail form cart param 
			$shipping_address_instance->bind($shipping_address);
		}

		return $shipping_address_instance;
	}
	
	/**
	 * 
	 * Return buyeraddress-Lib instance Or buyeraddress-Id
	 * @param Boolean $instance_required : true, if you need buyeraddress-lib instance. default buyeraddress-Id 
	 * 
	 * @return buyeraddress-Lib instance Or buyeraddress-Id
	 */
	public function getBillingAddress($instance_required = false)
	{
		if (!$instance_required) {
			return $this->billing_address_id;
		}

		$billing_address_instance = PaycartBuyeraddress::getInstance($this->billing_address_id);
		
		//if address availble in cart param then bind it
		$billing_address	=	$this->getParam('shipping_address', Array());
		if (!empty($billing_address)) {
			// get buyer detail form cart param 
			$billing_address_instance->bind($billing_address);
		}

		return $billing_address_instance;
	}
	
	public function setInvoiceId($invoice_id)
	{
		$this->invoice_id	=	$invoice_id; 
	}
	
	public function setStatus($status)
	{
		$this->status	=	$status; 
	}
	
	/**
	 * 
	 * Get Cart particular
	 * @param $type : Cartparticular Type {duties, product, promotion, shipping, adjustment}
	 * @throws RuntimeException : is type is not exist
	 * 
	 * @return Array of specific $type particular array. Default return all particulars
	 */
	public function getCartparticulars($type = null)
	{
		if ($type) {
			if (!isset($this->_cartparticulars[$type])) {
				throw new RuntimeException(Rb_Text::_("COM_PAYCART_CART_PARTICULAR_TYPE_INVALID"));
			}
			
			return $this->_cartparticulars[$type];
		} 
		
		return $this->_cartparticulars;
		
	}
	
	/**
	 * 
	 * Reinitialize cart total
	 * 
	 * @return PaycartCart
	 */
	public function updateTotal()
	{
		$this->_total = 0 ;
		
		foreach ($this->getCartparticulars() as $type => $cartparticulars) {
			foreach ($cartparticulars as $cartparticular) {
				$this->_total = ($this->_total) + ($cartparticular->getTotal(true));
			}			
		}
		
		return $this;
	}
	
	/**
	 * 
	 * Add product to cart
	 * @param stdClass $product
	 *		StdClass(
	 *   				'product_id' => (INT)		Product-ID (Support only integer id)   
	 *	 				'quantity'	 => (INT)
	 * 				 ) 
	 * @since 1.0
	 * @author mManishTrivedi, Gaurav Jain
	 * 
	 * @return PaycartCart instance
	 */
	public function addProduct(stdclass $product)
	{
		$existing_products = $this->params->get('products', new stdClass());
		
		// product is not already added, set it with quantity 1
		if(!isset($existing_products->{$product->product_id})){
			$existing_products->{$product->product_id}	=	new stdClass();
			
			$existing_products->{$product->product_id}->product_id 	= $product->product_id;
			$existing_products->{$product->product_id}->quantity 	= ($product->quatity)?$product->quatity:'1'; //PCTODO : Don't use 1, add minimum quantity of product 
		}
		
		/**
		 * product has already been added and quantity is being updated
		 * Case : If quantity is less than 1 then we will do nothing
		 */
		elseif($product->quantity > 0 && $existing_products->{$product->product_id}->quantity != $product->quantity){
			$existing_products->{$product->product_id}->quantity 	= $product->quantity;
		}

		// set the new products
		$this->params->set('products', $existing_products);
				
		return $this;
	}
 	/**
     *
     * delete product to cart
     * @param $productId  
     * @return PaycartCart instance
     */
     public function removeProduct($productId)
     {
     	$existing_products = $this->params->get('products', new stdClass());
     	
     	// product is not already added, set it with quantity 0
     	if(isset($existing_products->{$productId})) {
     		unset($existing_products->{$productId});
     	}
               
        // set the updates products
        $this->params->set('products', $existing_products);
        
        return $this;
      }
       
	public function addPromotionCode($code)
	{
		$promotions = $this->params->get('promotions', array());
		
		// @PCTODO :: its far better if we will add {code => discount-rule-id} 
		// so we can utlize discount-rule-id on getDiscountRule method (in Query performance) 
		  
		// product is not already added, set it with quantity 0
		if(!in_array($code, $promotions)){
			$promotions[] = $code;
		}
			
		// set the new products
		$this->params->set('promotions', $promotions);
				
		return $this;
	}
	/**
	 * 
	 * Invoke to Load product particular on cart (from cart param)
	 * 
	 * @return false if product is not exist otherwise return $this
	 */
	public function loadProductCartparticulars()
	{
		$products = $this->params->get('products', new stdClass());
		
		foreach($products as $product_id => $bind_data)
		{
			/* @var $cartparticular PaycartCartparticularProduct */
			$this->_cartparticulars[Paycart::CART_PARTICULAR_TYPE_PRODUCT][$product_id] = PaycartCartparticular::getInstance(Paycart::CART_PARTICULAR_TYPE_PRODUCT, $bind_data); 
		}
		
		return $this;
	}	
	
	/**
	 * 
	 * Invoke to Load promotion particular on cart (from cart param)
	 * 
	 * @return PaycartCart
	 */
	public function loadPromotionCartparticulars()
	{
		$promotions = $this->params->get('promotions', new stdClass());
		$bindData = new stdClass();
		$bindData->promotions 	 = 	$promotions;
		$bindData->unit_price	 =	$this->getTotal();
		$bindData->particular_id =	$this->getId();
		
		$this->_cartparticulars[Paycart::CART_PARTICULAR_TYPE_PROMOTION][] = PaycartCartparticular::getInstance(Paycart::CART_PARTICULAR_TYPE_PROMOTION, $bindData);
		
		return $this;
	}
	
	/**
	 * 
	 * Invoke to Load duties particular on cart (from cart param)
	 * 
	 * @return PaycartCart
	 */
	public function loadDutiesCartparticulars()
	{
		$bindData = new stdClass();
		$bindData->unit_price	 =	0;
		$bindData->particular_id =	$this->getId();
		foreach ($this->getCartparticulars(Paycart::CART_PARTICULAR_TYPE_PRODUCT) as $product_particular) {
			$bindData->unit_price += $product_particular->getTax();
		}
		
		$this->_cartparticulars[Paycart::CART_PARTICULAR_TYPE_DUTIES][] = PaycartCartparticular::getInstance(Paycart::CART_PARTICULAR_TYPE_DUTIES, $bindData);
		
		return $this;
	}
	
	
	/**
	 * 
	 * clear existing particular
	 * 
	 */
	private function clearCartParticulars()
	{
		$this->_cartparticulars[Paycart::CART_PARTICULAR_TYPE_PRODUCT] 	 =	array();
		$this->_cartparticulars[Paycart::CART_PARTICULAR_TYPE_PROMOTION] =	array();
		$this->_cartparticulars[Paycart::CART_PARTICULAR_TYPE_DUTIES] 	 =	array();
		$this->_cartparticulars[Paycart::CART_PARTICULAR_TYPE_SHIPPING]  =	array();
	}
	
	
	/**
	 * 
	 * Calculate on Cart {cart-particulares}
	 * 1#. Calculate on Product type Cart-particular. They are available on $this object
	 * 2#. Create and calculate product{duties, promotion and shipping} type Cart-Particular
	 *	   (according to configure sequence)
	 * 3#. Craete and calculate Adjustment type Cart-Particular,if exist 
	 * 
	 * @return PaycartCart instance
	 * 
	 * @since 1.0
	 * @author mManishTrivedi, Gaurav Jain
	 */
	public function calculate()
	{
		// no need to re-calculation
		if ($this->is_locked) {
			return $this;
		}
		
		//clear particular before calculation
		$this->clearCartParticulars();
		
		// load the predefined products
		$this->loadProductCartparticulars();
		
		if (0 ==  count($this->getCartparticulars(Paycart::CART_PARTICULAR_TYPE_PRODUCT)))  {
			// @FIXME:: notify that product is not exist on cart
			//$this->addMessage($key, $type, $message, $cartparticular)
			return $this;
		}
		
		// reinitialize the cart total
		$this->updateTotal();
		
		/**
		 * Trigger before cart-calculation start
		 * 1#. You can change here Product-Price (Unit-price) 
		 * @NOTE :: Clone {$this}, If required
		 */ 
		$args 			= Array();
		$args['cart']	= $this;

		// At that Moment cart have only {product} particulars. 
		Rb_HelperPlugin::trigger('onPaycartCartBeforeCalculate', $args);
	
		// STEP 1 : calculate product cartparticular
		foreach ($this->getCartparticulars(Paycart::CART_PARTICULAR_TYPE_PRODUCT) as $product_id => $product_cartparticular){
			/* @var $productCartparticular PaycartCartparticularProduct */
			$product_cartparticular->calculate($this);			
		}
		
		$this->updateTotal();
	
		//@PCTODO : We'll give option for applying tax before discount, but as if now only one option
	 
		// STEP 2 : calculate promotion cartparticular
		$this->loadPromotionCartparticulars();
		
		foreach ($this->getCartparticulars(Paycart::CART_PARTICULAR_TYPE_PROMOTION) as $promotion_cartparticular){
			/* @var $promotion_cartparticular PaycartCartparticularPromotion */
			$promotion_cartparticular->calculate($this);			
		}
			
		$this->updateTotal();
	
		// STEP 3 : calculate duties cartparticular
		$this->loadDutiesCartparticulars();
		
		foreach ($this->getCartparticulars(Paycart::CART_PARTICULAR_TYPE_DUTIES) as $product_id => $duties_cartparticular){
			/* @var $duties_cartparticular PaycartCartparticularDuties */
			$duties_cartparticular->calculate($this);			
		}
		
		$this->updateTotal();
		
		// STEP 4 : calculate shipping cartparticular
		$this->loadShippingCartparticulars();
		// reinitialize the cart total
		$this->updateTotal();
		
		$shippingCartparticulars = $this->getCartparticulars(Paycart::CART_PARTICULAR_TYPE_SHIPPING);
		if(count($shippingCartparticulars) > 0){
			foreach ($shippingCartparticulars as $product_id => $shippingCartparticular){
				/* @var $productCartparticular PaycartCartparticularProduct */
				$shippingCartparticular->calculate($this);					
			}
			
			$this->updateTotal();
		}
		else{
			// No shipping rules
		}
	
		/**
		 * Trigger After cart-calculation
		 * @NOTE :: Clone {$this}, If required
		 */ 
		$args['cart']	=	$this;
		Rb_HelperPlugin::trigger('onPaycartCartAfterCalculate', $args);
		
		return $this;
	}
	
	public function loadShippingCartparticulars()
	{
		//@PCFIXME :: work without shipping
		return $this;
		
		$shippingOptions 			= $this->getShippingOptionList();	
		$selectedShippingMethods 	= $this->params->get('shipping', new stdClass());
		
		//@PCFIXME ::  $addressId		
		if(empty($selectedShippingMethods) || !isset($shippingOptions[$addressId][$selectedShippingMethods])) {
			// IMP
			// shipping method is not selected or invalid
			// so reset the previous selected shipping method or set default shipping method				
			//@PCFIXME ::  $defaultShipping
			$selectedShippingMethods = $defaultShipping.','; //@PCTODO
			$this->params->set('shipping', $selectedShippingMethods);
		}
		
		if(isset($shippingOptions[$addressId][$selectedShippingMethods])){
			foreach($shippingOptions[$addressId][$selectedShippingMethods]['shippingrule_list'] as $shippingrule_id => $shippingOption){
				$binddata = $shippingOption;
				$binddata['shippingrule_id'] = $shippingrule_id;
				$this->_cartparticulars[Paycart::CART_PARTICULAR_TYPE_SHIPPING][$shippingrule_id] = PaycartCartparticular::getInstance(Paycart::CART_PARTICULAR_TYPE_DUTIES, $binddata);
			}
		}
		else{
			// No matching Shipping Rules
			// @PCTODO : what to do ???
		}
		
		return $this;
	}
	
	/**
	 *
	 *
	 * @since 1.0
	 * @author Gaurav Jain
	 */
	public function getShippingOptionList()
	{
		//@PCFIXME:: work on it
		return array();
		
		/* @var $shippingruleHelper PaycartHelperShippingrule */
		$shippingruleHelper 	= PaycartFactory::getHelper('shippingrule'); 
		$productCartparticulars = $this->getCartparticulars(Paycart::CART_PARTICULAR_TYPE_PRODUCT);
		$addressId 				= $this->shipping_address_id;
		$product_grouped_by_address[$addressId] = $productCartparticulars;
		$shippingrules_grouped_by_product = $shippingruleHelper->getRulesGroupedByProducts();
		
		return $shippingruleHelper->getDeliveryOptionList($product_grouped_by_address, $shippingrules_grouped_by_product);
	}
	
	/**
	 * 
	 * Invoke to checkout existing cart
	 * 	- Store all params value
	 * @since 1.0
	 * @author Gaurav Jain, mManishTrivedi
	 * 
	 * @return PaycartCart
	 */
	public function checkout()
	{
		if ($this->is_locked) {
			return $this;
		}
		
		// before starting Process invoke calculate, usage tracking set or any price can effect it
		//@PCTODO :: if any change in price then how to notify end-user and how to move forward 
		$this->calculate();
		
		//lock cart after calculation
		$this->is_locked	=	true;
		
		//register user, if guest checkout  
		if ($this->getIsGuestCheckout()) {
			$this->guestRegistration();
		}
		
		// Step-1# Set buyer on cart
		$buyer_instance = $this->getBuyer(true);
		$this->setBuyer($buyer_instance->getId());
		
		// Step-2# Set addresses on cart
		$billing_address_instance	=	$this->getBillingAddress(true);
		$shipping_address_instance	=	$this->getShippingAddress(true);
		
		//if address is not save  then save it
		if ( !$billing_address_instance->getId() ) {
			//@PCTODO :: break into function {same job for billing nd shipping}
			//set buyer id
			$billing_address_instance->setBuyerId($this->getBuyer());
			
			$md5 = $billing_address_instance->getMD5();

			//if already save then no need to save it just bind address id
			$existing_address = PaycartFactory::getModel('buyeraddress')->loadRecords(Array('md5' => $md5));

			if (empty($existing_address)) {
				$billing_address_instance->save();
			} else {
				$existing_address = array_shift($existing_address);
				$billing_address_instance->setId($existing_address->buyeraddress_id);
			}
		}
		
		// if address copy from one to another
		if ( $this->params->get('billing_to_shipping', false)  || $this->params->get('shipping_to_billing', false)) {
			$shipping_address_instance = $billing_address_instance->getClone(); 
		}
		
		// if shipping address is not saved then save it 
		if (! $shipping_address_instance->getId()) {
			$shipping_address_instance->setBuyerId($this->getBuyer());
			
			$md5 = $shipping_address_instance->getMD5();
			
			//if already save then no need to save it just bind address id
			$existing_address = PaycartFactory::getModel('buyeraddress')->loadRecords(Array('md5' => $md5));

			if (empty($existing_address)) {
				$shipping_address_instance->save();
			} else {
				$existing_address = array_shift($existing_address);
				$shipping_address_instance->setId($existing_address->buyeraddress_id);
			}

		}
		
		//Set address
		$this->setBillingAddressId($billing_address_instance->getId());
		$this->setShippingAddressId($shipping_address_instance->getId());
		
		// Step-3# Save particulars data
		foreach ($this->getCartparticulars() as $type => $cartparticulars) {
			foreach ($cartparticulars as $cartparticular) {
				// @PCTODO : Error-Handling
				$cartparticular->save($this);  
			}
		}
		
		// Step-4# change status Lock cart
		$this->status		=	Paycart::STATUS_CART_LOCKED;
		$this->locked_date	= 	Rb_Date::getInstance();
		
		// Step-5# Save cart
		return $this->save();
	}
	
	/**
 	 * Email Checkout by user email-id
  	 *  - Create new account if user is not exist
 	 *  - Or get User id from existing db if user already register
 	 */
	protected function guestRegistration()
	{
		$buyer = $this->getParam('buyer', new stdClass());

		/* @var PaycartHelperBuyer */
		$buyer_helper = PaycartFactory::getHelper('buyer');
		
		//check user already exist or not
		$username	= $buyer_helper->getUsername($buyer->email);
		
		if($username) {
			//user already exist
			$user_id = JUserHelper::getUserId($username);
		} else {
			// Create new account
			$user_id = $buyer_helper->createAccount($buyer->email);
		}
		
		// fail to get user-id
		if (!$user_id) {
			throw new RuntimeException(JText::_('COM_PAYCART_CHECKOUT_FAIL_TO_PROCESS_EMAIL_CHECKOUT'));
		}
		$buyer->id 		= $user_id;
		
		// set buyer 
		$this->setParam('buyer', $buyer);
		
		$this->setBuyer($user_id);
		
		return $this;
	}
	
	/**
	 * Invoke to confirm cart
	 * 	- Invoice will be create
	 * 	- If already exist then update it
	 *   
	 * @since	1.0
	 * @author	mManishTrivedi
	 * 
	 * @return PaycartCart
	 */
	public function confirm()
	{
		/* @var $invoice_helper PaycartHelperInvoice */
		$invoice_helper = PaycartFactory::getHelper('invoice');
		
		// Update invoice if already created
		if ($this->getInvoiceId()) {
			$invoice_helper->updateInvoice($this);
		} else { // Create new invoice
			$invoice_id	=	$invoice_helper->createInvoice($this);
			$this->setInvoiceId($invoice_id);
		}
		
		return $this;
	}
	
	/**
	 * 
	 * Invoke to update processor and processor config on cart's invoice
	 * @param INT $processor_id
	 * @param array $processorData
	 *
	 * @return PaycartCart
	 */
	public function updateInvoiceProcessor( $processor_id, Array $processorData = Array())
	{
		/* @var $invoice_helper PaycartHelperInvocie */
		$invoice_helper		= PaycartFactory::getHelper('invoice');
		
		if (empty($processorData)) {
			 
			$payment_gateway	=	PaycartFactory::getModel('paymentgateway')
										->loadRecords(Array('paymentgateway_id' => $processor_id, 'published' => 1));

			$processorData 		=	$payment_gateway[$processor_id];
		}
		
		$payment_gateway_lib	= PaycartPaymentgateway::getInstance($processor_id, $processorData);
		
		// save the Paymnet Gateway configuration
		$payment_gateway_data	=	Array();
		$payment_gateway_data['processor_type'] 	= $payment_gateway_lib->getType();
		$payment_gateway_data['processor_config'] 	= $payment_gateway_lib->getConfig()->toObject();
		
		$invoice_helper->updateInvoice($this, $payment_gateway_data);
		
		return $this;
	}
	
	/**
	 * 
	 * ???
	 * @param unknown_type $key
	 * @param unknown_type $type
	 * @param unknown_type $message
	 * @param unknown_type $cartparticular
	 * 
	 * @since 1.0
	 * @author Gaurav Jain
	 */
	public function addMessage($key, $type, $message, $cartparticular)
	{
		// @TODO
	}
	
	public function getPromotions()
	{
		return $this->getParam('promotions','');
	}
	
	/**
	 * 
	 * Process on cart 
	 * @param $data				:	
	 * 		# if $processingType  is 'Payment' then $data is post data from Payment Processor
	 * 		# if $processingType  is 'notify' then $data is request data from Payment Processor 			
	 * @param $processingType 	: {'payment', 'notify', 'complete' }
	 * 
	 * 
	 * @return bool value
	 */
	
	/**
	 * Invoke to collect payment on this cart
	 * Payment collection on cart 
	 * @param Array $payment_data : $data is post data from Payment Processor
	 * @throws RuntimeException
	 */
	public function collectPayment(Array $payment_data) 
	{
		/* @var $invoice_helper_instance PaycartHelperInvoice */
		$invoice_helper_instance = PaycartFactory::getHelper('invoice');
		
		$invoice_id = $this->getInvoiceId();
		
		// before process invoice
		$invoice_beforeProecess = $invoice_helper_instance->getInvoiceData($invoice_id);

		// Process Payment data . 
		$invoice_helper_instance->processPayment($invoice_id, $payment_data);

		//after process invoice
		$invoice_afterProecess = $invoice_helper_instance->getInvoiceData($invoice_id);
		
		// After Payment cart status must be changed
		$this->processCart($invoice_beforeProecess, $invoice_afterProecess);	
		
		return $this;
	}
	
	
	/**
	 * Invoke to process cart-notification on this cart
	 * Payment collection on cart 
	 * @param Std Class $payment_data : $data is post data from Payment Processor
	 * @throws RuntimeException
	 */
	public function processNotification( $processor_notification_data) 
	{
		/* @var $invoice_helper_instance PaycartHelperInvoice */
		$invoice_helper_instance = PaycartFactory::getHelper('invoice');
		
		$invoice_id = $this->getInvoiceId();
		
		// before process invoice
		$invoice_beforeProecess = $invoice_helper_instance->getInvoiceData($invoice_id);

		// Process Notification data
		$invoice_helper_instance->processNotification($invoice_id, $processor_notification_data);
				
		//after process invoice
		$invoice_afterProecess = $invoice_helper_instance->getInvoiceData($invoice_id);
		
		// After Payment cart status must be changed
		$this->processCart($invoice_beforeProecess, $invoice_afterProecess);	
		
		return $this;
	}

	/**
	 * #######################################################################
	 * 		1#. ProcessCart
	 * 		2#. OninvoicePaid
	 * 		3#. OnInvoiceRefund
	 * 		4#. OnInvoiceInprocess
	 * #######################################################################
	 */	
	
	/**
	 * 
	 * Process cart on invoice changes
	 * @param PaycartCart $cart				: Cart which will process
	 * @param array $data_beforeInvoiceSave	: Invoice-date, Before change invoice
	 * @param array $data_afterInvoiceSave	: Invoice-date, Aefore change invoice
	 * 
	 * @return bool vale
	 */
	protected function processCart(Array $data_beforeInvoiceSave, Array $data_afterInvoiceSave)
	{
		// Invoke protected-methods on bases og invoice-changes 
		
		// 1#. Is invoice paid
		if ( PaycartHelperInvoice::STATUS_INVOICE_PAID != $data_beforeInvoiceSave['status'] && PaycartHelperInvoice::STATUS_INVOICE_PAID === $data_afterInvoiceSave['status']){
			return $this->onInvoicePaid($data_beforeInvoiceSave, $data_afterInvoiceSave);
		}
		
		// 2#. Is invoice refunded
		if ( PaycartHelperInvoice::STATUS_INVOICE_REFUNDED != $data_beforeInvoiceSave['status'] && PaycartHelperInvoice::STATUS_INVOICE_REFUNDED === $data_afterInvoiceSave['status']){
			return $this->onInvoiceRefund($data_beforeInvoiceSave, $data_afterInvoiceSave);
		}
		
		// 3#. Is invoice in-process
		if ( PaycartHelperInvoice::STATUS_INVOICE_INPROCESS != $data_beforeInvoiceSave['status'] && PaycartHelperInvoice::STATUS_INVOICE_INPROCESS === $data_afterInvoiceSave['status']){
			return $this->onInvoiceInprocess($data_beforeInvoiceSave, $data_afterInvoiceSave);
		} 
		
		//@PCTODO :: Handle other status when required
		//4#. Is Invoice expired 
		//5#. Is invoice none
		//6#. Is Invoice due
		
		return true;
	}
	
	
	/**
	 * 
	 * Change cart status on Invoice Piad
	 * @param array $data_beforeSave	: Invoice-Data before save it 
	 * @param array $data_afterSave		: Invoice-Data After save it
	 * 
	 * @return bool value;
	 */
	protected function onInvoicePaid(Array $data_beforeSave, Array $data_afterSave)
	{
		// 1#. change stuff which are depends on invoice
		// change cart status, payment date
		$this->setStatus(Paycart::STATUS_CART_PAID);
		$this->set('paid_date', Rb_Date::getInstance());
		
		// 2#. save cart
		$this->save();
		
		// @PDCTODO :: Fire event obPaycartCartPaid

		// 3#. update products' quatity
		PaycartFactory::getHelper('product')->updateProductStock($this->loadProductCartparticulars());
		
		return true;
	}
	
	/**
	 * 
	 * Change cart status on Invoice refund
	 * @param array $data_beforeSave	: Invoice-Data before save it 
	 * @param array $data_afterSave		: Invoice-Data After save it
	 * 
	 * @return bool value;
	 */
	protected function onInvoiceRefund(Array $data_beforeSave, Array $data_afterSave)
	{
		// @PCTODO :: Create new cart  
		// 1#. change stuff which are depends on invoice
		// change cart status, payment date (its a reversal cart so same treatment will apply like OnInvoicePaid)
		//$this->setStatus(Paycart::STATUS_CART_PAID);	
		//$this->set('paid_date', Rb_Date::getInstance());
		
		// 2#. save cart
		//$cart->save();
		
		return true;
	}

	/**
	 * 
	 * Change cart status on Invoice inprocess
	 * @param array $data_beforeSave	: Invoice-Data before save it 
	 * @param array $data_afterSave		: Invoice-Data After save it
	 * 
	 * @return bool value;
	 */
	protected function onInvoiceInprocess(Array $data_beforeSave, Array $data_afterSave)
	{
		//@NOTE:: no need to update cart for {status and request date}. 
		// Becoz at that moment cart is already checkout and req. date is already set 
		
		return true;
	}
}