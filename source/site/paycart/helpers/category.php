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
 * Category Helper
 */
class PaycartHelperCategory extends PaycartHelper
{	
	
	/**
	 * Translate alias to id.
	 *
	 * @param string $alias The alias string
	 *
	 * @return numeric value The Category id if found, or false/empty
	 */
	public static function translateAliasToID($alias) 
	{	
		$query 	= new Rb_Query();
		$result = $query->select('category_id')
						->where("`alias` = '$alias'")
			  			->from('#__paycart_category')
			  			->dbLoadQuery()->loadResult();
			  			
		return $result;	
	}
}
