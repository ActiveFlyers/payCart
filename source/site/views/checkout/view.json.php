<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	front-end
* @contact		support+paycart@readybytes.in
* @author		mManishTrivedi
*/

defined( '_JEXEC' ) or	die( 'Restricted access' );
/**
 * 
 * Checkout JSON View
 * @author Manish
 *
 */
require_once dirname(__FILE__).'/view.php';

class PaycartSiteViewCheckout extends PaycartSiteBaseViewCheckout
{
	/**
	 * Get HTML from Payment Gateway
	 */
	public function getPaymentFormHtml()
	{
		$this->json = new stdClass();
		
		// message type set by controller
		if ($this->message_type ) {
			$this->json->message_type	= $this->message_type;
			$this->json->message	 	= $this->message;
			
			return true;
		}
		
		$response_object	=	PaycartFactory::getHelper('invoice')->getBuildForm($this->cart);
		
		$this->assign('response_object', $response_object); 
		
		if ( empty($response_object->post_url )) {
			$response_object->post_url = 'index.php?option=com_paycart&view=cart&task=pay&cart_id='.$this->cart->getId(); 
		}
				
		$html =  $this->loadTemplate('paynow');
		
		$this->json->html 		= $html;
		$this->json->post_url 	= $response_object->post_url;
		
		return true;
	}
	
	/**
	 * 
	 * Enter description here ...
	 */
	function checkout()
	{
		// message type set by controller
		if ($this->message_type ) {
			$this->json->message_type	= $this->message;
			$this->json->message	 	= $this->message_type;
			
			return true;
		}
		
		$this->json->html 		= 'Good!! Go and collect Payment'; 
		
		return true;
	}
	
	public function getBuyerAddress()
	{
		$buyeraddress = PaycartBuyeraddress::getInstance($this->input->get('buyeraddress_id'));
		// user is not loggedin , no need to serve any abbdress
		
		$user = JFactory::getUser();
		if (!$user->get('id') && $buyeraddress->getBuyer() == $user->get('id') ) {
			$this->json->message		=		JText::_('not permitted');
			$this->json->message_type	=	 	Paycart::MESSAGE_TYPE_ERROR;
			
			return true;
		}
		
		$this->json->selector_index		=	$this->input->get('selector_index');
		$this->json->buyeraddress 		= 	$buyeraddress->toArray();
		
		return true;
	}
	
	function _basicFormSetup() {
		return true;
	}
}