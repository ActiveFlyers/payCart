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
 * Discountrule model
 * @author mManishTrivedi
 *
 */
class PaycartModelDiscountrule extends PaycartModel
{
//	var $filterMatchOpeartor = Array(
//									'coupon' => array('LIKE')
//									);

	
	/**
	 * 
	 * get data from Discountrule table  
	 * @param  $where
	 * @param  $orderBy
	 * 
	 * @return Array of stdclass
	 */
	public function getData($where, $orderBy)
	{
		//@PCTODO:: $record can be cached 
		
		// get new query object
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

class PaycartModelformDiscountrule extends PaycartModelform { }


/**
 * 
 * Discountrule Lang model
 * @author mManishTrivedi
 *
 */
class PaycartModelDiscountruleLang extends PaycartModel
{
	/**
	 * (non-PHPdoc)
	 * @see plugins/system/rbsl/rb/rb/Rb_Model::save()
	 */
	public function save($data, $pk=null, $new=false)
	{
		
		// 1#.no data then return true without any action
		if(empty($data)) {
			return true;
		}
		// 2#. Valiadate Data
		if(!$this->validate($data)) {
			return false;
		}
		
		// 3#. Delete existing data
		//$this->deleteMany(Array('discountrule_id'=>$discountruleId));
		
		// 4#. Insert new data
		$query = $this->_db->getQuery(true);
		
		// build inert query
//		$query->insert($this->getTable()->get('_tbl'))
//					->columns(
//						array(
//							$this->_db->quoteName('discountrule_id'),
//							$this->_db->quoteName('lang_id'), $this->_db->quoteName('message')
//						)
//					);

		foreach ($data as $row) {
			$query->values("{$this->_db->quote($row['discountrule_id'])}, 
							{$this->_db->quote($row['lang_code'])},
							{$this->_db->quote($row['message'])}
						   ");
		}

		$this->_db->setQuery($query);
		try	{
			$this->_db->execute();
		}
		catch (RuntimeException $e) {
			//@PCTODO::proper message propagates
			Rb_Error::raiseError(500, $e->getMessage());
		}
		
		return true;	
	}
}

/**
 * 
 * Discountrule and class Mapper model
 * @author mManishTrivedi
 *
 */
class PaycartModelDiscountruleXGroup extends PaycartModel
{}