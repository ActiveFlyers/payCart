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
		Rb_HelperToolbar::addNew('new');
		Rb_HelperToolbar::editList();
		Rb_HelperToolbar::divider();
		Rb_HelperToolbar::deleteList(Rb_Text::_('COM_PAYCART_ENTITY_DELETE_CONFIRMATION'));
	}
	
	protected function _adminEditToolbar()
	{
		Rb_HelperToolbar::apply();
		Rb_HelperToolbar::save();
		Rb_HelperToolbar::cancel();
		Rb_HelperToolbar::custom('addvariant','retweet','',Rb_Text::_('COM_PAYCART_VARIANT_ADD'),false);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_View::edit()
	 */
	public function edit($tpl=null) {
		
		$productId	=  $this->getModel()->getId();
		$product	=  PaycartProduct::getInstance($productId);
		
		$form 		= $product->getModelform()->getForm($product);
	    
		$this->assign('form', $form );
		$this->assign('product', $product );
		
		
		// ATTRIBUTES
		$attributes 					= PaycartFactory::getModel('productattribute')->loadRecords();
		$availableAttributes 			= array();
		$availableAttributesInstances 	= array();
		foreach($attributes as $attribute){
			$instance = PaycartProductAttribute::getInstance($attribute->productattribute_id, $attribute);
			$availableAttributes[] = (object) $instance->toArray();
		}	
		$this->assign('availableAttributes',  $availableAttributes);
		
		// prepare addedAttributes
		$productAttributes = $product->getAttributeValues();
		$addedAttributes = array();
		foreach($productAttributes as $attribute_id => $value){
			$addedAttributes[] = array('productattribute_id' => $attribute_id, 'value' => $value);
		}		
		$this->assign('addedAttributes', $addedAttributes);
		
		
		//set images
		$this->assign('images', $product->getImages());
		
		// @PCTODO:: Set availble variants
		$this->assign('variants',  Array());
		
		return parent::edit($tpl);
	}
	
}
