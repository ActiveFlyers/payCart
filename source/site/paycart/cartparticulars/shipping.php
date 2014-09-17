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
	
	public function bind($binddata = array()) 
	{		
		$this->unit_price		= $binddata['price'];
		$this->quantity			= 1;		
		$this->type 			= Paycart::CART_PARTICULAR_TYPE_SHIPPING;
		$this->particular_id	= $binddata['shippingrule_id'];		
		$this->tax				= 0;
		$this->discount			= 0;
		$this->price 			= $binddata['price'];
		$this->total 			= $this->getTotal();
		$this->cart_id			= $binddata['cart_id'];
		$this->updateTotal();
		
		$this->title 	= Rb_Text::_('COM_PAYCART_CARTPARTICULAR_SHIPPING_TITLE');  // @PCTODO : Calulate dynamically
		$this->message 	= Rb_Text::_('COM_PAYCART_CARTPARTICULAR_SHIPPING_MESSAGE'); // @PCTODO : Calulate dynamically
		
		$this->params 		    = $binddata['params'];
		
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
		$products = $this->params['product_list'];
		foreach($products as $productId => $detail){
			$groups[Paycart::GROUPRULE_TYPE_PRODUCT] =  array_merge($groups[Paycart::GROUPRULE_TYPE_PRODUCT],$groupHelper->getApplicableRules(Paycart::GROUPRULE_TYPE_PRODUCT, $productId));	
		}
		
		return $groups;	
	}
}