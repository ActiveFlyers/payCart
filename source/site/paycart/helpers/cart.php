<?php

/**
 * @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @package 	PAYCART
 * @subpackage	Front-end
 * @contact		team@readybytes.in
 * @author 		Puneet Singhal
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Product Helper
 */
class PaycartHelperCart extends PaycartHelper
{	
	static protected $cached_cart = null;
	
	/**
	* Invoke to get current cart whcih is mapped with current session id
	*
	* @param $requireNew : If true then create new cart only if any cart doesn't exist
	* 					   If false then check for existing cart and return existing cart (if any) otherwise false
	* @since 1.0
	* @author Manish
	* 
	* @return Paycartcart if cart exits otherwise false
	*/
	public function getCurrentCart($requireNew = false)
	{
		if(self::$cached_cart){
			return self::$cached_cart;
		}
		
		// get current session id
		$session_id =	PaycartFactory::getSession()->getId();
		
		// get cart data
		$cart_data =	PaycartFactory::getModel('cart')
							->loadRecords( Array( 'session_id' => $session_id,
											  	  'status' => Paycart::STATUS_CART_DRAFTED,
												  'is_locked'=>0 )
										  );

		// if cart doesn't exist and new cart is not requested then don't create new cart 
		// and return false
		if(empty($cart_data) && !$requireNew){
			return false;
		}					
		
		if (empty($cart_data)) {
			$cart = $this->_createNew();
		}
		else {
			$data = array_shift($cart_data);
			$cart = PaycartCart::getInstance($data->cart_id, $data);
		}
		
		self::$cached_cart = $cart;
		
		// Calculation should be done before any action
		if ( $cart instanceof PaycartCart ) {
			$cart->calculate();
		}
		
		return self::$cached_cart;
	}
	
   /**
	* Invoke to check is-current cart or not (cart exist into session or not )
	*
	* @param $cart_id : Cart id
	* 					  
	* @since 1.0
	* @author Manish
	* 
	* @return (bool) true if cart is exist otherwise false
	*/
	public function isSessionCart($cart_id)
	{
		// get current session id
		$session_id =	PaycartFactory::getSession()->getId();
		
		// get cart data
		$cart_data =	PaycartFactory::getModel('cart')
							->loadRecords( Array( 'session_id' 	=> $session_id,
											  	  'cart_id' 	=> $cart_id )
										  );
										  
		return !empty($cart_data);
	}
	
	/**
	 * Create a new cart 
	 * @return PaycartCart
	 */
	private function _createNew()
	{
		// get current session id
		$session_id =	PaycartFactory::getSession()->getId();
		$cart		= 	PaycartCart::getInstance();
		
		$cart->setSessionId($session_id);
		return $cart->save();
	}
	
	/**
	 * 
	 * Invoke to add product +calculate
	 * @param INT $productId
	 * @param INT $quantity
	 * 
	 * @return PaycartCart
	 */
	public function addProduct($productId, $quantity)
	{
		$cart 	= $this->getCurrentCart(true);
		$prevQuantity = isset($cart->getParam('products')->$productId)?$cart->getParam('products')->$productId->quantity:1;
		
		//validate quantity before adding product
		//if the given quantity is greater than the avaiable quantity of product 
		// PCFIXME #123: then through a message to user showing the maximum quantity he can order for this item 
		$product = PaycartProduct::getInstance($productId);
		$allowedQuantity = $product->getQuantity(); 
		if($quantity > $allowedQuantity){
			return array(false,$prevQuantity,$productId,$allowedQuantity);
		}
		
		//add product
		$product = new stdClass();
		$product->product_id = $productId;
		$product->quantity   = $quantity;
		
		$cart->addProduct($product);
		$cart->calculate()->save();
		
		return array(true,$prevQuantity,$productId,$allowedQuantity);
	}
	
	public function isProductExist($productId)
	{
		$cart = $this->getCurrentCart();
		
		//if no cart exist then no need to check for products
		if(!$cart){
			return false;
		}
		
		$existingProducts = $cart->getParam('products');
		
		// product is not already added, set it with quantity 1
		if(!isset($existingProducts->$productId)){
			return false;
		}
		
		return true;
	}
	
	/**
	 * 
	 * Invoke to apply promotion on {Producr/cart + Calculate}
	 * @param string $promotion_code
	 * 
	 * @return PaycartCart instance 
	 */
	public function applyPromotion($promotion_code)
	{
		$cart = $this->getCurrentCart();
		$cart->addPromotionCode($promotion_code);
		return $cart->calculate()->save();
	}
	
