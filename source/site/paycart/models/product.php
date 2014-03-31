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
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Product Model
 */
class PaycartModelProduct extends PaycartModel
{
	/**
	 * 
	 * Array of those column which are unique. It will be checked (uniqueness) before save Product object 
	 * @var Array
	 */
	protected $uniqueColumns = Array('sku');
	
	/**
	 * 
	 * Validation check beofore save:
	 * 1#. Don't support variant of variant.(Multi-level variation) 
	 *
	 * @see components/com_paycart/paycart/base/PaycartModel::validate() 
	 */
	public function validate(&$data, $pk=null,array $filter = array(),array $ignore = array()) 
	{
		// 1#. No need to create variant of variant
		if ($data['variation_of']) {
			$product  = PaycartProduct::getInstance($data['variation_of']);
			if(!$product || ($product->getVariationOf() && $product->getVariationOf() != $product->getId())) {
				// PCTODO :: Dont need to fire exception juts set variation_of property    
				// Notify to user we dont support multi-level variation
				throw new UnexpectedValueException(Rb_Text::_('COM_PAYCART_NOT_SUPPORT_MULTILEVEL_VARIATION'));
			}
		}
		// Invoke parent validation
		return parent::validate($data, $pk, $filter, $ignore);
	}
}

class PaycartModelformProduct extends PaycartModelform 
{
	// Load Custom atrributes configuration XML
	protected function preprocessForm($form, $data)
	{
		// If Custom Attributes available at Product 
		// Then get all attribute's config Xml annd inject into Product Form
		if(!empty($data['_attributeValue'])) {
			// Step-1 : Get All Attribute's Config XML
			$configXML = PaycartHelperAttribute::getAttributeXML(array_keys($data['_attributeValue']));
			// Step-2 : Load on Form
			// Don't use Setfields here becoz we have fields hierarchy nd SetFields does not support it.(support only one level)
			// All Attribute config will be availble with form object.
			$form->load($configXML);
		}
		return parent::preprocessForm($form, $data);
	} 
	
	// Load Custom Attributes Data 
	protected function preprocessData($context, &$data)
	{
		if(!empty($data['_attributeValue'])) {
			//IMP :: We have always set attributeValue lib data
			foreach ( $data['_attributeValue'] as $attributeId => $attribute) {
				// set attributes index on Data
				$data['attributes'][$attributeId] = $attribute->toArray();
			}
		}
		return parent::preprocessData($context, $data);
	}
	
	protected function loadFormData()
	{
		$data  = parent::loadFormData();
		$this->preprocessData('com_paycart.product', $data);
		return $data;
	}
	
}

/**
 * 
 * Product Lang Model
 * @author rimjhim
 *
 */
class PaycartModellangProduct extends PaycartModel
{
	protected $uniqueColumns = Array('alias');
}