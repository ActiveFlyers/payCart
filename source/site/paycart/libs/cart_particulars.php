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
 * Cart_Particulars Lib
 */
class PaycartCart_Particulars extends PaycartLib
{
	protected $cart_particulars_id;
	protected $cart_id;
	protected $buyer_id;
	protected $product_id;
	protected $title;
	protected $quantity;
	protected $unit_cost;
	protected $tax;
	protected $discount;	
	protected $price;
	protected $shipment_date;
	protected $reversal_date;
	protected $delivery_date;
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
		return parent::getInstance('cart_particulars', $id, $bindData);
	}
	
	public function reset() 
	{
		$this->cart_particulars_id	=	0;
		$this->cart_id	 			=	0; 
		$this->buyer_id 		 	=	0;
		$this->product_id 		 	=	0;
		$this->title		 		=	null;
		$this->quantity	 			=	0;
		$this->unit_cost 		 	=	0.00;
		$this->tax	 				=	0.00; 	
		$this->discount 			=	0.00;
		$this->price				=	0.00;
		$this->shipment_date  		=	Rb_Date::getInstance('0000-00-00 00:00:00');	
		$this->reversal_date 		=	Rb_Date::getInstance('0000-00-00 00:00:00');
		$this->delivery_date 		=	Rb_Date::getInstance('0000-00-00 00:00:00');
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
	
	public function getCart($instance = false)
	{
		if($instance){
			return PaycartCart::getInstance($this->cart_id);
		}
		
		return $this->cart_id;
	}
	
	public function getProduct($instance = false)
	{
		if($instance){
			return PaycartProduct::getInstance($this->product_id);
		}
		return $this->product_id;
	}
	
	public function getUnitCost()
	{
		return PaycartHelper::price_format($this->unit_cost);
	}
	
	public function getPrice()
	{
		return PaycartHelper::price_format($this->price);
	}
	
	public function getParams($object = true)
	{
		if($object){
			return $this->params->toObject();
		}

		return $this->params->toArray();
	}
	
}
