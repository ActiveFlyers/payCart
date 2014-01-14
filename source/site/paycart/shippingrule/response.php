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
	/**
	 * Set html if any html to be shown
	 * @var String
	 */
	public $html = '';
}