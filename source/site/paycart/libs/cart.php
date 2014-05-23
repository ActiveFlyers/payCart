<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author 		Puneet Singhal
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
	
	protected $request_date;					// Checout-Date (Request for Payment)
	protected $payment_date;					// Payment Completion date
	protected $delivered_date;					// Cart deliver-date (Fill by manually) 
	
	protected $reversal_for;					// when reverse/return cart build. 
												// Set here cart_id which is reversed 
	
	protected $cancellation_date;				// Before delivery, if cancel existing cart  
	
	protected $created_date;
	protected $modified_date;
	
	protected $session_id;
	
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
		
		$this->status				= Paycart::STATUS_CART_DRAFT;
		$this->currency				= PaycartFactory::getConfig()->get('currency', '$');
		$this->ip_address;
		$this->billing_address_id	= 0;
		$this->shipping_address_id	= 0;	
		$this->is_locked			= 0;			
		
		$this->request_date			= Rb_Date::getInstance('0000-00-00 00:00:00');
		$this->payment_date			= Rb_Date::getInstance('0000-00-00 00:00:00');
		$this->delivered_date		= Rb_Date::getInstance('0000-00-00 00:00:00'); 
		
		$this->reversal_for			= 0; 
		$this->cancellation_date	= Rb_Date::getInstance('0000-00-00 00:00:00');  
		
		$this->created_date			= Rb_Date::getInstance();
		$this->modified_date		= Rb_Date::getInstance();
		
		$this->session_id	= '';
		
		// Related Table Fields: cart particulars libs instance
		$this->_cartparticulars[Paycart::CART_PARTICULAR_TYPE_PRODUCT] 	 =	array();
		$this->_cartparticulars[Paycart::CART_PARTICULAR_TYPE_PROMOTION] =	array();
		$this->_cartparticulars[Paycart::CART_PARTICULAR_TYPE_DUTIES] 	 =	array();
		$this->_cartparticulars[Paycart::CART_PARTICULAR_TYPE_SHIPPING]  =	array();
		
		return $this;
	}
	
	/**
	 * 
	 * Return Buyer-Lib instance Or buyer-Id
	 * @param Boolean $instance : true, if you need buyer-lib instance. default buyer-Id 
	 * 
	 * @return Buyer-Lib instance Or buyer-Id
	 */
	public function getBuyer($instance = false)
	{
		if(!$instance) {
			return $this->buyer_id;
		}

		return PaycartBuyer::getInstance($this->buyer_id);
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
		
	public function setShippingAddressId($id)
	{
		$this->shipping_address_id = $id; 
	}
	
	public function setBillingAddressId($id)
	{
		$this->billing_address_id =	$id; 
	}
	
	
	/**
	 * 
	 * Save CartParticulrs
	*/
	public function saveCartparticulars()
	{	
		foreach( $this->getCartparticulars() as $type => $cartparticulars) {
			if(!empty($cartparticulars)){
				foreach ($cartparticulars as $key => $cartparticular) {
					if(!$cartparticular->save($this)){
						throw new RuntimeException(Rb_Text::_("COM_PAYCART_SAVE_ERROR"));
					}
				}
			}			
		}
		
		return $this;
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
			if(!empty($cartparticulars)){
				foreach ($cartparticulars as $cartparticular) {
					$this->_total = ($this->_total) + ($cartparticular->getTotal());
				}
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
	 */
	public function addProduct(stdclass $product)
	{
		$products = $this->params->get('products', array());
		
		// product is not already added, set it with quantity 0
		if(!isset($products[$product->product_id])){
			$products[$product->product_id]['product_id'] = $product->product_id;
			$products[$product->product_id]['quantity'] = $product->quantity;
		}
	
		// update product quantity
		$products[$product->product_id] += $product->quantity;
		
		// set the new products
		$this->params->set('products', $products);
				
		return $this;
	}
	
	public function addPromotionCode($code)
	{
		$promotions = $this->params->get('promotions', array());
		
		// product is not already added, set it with quantity 0
		if(!in_array($code, $promotions)){
			$promotions[] = $code;
		}
			
		// set the new products
		$this->params->set('promotions', $promotions);
				
		return $this;
	}
	
	public function loadProductCartparticulars()
	{
		$products = $this->params->get('products', array());
		
		if(count($products) > 0){
			foreach($products as $product_id => $product){
				/* @var $cartparticular PaycartCartparticularProduct */
				$binddata = $product;				
				$this->_cartparticulars[$type][$product_id] = PaycartCartparticular::getInstance(Paycart::CART_PARTICULAR_TYPE_PRODUCT, $binddata); 
			}
		}
		
		return $this;
	}	
	
	/**
	 * 
	 * Calculate on Cart {cart-particulares}
	 * 1#. Calculate on Product type Cart-particular. They are available on $this object
	 * 2#. Create and calculate product{duties, promotion and shipping} type Cart-Particular
	 *	   (according to configure sequence)
	 * 3#. Craete and calculate Adjustment type Cart-Particular,if exist 
	 * 
	 */
	public function calculate()
	{
		// no need to re-calculation
		if ($this->is_locked) {
			return $this;
		}
		
		// STEP 1 : calculate product cartparticular
			// load the predefined products
			$this->loadProductCartparticulars();
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
		
			foreach ($this->getCartparticulars(Paycart::CART_PARTICULAR_TYPE_PRODUCT) as $product_id => $productCartparticular){
				/* @var $productCartparticular PaycartCartparticularProduct */
				$productCartparticular->calculate($this);			
			}
			
			$this->updateTotal();
		
		//@PCTODO : We'll give option for applying tax before discount, but as if now only one option
		 
		// STEP 2 : calculate promotion cartparticular
			$promotions = $this->params->get('promotions', array());
			$promotionCartparticular = PaycartCartparticular::getInstance(Paycart::CART_PARTICULAR_TYPE_PROMOTION, array('promotions', $promotions));
			$promotionCartparticular->calculate($this);
			$this->_cartparticulars[Paycart::CART_PARTICULAR_TYPE_PROMOTION][] = $promotionCartparticular; 
			$this->updateTotal();
		
		// STEP 3 : calculate duties cartparticular
			$dutiesCartparticular = PaycartCartparticular::getInstance(Paycart::CART_PARTICULAR_TYPE_DUTIES, array());
			$dutiesCartparticular->calculate($this);
			$this->_cartparticulars[Paycart::CART_PARTICULAR_TYPE_DUTIES][] = $dutiesCartparticular;
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
		
		//save the cart after calculation
		return $this->save();
	}
	
	public function loadShippingCartparticulars()
	{
		$shippingOptions 			= $this->getShippingOptionList();	
		$selectedShippingMethods 	= (array)$this->params->get('shipping', '');
		
		if(empty($selectedShippingMethods) || !isset($shippingOptions[$addressId][$selectedShippingMethods])){
			// IMP
			// shipping method is not selected or invalid
			// so reset the previous selected shipping method or set default shipping method				
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
	
	public function getShippingOptionList()
	{
		/* @var $shippingruleHelper PaycartHelperShippingrule */
		$shippingruleHelper 	= PaycartFactory::getHelper('shippingrule'); 
		$productCartparticulars = $this->getCartparticulars(Paycart::CART_PARTICULAR_TYPE_PRODUCT);
		$addressId 				= $this->shipping_address_id;
		$product_grouped_by_address[$addressId] = $productCartparticulars;
		$shippingrules_grouped_by_product = $shippingruleHelper->getRulesGroupedByProducts();
		
		return $shippingruleHelper->getDeliveryOptionList($product_grouped_by_address, $shippingrules_grouped_by_product);
	}
	
	public function checkout()
	{
		foreach ($this->getCartparticulars() as $type => $cartparticulars) {
			if(!empty($cartparticulars)){
				foreach ($cartparticulars as $cartparticular) {
					$cartparticular->save(); // @PCTODO : Error Handling 
				}
			}
		}

		$this->is_locked = true;
		return $this->save();
	}
	
	public function addMessage($key, $type, $message, $cartparticular)
	{
		// @TODO
	}
}