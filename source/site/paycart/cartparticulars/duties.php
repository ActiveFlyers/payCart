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
 * Duties Cartparticular
 * @author Gaurav Jain
 *
 */
class PaycartCartparticularDuties extends PaycartCartparticular
{
	protected $_rule_apply_on = Paycart::RULE_APPLY_ON_CART;
	
	public function bind($binddata = array()) 
	{
		$this->unit_price 		= 0;
		$this->quantity			= 1;
		$this->type 			= Paycart::CART_PARTICULAR_TYPE_DUTIES;
		$this->particular_id	= 0;
		$this->tax				= 0;
		$this->discount			= 0;
		$this->price 			= $this->getPrice();
		$this->total 			= $this->getTotal();
		
		$this->title 	= Rb_Text::_('COM_PAYCART_CARTPARTICULAR_DUTIES_TITLE'); 
		$this->message 	= Rb_Text::_('COM_PAYCART_CARTPARTICULAR_DUTIES_MESSAGE');
		
		return $this;
	}
	
	public function calculate($cart) 
	{
		$this->applyTaxrules($cart);
		return $this;
	}
	
	public function getDiscountrules($groupsRules)
	{
		// empty discount rules
		return array();
	}
}