	/**
	 * 
	 * Invoke to remove promotion on {Producr/cart + Calculate}
	 * @param string $promotion_code
	 * 
	 * @return PaycartCart instance 
	 */
	public function removePromotion($promotion_code)
	{
		$cart = $this->getCurrentCart();
		$cart->removePromotionCode($promotion_code);
		return $cart->calculate()->save();
	}
	
	/**
	 * return stdclass object of cartparticular of given cart id and type 
	 * @param $cartId : cart id to which the cart particular belongs
	 * @param $type : type of cart particular to be fetched. if null then all types will be returned
	 */
	public function getCartParticularsData($cartId, $type = null)
	{
		static $particularData = array();
		
		if(!$cartId){
			throw new RuntimeException('Invalid cart id');
		}
		
		if(!isset($particularData[$cartId])){
			//load data from model
			$records = PaycartFactory::getModel('cartparticular')
													->loadRecords(array('cart_id' => $cartId), array(),false);
			
			foreach ($records as $particularId => $data){
				$particularData[$cartId][$data->type][$data->particular_id] =  $data;
				$particularData[$cartId][$data->type][$data->particular_id]->params = json_decode($data->params);
			}	
		}
		
		if(!$type && isset($particularData[$cartId])){
			return $particularData[$cartId];
		}
		
		if($type && isset($particularData[$cartId][$type])){
			return $particularData[$cartId][$type];
		}
		
		return array();
	}
	
	/**
     * get all the shipments of the given cart
     */
	public function getShipments($cartId)
	{
		return PaycartFactory::getModel('shipment')->loadRecords(array('cart_id'=>$cartId));
	}
	
	/**
	 * get cart total when cart has been locked and all particulars have been saved
     * PCTODO: remove it when clean function to get cart particulars from cart
	 */
	public function getTotal($cart)
	{
		$total = 0 ;
		
		foreach ($this->getCartParticularsData($cart->getId()) as $type => $cartparticulars) {
			foreach ($cartparticulars as $cartparticular) {
				$total = ($total) + ($cartparticular->total);
			}			
		}
		
		return $total;
	}

	/**
	 * build json object with number of product in current cart  
	 */
	public function getProductCount() 
	{
		$cart = PaycartFactory::getHelper('cart')->getCurrentCart();
		
		$products_count = 0;
		
		if ( $cart instanceof Paycartcart ) {
			foreach ($cart->getCartparticulars(Paycart::CART_PARTICULAR_TYPE_PRODUCT) as $product) {
				$products_count += $product->getQuantity();
			}
		}

		return $products_count;
	}
	
	/**
	 * 
	 * Invoke to get Promotion code
	 * @param array $applied_promotion_particular_ids
	 * @param array $available_promotion_code
	 * 
	 * @author mManishTrivedi
	 * @since 1.1
	 * 
	 * @return Array applied promotion code
	 */
	public function getAppliedPromotionCode(Array $applied_promotion_particular_ids, Array $available_promotion_code)
	{
		$applied_promotion_code = Array();
		
		if (empty($available_promotion_code)) {
			return $applied_promotion_code;
		}
		
		foreach ($available_promotion_code as $value) {
			$promotion_code_string = "'$value'" ;
		}

		// get discount rule which have applied coupon code
		$promotion_rules = PaycartFactory::getModel('discountrule')
								->loadRecords(Array('coupon'=> Array( Array('IN', "($promotion_code_string)" ))));

		if (empty($promotion_rules)) {
			return $applied_promotion_code;
		}
			
		foreach ($promotion_rules as $promotion_rule) {
			// make sure  applied promotion and coupon code match it means promotion code applied
			if ( in_array($promotion_rule->coupon, $available_promotion_code) && 
				 in_array($promotion_rule->discountrule_id, $applied_promotion_particular_ids) ) {
				$applied_promotion_code[] = $promotion_rule->coupon;  	
			}
		}
		
		return $applied_promotion_code;
	}
	
	/**
     * Create default shipments/packages, which cound be edited by admin in backend 
     */
	function createDefaultShipments(PaycartCart $cart)
	{
		$cartId = $cart->getId();
		$particulars = $this->getCartParticularsData($cartId,Paycart::CART_PARTICULAR_TYPE_SHIPPING);
		
		foreach ($particulars as $particular){
			$data = array();
			$data['cart_id'] 		   = $cartId;
			$data['est_delivery_date'] = $particular->params->delivery_date;
			$data['shippingrule_id']   = $particular->particular_id;
			$data['status']			   = Paycart::STATUS_SHIPMENT_PENDING;
			$data['products'] 		   = array();
			$data['actual_shipping_cost'] = $particular->price;
			$data['weight'] = 0;
			
			foreach ($particular->params->product_list as $productId => $details){
				$data['products'][] = array('product_id' => $productId, 'quantity' => $details->quantity);	
				$data['weight'] += (PaycartProduct::getInstance($productId)->getWeight() * $details->quantity);
			}
			
			PaycartShipment::getInstance(0,$data)->save();
		}
	}
	
