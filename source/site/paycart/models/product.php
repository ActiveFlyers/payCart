<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		team@readybytes.in
* @author 		mManishTrivedi 
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Product Model
 */
class PaycartModelProduct extends PaycartModel
{
	var $filterMatchOpeartor = Array(
									'alias' => array('LIKE')
									);
	/**
	 * 
	 * Array of those column which are unique. It will be checked (uniqueness) before save Product object 
	 * @var Array
	 */
	protected $uniqueColumns = Array( 'alias','sku');
	
	/**
	 * 
	 * Validation check beofore save:
	 * 1#. @PCTODO::Discuess anout it (Don't support variant of variant.) 
	 *
	 * @see components/com_paycart/paycart/base/PaycartModel::validate() 
	 */
	public function validate(&$data, $pk=null,array $filter = array(),array $ignore = array()) 
	{
		// 1#. No need to create variant of variant
		if ($data['variation_of']) {
			$product  = PaycartProduct::getInstance($data['variation_of']);
			if(!$product || $product->getVariationOf()) {
				//PCTODO :: Notify to user we dont support this kind of thing.
				return false;
			}
		}
		// Invoke parent validation
		return parent::validate($data, $pk, $filter, $ignore);
	}
}

class PaycartModelformProduct extends PaycartModelform 
{
	// Load Extra Custom atrributes configuration 
	protected function preprocessForm($form, $data)
	{
		// If Custom Attributes available at Product 
		// Then get all attribute's config Xml annd inject into Product Form
		if(!empty($data['attributes'])) {
			// Step-1 : Get All Attribute's Config XML
			$configXML = PaycartHelperAttribute::getAttributeXML(array_keys($data['attributes']));
			// Step-2 : Load on Form
			// Don't use Setfields here becoz we have fields hierarchy nd SetFields does not support it.(support only one level)
			// All Attribute config will be availble with form object.
			$form->load($configXML);
		}
		return parent::preprocessForm($form, $data);
	} 
	
}