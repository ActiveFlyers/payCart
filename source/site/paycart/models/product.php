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
class PaycartModelProduct extends PaycartModelLang
{
	/**
	 * 
	 * Array of those column which are unique. It will be checked (uniqueness) before save Product object 
	 * @var Array
	 */
	protected $uniqueColumns = Array('sku', 'alias');
	
	/**
	 * 
	 * Validation check beofore save:
	 * 1#. Don't support variant of variant.(Multi-level variation) 
	 *
	 * @see components/com_paycart/paycart/base/PaycartModel::validate() 
	 */
//	public function validate(&$data, $pk=null,array $filter = array(),array $ignore = array()) 
//	{
//		// 1#. No need to create variant of variant
//		if ($data['variation_of']) {
//			$product  = PaycartProduct::getInstance($data['variation_of']);
//			if(!$product || ($product->getVariationOf() && $product->getVariationOf() != $product->getId())) {
//				// PCTODO :: Dont need to fire exception juts set variation_of property    
//				// Notify to user we dont support multi-level variation
//				throw new UnexpectedValueException(Rb_Text::_('COM_PAYCART_NOT_SUPPORT_MULTILEVEL_VARIATION'));
//			}
//		}
//		// Invoke parent validation
//		return parent::validate($data, $pk, $filter, $ignore);
//	}

	public function updateStock($productId, $quantity)
	{
		$query = new Rb_Query();
		
		return $query->update($this->getTable()->get('_tbl'))
					 ->set('quantity = quantity - '.$quantity)
					 ->where('product_id = '.$productId)
					 ->dbLoadQuery()
					 ->query();
			  
	}
}

class PaycartModelformProduct extends PaycartModelform {}

class PaycartTableProduct extends PaycartTable {}

class PaycartTableProductLang extends PaycartTable
{
	function __construct($tblFullName='#__paycart_product_lang', $tblPrimaryKey='product_lang_id', $db=null)
	{
		return parent::__construct($tblFullName, $tblPrimaryKey, $db);
	}
}