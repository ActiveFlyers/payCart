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
	//protected $session_data;
	
	
	// Related Table Fields: Array of cart-particulars
	protected $_cartParticulars;
	
	//
	protected $_total;
	
	/** 
	 * 
	 * @var PaycartHelperCart
	 */
	protected $_helper;
	
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
		parent::getInstance('cart', $id, $bindData);

		//$this->_helper = PaycartFactory::getHelper('cart');

		return $this; 
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
		
		$this->status				= Paycart::STATUS_CART_NONE;
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
		//$this->session_data	= new Rb_Registry();
	
		// Related Table Fields: cart particulars libs instance
		$this->_cartParticulars		=	array();
		
		return $this;
	}
	
	/**
	 * 
	 * return table field value
	 */
	public function getBuyerId()
	{
		return $this->buyer_id;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_Lib::bind()
	 */
	function bind($data, $ignore = Array())
	{
		if(is_object($data)){
			$data = (array) ($data);
		}
		// Bind : table field
		parent::bind($data, $ignore);
		
		// Bind : related table field
		// if cartparticulars are available in data then bind with lib object 
		//$cartParticulars = isset($data['_cartParticulars']) ? $data['_cartParticulars'] : Array();
		
		$this->loadCartParticulars();
		
		return $this;
	}
	
	/**
	 * 
	 * Set Cart-particulars on Lib object
	 *  
	 */
	protected function loadCartParticulars()
	{
		$cardId = $this->getId();

		// if cart exist and cartparticulars are empty then load data from db
		if ($cardId) {
			$cartParticulars = PaycartFactory::getInstance('cartparticulars','model')
												->loadRecords(array('cart_id' => $cartId));
		}

		// bind data on cartParticulars
		foreach ($cartParticulars as $key => $bindData) {		
//			$bindData['buyer_id']		= $this->getBuyerId();
			
			// no need to recalculation
			if ($this->is_locked) {
				$cartParticular 	= PaycartCartparticular::getInstance(0, $bindData);
				continue;
			} else {
					switch ($bindData->particular_type) {
						
						case Paycart::CART_PARTICULAR_TYPE_PRODUCT :
							$cartParticular = $this->createParticularProduct($bindData);
							break;
							
						case Paycart::CART_PARTICULAR_TYPE_DUTIES :
							$cartParticular = $this->createParticularDuties($bindData);
							break;
							
						case Paycart::CART_PARTICULAR_TYPE_PROMOTION :
							$cartParticular = $this->createParticularPromotion($bindData);
							break;
							
						case Paycart::CART_PARTICULAR_TYPE_SHIPPING :
							$cartParticular = $this->createParticularShipping($bindData);
							break;
					/**
					 *	@Future purpose
					 *	case Paycart::CART_PARTICULAR_TYPE_SHIPPING_DUTIES :
					 *	case Paycart::CART_PARTICULAR_TYPE_SHIPPING_PROMOTION :
					 *	case Paycart::CART_PARTICULAR_TYPE_ADJUSTMENT :
					 **/
						default:
							throw new RuntimeException(Rb_Text::_("COMA_PAYCART_CART_PARTICULAR_TYPE_INVALID"));
					}
			}
			
			//2#. Cart-particular's changes should be reflected into cart 
			$this->_total 	= ($this->_total) + ($cartParticular->getTotal());
			
			//3#. Set cart-particular on cart
			$hashKey 	= $this->_helper->getHash($cartParticular);
			$this->_cartParticulars[$hashKey] 	= $cartParticular;
		}
		
		return $this;
	}
	
	
	/**
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_Lib::_save()
	 */
	protected function _save($previousObject)
	{
		// Save : Table fields
		$id = parent::_save($previousObject);

		//if save was not complete, then id will be null, do not trigger after save
		if(!$id){
			return false;
		}

		// correct the id, for new records required
		$this->setId($id);

		// Save :: releated table fields
		$this->_saveCartParticulars();
		
		return $id;
	}
		
	/**
	 * 
	 * Save CartParticulrs
	 */
	protected function _saveCartParticulars()
	{	
		// @PCTODO :: save all cartparticulars in one shot, If performance improvment required
		foreach( $this->_cartParticulars as $key => $cartParticular) {
			
			//Dont save if ((total of particular is 0) AND ( particular is dynamic created))
			$type = $cartParticular->gettype();
			if ( ($type == Paycart::CART_PARTICULAR_TYPE_DUTIES || $type == Paycart::CART_PARTICULAR_TYPE_PROMOTION) &&
				 $cartParticular->getTotal()) 
				{	continue; }
				
			// Save cart-particules one by one
			$cartParticular->save();
		}
		
		return $this;
	}
	
	public function getCartParticulars()
	{
		return $this->_cartParticulars;
	}
	
	/**
	 * 
	 * Add product to cart
	 * @param stdClass $product
	 *		StdClass(
	 *   				'product_id' => (INT)		Product-ID (Support only integer id)   
	 *	 				'quantity'	 	=> (INT)
	 *				//	'buyer_id'	 	=> (INT)
	 * 				 ) 
	 */
	public function addProduct($product)
	{
		//1#. Set Product type cart-particular 
		
		//create Product-type cart-particulars
		$request = new stdClass();
		$request->particular_id = $product->product_id; 	// id or product_id
		$request->quantity 		= $product->quantity; 		
		
		$cartParticular = $this->createParticularProduct($request);
		
		// Cart-particular's changes should be reflected into cart 
		$this->_total 	= ($this->_total) + $cartParticular->getTotal();
		
		// If product already exist then it will update
		//@PCTODO:: If (old quantity is 2 and add same product)  then update quantity  
		// Set cart-particular on cart
		$hashKey 	= $this->_helper->getHash($cartParticular);
		
		$this->_cartParticulars[$hashKey] 	= $cartParticular;
		
		//2#. Calculation on Cart-particulars
		$this->calculate();
		
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
		//Before start calculation
		//1#. Reset existing dynamic created particulars like product {promotion,duties} etc
		//2#. Reset Cart-Total
		$this->resetParticulars();
		
		/**
		 * Trigger before cart-calculation start
		 * 1#. You can change here Product-Price (Unit-price) 
		 * @PCTODO :: Clone {$this}, If required
		 */ 
		$args 			= Array();
		$args['cart']	= $this;

		// At that Moment cart have only {product} particulars. 
		Rb_HelperPlugin::trigger('onPaycartCartBeforeCalculate', $args);

		//1#. Process Product-type cart-particulars
		$this->calculateProducts();
		
		//@PCTODO  :: Dynamic change ordering if shipping required before {Product-promotion and Product-duties}  
		
		// Get method sequence which one invoke first
		// Build and Process Product{Promotion & Duties} type cart-particular 
		if ( Paycart::CHECKOUT_SEQUENCE_OPTION_VALUE_TAX_DISCOUNT ==  PaycartFactory::getConfig()->get('checkout_tax_discount_sequence', Paycart::CHECKOUT_SEQUENCE_OPTION_VALUE_DISCOUNT_TAX)) {
			$this->calculateDuties();
			$this->calculatePromotion();
		} else {
			$this->calculatePromotion();
			$this->calculateDuties();
		}
		
		//3# create and process shipping type cart-particulars
		$this->calculateShipping();
		
		//4#. Build and Process Product{adjustment} type cart-particular
		$this->calculateAdjustment();
		
		/**
		 * Trigger After cart-calculation
		 * @PCTODO :: Clone {$this}, If required
		 */ 
		$args['cart']	=	$this;
		Rb_HelperPlugin::trigger('onPaycartCartAfterCalculate', $args);
		
		return $this;
	}
	
	/**
	 * 
	 * Reset all cart particulares on cart
	 * @throws RuntimeException : when you will reset unknown type particular
	 */
	protected function resetParticulars()
	{
		
		// no need to re-calculation
		if ($this->is_locked) {
			return true;
		}
		
		//@NOTE: When reset cart-particulars then need to reset cart total 
		$this->_total = 0;
		
		foreach ($this->getCartParticulars() as $key => $cartParticular) {
			
			$bindData	=  new stdClass();
			
//			$bindData->buyer_id				= $this->getBuyerId();
			
			$bindData->cartparticular_id	= $cartParticular->getId();
			$bindData->particular_id		= $cartParticular->getParticularId();
			$bindData->quantity				= $cartParticular->getQuantity();
//			$bindData->type 				= $cartParticular->getType();
//			$bindData->unit_price			= $cartParticular->getPrice();
//			$bindData->price 				= 0;
			
			// no need to recalculation
			switch ($bindData->particular_type) 
			{
				case Paycart::CART_PARTICULAR_TYPE_PRODUCT :
					$cartParticular 	= $this->createParticularProduct($bindData);
					break;
					
				case Paycart::CART_PARTICULAR_TYPE_DUTIES :
					$bindData->price = 0;
					$cartParticular = $this->createParticularDuties($bindData);
					break;
					
				case Paycart::CART_PARTICULAR_TYPE_PROMOTION :
					$cartParticular = $this->createParticularPromotion($bindData);
					break;
					
				case Paycart::CART_PARTICULAR_TYPE_SHIPPING :
					$cartParticular = $this->createParticularShipping($bindData);
					break;
			/**
			 *	@Future purpose
			 *	case Paycart::CART_PARTICULAR_TYPE_SHIPPING_PROMOTION :
			 *	case Paycart::CART_PARTICULAR_TYPE_ADJUSTMENT :
			 **/
				default:
					throw new RuntimeException(Rb_Text::_("COMA_PAYCART_CART_PARTICULAR_TYPE_INVALID"));
			}
			
			//2#. Cart-particular's changes should be reflected into cart 
			//$this->_total 	= ($this->_total) + ($cartParticular->getTotal());
			
			//3#. Set cart-particular on cart
			//$hashKey 	= $this->_helper->getHash($cartParticular);
			$this->_cartParticulars[$key] 	= $cartParticular;
		}
		return $this;
	}
	/**
	 * Calculate {tax + Discount} on all product type Cart-Particulars
	 * 
	 * @return PaycartCart Lib Instance
	 */
	public function calculateProducts() 
	{
		$invoke1 ='applyDiscountrule';
		$invoke2 ='applyTaxrule';
		
		//get sequence which one invoke first 
		if ( Paycart::CHECKOUT_SEQUENCE_OPTION_VALUE_TAX_DISCOUNT ==  PaycartFactory::getConfig()->get('checkout_tax_discount_sequence', Paycart::CHECKOUT_SEQUENCE_OPTION_VALUE_DISCOUNT_TAX)) 
		{
			$invoke1 =	'applyTaxrule';
			$invoke2 =	'applyDiscountrule';
		}
		
		foreach ($this->_cartParticulars as $key => $cartParticular) {
			/**
			 * @var $cartParticular PaycartCartparticular
			 */
			//Make sure, you are processing only Product type cart-particulars
			if ( $cartParticular->getType() != Paycart::CART_PARTICULAR_TYPE_PRODUCT) {
				continue;
			}
			
			//2#. Apply {Tax and discount} on each Product
			$this->_helper->$invoke1($this, $cartParticular);
			$this->_helper->$invoke2($this, $cartParticular);
			
			// Cart-particular's changes should be reflected into cart 
			$this->_total 	 = ($this->_total) + ($cartParticular->getTotal());
			
			//3#. Set cart-particular on cart
			$this->_cartParticulars[$key] 	= $cartParticular;
		}
		
		return $this;
	}
	
	
	
	
	/**
	 * Calculate Promotion(cart-discount) on Cart 
	 * 
	 * @return PaycartLib Instance
	 */
	public function calculatePromotion()  
	{
		//Since, We have already reseat Product-Promotion
		//Therefore, We need to re-build {product-promotion} type cart-particular. Hence-Proved  
		$requestObject = new stdClass();
		$requestObject->particular_id = 0;
		
		foreach ($this->_cartParticulars as $key => $cartParticular) {
			/**
			 * @var $cartParticular PaycartCartparticular
			 */
			//Make sure, you are processing only {Product-promotion} type cart-particulars
			if ( $cartParticular->getType() == Paycart::CART_PARTICULAR_TYPE_PROMOTION) {
				$requestObject->particular_id = $cartParticular->getId();
				break;
			}			
		}
			
		//create product-promotion particular
		$cartParticular = $this->createParticularPromotion($requestObject);
		
//		// before invoke Discount system
//		$totalBeforePromotion  = $cartParticular->getTotal();
		
		//invoke tax system
		$this->_helper->applyDiscountrule($this, $cartParticular);
		
		//get hash key
		$key = $this->_helper->getHash($cartParticular);
		
		$this->_cartParticulars[$key] 	= $cartParticular;
		
//		// Treat as cart-particular, if product-promotion will be applied
//		if ($totalBeforePromotion != $cartParticular->getTotal()) {
//			$this->_cartParticulars[$key] 	= $cartParticular;
//		}else {
//			unset($this->_cartParticulars[$key]);
//		}
		
		return $this;
	}
	
	
	/**
	 * Calculate Duties(Tax-On-Tax) on Cart 
	 * 
	 * @return PaycartLib Instance
	 */
	public function calculateDuties() 
	{
		$requestObject = new stdClass();
		
		foreach ($this->_cartParticulars as $key => $cartParticular) {
			/**
			 * @var $cartParticular PaycartCartparticular
			 */
			//Make sure, you are processing only {Product-duties} type cart-particulars
			if ( $cartParticular->getType() == Paycart::CART_PARTICULAR_TYPE_DUTIES) {
				$requestObject->cartparticular_id = $cartParticular->getId();
				break;
			}
		}
			
		//create duties particular
		$cartParticular = $this->createParticularDuties($requestObject);
		
//		// before invoke TD system
//		$totalBeforeDuties  = $cartParticular->getTotal();
		
		//invoke tax system
		$this->_helper->applyTax($this, $cartParticular);
		
		//get hash key
		//$key = $this->_helper->getHash($cartParticular);
		
		$this->_cartParticulars[$key] 	= $cartParticular;
		
		// Treat as cart-particular, if duties will be applied
//		if ($totalBeforeDuties != $cartParticular->getTotal() ) {
//			$this->_cartParticulars[] 	= $cartParticular;
//		}else {
//			unset($this->_cartParticulars[$key]);
//		}

		return $this;
	}
	
	
	public function calculateAdjustment() 
	{
		return $this;
	}
	
	
	public function calculateShipping() 
	{
		return $this;
	}
	
	
	/**
	 * 
	 * Create Product type cart-particulare on $requestedProduct
	 * @param Object $requestedCartParticular 
	 * 		{
	 * 			particular_id		=> (INT) *Required
	 * 			quantity			=> (INT)
	 * 			//buyer_id			=> (INT)
	 * 			cartparticular_id	=> (INT)
	 * 		}
	 *		 
	 */
	public function createParticularProduct($requestedCartParticular = null) 
	{
		
		/**
		 * Need few validation, Before creation
		 */ 
		//#Validation-1 : Paricular Id must exist
		if (!isset($requestedCartParticular->particular_id) || !$requestedCartParticular->particular_id) {
			throw new EmptyValueException('COM_PAYCART_CART_PARTICULAR_EMPTY_PRODUCT');
		}
		
		//#Validation-2 : if Quantity have then must be equal or greater than product-min. quantity 
		if (!isset($requestedCartParticular->quantity)) {
			$requestedCartParticular->quantity = Paycart::CART_PARTICULAR_QUANTITY_MINIMUM;
		} 
		
		//@PCTODO : you will get minimum value form product, if required
		if ($requestedCartParticular->quantity < Paycart::CART_PARTICULAR_QUANTITY_MINIMUM) {
			throw new InvalidArgumentException('COM_PAYCART_CART_PARTICULAR_QUANTITY_UNDERFLOW');
		}
		
		//1# get added product
		$product = PaycartProduct::getInstance($requestedCartParticular->particular_id);
		
		$bindData = new stdClass();
		// initial data bind on Cart-particular
		if (isset($requestedProduct->cartparticular_id) ) {
			$bindData->cartparticular_id	= $requestedCartParticular->cartparticular_id;
		}
		
		$bindData->unit_price		= $product->getPrice();		// unit price
		$bindData->quantity			= $requestedCartParticular->quantity;
		$bindData->type 			= Paycart::CART_PARTICULAR_TYPE_PRODUCT;
		$bindData->particular_id	= $product->getId();
		$bindData->buyer_id			= $this->getBuyerId();
		
		$bindData->price 			= $bindData->unit_price * $bindData->quantity;
		$bindData->tax				= isset($requestedCartParticular->tax) ? $requestedCartParticular->tax : 0;
		// Should be -ive number
		$bindData->discount			= isset($requestedCartParticular->discount) ? $requestedCartParticular->discount : 0;
		
		$bindData->total 			= ($bindData->price) + ($bindData->tax) + ($bindData->discount);
		
		//2#. Build cart-particular
		$cartParticular	= PaycartCartparticular::getInstance(0, $bindData);
		
		return $cartParticular;
	}
	
	/**
	 * 
	 * Create Product-Promotion cart-particular
	 * @param Object $requestedCartParticular 
	 * 		{
	 * 			particular_id		=> (INT) 	Set particular id if already exist
	 * 		}
	 * 
	 * @return PaycartCartparticular instance with Product-Promotion type
	 */
	public function createParticularPromotion($requestedCartParticular = null) 
	{
		$bindData = new stdClass();
		// Set initial property of cart-paricular
		
		//@PCTODO:: useless $this->_total 
		
		$bindData->cartparticular_id	= isset($requestedCartParticular->cartparticular_id) ? $requestedCartParticular->cartparticular_id :0;
		$bindData->unit_price 	 		= isset($requestedCartParticular->unit_price) ? $requestedCartParticular->unit_price : $this->_total;
		$bindData->price 				= isset($requestedCartParticular->price) ? $requestedCartParticular->price : $this->_total;
		$bindData->total				= isset($requestedCartParticular->total) ? $requestedCartParticular->total : $this->_total;
		$bindData->particular_id		= 0;
		$bindData->buyer_id				= $this->getBuyerId();
		$bindData->particular_type  	= Paycart::CART_PARTICULAR_TYPE_PROMOTION;
		
		// Build cartparticular
		return  PaycartCartparticular::getInstance(0, $bindData);
	}
	
	/**
	 * 
	 * Create Product-Duties type cart-particular
	 * @param Object $requestedCartParticular 
	 * 		{
	 * 			cartparticular_id		=> (INT) Set cartparticular id if already exist
	 * 		}
	 * 
	 * @return PaycartCartparticular instance with Product-Duties type
	 */
	public function createParticularDuties($requestedCartParticular = null) 
	{
		$bindData = new stdClass();
		
		// Set initial property of cart-paricular
		$bindData->cartparticular_id	= isset($requestedCartParticular->cartparticular_id) ? $requestedCartParticular->cartparticular_id : 0;
		
		$bindData->price = 0;
		if(isset($requestedCartParticular->price)) {
			$bindData->price = $requestedCartParticular->price;
		} else{
			foreach ($this->_cartParticulars as $key => $cartParticular) {
				/**
				 * @var $cartParticular PaycartCartparticular
				 */
				//@NOTE :: include only Product-type cart-particular taxes  
				if ( $cartParticular->getType() == Paycart::CART_PARTICULAR_TYPE_PRODUCT) {
					$bindData->price += $cartParticular->getTax();
				}
			}
		}
			
		// Set initial property of cart-paricular
		$bindData->unit_price 	 	= isset($requestedCartParticular->unit_price) ? $requestedCartParticular->unit_price : $bindData->price;
		$bindData->total			= isset($requestedCartParticular->total) ? $requestedCartParticular->total : $bindData->price;
		$bindData->particular_id	= 0;
		$bindData->buyer_id			= $this->getBuyerId();
		$bindData->particular_type  = Paycart::CART_PARTICULAR_TYPE_DUTIES;
		
		// Build cartparticular
		$cartParticular	= PaycartCartparticular::getInstance(0, $bindData);
		
		return $cartParticular;
	}

	/**
	 * 
	 * Create shipping type cart-particulare on $requestedProduct
	 * @param Object $requestedCartParticular 
	 * 		{
	 * 			particular_id		=> (INT) *Required
	 * 			quantity			=> (INT)
	 * 			//buyer_id			=> (INT)
	 * 			cartparticular_id	=> (INT)
	 * 		}
	 *		 
	 */
	public function createParticularShipping($requestedCartParticular = null) 
	{
		$bindData = new stdClass();
		
		// Set initial property of cart-paricular
		$bindData->cartparticular_id	= isset($requestedCartParticular->particular_id) ? $requestedCartParticular->particular_id :0;
		
		//@PCTODO:: recalculate shipping like product
		
		// Set initial property of cart-paricular
		$bindData->particular_type  = Paycart::CART_PARTICULAR_TYPE_SHIPPING;
		
		// Build cartparticular
		$cartParticular	= PaycartCartparticular::getInstance(0, $bindData);
		
		return $cartParticular;
	}
	
}