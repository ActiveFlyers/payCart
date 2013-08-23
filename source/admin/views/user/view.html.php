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
 * User Html View
 */
require_once dirname(__FILE__).'/view.php';
class PaycartAdminViewUser extends PaycartAdminBaseViewUser
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
		
		$user_id	=  $this->getModel()->getState('id');
		$user		=  PaycartUser::getInstance($user_id);
		$addresses	= PaycartHelperUser::getAddresses($user_id);
		
		$this->assign('form',  $user->getModelform()->getForm($user));
		$this->assign('addresses', $addresses);
		
		return parent::edit($tpl);
	}
	
}
