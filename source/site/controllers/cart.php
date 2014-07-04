<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author		Puneet Singhal, mManishTrivedi
*/

// no direct access
defined( '_JEXEC' ) or	die( 'Restricted access' );

/** 
 * Cart Front end Controller
 */
class PaycartSiteControllerCart extends PaycartController
{
	
	/**
	 * Collect payment on cart
	 */
	function pay() 
	{
		$payment_data	=	$this->input->post->get('payment_data', array(), 'array');		
		$cart_id		=	$this->input->get('cart_id', 0);
		$cart_instance	=	PaycartCart::getInstance($cart_id);

		$cart_instance->collectPayment($payment_data);

		$this->setRedirect(Rb_Route::_('index.php?option=com_paycart&view=cart&task=complete&cart_id='.$cart_id));

		return false;
	}


	/**
	 * 
	 * Enter description here ...
	 */
	function complete()
	{
		$cart_id = $this->get('cart_id', 0);
		
		return true;
	}
	
	
}