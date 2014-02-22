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
	
	// Related Table Fields: Array of cart-particulars
	protected $_particulars;
	
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
		$this->_particulars[Paycart::CART_PARTICULAR_TYPE_PRODUCT] 	 =	array();
		$this->_particulars[Paycart::CART_PARTICULAR_TYPE_PROMOTION] =	array();
		$this->_particulars[Paycart::CART_PARTICULAR_TYPE_DUTIES] 	 =	array();
		$this->_particulars[Paycart::CART_PARTICULAR_TYPE_SHIPPING]  =	array();
		
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
		return $this->_total;
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
		$this->loadParticulars();
		
		return $this;
	}
	
	/**
	 * 
	 * Set Cart-particulars on Lib object
	 *  
	 */
	protected function loadParticulars()
	{
		$cartId = $this->getId();

		// if cart is not exist then no need to load
		if (!$cartId) {
			return $this;
		}

		$particulars = PaycartFactory::getInstance('cartparticulars','model')
									->loadRecords(array('cart_id' => $cartId));
		
		// bind data on cartParticulars
		foreach ($particulars as $bindData) {		
			$bindData['buyer_id']		= $this->getBuyer();
			
			//1#. Get cart-Particular
			
			if ($this->is_locked) {
				// no need to recalculation
				$particular 	= PaycartCartparticular::getInstance(0, $bindData);
			} else {
				
				switch ($bindData->type) {
					case Paycart::CART_PARTICULAR_TYPE_PRODUCT :
						$particular = $this->createProductParticular($bindData);
						break;

					case Paycart::CART_PARTICULAR_TYPE_DUTIES :
						$particular = $this->createDutiesParticular($bindData);
						break;
							
					case Paycart::CART_PARTICULAR_TYPE_PROMOTION :
						$particular = $this->createPromotionParticular($bindData);
						break;
						
					case Paycart::CART_PARTICULAR_TYPE_SHIPPING :
						$particular = $this->createShippingParticular($bindData);
						break;
				/**
				 *	@Future purpose
				 *	case Paycart::CART_PARTICULAR_TYPE_SHIPPING_PROMOTION :
				 *	case Paycart::CART_PARTICULAR_TYPE_ADJUSTMENT :
				 **/
					default:
						throw new RuntimeException(Rb_Text::_("COMA_PAYCART_CART_PARTICULAR_TYPE_INVALID"));
				}
			}
			
			//2#. Cart-particular's changes should be reflected into cart 
			$this->_total 	= ($this->_total) + ($particular->getTotal());
			
			//3#. Set cart-particular on cart
			$hashKey 	= $this->_helper->getHash($particular);
			$type		= $particular->getType();
			
			$this->_particulars[$type][$hashKey] 	= $particular;
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
		$this->_saveParticulars();
		
		return $id;
	}
		
	/**
	 * 
	 * Save CartParticulrs
	*/
	protected function _saveParticulars()
	{	
		// @PCTODO :: save all cartparticulars in one shot, If performance improvment required
		foreach( $this->getParticulars() as $key => $particular) {
			/**
			 * @var PaycartCartparticular $particular
			 */
			$type = $particular->getType();
			
			//Don't save if ((total of particular is 0) AND ( particular is dynamic created))
			if (($type == Paycart::CART_PARTICULAR_TYPE_DUTIES) && !($particular->getTotal())) {
				continue;
			} 
			
			if (($type == Paycart::CART_PARTICULAR_TYPE_PROMOTION) && !($particular->getTotal())) {
				continue; 
			}

			// set cart id before save particular
			$particular->setCartId($this->getId());
			
			// Save cart-particules one by one
			$particular->save();
		}
		
		return $this;
	}
 
	public function getParticulars($type = null)
	{
		if ($type) {
			if (!isset($this->_particulars[$type])) {
				throw new RuntimeException(Rb_Text::_("COM_PAYCART_CART_PARTICULAR_TYPE_INVALID"));
			}
			
			return $this->_particulars[$type];
		} 
		
		return $this->_particulars;
		
	}
	
	/**
	 * 
	 * Reinitialize cart total
	 * 
	 * @return PaycartCart
	 */
	public function reinitializeTotal()
	{
		$this->_total = 0 ;
		
		foreach ($this->getParticulars() as $key => $particulars) {
			
			foreach ($particulars as $hash => $particular) {
				$this->_total = ($this->_total) + ($particular->getTotal());
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
		
		$particular = $this->createProductParticular($request);
		
		// Set cart-particular on cart
		$hashKey 	= $this->_helper->getHash($particular);
		$type		= $particular->getType();

		// If add same product (which is already exist in cart) 
		//  Add previous product quantity with new 
		if ($this->_particulars[$type][$hashKey]) {
			$previoue_particular = $this->_particulars[$type][$hashKey];
			$quantity = $previoue_particular->getQuantity() + $particular->getQuantity();
			$particular->setQuantity($quantity);
		}
		
		$this->_particulars[$type][$hashKey] 	= $particular;
		
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
		// no need to re-calculation
		if ($this->is_locked) {
			return $this;
		}
		
		//Before start calculation
		//1#. Re-initialize all cart's Particulars
		//2#. Re-initialize Cart-Total
		$this->reinitializeParticulars();
		
		/**
		 * Trigger before cart-calculation start
		 * 1#. You can change here Product-Price (Unit-price) 
		 * @NOTE :: Clone {$this}, If required
		 */ 
		$args 			= Array();
		$args['cart']	= $this;

		// At that Moment cart have only {product} particulars. 
		Rb_HelperPlugin::trigger('onPaycartCartBeforeCalculate', $args);

		//1#. Process Product-type cart-particulars
		$this->calculateProductsParticular();
		
		/**
		 * @NOTE :: Ordering(2# and 4#) should be dynamicaly changed 
		 * if shipping required before {Product-promotion and Product-duties}
		 */  
		
		// 2#  Get method sequence which one invoke first
		// 	 Build and Process Product{Promotion & Duties} type cart-particular 
		if ( Paycart::CHECKOUT_SEQUENCE_OPTION_VALUE_TAX_DISCOUNT ==  PaycartFactory::getConfig()->get('checkout_tax_discount_sequence', Paycart::CHECKOUT_SEQUENCE_OPTION_VALUE_DISCOUNT_TAX)) {
			$this->calculateDutiesParticular();
			$this->calculatePromotionParticular();
		} else {
			$this->calculatePromotionParticular();
			$this->calculateDutiesParticular();
		}
		
		//3# create and process shipping type cart-particulars
		$this->calculateShippingParticular();
		
		/**
		 * Trigger After cart-calculation
		 * @NOTE :: Clone {$this}, If required
		 */ 
		$args['cart']	=	$this;
		Rb_HelperPlugin::trigger('onPaycartCartAfterCalculate', $args);
		
		return $this;
	}
	
	/**
	 * 
	 * remove all calc and reset with latest update
	 * @throws RuntimeException : when you will reset unknown type particular
	 */
	protected function reinitializeParticulars()
	{	
		// no need to re-calculation
		if ($this->is_locked) {
			return $this;
		}
		
		//@NOTE: When reset cart-particulars then need to reset cart total 
		$this->_total = 0;
				
		$productParticulars 	= $this->getParticulars(Paycart::CART_PARTICULAR_TYPE_PRODUCT);
		$promotionParticulars 	= $this->getParticulars(Paycart::CART_PARTICULAR_TYPE_PROMOTION);
		$dutiewParticulars 		= $this->getParticulars(Paycart::CART_PARTICULAR_TYPE_DUTIES);
		$shippingParticulars 	= $this->getParticulars(Paycart::CART_PARTICULAR_TYPE_SHIPPING);
				
		/**
		 * @NOTE:: Do not change following sequence
		 * You can change {2#, 3#} after 4# if you need to shipping first before promotion and duties, Cart{discount, tax}
		 */

		//1#. Reinitialize: Product-Particular
		foreach ($productParticulars as $key => $particular) {
			$this->_reInitializeParticular($particular);
		}
		
		//2#. Reinitialize: Promotion-Particular
		foreach ($promotionParticulars as $key => $particular) {
			$this->_reInitializeParticular($particular);
		}
		
		//3#. Reinitialize: Duties-Particular
		foreach ($dutiewParticulars as $key => $particular) {
			$this->_reInitializeParticular($particular);
		}
		
		//4#. Reinitialize: Shipping-Particular
		foreach ($shippingParticulars as $key => $particular) {
			$this->_reInitializeParticular($particular);
		}
		
		// After reinitialize particular update new cart total
		$this->reinitializeTotal();
		
		return $this;
	}
	
	/**
	 * 
	 * Re-initialize speicfic particular
	 * @param PaycartCartparticular $particular
	 * @throws RuntimeException
	 * 
	 */
	private function _reInitializeParticular(PaycartCartparticular $particular)
	{
		$bindData	=  new stdClass();

		// don't change following property on re-initialization
		$bindData->cartparticular_id	= $particular->getId();
		$bindData->particular_id		= $particular->getParticularId();
		$bindData->quantity				= $particular->getQuantity();
			
		// no need to recalculation
		switch ($particular->getType()) 
		{
			case Paycart::CART_PARTICULAR_TYPE_PRODUCT :
				$particular 	= $this->createProductParticular($bindData);
				break;
				
			case Paycart::CART_PARTICULAR_TYPE_DUTIES :
				$particular = $this->createDutiesParticular($bindData);
				break;
				
			case Paycart::CART_PARTICULAR_TYPE_PROMOTION :
				$particular = $this->createPromotionParticular($bindData);
				break;
				
			case Paycart::CART_PARTICULAR_TYPE_SHIPPING :
				$particular = $this->createShippingParticular($bindData);
				break;

			default:
				throw new RuntimeException(Rb_Text::_("COMA_PAYCART_CART_PARTICULAR_TYPE_INVALID"));
		}
		
		// Set cart-particular on cart
		$key 	= $this->_helper->getHash($particular);
		$type	= $particular->getType();
			
		$this->_particulars[$type][$key] 	= $particular;
		
		return $this;
	} 
	
	/**
	 * Calculate {tax + Discount} on all product type Cart-Particulars
	 * 
	 * @return PaycartCart Lib Instance
	 */
	public function calculateProductsParticular() 
	{
		$invoke1 ='applyDiscountrule';
		$invoke2 ='applyTaxrule';
		
		//1# Get sequence which one invoke first 
		if ( Paycart::CHECKOUT_SEQUENCE_OPTION_VALUE_TAX_DISCOUNT ==  PaycartFactory::getConfig()->get('checkout_tax_discount_sequence', Paycart::CHECKOUT_SEQUENCE_OPTION_VALUE_DISCOUNT_TAX)) 
		{
			$invoke1 =	'applyTaxrule';
			$invoke2 =	'applyDiscountrule';
		}
		
		foreach ($this->getParticulars(Paycart::CART_PARTICULAR_TYPE_PRODUCT) as $key => $particular) {
			/**
			 * @var $particular PaycartCartparticular
			 */			
			//2#. Apply {Tax and discount} on each Product
			$this->_helper->$invoke1($this, $particular);
			$this->_helper->$invoke2($this, $particular);
		}
		
		return $this;
	}
	
	
	
	
	/**
	 * Calculate Promotion(cart-discount) on Cart 
	 * 
	 * @return PaycartLib Instance
	 */
	public function calculatePromotionParticular()  
	{
		//Since, We have already reset Product-Promotion
		//Therefore, We need to re-build {product-promotion} type cart-particular. Hence-Proved  
		$requestObject = new stdClass();
		
		// Promotion have value array 
		foreach ($this->getParticulars(Paycart::CART_PARTICULAR_TYPE_PROMOTION) as $key => $particular) {
			//Already created Promotion type's cart-particulars
			$requestObject->cartparticular_id = $particular->getId();
		}
		
		//create product-promotion particular
		$particular = $this->createPromotionParticular($requestObject);
		
		//get hash key
		$key = $this->_helper->getHash($particular);
		
		$this->_particulars[Paycart::CART_PARTICULAR_TYPE_PROMOTION][$key] 	= $particular;

		//invoke tax system
		$this->_helper->applyDiscountrule($this, $particular);

			
		return $this;
	}
	
	
	/**
	 * Calculate Duties(Tax-On-Tax) on Cart 
	 * 
	 * @return PaycartLib Instance
	 */
	public function calculateDutiesParticular() 
	{
		$requestObject = new stdClass();
		
		// Duties have single value array 
		foreach ($this->getParticulars(Paycart::CART_PARTICULAR_TYPE_DUTIES) as $key => $particular) {
			//Already created Duties type cart-particulars
			$requestObject->cartparticular_id = $particular->getId();
		}
		
		//create duties particular
		$particular = $this->createDutiesParticular($requestObject);
		
		//get hash key
		$key = $this->_helper->getHash($particular);
		
		$this->_particulars[Paycart::CART_PARTICULAR_TYPE_DUTIES][$key] = $particular;
		
		//invoke tax system
		$this->_helper->applyTaxrule($this, $particular);

		return $this;
	}
	
	
	public function calculateShippingParticular() 
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
	public function createProductParticular($requestedCartParticular = null) 
	{
		
		/**
		 * Need few validation, Before creation
		 */ 
		//#Validation-1 : Paricular Id must exist
		if (!isset($requestedCartParticular->particular_id) || !$requestedCartParticular->particular_id) {
			throw new InvalidArgumentException('COM_PAYCART_CART_PARTICULAR_EMPTY_PRODUCT');
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
		if (isset($requestedCartParticular->cartparticular_id) ) {
			$bindData->cartparticular_id	= $requestedCartParticular->cartparticular_id;
		}
		
		$bindData->unit_price		= $product->getPrice();		// unit price
		$bindData->quantity			= $requestedCartParticular->quantity;
		$bindData->type 			= Paycart::CART_PARTICULAR_TYPE_PRODUCT;
		$bindData->particular_id	= $product->getId();
		$bindData->buyer_id			= $this->getBuyer();
		$bindData->cart_id			= $this->getId();
		
		$bindData->price 			= $bindData->unit_price * $bindData->quantity;
		$bindData->tax				= isset($requestedCartParticular->tax) ? $requestedCartParticular->tax : 0;
		// Should be -ive number
		$bindData->discount			= isset($requestedCartParticular->discount) ? $requestedCartParticular->discount : 0;
		
		$bindData->total 			= ($bindData->price) + ($bindData->tax) + ($bindData->discount);
		
		//2#. Build cart-particular
		$particular	= PaycartCartparticular::getInstance(0, $bindData);
		
		return $particular;
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
	public function createPromotionParticular($requestedCartParticular = null) 
	{
		$bindData = new stdClass();
		// Set initial property of cart-paricular
			
		$bindData->cartparticular_id	= isset($requestedCartParticular->cartparticular_id) ? $requestedCartParticular->cartparticular_id :0;
		$bindData->unit_price 	 		= isset($requestedCartParticular->unit_price) ? $requestedCartParticular->unit_price : $this->_total;
		$bindData->price 				= isset($requestedCartParticular->price) ? $requestedCartParticular->price : $this->_total;
		$bindData->total				= isset($requestedCartParticular->total) ? $requestedCartParticular->total : 0;
		$bindData->particular_id		= 0;
		$bindData->buyer_id				= $this->getBuyer();
		$bindData->cart_id				= $this->getId();
		$bindData->type				  	= Paycart::CART_PARTICULAR_TYPE_PROMOTION;

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
	public function createDutiesParticular($requestedCartParticular = null) 
	{
		$bindData = new stdClass();
		
		// Set initial property of cart-paricular
		$bindData->cartparticular_id	= isset($requestedCartParticular->cartparticular_id) ? $requestedCartParticular->cartparticular_id : 0;
		
		$bindData->price = 0;
		if(isset($requestedCartParticular->price)) {
			$bindData->price = $requestedCartParticular->price;
		} else{
			foreach ($this->getParticulars(Paycart::CART_PARTICULAR_TYPE_PRODUCT) as $key => $particular) {
				//@NOTE :: include only Product-type cart-particular taxes  
				$bindData->price += $particular->getTax();
			}
		}
			
		// Set initial property of cart-paricular
		$bindData->unit_price 	 	= isset($requestedCartParticular->unit_price) ? $requestedCartParticular->unit_price : $bindData->price;
		$bindData->total			= isset($requestedCartParticular->total) ? $requestedCartParticular->total : 0 ;
		$bindData->particular_id	= 0;
		$bindData->cart_id			= $this->getId();
		$bindData->buyer_id			= $this->getBuyer();
		$bindData->type  			= Paycart::CART_PARTICULAR_TYPE_DUTIES;
		
		// Build cartparticular
		$particular	= PaycartCartparticular::getInstance(0, $bindData);
		
		return $particular;
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
	public function createShippingParticular($requestedCartParticular = null) 
	{
		$bindData = new stdClass();
		
		// Set initial property of cart-paricular
		$bindData->cartparticular_id	= isset($requestedCartParticular->particular_id) ? $requestedCartParticular->particular_id :0;
		
		//@PCTODO:: recalculate shipping like product
		
		// Set initial property of cart-paricular
		$bindData->type  = Paycart::CART_PARTICULAR_TYPE_SHIPPING;
		$bindData->cart_id			= $this->getId();
		$bindData->buyer_id			= $this->getBuyer();
		
		// Build cartparticular
		$particular	= PaycartCartparticular::getInstance(0, $bindData);
		
		return $particular;
	}
	
}