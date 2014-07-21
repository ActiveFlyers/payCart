<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author		Puneet Singhal
*/

// no direct access
if(!defined( '_JEXEC' )){
	die( 'Restricted access' );
}

/** 
 * Cart Front end Controller
 */
class PaycartSiteControllerCart extends PaycartController
{
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