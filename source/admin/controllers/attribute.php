<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		team@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or	die( 'Restricted access' );

/** 
 * Product Controller
 * @author Manish Trivedi
 */

class PaycartAdminControllerAttribute extends PaycartController 
{
		
	public function window()
	{
		//Check Joomla Session user should be login
		if ( !JSession::checkToken() ) {
			//@PCTODO :: Rise exception 
		}
		 return true;
	}
		
}