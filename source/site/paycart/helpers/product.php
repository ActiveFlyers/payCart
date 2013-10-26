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
	 * @PCTODO: remove it if unused
	 * Translate alias to id.
	 *
	 * @param string $alias The alias string
	 *
	 * @return numeric value The Product id if found, or false/empty
	 */
	public static function XXX_translateAliasToID($alias) 
	{	
		$query 	= new Rb_Query();
		$result = $query->select('product_id')
						->where("`alias` = '$alias'")
			  			->from('#__paycart_product')
			  			->dbLoadQuery()->loadResult();
			  			
		return $result;	
	}
	
	/**
	 * Enter description here ...
	 * Array (	'keyword'	 => Keyword search data,
	 *			'filter'	 =>	Filtered data,
	 *			'limitstart' =>	Start limit,
	 *			'limit'		 => end limit,
	 *			'ordering'	 => Search Ordering (eithre asending or desending)
	 * 		  )
	 * 
	 */
	public function XXgetSearchData($data)
	{
		if(is_array($data)) {
			$data = (Object)$data;
		}
		
		$rows 	= Array();
				
		if (isset($data->keyword) && !empty($data->keyword)) {
			$row[]	=	PaycartFactory::getHelper('productindex')->getData($data);
		}
		
		if (isset($data->filter)) {
			$row[]	=	PaycartFactory::getHelper('productfilter')->getData($data);
		}
		
		$results = Rb_HelperPlugin::trigger('onPayCartProductSearch', $data);
		
		
		foreach ($results as $result) {
			$rows = array_merge((array) $rows, (array) $result);
		}
		
		return $rows;
	}
}
