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
	 * Apply Tax rule
	 * @param PaycartCart $currentCart
	 * @param PaycartCartparticular $cartParticular
	 */
	public function applyTaxrule(PaycartCart $currentCart, PaycartCartparticular $cartParticular) 
	{
		// invoke discount helper to apply all apllicable tax rules
		return PaycartFactory::getHelper('taxrule')->applyTax($cartParticular, $currentCart);
	}

	/**
	 * 
	 * Apply Discount rule
	 * @param PaycartCart $currentCart
	 * @param PaycartCartparticular $cartParticular
	 */
	public function applyDiscountrule(PaycartCart $currentCart, PaycartCartparticular $cartParticular)  
	{
		// invoke discount helper to apply all apllicable discount rules
		return PaycartFactory::getHelper('discountrule')->applyDiscount($cartParticular, $currentCart);
	}
	
	/**
	 * 
	 * Create hash key on cart-particular basis
	 * @param PaycartCartparticular $cartParticular
	 * 
	 * @return retun hash-key
	 */
	public function getHash(PaycartCartparticular $cartParticular)
	{
		$cartId 		= $cartParticular->getCartId();
		$particularId 	= $cartParticular->getParticularId();
		$particularType	= $cartParticular->getType();
		
		/**
		 * 
		 * @NOTE:: 
		 * 1#. Sequence must be "$cartId.$particularId.$particularType"  
		 * 2#. Hash must be 16 digit (hex-code) (that why second arg is true)
		 */
		$hash	=	md5($cartId.$particularId.$particularType, true);
		
		return $hash;
	}
	
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
}
