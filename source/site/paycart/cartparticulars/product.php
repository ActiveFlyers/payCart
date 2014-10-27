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
 * Product Cartparticular
 * @author Gaurav Jain
 *
 */
class PaycartCartparticularProduct extends PaycartCartparticular
{		
	protected $_rule_apply_on = Paycart::RULE_APPLY_ON_PRODUCT;

	public function bind($binddata = array()) 
	{		
		/**
		 * Need few validation, Before creation
		 */ 
		if(is_array($binddata)) {
			$binddata = (object) $binddata;
		}
		
		//#Validation-1 : Paricular Id must exist
		if (!isset($binddata->product_id) || !$binddata->product_id) {
			throw new InvalidArgumentException('COM_PAYCART_CART_PARTICULAR_EMPTY_PRODUCT');
		}
		
		//#Validation-2 : if Quantity have then must be equal or greater than product-min. quantity 
		if (!isset($binddata->quantity) || $binddata->quantity < Paycart::CART_PARTICULAR_QUANTITY_MINIMUM) {
			// DISCUSS : We should not throw exception here, as we cart already create and one product is added
			// then minimum value is changed than this cart can never be used 
			throw new InvalidArgumentException('COM_PAYCART_CART_PARTICULAR_QUANTITY_UNDERFLOW');			
		} 
		
		//1# get added product
		$product = PaycartProduct::getInstance($binddata->product_id);		
		
		$this->unit_price		= $product->getPrice();		// unit price
		$this->quantity			= $binddata->quantity;

		$this->type 			= Paycart::CART_PARTICULAR_TYPE_PRODUCT;
		$this->particular_id	= $binddata->product_id;		
		$this->tax				= 0;
		$this->discount			= 0;
		$this->price 			= $this->getPrice();
		$this->total 			= $this->getTotal();		

		$this->updateTotal();				

		// set dimensions 
		$this->weight = $product->getWeight();
		$this->width  = $product->getWidth();
		$this->height = $product->getHeight();
		$this->length = $product->getLength();
		 
		$this->title 	= $product->getTitle(); 
		$this->message 	= Rb_Text::_('COM_PAYCART_CARTPARTICULAR_PRODUCT_MESSAGE');
		
		return $this;
	}
	
	/**
	 * 
	 * Invoke to calculate and apply Dicosunt and tax on product particular.
	 * @param PaycartCart $cart
	 * 
	 * @since 1.0
	 * @author mManisTrivedi, gaurav Jain
	 * 
	 * @return PaycartCartparticularProduct
	 */
	public function calculate(PaycartCart $cart)
	{
		$this->applyDiscountrules($cart);
		$this->applyTaxrules($cart);
				
		return $this;
	}
	
	/**
	 * (non-PHPdoc)
	 * @see /components/com_paycart/paycart/base/PaycartCartparticular::getApplicableGrouprules()
	 */
	public function getApplicableGrouprules(PaycartCart $cart)
	{
		/* @var $groupHelper PaycartHelperGroup */
		$groupHelper = PaycartFactory::getHelper('group');
		
		//@PCTODO : caching on bases { group-rules type + type_id }
		
		$groups = array();

		// get grouprules which are applicable on buyer
		$groups[Paycart::GROUPRULE_TYPE_BUYER]   = $groupHelper->getApplicableRules(Paycart::GROUPRULE_TYPE_BUYER, $cart->getBuyer());
		
		//get grouprules which are applicable on Cart 
		$groups[Paycart::GROUPRULE_TYPE_CART]    =  $groupHelper->getApplicableRules(Paycart::GROUPRULE_TYPE_CART, $cart->getId());
		
		// get grouprules which are applicable on Product
		$groups[Paycart::GROUPRULE_TYPE_PRODUCT] =  $groupHelper->getApplicableRules(Paycart::GROUPRULE_TYPE_PRODUCT, $this->particular_id);	
		
		return $groups;	
	}
}