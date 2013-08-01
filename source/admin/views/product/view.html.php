<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @contact		team@readybytes.in
*/

// no direct access
defined('_JEXEC') or die( 'Restricted access' );

/** 
 * Product Html View
* @author Team Readybytes
 */
require_once dirname(__FILE__).'/view.php';
class PaycartAdminViewProduct extends PaycartAdminBaseViewProduct
{	
	protected function _adminGridToolbar()
	{
		Rb_HelperToolbar::addNew('new','COM_PAYCART_ADD_NEW_PRODUCT');
		Rb_HelperToolbar::editList();
		Rb_HelperToolbar::divider();
		Rb_HelperToolbar::deleteList(Rb_Text::_('COM_PAYCART_ENTITY_DELETE_CONFIRMATION'));
//		Rb_HelperToolbar::publish();
//		Rb_HelperToolbar::unpublish();
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
		
		$product_id	=  $this->getModel()->getState('id');
		$product	=  PaycartProduct::getInstance($product_id);
		
		$this->assign('form',  $product->getModelform()->getForm($product));
		// @PCTODO:: Set availble variants
		$this->assign('variant',  Array());
		
		return parent::edit($tpl);
	}
	
}
