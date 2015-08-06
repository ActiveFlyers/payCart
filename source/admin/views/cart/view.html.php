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

		$cartHelper	= PaycartFactory::getHelper('cart');
		$data = $cartHelper->getDetailedCartData($cart_id);
		$this->assign('product_particular',		$data['product_particular']);
		$this->assign('shipping_particular',	$data['shipping_particular']);
		$this->assign('promotion_particular',	$data['promotion_particular']);
		$this->assign('duties_particular',		$data['duties_particular']);
		$this->assign('shipments', array_values($data['shipments']));
		$this->assign('form', $data['cart']->getModelform()->getForm($data['cart']));
		$this->assign('cart',$data['cart']);
		$this->assign('shippingMethods',$data['shippingMethods']);
		$this->assign('status',Paycart::getShipmentStatus());
		$this->assign('usageDetails', $data['usageDetails']);
		$this->assign('transactions',$data['transactions']);
		$this->assign('transactionStatusList',Rb_EcommerceAPI::response_get_status_list());
		$this->assign('isShippableProductExist',$cartHelper->isShippableProductExist($data['cart']));
		$this->assign('shippable_product_particular',$data['shippable_product_particular']);
		
		return parent::edit($tpl);
	}
}
