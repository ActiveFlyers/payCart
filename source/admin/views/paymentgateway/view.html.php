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
 * Paymentgateway Html View
 * @author mMAnishTrivedi 
 */
require_once dirname(__FILE__).'/view.php';
class PaycartAdminViewPaymentgateway extends PaycartAdminBaseViewPaymentgateway
{	
	public function edit($tpl = null)
	{
		$itemid	= $this->getModel()->getId();
		
		$paymentgateway = PaycartPaymentgateway::getInstance($itemid);
		
		$form =  $paymentgateway->getModelform()->getForm();
		
		$this->assign('form', $form );
		
		return parent::edit($tpl);
	}
	
	
}
