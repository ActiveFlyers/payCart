<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	front-end
* @contact		support+paycart@readybytes.in
*/

// no direct access
if(!defined( '_JEXEC' )){
	die( 'Restricted access' );
}

/** 
 * Cart Html View
* @author rimjhim
 */
require_once dirname(__FILE__).'/view.php';

class PaycartSiteViewCart extends PaycartSiteBaseViewCart
{	
	/**
	 * Display cart details
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_View::display()
	 */
	public function display($tpl = NULL)
	{	
		return parent::_assignTmplVars();
	}
}