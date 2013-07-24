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
			return PaycartUser::getInstance($this->buyer_id);
		}
		return $this->buyer_id;
	}
	
	public function getSubtotal()
	{
		return PaycartHelper::price_format($this->subtotal);
	}
	
	public function setSubtotal($subtotal)
	{
		$this->subtotal = $subtotal;
		return $this;
	}
	
	public function getTotal()
	{
		return PaycartHelper::price_format($this->total);
	}
	
	public function setTotal($total)
	{
		$this->total = $total;
		return $this;
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
