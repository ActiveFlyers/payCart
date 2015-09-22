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
class PaycartSiteAjaxViewAccount extends PaycartSiteBaseViewAccount
{
	public function addNewAddress()
	{	
		$buyer_id			=  PaycartFactory::getUser()->id;		
		
		// prepare display data
		$display_data = new stdClass();
		
		$display_data->prefix 			= 'paycart_buyeraddress_form';
		
		$display_data->buyeraddress_id 	= '';
		$display_data->buyer_id		 	= '';
		$display_data->to		 		= '';
		$display_data->address		 	= '';
		$display_data->city		 		= '';
		$display_data->state_id		 	= '';
		$display_data->country_id	 	= '';
		$display_data->zipcode		 	= '';
		$display_data->vat_number		= '';
		$display_data->phone		 	= '';
		$display_data->record_id		= 0;
		// set display data
		$this->assign('display_data', $display_data);
		$this->setTpl('address_new');
		return true;
	}
	
	public function cancelorder()
	{
		$cart_id = $this->input->get('cart_id','0');
		$this->assign('cart_id', $cart_id);
		$this->setTpl('cancel_confirmation');
		$this->_renderOptions = array('domObject'=>'pc-account-confirmation','domProperty'=>'innerHTML');
		return true;
	}
}