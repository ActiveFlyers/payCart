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
	* @return array of availble product types.
	*/
	public static function getStatus() 
	{
		$status = array('NONE', 'CHECKOUT', 'PAID', 'SHIPPED', 'DELIVERED', 'CANCEL', 'REFUND', 'REVERSAL', 'COMPLETE');
		$data	= array();
		
		foreach ($status as $value){
			$key = 'Paycart::CART_STATUS_'.$value;
			$key = constant($key);
			$data[$key] = 'COM_PAYCART_CART_STATUS_'.$value;
		}
		
		return $data;
	}
}
