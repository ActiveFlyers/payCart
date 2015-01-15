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
	 * @PCTODO: you can use save/edit method
	 */
	public function add() 
	{
		$post 				= $this->input->post->get($this->getControlNamePrefix(), array(), 'array');
		$buyeraddress_id 	= $this->_getId();

		$buyeraddress		= $this->_save($post, $buyeraddress_id);
	
		//perform redirection
		$redirect  = "index.php?option=com_paycart&view=buyer&task=edit&id={$buyeraddress->getBuyer()}";
				
		// if buyeraddress do not save succesfully   
		if(!($buyeraddress instanceof PaycartBuyeraddress)) {	
			$response = Array('message' => '');
			$ajax     = Rb_Factory::getAjaxResponse();
			$response['message'] =	'Buyeraddress was failed to save';
			$callback 			 =	'paycart.admin.buyeraddress.add.error';
			$redirect			 =	'';

			// set call back function
			$ajax->addScriptCall($callback, json_encode($response)); 				
		}
		
		$this->setRedirect( $redirect , $this->getMessage(), $msgType);

		// no need to move at view
		return false;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see /plugins/system/rbsl/rb/rb/Rb_Controller::edit()
	 */
	public function edit() 
	{
		// Id required in View
		$this->getModel()->setState('buyer_id', $this->input->get('buyer_id', 0));
	}
	
	
	
}