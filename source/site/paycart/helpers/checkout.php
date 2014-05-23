<?php

/**
 * @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @package 	PAYCART
 * @subpackage	Front-end
 * @contact		support+paycart@readybytes.in
 * @author 		mManishTrivedi
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Checkout Helper
 * @author 	mManishTrivedi
 */
class PaycartHelperCheckout extends PaycartHelper
{
	
	/**
	 * 
	 * Checkout sequence
	 * 
	 * @since	1.0
	 * @author	Manish Trivedi
	 * 
	 * @return Array (	_CURRENT_STATE_ => _NEXT_STATE_	)
	 */
	public function getSequence()
	{
		return 
				// Array (	_CURRENT_STATE_ => _NEXT_STATE_	)
				Array(
						//@PCXXX: Empty Cart
						Paycart::CHECKOUT_STEP_LOGIN 	=> Paycart::CHECKOUT_STEP_ADDRESS,
						Paycart::CHECKOUT_STEP_ADDRESS 	=> Paycart::CHECKOUT_STEP_CONFIRM,
						Paycart::CHECKOUT_STEP_CONFIRM 	=> Paycart::CHECKOUT_STEP_PAYMENT,
						Paycart::CHECKOUT_STEP_PAYMENT 	=> '' //@PCXXX: Thank you page //Paycart::CHECKOUT_STEP_PAYMENT
					);
	} 	

	
	
}
