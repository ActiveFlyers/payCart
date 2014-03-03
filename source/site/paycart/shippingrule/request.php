<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in 
*/
defined('_JEXEC') or die( 'Restricted access' );
/**
 * Object of this class should be sent to Shipping Rule Processor.
 * Contains list or properties wihch is common for all Shipping Rule Processors.
 *
 * @since 1.0.0
 *  
 * @author Gaurav Jain
 */
class PaycartShippingruleRequest
{
	/**
	 * @var PaycartShippingruleRequestProduct[]
	 */
	public $products = array();
	
	/**
	 * @var PaycartShippingruleRequestAddress
	 */
	public $delivery_address;
	
	/**
	 * @var PaycartShippingruleRequestAddress
	 */
	public $origin_address;
	
	/**
	 * @var PaycartShippingruleRequestConfig
	 */
	public $config;
}

class PaycartShippingruleRequestProduct
{
	public $title;
	public $unit_price;
	public $quantity;
	public $price;
	public $discount;
	public $tax;
	public $total;
	
	public $length;
	public $width;
	public $height;
	public $weight;
}

class PaycartShippingruleRequestAddress
{
	public $line1;
	public $line2;
	public $city;
	public $state;
	public $country;
	public $zipcode;
	public $phone;
}

class PaycartShippingruleRequestConfig
{
	public $dimenssion_unit;
	public $weight_unit; 
	public $packaging_weight;	
	public $handling_charge;
}