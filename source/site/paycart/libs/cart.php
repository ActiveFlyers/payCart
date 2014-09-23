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

	protected $locked_date;					// Checkout/refund  Request date (Request for Payment)
    protected $approved_date;               // Approved date
    protected $paid_date;					// Payment Completion date
	protected $delivered_date;				// Cart deliver-date (Fill by manually) 
	
	protected $reversal_for;				// when reverse/return cart build. 
								// Set here cart_id which is reversed 
	
	protected $cancelled_date;				// Before delivery, if cancel existing cart  
	
	protected $created_date;
	protected $modified_date;
	
	protected $session_id;
	
	protected $params;
	protected $is_guestcheckout;
        
    protected $is_locked;           // Stop Cart calculation
    protected $is_approved;         // Cart is valid for paid 
    protected $is_delivered;        // All shipment 
        
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
		$this->cart_id	 		= 0; 
		$this->buyer_id 		= 0;
		$this->invoice_id		= 0;
		
		$this->status			= Paycart::STATUS_CART_DRAFTED;
		$this->currency			= PaycartFactory::getConfig()->get('localization_currency');
		$this->ip_address		= PaycartFactory::getHelper('buyer')->getClientIP();
		$this->billing_address_id	= 0;
		$this->shipping_address_id	= 0;	
		$this->is_locked		= 0;
        $this->is_approved		= 0;
        $this->is_delivered		= 0;

		$this->locked_date		= Rb_Date::getInstance('0000-00-00 00:00:00');
		$this->paid_date		= Rb_Date::getInstance('0000-00-00 00:00:00');
        $this->approved_date		= Rb_Date::getInstance('0000-00-00 00:00:00'); 
		$this->delivered_date		= Rb_Date::getInstance('0000-00-00 00:00:00'); 
		
		$this->reversal_for		= 0; 
		$this->cancelled_date		= Rb_Date::getInstance('0000-00-00 00:00:00');  
		
		$this->created_date		= Rb_Date::getInstance();
		$this->modified_date		= Rb_Date::getInstance();
		
		$this->session_id		= '';
		$this->is_guestcheckout		= false;
		
        $this->params	=	new Rb_Registry();
                
		// Related Table Fields: cart particulars libs instance
		$this->clearCartParticulars();
		
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
		/**
		 * if cart is not locked yet then total will be fetched from 
         * cartparticulars objects available on cart
		 */
		if(!$this->is_locked){
			$this->updateTotal();
		}
		else{
			/**
			 * After cart locked, cartparticulars will be fetched from model
			 */
			$this->_total = PaycartFactory::getHelper('cart')->getTotal($this);
		}
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
	public function getStatus()
    {
           return $this->status;
    }
    
	public function isGuestcheckout() 
	{
		return (bool)$this->is_guestcheckout;
	}
        
    public function isApproved() 
	{
		return (bool)$this->is_approved;
	}
        
    public function isLocked() 
	{
		return (bool)$this->is_locked;
	}
        
    public function isDelivered() 
	{
		return (bool)$this->is_delivered;
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
        
     public function getCreatedDate()
     {
        return $this->created_date;
     }
     
	public function getPaidDate()
    {
		return $this->paid_date;
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
			$existing_products->{$product->product_id}->quantity 	= ($product->quantity)?$product->quantity:'1'; //PCTODO : Don't use 1, add minimum quantity of product 
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
         * Invoke to approve cart
         * 
         * @return \PaycartCart 
         */
        public function markLocked()
        {
             // check if already locked or not other wise date will be changed
            if (!$this->is_locked) {
                $this->is_locked    =   1;
                $this->locked_date	=   Rb_Date::getInstance();
            }
            
            return $this;
        }

        /**
         * Invoke to approve cart
         * 
         * @return \PaycartCart 
         */
        public function markApproved()
        {
			$this->markLocked();

            // check if already approved or not other wise date will be changed
            if (!$this->is_approved) {
                $this->is_approved      =   1;
                $this->approved_date    =   Rb_Date::getInstance();
            }
            
            return $this;
        }
        
        /**
         * Invoke to approve cart
         * 
         * @return \PaycartCart 
         */
        public function markPaid()
        {
            // change cart status, payment date, make it approval
            $this->markApproved();
            
            // check if already paid or not other wise date will be changed
            if (Paycart::STATUS_CART_PAID != $this->status) {
                $this->status       =   Paycart::STATUS_CART_PAID;
                $this->paid_date    =   Rb_Date::getInstance();
            }
   
            return $this;
        }
        
        /**
         * Invoke to approve cart
         * 
         * @return \PaycartCart 
         */
        public function markDelivered()
        {
            $this->markPaid();
 
            $this->is_delivered     = 1;
            $this->delivered_date   = Rb_Date::getInstance();
   
            return $this;
        }
        

       /**
        *
        * delete product to cart
        * @param $productId  
        * @return PaycartCart instance
        * 
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
			$bind_data->cart_id = $this->getId();
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
		$bindData->cart_id       =  $bindData->particular_id;
		
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
		$bindData->cart_id       =  $bindData->particular_id;
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
		$shippingOptions 			= $this->getShippingOptionList();
		// No matching Shipping Rules for all or one of the product
		if(empty($shippingOptions)){
			return $this;
		}	
		
		$selectedShippingMethods 	= $this->params->get('shipping',null);
		$address					= $this->getShippingAddress(true);
		$md5Address					= $address->getMD5();
		$md5Address					= !isset($md5Address)?0:$md5Address;

		if(empty($selectedShippingMethods) || !isset($shippingOptions[$md5Address][$selectedShippingMethods])) {
			// IMP
			// shipping method is not selected or invalid
			// so reset the previous selected shipping method or set default shipping method
			$default = Paycart::SHIPPING_BEST_IN_PRICE; //PCTODO:: Can be fetched from global configuration
			
			foreach ($shippingOptions as $addressId => $shippingOption){
				foreach ($shippingOption as $key => $option){
					if($default == Paycart::SHIPPING_BEST_IN_PRICE && $option['is_best_price'] == true){
						$selectedShippingMethods = $key;
						$this->params->set('shipping', $key);
						break;
					}
					elseif ($default == Paycart::SHIPPING_BEST_IN_TIME && $option['is_best_grade'] == true){
						$selectedShippingMethods = $key;
						$this->params->set('shipping', $key);
						break;
					}
				}
			}
		}
		
		if(isset($shippingOptions[$md5Address][$selectedShippingMethods])){
			foreach($shippingOptions[$md5Address][$selectedShippingMethods]['shippingrule_list'] as $shippingrule_id => $shippingOption){
				$productParticulars = $this->getCartparticulars(Paycart::CART_PARTICULAR_TYPE_PRODUCT);
				
				foreach ($shippingOption['product_list'] as $product_id){
					$binddata['params']['product_list'][$product_id] = array('product_id' => $product_id, 'quantity' => $productParticulars[$product_id]->getQuantity());
				}
				
				//calculate delivery date
				$instance = PaycartShippingrule::getInstance($shippingrule_id);
				$date     = new Rb_Date();
				$binddata['params']['delivery_date'] = PaycartFactory::getHelper('format')
															->date($date->add(new DateInterval('P'.$instance->getDeliveryMaxDays().'D')));
				
				$binddata['price']	         = $shippingOption['price_without_tax'];
				$binddata['shippingrule_id'] = $shippingrule_id;
				$binddata['cart_id']		 = $this->getId();
				$this->_cartparticulars[Paycart::CART_PARTICULAR_TYPE_SHIPPING][$shippingrule_id] = PaycartCartparticular::getInstance(Paycart::CART_PARTICULAR_TYPE_SHIPPING, $binddata);
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
		$address				= $this->getShippingAddress(true);
		$md5Address				= $address->getMD5();
		$md5Address				= !isset($md5Address)?0:$md5Address;
		
		//set address in addressObject so that we can fetch in shipping calculation when required
		PaycartFactory::getHelper('shippingrule')->setAddressObject($md5Address, (object)$address->toArray());

		/* @var $shippingruleHelper PaycartHelperShippingrule */
		$shippingruleHelper 	= PaycartFactory::getHelper('shippingrule'); 
		$productCartparticulars = $this->getCartparticulars(Paycart::CART_PARTICULAR_TYPE_PRODUCT);
		
		$product_grouped_by_address[$md5Address] = $productCartparticulars;
		$shippingrules_grouped_by_product = $shippingruleHelper->getRulesGroupedByProducts($this);
		
		//if no shipping rule available for any of the cart product then just return and stop calculation
		if(empty($shippingrules_grouped_by_product)){
			return array();
		}
		
		return $shippingruleHelper->getDeliveryOptionList($product_grouped_by_address, $shippingrules_grouped_by_product);
	}
	
	/**
	 * 
	 * Invoke to checkout/Order existing cart
	 * 	- Store all params value
	 * @since 1.0
	 * @author Gaurav Jain, mManishTrivedi
	 * 
	 * @return PaycartCart
	 */
	public function order()
	{
		if ($this->is_locked) {
			return $this;
		}
		
		// before starting Process invoke calculate, usage tracking set or any price can effect it
		//@PCTODO :: if any change in price then how to notify end-user and how to move forward 
		$this->calculate();
		
		//lock cart after calculation
                $this->markLocked();
		
		//register user, if guest checkout  
		if ($this->isGuestcheckout()) {
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
		
		// Step-4# Save cart
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
			throw new RuntimeException(JText::_('COM_PAYCART_CART_FAIL_TO_PROCESS_EMAIL_CHECKOUT'));
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
	
        public function getLink()
        {
            //@FIXME :: build cart secure key
            return PaycartRoute::_('index.php?option=com_paycart&view=cart&task=display&key='.$this->cart_id);
        }

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
         *
         * @param PaycartCart $previousObject
         * @return boolean 
         */
        protected function _save($previousObject) 
        {
            // save to data to table
            $id =  parent::_save($previousObject);
            
            //if save was not complete, then id will be null, do not trigger after save
            if(!$id){
                    return false;
            }

            // correct the id, for new records required
            $this->setId($id);

            //save $this to static cache, so that if someone tries to create instance in between the save process
            //then proper object would be returned 
            if(!$previousObject){
               self::setInstance($this);			
            }
            
            $this->_triggerAfterSave($previousObject);
            
            return $id;
        }
        
        
        /**
         * Invoke to trigger all cart after save events
         * @param $previousObject PaycartCart type or might be null 
         * @return int id
         *  
         */
        private function _triggerAfterSave($previousObject) 
        {
            /*  @var $event_helper PaycartHelperEvent   */
            $event_helper = PaycartFactory::getHelper('event');
            
            //trigger-1 :: onPaycartCartDraft
            if ( empty($previousObject) ||  
                 ( Paycart::STATUS_CART_DRAFTED != $previousObject->status && Paycart::STATUS_CART_DRAFTED == $this->status ) 
                ){
                $event_helper->onPaycartCartAfterDrafted($this);
            }
            
            //trigger-2 :: onPaycartCartLocked
            if ( !empty($previousObject) &&
                 (!$previousObject->is_locked && $this->is_locked ) ) {
                $event_helper->onPaycartCartAfterLocked($this);
            }
            
            //trigger-3 :: onPaycartCartApproved
            if ( !empty($previousObject) &&
                 (!$previousObject->is_approved && $this->is_approved ) 
                ) {
                $event_helper->onPaycartCartAfterApproved($this);
            }
            
            //trigger-4 :: onPaycartPaid
            if ( !empty($previousObject) &&
                  ( Paycart::STATUS_CART_PAID != $previousObject->status && Paycart::STATUS_CART_PAID == $this->status )
               ) {
                $event_helper->onPaycartCartAfterPaid($this);
            }
            
            //trigger-5 :: onPaycartCart Delivered
            if ( !empty($previousObject) &&
                 ( !$previousObject->is_delivered && $this->is_delivered ) 
               ) {
                $event_helper->onPaycartCartAfterDelivered($this);
            }

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
            $this->markPaid()->save();
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