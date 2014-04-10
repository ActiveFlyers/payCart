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

require_once dirname(__FILE__).'/view.php';
class PaycartAdminViewBuyer extends PaycartAdminBaseViewBuyer
{	
	public function addAddress()
	{
		$this->_setAjaxWinTitle(JText::_('COM_PAYCART'));
		
		$buyer_address = PaycartFactory::getModelForm('buyeraddress');
		 
		$this->assign('form',  $buyer_address->getForm());
		
		$this->setTpl('editaddress');
		
		return true;
	}
	
}