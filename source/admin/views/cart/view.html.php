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
class PaycartAdminViewCart extends PaycartAdminBaseViewCart
{	
	protected function _adminGridToolbar()
	{
		Rb_HelperToolbar::deleteList(Rb_Text::_('COM_PAYCART_ENTITY_DELETE_CONFIRMATION'));
	}
	
	protected function _adminEditToolbar()
	{
		Rb_HelperToolbar::apply();
		Rb_HelperToolbar::save();
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
		
		// collect all particular details		
		$product_particular	  = $cartHelper->getCartparticularsData($cart->getId(),Paycart::CART_PARTICULAR_TYPE_PRODUCT);
		$promotion_particular = $cartHelper->getCartparticularsData($cart->getId(),Paycart::CART_PARTICULAR_TYPE_PROMOTION);
		$duties_particular	  = $cartHelper->getCartparticularsData($cart->getId(),Paycart::CART_PARTICULAR_TYPE_DUTIES);
		$shipping_particular  = $cartHelper->getCartparticularsData($cart->getId(),Paycart::CART_PARTICULAR_TYPE_SHIPPING);
				
		//collect usage of tax, discount and shipping
		$usage = PaycartFactory::getModel('usage')->loadRecords(array('cart_id' => $cart_id), array(), false, 'cartparticular_id');
		
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
		$this->assign('shipments', $shipments);
		$this->assign('usage',$usage);
		$this->assign('form', $cart->getModelform()->getForm($cart));
		$this->assign('cart',$cart);
		$this->assign('shippingMethods',$shippingMethods);
		$this->assign('status',Paycart::getShipmentStatus());
		
		return parent::edit($tpl);
	}
}
