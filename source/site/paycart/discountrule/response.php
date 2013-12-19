<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author 		mManishTrivedi 
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );


/**
 * 
 * PaycartDiscountRuleResponse required after discount rule processing
 * @author mManishTrivedi
 *
 */

class PaycartDiscountRuleResponse
{
	// Response Field: Discounted-Amount
	public $amount 			=	0;
	
	// Response Field: multiple discount further process or not. 
	public $furtherProcess 	=	true;
	
	// Response Field:if get any kind of error	
	public $error 			=	null; 
}