	function getDetailedCartData($cart_id)
	{
		$cart		  =  PaycartCart::getInstance($cart_id);
		$data		  = array();
		$data['cart'] = $cart;
		$data['product_particular']		= array();
		$data['promotion_particular']	= array();
		$data['duties_particular']		= array();
		$data['shipping_particular']	= array();
		
		if($cart->isLocked()){
			// collect all particular details		
			$data['product_particular']	  = $this->getCartparticularsData($cart->getId(),Paycart::CART_PARTICULAR_TYPE_PRODUCT);
			$data['promotion_particular'] = $this->getCartparticularsData($cart->getId(),Paycart::CART_PARTICULAR_TYPE_PROMOTION);
			$data['duties_particular']	  = $this->getCartparticularsData($cart->getId(),Paycart::CART_PARTICULAR_TYPE_DUTIES);
			$data['shipping_particular']  = $this->getCartparticularsData($cart->getId(),Paycart::CART_PARTICULAR_TYPE_SHIPPING);
			
			$cartparticulars = array();
			$particulars = array_merge($data['product_particular'], $data['promotion_particular'], $data['duties_particular'], $data['shipping_particular']);
			foreach($particulars as $particular){
					$cartparticulars[$particular->cartparticular_id] = $particular;
			}
					
			//collect usage of tax, discount and shipping
			$usage = PaycartFactory::getModel('usage')->loadRecords(array('cart_id' => $cart_id));
					
			$usageDetails = array();
			foreach ($usage as $id => $use){
				// this will always be unique, cartparticular_type and particular id
				$key = $cartparticulars[$use->cartparticular_id]->type.'-'.$cartparticulars[$use->cartparticular_id]->particular_id;
				if(!isset($usageDetails[$key])){
					$usageDetails[$key] 		=  array();
				}
				
				if(!isset($usageDetails[$key][$use->rule_type])){
					$usageDetails[$key][$use->rule_type] = array();
				}
					 
				$usageDetails[$key][$use->rule_type][] = $use->message;
			}
			$data['usageDetails'] = $usageDetails;
		}
		else{
			// calculate the cart
			$cart->calculate();
			// collect all particular details		
			$carparticulars['product_particular'] 		= $cart->getCartparticulars(Paycart::CART_PARTICULAR_TYPE_PRODUCT);
			$carparticulars['promotion_particular'] 	= $cart->getCartparticulars(Paycart::CART_PARTICULAR_TYPE_PROMOTION);
			$carparticulars['duties_particular'] 		= $cart->getCartparticulars(Paycart::CART_PARTICULAR_TYPE_DUTIES);
			$carparticulars['shipping_particular']  	= $cart->getCartparticulars(Paycart::CART_PARTICULAR_TYPE_SHIPPING);
			
			foreach ($carparticulars as $name => $particulars){
				
				$tmppraticulars =$usageDetails = array();
				foreach($particulars as $particular){
					$usage = $particular->getUsage();
					
					// IMP :: This is patch for promotion and duties type processors
					// as we need to pass true to get actual total
					$total = $particular->getTotal(true);
					$particular = $particular->toObject();
					$particular->total = $total;
					 
					
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
				}
				
				${$name} = $tmppraticulars;
			}
			$data['usageDetails'] = $usageDetails;
		}

		
		$shippingMethods = array();
		foreach ($data['shipping_particular'] as $particular){
			$shippingMethods[$particular->particular_id] = PaycartShippingrule::getInstance($particular->particular_id)->getTitle();
		}
		
		$data['shippingMethods'] = $shippingMethods;
		//load shipments
		$shipmentModel = PaycartFactory::getModel('shipment');
		$shipments    = $shipmentModel->loadRecords(array('cart_id' => $cart_id)); 
		
		foreach ($shipments as $key => $shipment){
			$shipments[$key]->notes = json_decode($shipment->notes, true); 
		}
		
		$data['shipments'] = 
		$data['transactions'] = Rb_EcommerceAPI::transaction_get_records(array('invoice_id' => $cart->getInvoiceId()));
		
		return $data;
	}
}
