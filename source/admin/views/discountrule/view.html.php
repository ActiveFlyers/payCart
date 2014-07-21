<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in 
*/

defined('_JEXEC') or die( 'Restricted access' );

/**
 * Admin Html View for Discount Rules
 * 
 * @since 1.0.0
 */
require_once dirname(__FILE__).'/view.php';
class PaycartAdminViewDiscountrule extends PaycartAdminBaseViewDiscountrule
{
	public function edit($tpl = null)
	{
		$itemid	  		= $this->getModel()->getId();
		$discountrule 	= PaycartDiscountrule::getInstance($itemid);
		$form 	  		= $discountrule->getModelform()->getForm($discountrule);
		
		$this->assign('form', $form );
		
		return parent::edit($tpl);
	}
}