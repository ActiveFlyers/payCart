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

class PaycartModelformProduct extends PaycartModelform {}

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