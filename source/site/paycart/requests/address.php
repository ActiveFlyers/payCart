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
 * @since 1.0.0
 *  
 * @author Gaurav Jain
 */
class PaycartRequestAddress
{
	public $to;
	public $address;
	public $city;
	public $state;
	public $country;
	public $zipcode;
	public $phone1;
	public $phone2;
	public $vat_number;
}