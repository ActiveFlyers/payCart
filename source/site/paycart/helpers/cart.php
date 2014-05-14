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
}
