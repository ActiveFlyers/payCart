<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @contact		support+paycart@readybytes.in
*/

// no direct access
defined('_JEXEC') or die( 'Restricted access' );

require_once dirname(__FILE__).'/view.php';
class PaycartSiteViewAccount extends PaycartSiteBaseViewAccount
{
	public function removeAddress()
	{	
		$json = new stdClass();
		$json->address_id = $this->get('address_id', 0);
		
		$errors = $this->get('errors', array());		
		if(!empty($errors)){
			$json->isValid = false;
			$json->errors = $errors;
			$this->assign('json', $json);
			return true;
		}
		
		$json->isValid 	= true;
		$json->html 		= JText::_('COM_PAYCART_ACCOUNT_ADDRESS_DELETED'); 
		$this->assign('json', $json);
		return true;
	}
}