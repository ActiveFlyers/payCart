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
 * DiscountRuleRequest class required for discounrule processing 
 * @author mManishTrivedi
 *
 */
class PaycartDiscountRuleRequest
{
	// Request Field : Discount speicifc
	public $isPercentage	=	1; 
	public $amount	  		=	0;
	public $isSuccessive 	=	1;
	
	// Request Field : Cart/Product specific
	public $price	 		=	0;	// unitPrice * quantity
	public $total 			=	0;
	
	//entity object either product or cart
	public $entity 			=	null;
	//std class object
	public $discountRule	=	null;
}
