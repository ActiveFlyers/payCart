<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @contact		support+paycart@readybytes.in
* @author		mManishTrivedi
*/

// no direct access
defined('_JEXEC') or die( 'Restricted access' );

/** 
 * Buyer-address Html View
 * @author mManishTrivedi
 */
require_once dirname(__FILE__).'/view.php';
class PaycartAdminViewBuyeraddress extends PaycartAdminBaseViewBuyeraddress
{	
	protected function _adminEditToolbar()
	{
		Rb_HelperToolbar::cancel();
	}
}
