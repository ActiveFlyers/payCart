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
 * 
 * DiscountRule model
 * @author mManishTrivedi
 *
 */
class PaycartModelDiscountRule extends PaycartModel
{
//	var $filterMatchOpeartor = Array(
//									'coupon' => array('LIKE')
//									);

	
	/**
	 * 
	 * get data from discountrule table  
	 * @param  $where
	 * @param  $orderBy
	 * 
	 * @return Array of stdclass
	 */
	public function getData($where, $orderBy)
	{
		//@PCTODO:: $record can be cached 
		
		// get new queru object
		$query 	= 	PaycartFactory::getQuery();
		
		$table	=	$this->getTable();
		// build query
		$query->select('*')
			  ->from($table()->getTableName())
			  ->where($where)
			  ->order($orderBy);
			  
		try	{
			$records =	$this->_db->setQuery($query)->loadObjectList($table->getKeyName());
		}
		catch (RuntimeException $e) {
			//@PCTODO::proper-message propagates
			Rb_Error::raiseError(500, $e->getMessage());
		}
		
		return $records;
			  
	}
}

class PaycartModelformDiscountRule extends PaycartModelform { }


/**
 * 
 * DiscountRule Lang model
 * @author mManishTrivedi
 *
 */
class PaycartModelDiscountRuleLang extends PaycartModel
{}

/**
 * 
 * DiscountRule and class Mapper model
 * @author mManishTrivedi
 *
 */
class PaycartModelDiscountRuleXclass extends PaycartModel
{}