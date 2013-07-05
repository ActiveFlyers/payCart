<?php

/**
 * @copyright	Copyright (C) 2009 - 2012 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
 * @license		GNU/GPL, see LICENSE.php
 * @package 		PAYCART
 * @subpackage	Front-end
 * @contact		team@readybytes.in
 * @author 		Manish Trivedi
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/** 
 * Product Helper
 */
class PaycartHelperProduct extends PaycartHelper
{	
	/**
	* @return array of availble product types.
	*/
	public static function getTypes() 
	{
		return 
			Array(
					Paycart::PRODUCT_TYPE_PHYSICAL		=>	'COM_PAYCART_PRODUCT_TYPE_PHYSICAL',
					Paycart::PRODUCT_TYPE_DIGITAL		=>	'COM_PAYCART_PRODUCT_TYPE_DIGITAL'	
				  );
	}	
	
	/**
	 * Translate alias to id.
	 *
	 * @param string $alias The alias string
	 *
	 * @return numeric value The Product id if found, or false/empty
	 */
	public static function translateAliasToID($alias) 
	{	
		$query 	= new Rb_Query();
		$result = $query->select('product_id')
						->where("`alias` = '$alias'")
			  			->from('#__paycart_product')
			  			->dbLoadQuery()->loadResult();
			  			
		return $result;	
	}
}
