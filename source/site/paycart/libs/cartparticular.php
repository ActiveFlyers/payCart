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
	protected $type;
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
		
		$this->type   = '';
		$this->unit_price		 = 0;
		$this->quantity			 = Paycart::CART_PARTICULAR_QUANTITY_MINIMUM;
		
		$this->price			 = $this->unit_price * $this->quantity;
		$this->tax				 = 0;
		$this->discount			 = 0;
		
		$this->total			 = ($this->price) + ($this->tax) + ($this->discount);
		
		$this->title			 = '';
		$this->message			 = '';
		
		return $this;
	}
	
	public function getTotal()
	{
		//@PCTODO:: don't do it if rules will update total
		switch ($this->type) {
			case Paycart::CART_PARTICULAR_TYPE_PROMOTION :
				$this->total = $this->discount;
				break;

			case Paycart::CART_PARTICULAR_TYPE_DUTIES:
				$this->total = $this->tax;
				break;

			case Paycart::CART_PARTICULAR_TYPE_PRODUCT :
			default:
				$this->total = ($this->price) + ($this->tax) + ($this->discount);
				break;
		}

		return $this->total;
	}
	
	public function getType()
	{
		return $this->type;
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
	
	public function getCartId()
	{
		return $this->cart_id ;
	}
	
	public function setCartId($cartId)
	{
		$this->cart_id = $cartId;
	}
	
	/**
	 * 
	 * set discount on cart
	 * @param double $value
	 * 
	 * @TODO:: should be -ive number here 
	 */
	public function setDiscount($value)
	{
		$this->discount = $value;
		
		// discount must be -ive number or Zero
		if ($this->discount > 0) {
			$this->discount = -$this->discount;
		}
	}
	
	public function setTax($value)
	{
		$this->tax = $value;
	}

}