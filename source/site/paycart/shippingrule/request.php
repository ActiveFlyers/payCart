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
	protected $cart 		= null;
	protected $products 	= null;
	protected $addresses 	= null;
	protected $buyer		= null;
	
}