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
	/**
	 * 
	 * Return All available Paycart status
	 * 
	 * @return Array()
	 */
	public function getStatus()
	{
		return 
			Array(
					Paycart::STATUS_CART_DRAFT,
					Paycart::STATUS_CART_CHECKOUT,
					Paycart::STATUS_CART_PAID,
					Paycart::STATUS_CART_CANCEL,
					Paycart::STATUS_CART_COMPLETE
				)	;
	}
	
	/**
	* Invoke to get current cart whcih is mapped with current session id
	*
	* @since 1.0
	* @author Manish
	*
	* @return Paycartcart if cart exits otherwise false
	*/
	public function getCurrentCart()
	{
		// get current session id
		$session_id =	PaycartFactory::getSession()->getId();
		
		// get cart data
		$cart_data =	PaycartFactory::getModel('cart')->loadRecords(Array('session_id' => $session_id));
		
		if (!$cart_data) {
			$cart = self::createNew();
		}
		else {
			$data = array_shift($cart_data);
			$cart = PaycartCart::getInstance($data->cart_id, $data);
		}
		
		// Calculation should be done before any action
		if ( $cart instanceof PaycartCart ) {
			return $cart->calculate();
		}
		
		return $cart;
	}
	
	/**
	 * Create a new cart 
	 * @return PaycartCart
	 */
	public function createNew()
	{
		// get current session id
		$session_id =	PaycartFactory::getSession()->getId();
		$cart		= 	PaycartCart::getInstance();
		
		return $cart->setSessionId($session_id)->save();
	}
}
