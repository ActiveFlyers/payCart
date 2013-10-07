<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @contact		support+paycart@readybytes.in
*/

// no direct access
defined('_JEXEC') or die( 'Restricted access' );

/** 
 * Attribute Html View
 * @author mManishTrivedi
 */

require_once dirname(__FILE__).'/view.php';
class PaycartAdminViewAttribute extends PaycartAdminBaseViewAttribute 
{
	protected function _adminEditToolbar()
	{
		Rb_HelperToolbar::apply();
		Rb_HelperToolbar::save();
		Rb_HelperToolbar::cancel();
	}
	
	public function edit()
	{
		$attributeId	=  $this->getModel()->getState('id');
		$attribute		=  PaycartAttribute::getInstance($attributeId);
		
		$this->assign('form',  $attribute->getModelform()->getForm());
			
		return true;
	}
}