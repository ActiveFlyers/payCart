<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support_paycart@readybytes.in
* @author		mManish Trivedi
*/

// no direct access
defined( '_JEXEC' ) or	die( 'Restricted access' );

class PaycartAdminControllerBuyeraddress extends PaycartController 
{

	/**
	 * 
	 * Ajax Call create new buyer-address
	 */
	public function add() 
	{
		$post 				= $this->input->post->get('paycart_form', array(), 'array');
		$buyeraddress_id 	= $this->_getId();

		$buyeraddress		= $this->_save($post, $buyeraddress_id);
		
		$response = Array('message' => '');
		
		$ajax = Rb_Factory::getAjaxResponse();
	
		// default callback method
		$response['message'] =	'//PCTODO: Oops!! Buyeraddress fail to save. :(';
		$callback 			 =	'paycart.admin.buyeraddress.add.error';
		$redirect			 =	'';
				
		// if buyeraddress succesfully save  
		if($buyeraddress instanceof PaycartBuyeraddress) {			
			$response['message']	= '//PCTODO: GOOD!! Buyeraddress successfully save. Now you need to fetch buyeraddress html and append into buyeraddreess template ';
			$callback 				= 'paycart.admin.buyeraddress.add.success';
	
			//perform redirection
			$redirect  = "index.php?option=com_paycart&view=buyer&task=edit&id={$buyeraddress->getBuyer()}";
			
		}
		
		// set call back function
		$ajax->addScriptCall($callback, json_encode($response)); 
		
		$this->setRedirect( $redirect , $this->getMessage(), $msgType);

		// no need to move at view
		return false;
	}
	
	public function edit() 
	{
		// Id required in View
		$this->getModel()->setState('buyer_id', JFactory::getApplication()->input->get('buyer_id', 0));
	}
	
	
	
}