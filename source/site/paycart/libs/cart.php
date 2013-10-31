<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
* @author 		Puneet Singhal
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Cart Lib
 */
class PaycartCart extends PaycartLib
{
	protected $cart_id;
	protected $buyer_id;
	protected $address_id;
	protected $modifiers;
	protected $subtotal;
	protected $total;
	protected $currency;
	protected $status;
	protected $created_date;	
	protected $modified_date;
	protected $checkout_date;
	protected $paid_date;
	protected $complete_date;
	protected $cancellation_date;
	protected $refund_date;
	protected $params;
	protected $_cartParticulars;

	/**
	 * 
	 * Enter description here ...
	 * @param int $id
	 * @param $bindData
	 * @param $dummy1
	 * @param $dummy2
	 */
	public static function getInstance($id = 0, $bindData = null, $dummy1 = null, $dummy2 = null)
	{
		return parent::getInstance('cart', $id, $bindData);
	}
	
	public function reset() 
	{		
		$this->cart_id	 			=	0; 
		$this->buyer_id 		 	=	0;
		$this->address_id			= 	0;
		$this->modifiers 		 	=	new Rb_Registry();
		$this->subtotal		 		=	0.00;
		$this->total	 			=	0.00;
		$this->currency 		 	=	null;
		$this->status	 			=	Paycart::CART_STATUS_NONE; 	
		$this->created_date 		=	Rb_Date::getInstance();
		$this->modified_date		=	Rb_Date::getInstance();
		$this->checkout_date  		=	Rb_Date::getInstance('0000-00-00 00:00:00');	
		$this->paid_date 			=	Rb_Date::getInstance('0000-00-00 00:00:00');
		$this->complete_date 		=	Rb_Date::getInstance('0000-00-00 00:00:00');
		$this->cancellation_date 	=	Rb_Date::getInstance('0000-00-00 00:00:00');
		$this->refund_date 			=	Rb_Date::getInstance('0000-00-00 00:00:00');
		$this->params				=  	new Rb_Registry();
		$this->_cartParticulars		=	array();
		
		return $this;
	}
	
	function bind($data, $ignore = Array())
	{
		if(is_object($data)){
			$data = (array) ($data);
		}
		
		parent::bind($data, $ignore);
		
		// if cartparticulars are available in data then bind with lib object 
		$cartParticulars = isset($data['cartparticulars']) ? $data['cartparticulars'] : Array();
		
		// Bind cart particulars instance on cart lib  
		$this->_setCartParticulars($cartParticulars);

		return $this;
	}
	
	public function save()
	{
		// if error in saving, then do not save other data
		if(!parent::save()){
			return false;
		}

		return $this->_saveCartParticulars();
	}
	
	protected function _saveCartParticulars()
	{
		// insert new values into cartparticular for current cart id
		$cartId = $this->getId();
		
		$model = PaycartFactory::getInstance('cartparticulars','model');
		
		// delete all existing values
		//PCTODO: we should not delete records otherwise unnecessary id's increament will be there 
		$model->deleteMany(array('cart_id' => $cartId ));
		
		// save new cartParticulars
		foreach($this->_cartParticulars as $item){
			$product = PaycartProduct::getInstance($item->product_id);
			if($product instanceof PaycartProduct){
				$item->cart_id    = $cartId;
				$item->buyer_id   = $this->getBuyer();
				$item->title      = $product->getTitle();
				$item->unit_cost  = $product->getAmount();

				//PCTODO:: Set other parameters also like  product details
	 			$model->save($item);
			}
		}
		return $this;
	}
	
	
	protected function _setCartParticulars($cartParticulars)
	{
		$cardId = $this->getId();
		//if cart exist and cartparticulars are empty then load data from db
		if ($cardId && empty($this->_cartParticulars)) {
			$this->_cartParticulars = PaycartFactory::getInstance('cartparticulars','model')
												->loadRecords(array('cart_id' => $cartId),array(),false,'product_id');
		}

		if(!empty($cartParticulars)){
			//convert array into object
			foreach ($cartParticulars as $productId=>$item){ 
					$this->_cartParticulars[$productId] = (object)$item;
			}
		}
		
		return $this;
	}
		
	/**
	 * 
	 * Buyer of cart
	 * @param boolean $instance
	 */
	public function getBuyer($instance = false)
	{
		if($instance){
			return PaycartBuyer::getInstance($this->buyer_id);
		}
		return $this->buyer_id;
	}
	
	public function getSubtotal()
	{
		return PaycartHelper::price_format($this->subtotal);
	}
	
	public function getTotal()
	{
		return PaycartHelper::price_format($this->total);
	}
	
	public function getCurrency()
	{
		return $this->currency;
	}
	
	public function setCurrency($currency)
	{
		$this->currency = $currency;
		return $this;
	}
	
	public function getStatus()
	{
		return $this->status;
	}
	
	public function setStatus($status)
	{
		$this->status = $status;
		return $this;
	}
	
	public function getParams($object = true)
	{
		if($object){
			return $this->params->toObject();
		}

		return $this->params->toArray();
	}
	
}