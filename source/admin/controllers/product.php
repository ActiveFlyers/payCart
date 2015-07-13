<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		support+paycart@readybytes.in
* @author 		mManishTrivedi
*/

// no direct access
defined( '_JEXEC' ) or	die( 'Restricted access' );

/** 
 * Product Controller
 * @author mManishTrivedi
 */

class PaycartAdminControllerProduct extends PaycartController 
{
	public function __construct($options = array())
	{
		parent::__construct($options);
		
		$this->_boolMap['visible']  = array('column' => 'visible','value'=>1, 'switch'=>false);
		$this->_boolMap['invisible']= array('column' => 'visible','value'=>0, 'switch'=>false);

		//Register visible task 
		$this->registerTask( 'visible','multidobool');
		$this->registerTask( 'invisible','multidobool');
	}
	
	/**
	 * override it due to get all uploaded files 
	 */
	public function _save(array $data, $itemId=null, $type=null)
	{
		//Get All files from paycart form
		$data['_uploaded_files'] = $this->input->files->get($this->getControlNamePrefix(), false);
		
		//manage media files related to attribute
		if(isset($data['_uploaded_files']['attributes'])){			
			foreach ($data['_uploaded_files']['attributes'] as $attributeId => $value ){
				if(!empty($value['path']['tmp_name'])){
					$data['attributes'][$attributeId]['path'] = $value['path'];
				}
			}
		}
		
		//set attributes and position indices in post data,otherwise there would not be any way to 
        //identify at lib whether saving product or just creating instance
		if(!isset($data['attributes'])){
			$data['attributes'] = array();
			$data['config']     = array('positions'=>array()); 
		}
		
		/*
		 * Format measurement data into standard format before saving
		 * 
		 * It should be on controller level so that bind data will always be in standard format
		 * both for new and existing records  
		 */
		$data = $this->_formatMeasurementData($data);
		
		return parent::_save($data, $itemId, $type);
	}
	
	/**
	 * Format measurement data into our standard format
	 */
	protected function _formatMeasurementData($data)
	{
		$formatter           = PaycartFactory::getHelper('format');
		$weightUnitConfig    = PaycartFactory::getConfig()->get('catalogue_weight_unit');
		$dimensionUnitConfig = PaycartFactory::getConfig()->get('catalogue_dimension_unit');
		
				
		if(isset($data['weight'])){
			$data['weight'] = $formatter->convertWeight($data['weight'],$weightUnitConfig);
		}
		
		if(isset($data['length'])){
			$data['length'] = $formatter->convertDimension($data['length'],$dimensionUnitConfig);	
		}
		
		if(isset($data['width'])){
			$data['width']  = $formatter->convertDimension($data['width'],$dimensionUnitConfig);	
		}
		
		if(isset($data['height'])){
			$data['height'] = $formatter->convertDimension($data['height'],$dimensionUnitConfig);	
		}
		
		return $data;
	}
	
	/**
	 * 
	 * Ajax Task.
	 * 
	 * @PCTODO::  Use JSON formate for this kind of method 
	 * (Where you can get only data from server. no validation or task execution required)
	 */
	public function getAlias() 
	{
		//Check Joomla Session user should be login
		// PCTODO:: Should be check into parent class
		if ( JSession::checkToken() ) {
			//@PCTODO :: Rise exception 
		}
		return true;
	}
	
	/**
	 * 
	 * Ajax Call : Display "Add ttribute" window 
	 * (to creating new attribute) 
	 */
	public function addAttribute()
	{
		//Check Joomla Session user should be login
		if ( !JSession::checkToken() ) {
			//@PCTODO :: Rise exception 
		}
		return true;
	}
	/**
	* Add New Product Variant
	*/
	public function addVariant()
	{
		$variantOf = $this->input->get('variant_of', false);
		//@PCTODO :: use setredirector
		$app = PaycartFactory::getApplication();
		// Check variantof 
		if(!$variantOf) {
			$app->enqueueMessage(Rb_Text::_('COM_PAYCART_ADMIN_PRODUCT_VARIANT_PARENT_REQUIRED'),'error');
			$this->setRedirect('index.php?option=com_paycart&view=product');
			return false;
		}
		
		$product = PaycartProduct::getInstance($variantOf);
		
		//Validate Variant exist or not
		if (!$product) {
			$app->enqueueMessage(Rb_Text::_('COM_PAYCART_ADMIN_PRODUCT_VARIANT_PARENT_NOT_EXIST'),'error');
			$this->setRedirect('index.php?option=com_paycart&view=product');
			return false;
		} 
		
		// if everything is ok then create new variant
		$productHelper = PaycartFactory::getHelper('product');
		$variant       = $productHelper->addVariant($product);
		if(!$variant) {
			$app->enqueueMessage(Rb_Text::_('COM_PAYCART_ADMIN_PRODUCT_VARIANT_CREATION_FAIL'),'error');
			return false;
		}
		
		$this->setRedirect(
						'index.php?option=com_paycart&view=product&task=edit&product_id='.$variant->getId(),
						Rb_Text::_('COM_PAYCART_ADMIN_PRODUCT_VARIANT_CREATION_SUCCESS')
							);
		// no need to execute view functions
		return false;
	}
	
	/**
	 * Ajax task : Delete any attached attribute
	 */
	public function deleteAttributeValues()
	{
		$productAttributeId =  $this->input->get('productattribute_id', false);
		$product = PaycartProduct::getInstance($this->input->get('product_id', false));
		$product->deleteAttributeValues($productAttributeId);
		
		$ajax = PaycartFactory::getAjaxResponse();
		$ajax->addScriptCall('paycart.jQuery(".paycart-product-attribute-'.$productAttributeId.'").remove');
		return false;
	}
	
	/**
	 * Ajax task : Delete image attached to product
	 */
	public function deleteImage()
	{
		$productId = $this->input->get('product_id',0);
		$instance  = PaycartProduct::getInstance($productId);
		$imageId   = $this->input->get('image_id',0);
		
		$productHelper = PaycartFactory::getHelper('product');
		$ret 	       = $productHelper->deleteImage($instance,$imageId);
		
		$view = $this->getView();
		if($ret){
			$view->assign('success', true);			
		}
		else{
			$view->assign('success', false);
		}
	
		return true;
	}
	
	public function reorderImages()
	{
		$productId 		= $this->input->get('product_id',0);
		$instance  		= PaycartProduct::getInstance($productId);
		$imageIds   	= $this->input->get('image_ids', array(), 'array');		
		$coverImageId 	= reset($imageIds);		
		$ret = $instance->setImages($imageIds)->setCoverMedia($coverImageId)->save();
		
		$view = $this->getView();
		if($ret){
			$view->assign('success', true);
			$view->assign('coverMedia',$instance->getCoverMedia(false));
		}
		else{
			$view->assign('success', false);
		}
	
		return true;
	}
}