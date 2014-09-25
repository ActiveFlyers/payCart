<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	front-end
* @contact		support+paycart@readybytes.in
* @author		mManishTrivedi, Rimjhim Jain
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
	function complete()
	{
		$cart_id 	= $this->get('cart_id', 0);		
		$cart		= PaycartCart::getInstance($cart_id);
		$this->setTpl('complete');
		return true;
	}
	
	/**
	 * Display cart details
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_View::display()
	 */
	public function display($tpl = NULL)
	{	
		return parent::_assignTmplVars();
	}
	
	public function checkout()
	{
		$this->assign('is_platform_mobile', PaycartFactory::getApplication()->client->mobile);
		return true;
	}
}