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
 * Shipping Cartparticular
 * @author Gaurav Jain
 *
 */
class PaycartCartparticularShipping extends PaycartCartparticular
{	
	protected $_rule_apply_on = Paycart::RULE_APPLY_ON_SHIPPING;	
	protected $_details = array(); 
	
	public function bind($binddata = array()) 
	{
		$this->unit_price 		= $data['total_price_without_tax'];
		$this->quantity			= 1;		
		$this->type 			= Paycart::CART_PARTICULAR_TYPE_SHIPPING;
		$this->particular_id	= $binddata['shippingrule_id'];		
		$this->tax				= 0;
		$this->discount			= 0;
		$this->price 			= $this->getPrice();
		$this->total 			= $this->getTotal();

		$this->title 	= Rb_Text::_('COM_PAYCART_CARTPARTICULAR_SHIPPING_TITLE');  // @PCTODO : Calulate dynamically
		$this->message 	= Rb_Text::_('COM_PAYCART_CARTPARTICULAR_SHIPPING_MESSAGE'); // @PCTODO : Calulate dynamically
		
		$this->_details 		= $data;
		
		return $this;
	}
	
	public function calculate($cart) 
	{
		$this->applyDiscountrules($cart);
		$this->applyTaxrules($cart);
		return $this;
	}
	
	public function getApplicableGrouprules(PaycartCart $cart)
	{
		/* @var $groupHelper PaycartHelperGroup */
		$groupHelper = PaycartFactory::getHelper('group');
		$groups		 = array();
		
		//@PCTODO : caching
	    $groups[Paycart::GROUPRULE_TYPE_BUYER] = $groupHelper->getApplicableRules(Paycart::GROUPRULE_TYPE_BUYER, $cart->getBuyer());

		$groups[Paycart::GROUPRULE_TYPE_CART] =  $groupHelper->getApplicableRules(Paycart::GROUPRULE_TYPE_CART, $cart->getId());
		
		$groups[Paycart::GROUPRULE_TYPE_PRODUCT] = array();
		$products = $this->_details['product_list'];
		foreach($products as $productId){
			$groups[Paycart::GROUPRULE_TYPE_PRODUCT] =  array_merge($groups[Paycart::GROUPRULE_TYPE_PRODUCT],$groupHelper->getApplicableRules(Paycart::GROUPRULE_TYPE_PRODUCT, $productId));	
		}
		
		return $groups;	
	}
}