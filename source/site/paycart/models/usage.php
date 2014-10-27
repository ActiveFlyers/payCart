<?php

/**
* @copyright	Copyright (C) 2009 - 2013 Ready Bytes Software Labs Pvt. Ltd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* @package 		PAYCART
* @subpackage	Back-end
* @contact		suppor+paycart@readybytes.in
* @author 		mManishTrivedi 
*/

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

/**
 * 
 * Usage Model
 * @author manish
 *
 */
class PaycartModelUsage extends PaycartModel
{
	/**
	 * 
	 * Invoke to get usage of any rule.
	 * 		- Count task perform on cart bases cart.
	 *  
	 * @param Array $filter
	 * 
	 * @return INT value
	 */
	public function getCount(Array $filter)
	{
		$query = new Rb_Query();
		
		foreach ($filter as $key => $value) {
			$query->where("{$this->_db->qn($key)} = {$this->_db->q($value)}");
		} 
		
		$query->select('cart_id')
			 ->from($this->getTable()->getTableName())
			 ->group('cart_id');
		
		$records = $this->_db->setQuery($query)->loadColumn();

		return count($records);
	}
	
}

/**
 * 
 * Usage Table
 * @author manish
 *
 */
class PaycartTableUsage extends PaycartTable
{
	
}
