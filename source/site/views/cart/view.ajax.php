<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	front-end
* @contact		support+paycart@readybytes.in
* @author		mManishTrivedi, Rimjhim Jain
*/

defined( '_JEXEC' ) or	die( 'Restricted access' );

require_once dirname(__FILE__).'/view.php';

class PaycartSiteViewCart extends PaycartSiteBaseViewCart
{	
	/**
	 * remove product from cart
	 */
	public function removeProduct()
	{	
		parent::_assignTmplVars();
		
		$this->setTpl('products');
		$this->_renderOptions = array('domObject'=>'pc-cart-products','domProperty'=>'innerHTML');
		return true;
	}	
	
	/**
	 * 
	 * Update Quantity of product
	 */
	public function updateQuantity()
	{
		return $this->removeProduct();
	}
	
	/**
	 * add product to cart
	 */
	public function addProduct()
	{
		$productId = $this->input->get('product_id');
		
		$this->assign('productId', $productId);
		$this->setTpl('product');
		return true;	
	}
}