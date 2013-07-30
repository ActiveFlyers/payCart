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

class PaycartSiteControllerAttribute extends PaycartController 
{
	/**
	 * Task for all ajax call with action. 
	 * All Ajax actiom must be define in same class otherwise they will not invoke.
	 * 
	 * @throws Exception
	 * 
	 * @PCTODO::  Validation required Dont use one function if you are calling from ajax
	 * Use JSON formate for this kind of method (Where you can get only data from server. no validation or task execution required)
	 */
	public function go() 
	{
		// PCTODO :: Joomla Session check.
		
		$user = PaycartFactory::getUser(); 
		// Validate is admin or not
		if(!$user->get('isRoot') ) {
			return false;
		}
		
		$method = $this->input->get('method');
		
		if(!$method) {
			throw new Exception(Rb_Text::sprintf('COM_PAYCART_INVALID_POST_DATA', '$method missing'));
		}
		
		if(method_exists($this, $method)) {
			$this->$method();
		}
		return true;
	}
		
}