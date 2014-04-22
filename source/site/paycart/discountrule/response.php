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
 * PaycartDiscountRuleResponse required after discount rule processing
 * @author mManishTrivedi
 *
 */
class PaycartDiscountruleResponse
{
	// Response Field : Discounted-Amount
	public $amount 				=	0;
	
	// Response Field : stop all next rules processing. 
	public $stopFurtherRules 	=	false;
	
	// Response Field : need to display any kind of msg for user/admin 	
	public $message				=	null;
	
	// Response Field : {'message', 'warning', 'notice', 'error' }	
	public $messageType			=	Paycart::MESSAGE_TYPE_MESSAGE;
	
	// Response Field : Set this var, If any exception occurred 
	public $exception			=	'';
	
	// Response Field : Processor config html  	
	public $configHtml			=	'';
	
	// Response Field : Processor html  	
	public $html				=	'';
}