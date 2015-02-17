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
class PaycartAdminHtmlViewProduct extends PaycartAdminBaseViewProduct
{	
	protected function _adminGridToolbar()
	{
		Rb_HelperToolbar::addNew('new');
		Rb_HelperToolbar::editList();
		Rb_HelperToolbar::divider();
		Rb_HelperToolbar::deleteList(Rb_Text::_('COM_PAYCART_ENTITY_DELETE_CONFIRMATION'));
		Rb_HelperToolbar::publish();
		Rb_HelperToolbar::unpublish();
		Rb_HelperToolbar::publish('visible',JText::_('COM_PAYCART_ADMIN_VISIBLE'));
		Rb_HelperToolbar::unpublish('invisible',JText::_('COM_PAYCART_ADMIN_INVISIBLE'));
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
		
		$model = $this->getModel();
		$productId	=  $model->getId();
		$product	=  PaycartProduct::getInstance($productId);
		
		// Get the prvioisly posted data if any
		// if it is not empty it means there wer some error
		$post_data = $model->getState('post_data', array());
		if(!empty($post_data)){
			$product->bind($post_data);
		}	
		$this->assign('error_fields', $model->getState('error_fields', array()));
		
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
		
		$positionedAttributes = $product->getPositionedAttributes();		
		// prepare addedAttributes
		$productAttributes = $product->getAttributes();
		$addedAttributes = array();
		foreach($positionedAttributes as $position => $positionAttributes){
			$addedAttributes[$position] = array(); 
			foreach($positionAttributes as $attribute_id){
				if(isset($attributes[$attribute_id])){
					$addedAttributes[$position][] = array('productattribute_id' => $attribute_id, 'value' => isset($productAttributes[$attribute_id]) ? $productAttributes[$attribute_id] : '');
				}
			}		
		}
		$this->assign('addedAttributes', $addedAttributes);
		
		// positions
		/* @var $helper PaycartHelperProduct */
		$helper = PaycartFactory::getHelper('product');
		$this->assign('positions', $helper->getPositions()); 	
		
		//set images
		$this->assign('images', $product->getImages());
		
		// @PCTODO:: Set availble variants
		$this->assign('variants',  Array());
		$this->assign('global_config', PaycartFactory::getConfig());
		$this->assign('uploadLimit',PaycartFactory::getHelper('media')->getUploadLimit());
		return parent::edit($tpl);
	}
	
	/**
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_View::_displayGrid()
	 */
	function _displayGrid($records)
	{
		parent::_displayGrid($records);
		
		foreach ($records as $record){
			$total = $record->quantity + $record->quantity_sold;
			$record->quantity_consumed  = ($total)?$record->quantity_sold/$total*100:0;
			$record->quantity_available = ($total)?$record->quantity/$total*100:100;
		}
		
		return true;
	}
}
