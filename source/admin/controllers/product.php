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
	
	/**
	 * override it due to get all uploaded files 
	 */
	public function _save(array $data, $itemId=null, $type=null)
	{
		//Get All files from paycart form
		$data['_uploaded_files'] = $this->input->files->get('paycart_form', false);
		
		//manage media files related to attribute
		if(isset($data['_uploaded_files']['attributes'])){			
			foreach ($data['_uploaded_files']['attributes'] as $attributeId => $value ){
				if(!empty($value['path']['tmp_name'])){
					$data['attributes'][$attributeId]['path'] = $value['path'];
				}
			}
		}
		
		return parent::_save($data, $itemId, $type);
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
			$app->enqueueMessage(Rb_Text::_('COM_PAYCART_VARIANT_PARENT_REQUIRED'),'error');
			return false;
		}
		$product = PaycartProduct::getInstance($variantOf);
		//Validate Variant exist or not
		if (!$product) {
			$app->enqueueMessage(Rb_Text::_('COM_PAYCART_VARIANT_PARENT_NOT_EXIST'),'error');
			return false;
		} 
		// if everything is ok then create new variant
		$productHelper = PaycartFactory::getHelper('product');
		$variant       = $productHelper->addVariant($product);
		if(!$variant) {
			$app->enqueueMessage(Rb_Text::_('COM_PAYCART_VARIANT_CREATION_FAIL'),'error');
			return false;
		}
		
		$this->setRedirect(
						'index.php?option=com_paycart&view=product&task=edit&product_id='.$variant->getId(),
						Rb_Text::_('COM_PAYCART_VARIANT_CREATION_SUCCESS')
							);
		// no need to execute view functions
		return false;
	}
		
}