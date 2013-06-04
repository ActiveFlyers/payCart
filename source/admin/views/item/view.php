<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		team@readybytes.in
*/

// no direct access
if(!defined( '_JEXEC' )){
	die( 'Restricted access' );
}

/** 
 * Invoice Base View
* @author Team Readybytes
 */
class PaycartAdminBaseViewInvoice extends PaycartView
{	
	/**
	 * @var PaycartHelperInvoice
	 */
	public $_helper = null;	
}