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
							->loadRecords(Array('session_id' => $session_id, 'status' => Paycart::STATUS_CART_DRAFTED));

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
		$allowedQuantity = PaycartProduct::getInstance($productId)->getQuantity(); 
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
	 * return stdclass object of cartparticular of given cart id and type 
	 * @param $cartId : cart id to which the cart particular belongs
	 * @param $type : type of cart particular to be fetched
	 */
	public function getCartParticularsData($cartId, $type)
	{
		static $particularData = array();
		
		if(isset($particularData[$cartId][$type])){
			return $particularData[$cartId][$type];
		}
		
		//load data from model
		$particularData[$cartId][$type] = PaycartFactory::getModel('cartparticular')
												->loadRecords(array('cart_id' => $cartId, 'type'=>$type), array(),false, 'particular_id');
		
		return $particularData[$cartId][$type];
	}
}
