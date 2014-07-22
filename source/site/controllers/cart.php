<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author		Puneet Singhal, mManishTrivedi
*/

// no direct access
defined( '_JEXEC' ) or	die( 'Restricted access' );

/** 
 * Cart Front end Controller
 */
class PaycartSiteControllerCart extends PaycartController
{
	
	/**
	 * Collect payment on cart
	 */
	function pay() 
	{
		$payment_data	=	$this->input->post->get('payment_data', array(), 'array');		
		$cart_id		=	$this->input->get('cart_id', 0);
		$cart_instance	=	PaycartCart::getInstance($cart_id);

		$cart_instance->collectPayment($payment_data);

		$this->setRedirect(Rb_Route::_('index.php?option=com_paycart&view=cart&task=complete&cart_id='.$cart_id));

		return false;
	}


	/**
	 * 
	 * Enter description here ...
	 */
	function complete()
	{
		$cart_id = $this->get('cart_id', 0);
		
		return true;
	}
	
	
	function cancel()
	{
		echo "Payment cancelllllllllllllllllll";
		exit;
	}
	
	
	
	public function notify()
	{
		$get 				= $this->input->getArray($_GET);	//Rb_Request::get('GET'); 
		$post 				= $this->input->getArray($_POST);	//Rb_Request::get('POST');
		
		$response_data 		= array_merge($get, $post);
		
		$response 			= new stdClass();
		$response->data 	= $response_data;
		$response->__post	= $post;
		$response->__get	= $get;

		//file_put_contents(JPATH_SITE.'/tmp/'.time(), var_export($response_data,true), FILE_APPEND);
		
		if (defined('JDEBUG') && JDEBUG) {
			// @PCFIXME:: dump data
		}
		
		if(!isset($response_data['processor'])){
			// @PCFIXME:: dump data and notify to admin	
		}
		
		try {
			/* @var $invoice_helper_instance PaycartHelperInvoice */
			$invoice_helper_instance = PaycartFactory::getHelper('invoice');
			$invoice_id = $invoice_helper_instance->getNotificationInvoiceId($response);
			
			$cart_records	= 	PaycartFactory::getModel('cart')->loadRecords(Array('invoice_id' => $invoice_id));
			$cart_record 	=	array_pop($cart_records);
			
			$cart = PaycartCart::getInstance(0, $cart_record);
			
			$cart->processNotification($response);

		} catch (Exception $ex) {
			//@PCTODO :: dump exception into log file
			$ex->getMessage();
		}
		echo "Success!!";
		exit;				
	}
	
	function display($cachable = false, $urlparams = array())
	{
		return parent::display();
	}
	
	public function buy()
	{
		$this->_addProduct();
		$this->setRedirect(PaycartRoute::_('index.php?option=com_paycart&view=cart'));
		return false;
	}
	
	/**
	 * 
	 * Ajaxified task to add product
	 */
	public function addProduct()
	{
		$this->_addProduct();
		return true;
	}
	
	/**
	 * 
	 * Ajaxified task to any product from cart
	 */
	public function removeProduct()
	{
		//get current cart
		$helper = PaycartFactory::getHelper('cart');
		$cart 	= $helper->getCurrentCart();
		
		//delete product
		$productId = $this->input->get('product_id',0,'INT');
		$cart->removeProduct($productId);
		
		$cart->calculate()->save();
		
		return true;
	}
	
	/**
	 * 
	 * Ajaxified task to update quantity
	 */
	public function updateQuantity()
	{
		return $this->_addProduct();
	} 

	/**
	 * add product the current cart
	 */
    protected function _addProduct()
	{
		$productId = $this->input->get('product_id',0,'INT');
		$quantity  = $this->input->get('quantity',1,'INT');
		
		//get current cart
		$helper = PaycartFactory::getHelper('cart');
		$cart 	= $helper->getCurrentCart();
		
		//add product
		$product = new stdClass();
		$product->product_id = $productId;
		$product->quantity   = $quantity;
		
		$cart->addProduct($product);
		$cart->calculate()->save();
	}
}