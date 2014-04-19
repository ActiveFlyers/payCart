<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in 
*/
defined('_JEXEC') or die( 'Restricted access' );

class PaycartTaxruleResponse
{
	// actual tax amount 
	public $amount   = 0;
	
	// message that will be displayed to users 
	public $message     = '';
	
	// type of message like error, warning or acknowledgement message
	public $messageType = Paycart::MESSAGE_TYPE_MESSAGE;
	
	// stores exception object
	public $exception   = null;
	
	//store configuration html of rule
	public $configHtml  = '';
}