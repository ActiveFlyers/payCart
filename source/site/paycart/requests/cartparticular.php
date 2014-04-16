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
class PaycartRequestCartparticular 
{
	public $type;
	public $title;
	public $unit_price;
	public $quantity;
	public $price;
	public $discount;
	public $tax;
	public $total;
	
	//Required for shipping rule processor
	public $length;
	public $width;
	public $height;
	public $weight;
}