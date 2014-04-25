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
class PaycartAdminViewBuyeraddress extends PaycartAdminBaseViewBuyeraddress
{	

	/**
	 * (non-PHPdoc)
	 * @see /plugins/system/rbsl/rb/rb/Rb_View::edit()
	 */
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
		
		
		// Tile of Modal
		$title = 'COM_PAYCART_BUYERADDRESS_ADD_NEW';
		if($buyer_address_id) {
			$title = 'COM_PAYCART_BUYERADDRESS_EDIT';
		}

		// Set window title 
		$this->assign('model_title', $title);
		
		return  parent::edit($tpl);
	}
	
	/**
	 * 
	 * Add buyeraddress
	 * @param $tpl
	 * 
	 * @return bool false always.  
	 */
	public function add($tpl=null)
	{
		$response = Array('message' => '');
		
		$ajax = Rb_Factory::getAjaxResponse();
	
		// default call back method
		$response['message']	= '//PCTODO: GOOD!! Buyeraddress successfully save. Now you need to fetch buyeraddress html and append into buyeraddreess template ';
		$callback 				= 'paycart.admin.buyeraddress.add.success';
		//
		if(!$this->getModel()->getState('id')) {			
			$response['message'] =	'//PCTODO: Oops!! Buyeraddress fail to save. :(';
			$callback 			 =	'paycart.admin.buyeraddress.add.error';
		}
		
		// set call back function
		$ajax->addScriptCall($callback, json_encode($response)); 
		
		// return false : no need to load any template
		return false;
		;
	}
	
}