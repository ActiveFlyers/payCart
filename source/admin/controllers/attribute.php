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
	 * Ajax call : Get elements of Attribute Configuration
	 */
	public function getTypeConfig()
	{
		//Check Joomla Session user should be login
		if ( !JSession::checkToken() ) {
			//@PCTODO :: Rise exception 
		}
		return true;
	}
	
	/**
	 * 
	 * Ajax Call create new attribute
	 */
	public function create() 
	{
		$attribute = parent::save();
		// Id required in View
		// IMP:: don't put category_id in property name otherwise it will not work 
		$this->getModel()->setState('id', $attribute->getId());
		return true;
	}
	
}