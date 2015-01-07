<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @contact		team@readybytes.in
* @author		Puneet Singhal
*/

// no direct access
defined('_JEXEC') or die( 'Restricted access' );

/** 
 * Product Html View
* @author Team Readybytes
 */
require_once dirname(__FILE__).'/view.php';
class PaycartAdminHtmlViewCart extends PaycartAdminBaseViewCart
{	
	protected function _adminGridToolbar()
	{
		Rb_HelperToolbar::deleteList(Rb_Text::_('COM_PAYCART_ENTITY_DELETE_CONFIRMATION'));
	}
	
	protected function _adminEditToolbar()
	{
            Rb_HelperToolbar::cancel();
	}
	
	/**
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_View::edit()
	 */
	public function edit($tpl=null) {
		
		$cart_id	=  $this->getModel()->getState('id');
		$cart		=  PaycartCart::getInstance($cart_id);
		
		$cartHelper	= PaycartFactory::getHelper('cart');
		$usageDetails = array();
		
		if($cart->isLocked()){
			// collect all particular details		
			$product_particular	  = $cartHelper->getCartparticularsData($cart->getId(),Paycart::CART_PARTICULAR_TYPE_PRODUCT);
			$promotion_particular = $cartHelper->getCartparticularsData($cart->getId(),Paycart::CART_PARTICULAR_TYPE_PROMOTION);
			$duties_particular	  = $cartHelper->getCartparticularsData($cart->getId(),Paycart::CART_PARTICULAR_TYPE_DUTIES);
			$shipping_particular  = $cartHelper->getCartparticularsData($cart->getId(),Paycart::CART_PARTICULAR_TYPE_SHIPPING);
			
			$cartparticulars = array();
			foreach(array($product_particular, $promotion_particular, $duties_particular, $shipping_particular) as $particulars){
				foreach($particulars as $particular){
					$cartparticulars[$particular->cartparticular_id] = $particular;
				}
			}
					
			//collect usage of tax, discount and shipping
			$usage = PaycartFactory::getModel('usage')->loadRecords(array('cart_id' => $cart_id));
					
			$usageDetails = array();
			foreach ($usage as $id => $use){
				// this will always be unique, cartparticular_type and particular id
				$key = $cartparticulars[$use->cartparticular_id]->type.'-'.$cartparticulars[$use->cartparticular_id]->particular_id;
				if(!isset($usageDetails[$key])){
					$usageDetails[$key] 		=  array();
				}
				
				if(!isset($usageDetails[$key][$use->rule_type])){
					$usageDetails[$key][$use->rule_type] = array();
				}
					 
				$usageDetails[$key][$use->rule_type][] = $use->message;
			}
		}
		else{
			// calculate the cart
			$cart->calculate();
			// collect all particular details		
			$carparticulars['product_particular'] 		= $cart->getCartparticulars(Paycart::CART_PARTICULAR_TYPE_PRODUCT);
			$carparticulars['promotion_particular'] 	= $cart->getCartparticulars(Paycart::CART_PARTICULAR_TYPE_PROMOTION);
			$carparticulars['duties_particular'] 		= $cart->getCartparticulars(Paycart::CART_PARTICULAR_TYPE_DUTIES);
			$carparticulars['shipping_particular']  	= $cart->getCartparticulars(Paycart::CART_PARTICULAR_TYPE_SHIPPING);
			
			foreach ($carparticulars as $name => $particulars){
				$tmppraticulars = array();
				foreach($particulars as $particular){
					$usage = $particular->getUsage();
					
					// IMP :: This is patch for promotion and duties type processors
					// as we need to pass true to get actual total
					$total = $particular->getTotal(true);
					$particular = $particular->toObject();
					$particular->total = $total;
					
					foreach($usage as $use){
						$key = $particular->type.'-'.$particular->particular_id;
						if(!isset($usageDetails[$key])){
							$usageDetails[$key] 		=  array();
						}
						
						if(!isset($usageDetails[$key][$use->rule_type])){
							$usageDetails[$key][$use->rule_type] = array();
						}
							 
						$usageDetails[$key][$use->rule_type][] = $use->message;
					}					
					
					$tmppraticulars[$particular->particular_id] = $particular;
				}
				${$name} = $tmppraticulars;
			}
		}

		$shippingMethods = array();
		foreach ($shipping_particular as $particular){
			$shippingMethods[$particular->particular_id] = PaycartShippingrule::getInstance($particular->particular_id)->getTitle();
		}
		//load shipments
		$shipmentModel = PaycartFactory::getModel('shipment');
		$shipments     = $shipmentModel->loadRecords(array('cart_id' => $cart_id)); 
		
		$this->assign('product_particular',		$product_particular);
		$this->assign('shipping_particular',	$shipping_particular);
		$this->assign('promotion_particular',	$promotion_particular);
		$this->assign('duties_particular',		$duties_particular);
		$this->assign('shipments', array_values($shipments));
		$this->assign('form', $cart->getModelform()->getForm($cart));
		$this->assign('cart',$cart);
		$this->assign('shippingMethods',$shippingMethods);
		$this->assign('status',Paycart::getShipmentStatus());
		$this->assign('usageDetails', $usageDetails);
		return parent::edit($tpl);
	}
}
