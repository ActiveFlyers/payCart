<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in 
*/

defined('_JEXEC') or die( 'Restricted access' );

/**
 * Lib for Shipping Rule
 * 
 * @since 1.0.0
 *  
 * @author Gaurav Jain
 */
class PaycartShippingRule extends PaycartLib 
{	
	protected $shippingrule_id	= 0;
	protected $processor_type	= '';
	
	/**
	 * @var PaycartHelperShippingRule
	 */
	protected $_helper = null;
	
	public function __construct($config = array())
	{
		parent::__construct($config);
		$this->_helper = PaycartFactory::getHelper('shippingrule');
	}
	
	/**
	 * @return PaycartShippingRule
	 */
	public static function getInstance($id = 0, $bindData = null, $dummy1 = null, $dummy2 = null)
	{
		return parent::getInstance('shippingrule', $id, $bindData);
	}
	
	public function reset() 
	{	
		return $this;
	}
	
	public function getConfigHtml()
	{
		$processor = $this->_helper->getProcessor('flatrate');
		return $processor->request('confightml', new PaycartShippingruleRequest())->html;		
	}
	
	public function getPackageShippingCost($product_list, $include_tax)
	{
		$price = array(2 => 5, 3 => 10, 4 => 17, 5 =>1);
		return $price[$this->shippingrule_id];
	}
	
	public function getOrdering()
	{
		$ordering = array(2 => 1, 3 => 2, 4 => 3, 5 => 4);
		return $ordering[$this->shippingrule_id];
	}
	
	public function getGrade()
	{
		$grades = array(2 => 5, 3 => 9, 4 => 7,  5 =>1);
		return $grades[$this->shippingrule_id];
	}
	
	protected function _createRequest($cart_id)
	{		
		// $cart
		// $buyer
		// array of products
				
		$request 		= new PaycartShippingruleRequest();
		$request->cart 	= $this->_createCartRequestObject();
		$request->buyer = $this->_createBuyerRequestObject();	
		$request->product = $this->_createProductRequestObject();
		// get applicable products : by using class checking
	}
	
	protected function _createCartRequestObject()
	{
		$cart = new stdClass();
		// total of cart
		$cart->total = 100;
		
		return $cart;
	}
	
	protected function _createBuyerRequestObject()
	{
		$buyer 				= new stdClass();
		$buyer->username 	= 'username';
		$buyer->name 		= 'name';
		$buyer->email 		= 'email@email.com';

		return $buyer;
	}
	
	protected function _createProductRequestObject()
	{
		$product 			= new stdClass();
		$product->title 	= "Product";
		$product->unitPrice = 5;
		$product->quantity	= 5;
		$product->price		= 25;
		$product->discount	= -5;
		$product->tax		= 5;
		$product->total		= 25;
		
		// address
		
		return $product;
	}
}