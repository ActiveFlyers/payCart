<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in 
*/
defined('_JEXEC') or die( 'Restricted access' );

class PaycartTaxruleRequest
{
	/**
	 * @var PaycartRequestCartparticular
	 */
	public $cartparticular;
	
	/**
	 * @var PaycartRequestBuyeraddress
	 */
	public $shipping_address;
	
	/**
	 * @var PaycartRequestBuyeraddress
	 */
	public $billing_address;
	
	/**
	 * @var PaycartRequestBuyer
	 */
	public $buyer;
		
	/**
	 * The amount on which to calculate and apply taxrate
	 * @var float
	 */
	public $taxable_amount	= 0.00;
}

class PaycartTaxruleRequestGlobalconfig {
	/**
	 * @var PaycartRequestBuyeraddress
	 */
	public $origin_address;
}

class PaycartTaxruleRequestRuleconfig {
	public $tax_rate;	
}