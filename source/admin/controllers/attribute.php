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
		
	/**
	 * 
	 * Ajax call : Open popup-window whith attribute creation + Available attribute list
	 */
	public function window()
	{
		//Check Joomla Session user should be login
		if ( !JSession::checkToken() ) {
			//@PCTODO :: Rise exception 
		}
		 return true;
	}
	
	/**
	 * 
	 * Ajax call : Get Attribute elements
	 */
	public function element()
	{
		//Check Joomla Session user should be login
		if ( !JSession::checkToken() ) {
			//@PCTODO :: Rise exception 
		}

		$this->getView()->assign('type', $this->input->get('type','')); 
		return true;
	}
		
}