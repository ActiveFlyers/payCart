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
	public $title		= '';
	public $unit_price	= 0;
	public $quantity 	= 1;
	public $price		= 0;
	public $discount	= 0;
	public $tax			= 0;
	public $total		= 0;
	
	//Required for shipping rule processor
	public $length;
	public $width;
	public $height;
	public $weight;
}