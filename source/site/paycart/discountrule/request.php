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
 * 
 * DiscountRuleRequest class required for discounrule processing 
 * @author mManishTrivedi
 *
 */	
class PaycartDiscountruleRequest
{
	/**
	 * @var PaycartRequestParticular
	 */
	public $particular;
	
	/**
	 * @var PaycartRequestAddress
	 */
	public $shipping_address;
	
	/**
	 * @var PaycartRequestAddress
	 */
	public $billing_address;
	
	/**
	 * @var PaycartRequestBuyer
	 */
	public $buyer;
	
	/**
	 * @var float
	 */
	public $cart_total = 0.00;
	public $cart_particular_quantity	=	0;
	
	public $previous_applied_rules	=	Array();	// used when checking clubbale 
	
	public $discountable_amount	= 0;
	//	public $particular_coupon	 			=	NULL;		// @PCTODO: cart or particular. if user have entered any coupon code
}

class PaycartDiscountruleRequestGlobalconfig {
	
}

class PaycartDiscountruleRequestRuleconfig {
	public $is_percentage		=	1; 		 
	public $amount	  			=	0;
	public $is_successive 		=	1;
	public $is_clubbable 		=	1;
	public $usage_limit			=	1;		// rule usage limit
	public $buyer_usage_limit	=	1;		// buyer usage limit as per rule
	public $coupon				=	null;	// If rule have coupon code then set it
}