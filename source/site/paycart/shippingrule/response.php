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
 * Object of this class should be sent by Shipping Rule Processor as a response of request.
 * Contains list or properties wihch is common for all Shipping Rule Processors.
 * Entity which has made request, will perform some actions according to response sent.
 * 
 * @since 1.0.0
 * 
 * @author Gaurav Jain
 */
class PaycartShippingruleResponse
{
	public $amount;
	
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