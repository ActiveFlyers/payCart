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
 * Promotion Cartparticular 
 * @author Gaurav Jain
 *
 */
class PaycartCartparticularPromotion extends PaycartCartparticular
{	
	protected $_rule_apply_on = Paycart::RULE_APPLY_ON_CART;
	
	public function bind($binddata = array()) 
	{
		if (!is_object($binddata)) {
			$binddata = (object) $binddata;
		}
		 
		$this->unit_price 		= $binddata->unit_price;
		$this->quantity			= 1;		
		$this->type 			= Paycart::CART_PARTICULAR_TYPE_PROMOTION;
		$this->particular_id	= $binddata->particular_id;	
		$this->tax				= 0;
		$this->discount			= 0;
		$this->price 			= $this->unit_price; // dont recalculate cart total just assign unit-price 
		
		$this->total 			= $this->getTotal(true);
		
		$this->updateTotal();
		
		$this->title 	= JText::_('COM_PAYCART_CARTPARTICULAR_PROMOTION_TITLE'); 
		$this->message 	= JText::_('COM_PAYCART_CARTPARTICULAR_PROMOTION_MESSAGE');
		
		if(isset($binddata->promotions)){
			$this->_applied_promotions = $binddata->promotions;
		}
		
		return $this;
	}
	
	public function getTotal($is_calculated_amount = false) 
	{
		if ($is_calculated_amount) {
			return $this->discount;
		}
		
		return parent::getTotal($is_calculated_amount);
	}
	
	public function calculate($cart) 
	{
		$this->applyDiscountrules($cart);		
		return $this;
	}
	
	public function getTaxrules(Array $groupRules = Array())
	{
		// empty tax rules
		return array();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see /components/com_paycart/paycart/base/PaycartCartparticular::save()
	 */
	public function save(PaycartCart $cart) 
	{
		$this->updateTotal();
		
		// no need to save if total is zero or any +ive value
		if ($this->getTotal(true) < 0) { 
			return parent::save($cart);
		}
		
		return $this;
	}
}