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
		
		$model 		= PaycartFactory::getModel('cartparticular');
		
		// collect all particular details
		$product_particular   = $model->loadRecords(array('type'=>paycart::CART_PARTICULAR_TYPE_PRODUCT, 'cart_id' => $cart_id));
		$shipping_particular  = $model->loadRecords(array('type'=>paycart::CART_PARTICULAR_TYPE_SHIPPING, 'cart_id' => $cart_id));
		$promotion_particular = $model->loadRecords(array('type'=>paycart::CART_PARTICULAR_TYPE_PROMOTION, 'cart_id' => $cart_id));
		$duties_particular	  = $model->loadRecords(array('type'=>paycart::CART_PARTICULAR_TYPE_DUTIES, 'cart_id' => $cart_id));
		
		//collect usage of tax, discount and shipping
		$usage = PaycartFactory::getModel('usage')->loadRecords(array('cart_id' => $cart_id), array(), false, 'cartparticular_id');
		
			
		$this->assign('product_particular',		$product_particular);
		$this->assign('shipping_particular',	$shipping_particular);
		$this->assign('promotion_particular',	$promotion_particular);
		$this->assign('duties_particular',		$duties_particular);
		$this->assign('usage',$usage);
		$this->assign('form', $cart->getModelform()->getForm($cart));
		$this->assign('cart',$cart);
		
		return parent::edit($tpl);
	}
	
}
