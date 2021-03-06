<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @contact		support+paycart@readybytes.in
* @author 		mManishTrivedi
*/

// no direct access
defined('_JEXEC') or die( 'Restricted access' );

/** 
 * Buyeraddress Ajax View
 * @author mManishTrivedi
 */
require_once dirname(__FILE__).'/view.php';
class PaycartAdminAjaxViewBuyeraddress extends PaycartAdminBaseViewBuyeraddress
{	

	/**
	 * (non-PHPdoc)
	 * @see /plugins/system/rbsl/rb/rb/Rb_View::edit()
	 */
	public function edit($tpl=null)
	{
		$buyer_address_id	=  $this->getModel()->getState('id',0);
		$buyer_id			=  $this->getModel()->getState('buyer_id');
		$buyer_address 		= PaycartBuyeraddress::getInstance($buyer_address_id);
		
		if (!$buyer_id) {
			$buyer_id = $buyer_address->getBuyer();
		}
		
		// prepare display data
		$display_data = new stdClass();
		
		$display_data->prefix 			=	'paycart_buyeraddress_form';
		
		$display_data->buyeraddress_id 	=	$buyer_address_id;
		$display_data->buyer_id		 	=	$buyer_id;
		$display_data->to		 		=	$buyer_address->getTo();
		$display_data->address		 	=	$buyer_address->getAddress();
		$display_data->city		 		=	$buyer_address->getCity();
		$display_data->state_id		 	=	$buyer_address->getStateId();
		$display_data->country_id	 	=	$buyer_address->getCountryId();
		$display_data->zipcode		 	=	$buyer_address->getZipcode();
		$display_data->vat_number		=	$buyer_address->getVatnumber();
		$display_data->phone		 	=	$buyer_address->getPhone();
		
		// set display data
		$this->assign('display_data', $display_data);
		
		
		// Tile of Modal
		$title = 'COM_PAYCART_BUYERADDRESS_ADD_NEW';
		if($buyer_address_id) {
			$title = 'COM_PAYCART_BUYERADDRESS_EDIT';
		}

		// Set window title 
		$this->assign('model_title', $title);
		
		return  parent::edit($tpl);
	}
	
}