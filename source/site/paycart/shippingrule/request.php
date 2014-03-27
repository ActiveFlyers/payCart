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
 * Contains the defination of variaous object to be used by processors
 * @since 1.0.0
 *  
 * @author Gaurav Jain
 */
class PaycartShippingruleRequest
{
	/**
	 * @var PaycartRequestParticular[]
	 */
	public $particulars = array();
	
	/**
	 * @var PaycartRequestAddress
	 */
	public $delivery_address;
	
	/**
	 * @var PaycartRequestAddress
	 */
	public $origin_address;
}


// @TODOD : common config for all
class PaycartShippingruleRequestGlobalconfig {
	public $dimenssion_unit;
	public $weight_unit; 
}

class PaycartShippingruleRequestRuleconfig {
	public $packaging_weight;	
	public $handling_charge;
}