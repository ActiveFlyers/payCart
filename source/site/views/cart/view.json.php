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

class PaycartSiteViewCart extends PaycartSiteBaseViewCart
{
	/**
	 * Get HTML from Payment Gateway
	 */
	public function paymentForm()
	{
		$this->json = new stdClass();
				
		$errors = $this->get('errors', array());
		if(!empty($errors)){
			$this->json->valid  = false;
			$this->json->errors = $errors;
			return true;
		}
		
		$response_object	=	PaycartFactory::getHelper('invoice')->getBuildForm($this->cart);
		
		$this->assign('response_object', $response_object); 
		
		if ( empty($response_object->post_url )) {
			$response_object->post_url = 'index.php?option=com_paycart&view=cart&task=paymentForm&cart_id='.$this->cart->getId(); 
		}
				
		$html =  $this->loadTemplate('payment_form');
		
		$this->json->valid = true;
		$this->json->html 		= $html;
		$this->json->post_url 	= $response_object->post_url;
		
		return true;
	}
	
	function order()
	{
		$errors = $this->get('errors', array());
		if(!empty($errors)){
			$this->json->isValid = false;
			$this->json->errors = $errors;
			return true;
		}
		
		$this->json->isValid 	= true;
		$this->json->html 		= JText::_('Good!! Go and collect Payment'); 
		
		return true;
	}
	
	public function getBuyerAddress()
	{
		$buyeraddress = PaycartBuyeraddress::getInstance($this->input->get('buyeraddress_id'));
		// user is not loggedin , no need to serve any abbdress
		
		$user = JFactory::getUser();
		if (!$user->get('id') && $buyeraddress->getBuyer() == $user->get('id') ) {
			$this->json->isValid	= 	true;
			$error 					= new stdClass();
			$error->message			= JText::_('not permitted');
			$error->message_type	= Paycart::MESSAGE_TYPE_ERROR;
			$error->for				= 'header';
			$this->json->errors 	= array($error);
			return true;
		}
		
		$this->json->isValid			= 	true;
		$this->json->selector_index		=	$this->input->get('selector_index');
		$this->json->buyeraddress 		= 	$buyeraddress->toArray();
		
		return true;
	}
	
	protected function _basicFormSetup($task){
		return true;
	}
	
	
	public function updateProductQuantity()
	{
		$response = new stdClass();
		$response->valid = true;
		
		$errors = $this->get('errors', array());
		if(!empty($errors)){
			$response->valid  = false;
			$response->errors = $errors;
		}
		
		$response->productId 		= $this->get('productId', 0);
		$response->prevQuantity 	= $this->get('prevQuantity', 0);
		$response->allowedQuantity 	= $this->get('allowedQuantity', 0);

		$this->assign('json', $response);
		return true;
	}
	
	public function removeProduct()
	{
		$response = new stdClass();
		$response->valid = true;
		
		$errors = $this->get('errors', array());
		if(!empty($errors)){
			$response->valid  = false;
			$response->errors = $errors;
		}
		
		$response->productId 		= $this->get('productId', 0);

		$this->assign('json', $response);
		return true;		
	}
	
	public function applyPromotion()
	{
		$response = new stdClass();
		$response->valid = true;
		
		$errors = $this->get('errors', array());
		if(!empty($errors)){
			$response->valid  = false;
			$response->errors = $errors;
		}
		
		$this->assign('json', $response);
		return true;		
	}
	
	function changeShippingMethod()
	{
		$response = new stdClass();
		$response->valid = true;
		
		$errors = $this->get('errors', array());
		if(!empty($errors)){
			$response->valid  = false;
			$response->errors = $errors;
		}
		
		$this->assign('json', $response);
		return true;	
	}
}