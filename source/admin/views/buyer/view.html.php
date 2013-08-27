<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @contact		team@readybytes.in
* @author		Puneet Singhal
*/

// no direct access
defined('_JEXEC') or die( 'Restricted access' );

/** 
 * Buyer Html View
 */
require_once dirname(__FILE__).'/view.php';
class PaycartAdminViewBuyer extends PaycartAdminBaseViewBuyer
{	
	protected function _adminEditToolbar()
	{
		Rb_HelperToolbar::apply();
		Rb_HelperToolbar::save();
		Rb_HelperToolbar::cancel();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_View::edit()
	 */
	public function edit($tpl=null) {
		
		$buyer_id	=  $this->getModel()->getState('id');
		$buyer		=  PaycartBuyer::getInstance($buyer_id);
		$addresses	= PaycartHelperBuyer::getAddresses($buyer_id);
		
		$this->assign('form',  $buyer->getModelform()->getForm($buyer));
		$this->assign('addresses', $addresses);
		
		return parent::edit($tpl);
	}
	
}
