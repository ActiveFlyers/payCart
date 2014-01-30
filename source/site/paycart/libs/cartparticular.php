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
 * 
 * Cartparicular Lib
 * @author manish
 *
 */
class PaycartCartparticular extends PaycartLib
{
	// Table fields :
	protected $cartparticular_id; 
	protected $cart_id;
	protected $buyer_id;
	protected $particular_id;
	protected $particular_type;
	protected $unit_price;
	protected $quantity;
	protected $price;
	protected $tax;
	protected $discount;
	protected $total;
	protected $title;
	protected $message;
	
	/**
	 * 
	 * PaycartCartparticular Instance
	 * @param  $id, existing PaycartCartparticular id
	 * @param  $data, required data to bind on return instance	
	 * @param  $dummy1, Just follow code-standards
	 * @param  $dummy2, Just follow code-standards
	 * 
	 * @return PaycartCart lib instance
	 */
	public static function getInstance($id = 0, $bindData = null, $dummy1 = null, $dummy2 = null)
	{
		return parent::getInstance('cartparticular', $id, $bindData);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_Lib::reset()
	 */
	public function reset() 
	{	
		// Table fields	
		$this->cartparticular_id = 0; 
		$this->cart_id			 = 0;
		$this->buyer_id			 = 0;
		$this->particular_id	 = 0;
		
		$this->particular_type   = '';
		$this->unit_price		 = 0;
		$this->quantity			 = Paycart::CART_PARTICULAR_QUANTITY_MINIMUM;
		
		$this->price			 = $this->unit_price * $this->quantity;
		$this->tax				 = 0;
		$this->discount			 = 0;
		
		$this->total			 = ($this->price) + ($this->tax) + ($this->discount);
		
		$this->title			 = 0;
		$this->message			 = 0;
		
		return $this;
	}
	
	public function getTotal()
	{
		return ($this->price) + ($this->tax) + ($this->discount);
	}
	
	public function getType()
	{
		return $this->particular_type;
	}
	
	public function getTax()
	{
		return $this->tax;
	}
	
	public function getQuantity()
	{
		return $this->quantity;
	}
	
	public function getUnitPrice()
	{
		return $this->unit_price;
	}
	
	public function getPrice()
	{
		return $this->price;
	}
	
	public function getParticularId()
	{
		return $this->particular_id;
	}
	
	public function getDiscount()
	{
		return $this->discount;
	}
	
	public function getBuyerId()
	{
		return $this->discount;
	}	
}