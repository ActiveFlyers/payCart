<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author		Puneet Singhal, rimjhim
*/

// no direct access
defined( '_JEXEC' ) or	die( 'Restricted access' );

class PaycartAdminControllerCart extends PaycartController 
{
	/**
	 * Json task : Create new shipment from the current cart
	 */
	public function createShipment()
	{
		return true;
	}

	/**
	 * Json task : remove shipment from the current cart
	 */
	public function removeShipment()
	{
		return true;
	}	
}