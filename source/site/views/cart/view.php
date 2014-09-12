<?php

/**
* @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	front-end
* @contact		support+paycart@readybytes.in
* @author		mManishTrivedi , Rimjhim Jain
*/

defined( '_JEXEC' ) or	die( 'Restricted access' );
/**
 * 
 * cart Base View
 * @author Manish
 */
class PaycartSiteBaseViewcart extends PaycartView
{
	
	/**
	 * (non-PHPdoc)
	 * @see /plugins/system/rbsl/rb/rb/Rb_AbstractView::_basicFormSetup()
	 */
	protected function _basicFormSetup($task)
	{
		return true;
	}
	
	public function complete()
	{
		
	}
	
	protected function _assignTmplVars()
	{
		$helper = PaycartFactory::getHelper('cart');
		$cart 	= $helper->getCurrentCart();
		
		$productParticulars  = $cart->getCartparticulars(Paycart::CART_PARTICULAR_TYPE_PRODUCT);
		$promotionParticular = $cart->getCartparticulars(Paycart::CART_PARTICULAR_TYPE_PROMOTION);
		$dutiesParticular    = $cart->getCartparticulars(Paycart::CART_PARTICULAR_TYPE_DUTIES);
		$shippingParticulars = $cart->getCartparticulars(Paycart::CART_PARTICULAR_TYPE_SHIPPING);
				
		$this->assign('products',$productParticulars);
		$this->assign('cart', $cart);
		$this->assign('promotionParticular',(empty($promotionParticular))?(array_shift($promotionParticular)->toObject()):new stdClass());
		$this->assign('dutiesParticular',(empty($promotionParticular))?(array_shift($dutiesParticular)->toObject()):new stdClass());
		$this->assign('shippingParticulars',$shippingParticulars);
		
		return true;
	}	
}