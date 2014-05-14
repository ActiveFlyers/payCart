<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Front-end
* @contact		support+paycart@readybytes.in
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * 
 * Shipping Promotion Cartparticular
 * @author Gaurav Jain
 *
 */
class PaycartCartparticularShippingpromotion extends PaycartCartparticular
{	
	protected $_rule_apply_on = Paycart::RULE_APPLY_ON_SHIPPING_TOTAL;
	
	public function bind($binddata = array()) 
	{
		$this->unit_price 		= 0;
		$this->quantity			= 1;
		$this->type 			= Paycart::CART_PARTICULAR_TYPE_SHIPPING_PROMOTION;
		$this->particular_id	= 0;
		$this->tax				= 0;
		$this->discount			= 0;
		$this->price 			= $this->getPrice();
		$this->total 			= $this->getTotal();
		
		$this->title 	= Rb_Text::_('COM_PAYCART_CARTPARTICULAR_SHIPPING_PROMOTION_TITLE'); 
		$this->message 	= Rb_Text::_('COM_PAYCART_CARTPARTICULAR_SHIPPING_PROMOTION_MESSAGE');
		
		return $this;
	}
	
	public function calculate($cart) 
	{
		$this->applyDiscountrules($cart);
		return $this;
	}	
	
	public function getTaxrules($groupsRules)
	{
		return array();
	}
}