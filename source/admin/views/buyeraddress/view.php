<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @contact		support+paycart@readybytes.in
* @author		mManishTrivedi
*/

// no direct access
defined('_JEXEC') or die( 'Restricted access' );

/** 
 * Buyer-Address base View
 * @author mManishTrivedi
 */
class PaycartAdminBaseViewBuyeraddress extends PaycartView
{	
	public function edit($tpl=null)
	{
		$buyer_address_id	=  $this->getModel()->getState('id');
		$buyer_id			=  $this->getModel()->getState('buyer_id');
		$buyer_address 		= PaycartBuyeraddress::getInstance($buyer_address_id);
		
		if (!$buyer_id) {
			$buyer_id = $buyer_address->getBuyer();
		}
		
		// prepare display data
		$display_data = new stdClass();
		
		$display_data->prefix 			=	'paycart_form';
		
		$display_data->buyeraddress_id 	=	$buyer_address_id;
		$display_data->buyer_id		 	=	$buyer_id;
		$display_data->to		 		=	$buyer_address->getTo();
		$display_data->address		 	=	$buyer_address->getAddress();
		$display_data->city		 		=	$buyer_address->getCity();
		$display_data->state		 	=	$buyer_address->getState();
		$display_data->country		 	=	$buyer_address->getCountry();
		$display_data->zipcode		 	=	$buyer_address->getZipcode();
		$display_data->vat_number		=	$buyer_address->getVatnumber();
		$display_data->phone1		 	=	$buyer_address->getPhone1();
		$display_data->phone2		 	=	$buyer_address->getPhone2();
		
		// set display data
		$this->assign('display_data', $display_data);
		
		return  parent::edit($tpl);
	}
	
}