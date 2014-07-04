<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	front-end
* @contact		support+paycart@readybytes.in
* @author		mManishTrivedi
*/

defined( '_JEXEC' ) or	die( 'Restricted access' );
/**
 * 
 * Cart Html View
 * @author Manish
 *
 */
require_once dirname(__FILE__).'/view.php';

class PaycartSiteViewcart extends PaycartSiteBaseViewcart
{
	
	/**
	 * 
	 * Enter description here ...
	 */
	function complete()
	{
		$cart_id = $this->get('cart_id', 0);
		
		$cart	=	PaycartCart::getInstance($cart_id);
		
		var_export($cart);
		return false;
		
		return true;
	}

}